<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Consignment;
use App\Models\Donation;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function forum(Request $request)
    {
        $posts = Post::query()
            ->with([
                'books.category',
                'users',
                'images',
                'reactions',
                'comments' => fn ($comments) => $comments->where('Status', 'Hiển thị')->with(['user', 'replies.user', 'reactions']),
            ])
            ->where('PostStatus', 'Đã duyệt')
            ->latest('CreatedAt')
            ->paginate(10)
            ->withQueryString();

        return view('posts.forum', ['posts' => $posts]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'post_type' => ['nullable', 'in:Bán,Trao đổi,Ký gửi,Quyên góp'],
            'condition' => ['nullable', 'in:Mới,Như mới,Tốt,Ổn,Cũ'],
            'category' => ['nullable', 'exists:categories,CategoryID'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:newest,oldest,price_asc,price_desc'],
        ]);
        if ($request->filled('min_price') && $request->filled('max_price')
            && (float) $request->min_price > (float) $request->max_price) {
            throw ValidationException::withMessages([
                'min_price' => 'Giá tối thiểu không được lớn hơn giá tối đa.',
            ]);
        }

        $posts = Post::query()
            ->with(['books.category', 'users', 'images'])
            ->where('PostStatus', 'Đã duyệt')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->string('q')->toString();
                $query->where(function ($query) use ($q) {
                    $query->where('Title', 'like', "%{$q}%")
                        ->orWhere('Description', 'like', "%{$q}%")
                        ->orWhereHas('books', fn ($book) => $book->where('Title', 'like', "%{$q}%")
                            ->orWhere('AuthorName', 'like', "%{$q}%"));
                });
            })
            ->when($request->filled('post_type'), fn ($query) => $query->where('PostType', $request->post_type))
            ->when($request->filled('condition'), fn ($query) => $query->where('Condition', $request->condition))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('books.category', fn ($category) => $category->where('CategoryID', $request->category)))
            ->when($request->filled('min_price'), fn ($query) => $query->where('Price', '>=', $request->min_price))
            ->when($request->filled('max_price'), fn ($query) => $query->where('Price', '<=', $request->max_price))
            ->when($request->input('sort') === 'price_asc', fn ($query) => $query->orderBy('Price'))
            ->when($request->input('sort') === 'price_desc', fn ($query) => $query->orderByDesc('Price'))
            ->when($request->input('sort') === 'oldest', fn ($query) => $query->orderBy('CreatedAt')->orderBy('PostID'))
            ->when(! in_array($request->input('sort'), ['price_asc', 'price_desc', 'oldest'], true), fn ($query) => $query->orderByDesc('CreatedAt')->orderByDesc('PostID'))
            ->paginate(12)
            ->withQueryString();

        return view('posts.index', [
            'posts' => $posts,
            'categories' => Category::where('Status', true)->orderBy('CategoryName')->get(),
        ]);
    }

    public function create()
    {
        return view('posts.form', [
            'post' => new Post,
            'categories' => Category::where('Status', true)->orderBy('CategoryName')->get(),
            'method' => 'POST',
            'action' => route('posts.store'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePost($request);
        $uploadedCount = count((array) $request->file('images', []));
        if ($uploadedCount > 4) {
            return back()->withInput()->withErrors(['images' => 'Một bài đăng chỉ được tối đa 4 ảnh.']);
        }
        $post = Post::create([
            'PostID' => Post::generateId(),
            ...$validated,
            'UserID' => $request->user()->UserID,
            'PostStatus' => 'Chờ duyệt',
        ]);

        $this->storeImages($request, $post);

        if ($post->PostType === 'Ký gửi' && Schema::hasTable('consignments')) {
            Consignment::create([
                'ConsignmentID' => Consignment::generateId(),
                'UserID' => $post->UserID,
                'PostID' => $post->PostID,
                'ContactInfo' => $request->user()->Phone,
                'Description' => $post->Description,
                'ConsignmentStatus' => 'Pending',
            ]);
        } elseif ($post->PostType === 'Quyên góp' && Schema::hasTable('donations')) {
            Donation::create([
                'DonationID' => Donation::generateId(),
                'UserID' => $post->UserID,
                'PostID' => $post->PostID,
                'ContactInfo' => $request->user()->Phone,
                'DonationStatus' => 'Pending',
            ]);
        }

        return redirect()->route('posts.mine')->with('success', 'Bài đăng đã được gửi và đang chờ kiểm duyệt.');
    }

    public function show(Post $post)
    {
        abort_unless(
            $post->PostStatus === 'Đã duyệt'
            || optional(auth()->user())->UserID === $post->UserID
            || optional(auth()->user())->isAdmin(),
            404
        );

        $post->load([
            'books.category',
            'users',
            'images',
            'reactions',
            'comments' => fn ($comments) => $comments->where('ParentCommentID', null)
                ->where('Status', 'Hiển thị')
                ->with(['user', 'reactions', 'replies.user', 'replies.reactions']),
        ]);

        $transaction = null;
        if (Schema::hasTable('transactions')) {
            $transaction = $post->transaction()->first();
        }

        $chatParticipant = auth()->check() && $transaction && (
            $transaction->BuyerID === auth()->user()->UserID
            || $transaction->SellerID === auth()->user()->UserID
        );

        return view('posts.show', [
            'post' => $post,
            'transaction' => $transaction,
            'chatParticipant' => $chatParticipant,
        ]);
    }

    public function purchase(Request $request, Post $post)
    {
        abort_unless($post->PostStatus === 'Đã duyệt', 404);

        if ($post->PostType !== 'Bán') {
            return back()->with('error', 'Chỉ bài đăng loại Bán mới có thể mua.');
        }

        if ($post->UserID === $request->user()->UserID) {
            return back()->with('error', 'Bạn không thể mua bài đăng của chính mình.');
        }

        if (! Schema::hasTable('transactions')) {
            return back()->with('error', 'Tính năng mua hàng chưa được khởi tạo.');
        }

        if ($post->transaction()->exists()) {
            return back()->with('error', 'Bài đăng này đã được người khác đặt mua.');
        }

        Transaction::create([
            'TransactionID' => Transaction::generateId(),
            'PostID' => $post->PostID,
            'BuyerID' => $request->user()->UserID,
            'SellerID' => $post->UserID,
            'TransactionStatus' => 'Pending',
        ]);

        return back()->with('success', 'Đã gửi yêu cầu mua. Người bán sẽ liên hệ với bạn.');
    }

    public function edit(Post $post)
    {
        $this->authorizeOwner($post);

        return view('posts.form', [
            'post' => $post->load('images'),
            'categories' => Category::where('Status', true)->orderBy('CategoryName')->get(),
            'method' => 'PUT',
            'action' => route('posts.update', $post),
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorizeOwner($post);
        $validated = $this->validatePost($request);
        $removeCount = count((array) $request->input('remove_images', []));
        $uploadedCount = count((array) $request->file('images', []));
        $remainingCount = $post->images()->count() - $removeCount + $uploadedCount;
        if ($remainingCount > 4) {
            return back()->withInput()->withErrors(['images' => 'Một bài đăng chỉ được tối đa 4 ảnh.']);
        }
        $post->update([...$validated, 'PostStatus' => 'Chờ duyệt']);
        $this->storeImages($request, $post);

        return redirect()->route('posts.mine')->with('success', 'Đã cập nhật bài đăng và gửi lại để kiểm duyệt.');
    }

    public function destroy(Post $post)
    {
        $this->authorizeOwner($post);
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->ImagePath);
        }
        $post->delete();

        return redirect()->route('posts.mine')->with('success', 'Đã xóa bài đăng.');
    }

    public function mine()
    {
        $posts = Post::with(['books', 'images'])
            ->where('UserID', auth()->user()->UserID)
            ->latest('CreatedAt')
            ->paginate(12);

        return view('posts.mine', ['posts' => $posts]);
    }

    private function validatePost(Request $request): array
    {
        $validated = $request->validate([
            'BookID' => ['required_without:BookName', 'nullable', 'exists:books,BookID'],
            'BookName' => ['nullable', 'string', 'min:5', 'max:220'],
            'CategoryID' => ['nullable', 'required_with:BookName', 'exists:categories,CategoryID'],
            'Title' => ['required', 'string', 'max:100'],
            'Price' => ['nullable', 'numeric', 'min:0', 'max:9999999999999999'],
            'PostType' => ['required', 'in:Bán,Trao đổi,Ký gửi,Quyên góp'],
            'Condition' => ['required', 'in:Mới,Như mới,Tốt,Ổn,Cũ'],
            'Description' => ['nullable', 'string', 'max:500'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string'],
        ], [
            'Title.required' => 'Vui lòng nhập tiêu đề bài đăng.',
            'BookID.required' => 'Vui lòng chọn sách.',
            'BookName.required' => 'Vui lòng nhập tên sách và tên tác giả.',
            'PostType.required' => 'Vui lòng chọn loại bài đăng.',
            'Condition.required' => 'Vui lòng chọn tình trạng sách.',
        ]);

        if ($request->filled('BookName')) {
            $book = $this->findOrCreateBook(
                $request->input('BookName'),
                $validated['CategoryID']
            );
            $validated['BookID'] = $book->BookID;
        }

        if (empty($validated['BookID'])) {
            throw ValidationException::withMessages([
                'BookName' => 'Vui lòng nhập tên sách theo cú pháp: Tên sách - Tên tác giả.',
                'BookID' => 'Vui lòng nhập hoặc chọn sách.',
            ]);
        }

        if ($validated['PostType'] !== 'Bán' && $validated['PostType'] !== 'Ký gửi') {
            $validated['Price'] = null;
        }

        unset($validated['BookName'], $validated['CategoryID']);

        return $validated;
    }

    private function findOrCreateBook(string $bookName, string $categoryId): Book
    {
        $parts = explode(' - ', $bookName, 2);
        if (count($parts) !== 2) {
            throw ValidationException::withMessages([
                'BookName' => 'Vui lòng nhập theo cú pháp: Tên sách - Tên tác giả.',
            ]);
        }

        $title = trim($parts[0]);
        $author = trim($parts[1]);
        if ($title === '' || $author === '') {
            throw ValidationException::withMessages([
                'BookName' => 'Vui lòng nhập đầy đủ tên sách và tên tác giả.',
            ]);
        }

        $existing = Book::where('Title', $title)
            ->where('AuthorName', $author)
            ->first();
        if ($existing) {
            return $existing;
        }

        $max = Book::selectRaw('MAX(CAST(SUBSTRING(BookID, 2) AS UNSIGNED)) AS m')->value('m');
        return Book::create([
            'BookID' => 'B'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
            'Title' => $title,
            'AuthorName' => $author,
            'CategoryID' => $categoryId,
        ]);
    }

    private function storeImages(Request $request, Post $post): void
    {
        foreach ((array) $request->input('remove_images', []) as $imageId) {
            $image = $post->images()->where('ImageID', $imageId)->first();
            if ($image) {
                Storage::disk('public')->delete($image->ImagePath);
                $image->delete();
            }
        }

        $hasPrimary = $post->images()->where('IsPrimary', true)->exists();
        foreach ((array) $request->file('images', []) as $uploadedImage) {
            $path = $uploadedImage->store('posts', 'public');
            $image = PostImage::create([
                'ImageID' => $this->generateImageId(),
                'PostID' => $post->PostID,
                'ImagePath' => $path,
                'IsPrimary' => ! $hasPrimary,
            ]);
            $hasPrimary = true;
        }
    }

    private function generateImageId(): string
    {
        $max = PostImage::selectRaw('MAX(CAST(SUBSTRING(ImageID, 3) AS UNSIGNED)) AS m')->value('m');

        return 'IM'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT);
    }

    private function authorizeOwner(Post $post): void
    {
        abort_unless(
            auth()->user()->UserID === $post->UserID || auth()->user()->isAdmin(),
            403
        );
    }
}

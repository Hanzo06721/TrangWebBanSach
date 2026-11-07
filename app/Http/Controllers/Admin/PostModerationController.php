<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostModeration;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostModerationController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['books', 'users'])
            ->when($request->filled('status'), fn ($query) => $query->where('PostStatus', $request->status))
            ->when(! $request->filled('status'), fn ($query) => $query->where('PostStatus', 'Chờ duyệt'))
            ->latest('PostID')
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.index', ['posts' => $posts]);
    }

    public function approve(Post $post)
    {
        $post->update(['PostStatus' => 'Đã duyệt']);
        $this->recordModeration($post, 'Approved', null);

        return back()->with('success', 'Đã duyệt bài đăng.');
    }

    public function reject(Request $request, Post $post)
    {
        $validated = $request->validate(['Reason' => ['nullable', 'string', 'max:500']]);
        $post->update(['PostStatus' => 'Bị từ chối']);
        $this->recordModeration($post, 'Rejected', $validated['Reason'] ?? null);

        return back()->with('success', 'Đã từ chối bài đăng.');
    }

    public function hide(Post $post)
    {
        $post->update(['PostStatus' => 'Đã ẩn']);
        $this->recordModeration($post, 'Hidden', null);

        return back()->with('success', 'Đã ẩn bài đăng.');
    }

    public function destroy(Post $post)
    {
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->ImagePath);
        }

        $post->delete();

        return back()->with('success', 'Đã xóa bài đăng.');
    }

    public function reports()
    {
        $reports = PostReport::with(['post', 'user'])
            ->when(request()->filled('status'), fn ($query) => $query->where('Status', request('status')))
            ->latest('CreatedAt')
            ->paginate(12);

        return view('admin.posts.reports', ['reports' => $reports]);
    }

    public function resolveReport(PostReport $report)
    {
        $report->update(['Status' => 'Đã xử lý']);

        return back()->with('success', 'Đã đánh dấu báo cáo là đã xử lý.');
    }

    public function rejectReport(PostReport $report)
    {
        $report->update(['Status' => 'Đã từ chối']);

        return back()->with('success', 'Đã từ chối báo cáo này.');
    }

    public function comments(Request $request)
    {
        $comments = PostComment::with(['post', 'user'])
            ->when($request->filled('status'), fn ($query) => $query->where('Status', $request->status))
            ->latest('CreatedAt')
            ->paginate(12)
            ->withQueryString();

        return view('admin.posts.comments', ['comments' => $comments]);
    }

    public function hideComment(PostComment $comment)
    {
        $comment->update(['Status' => 'Đã ẩn']);

        return back()->with('success', 'Đã ẩn bình luận.');
    }

    public function destroyComment(PostComment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận vi phạm.');
    }

    private function recordModeration(Post $post, string $status, ?string $reason): void
    {
        $max = PostModeration::selectRaw('MAX(CAST(SUBSTRING(ModerationID, 3) AS UNSIGNED)) AS m')->value('m');
        PostModeration::create([
            'ModerationID' => 'MD'.str_pad((string) ((int) $max + 1), 3, '0', STR_PAD_LEFT),
            'PostID' => $post->PostID,
            'AdminID' => auth()->user()->UserID,
            'Status' => $status,
            'Reason' => $reason,
        ]);
    }
}

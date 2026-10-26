<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Validators\ValidationException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();
    
        if($request->filled('keyword'))
            {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword)
                {
                    $q->where('BookID', 'like', "%{$keyword}%")->orWhere("Title","like", "%{$keyword}%");
                });
            }
        if($request->filled('CategoryID'))
            {
                $query->where('CategoryID', $request->CategoryID);
            }
        

        if($request-> sort === 'newest')
            {
                $query->orderBy('CreatedAt','desc');
            }
        else if($request->sort === 'oldest')
            {
                $query->orderBy('CreatedAt', 'asc');
            }
        else 
            {
                $query->orderBy('CreatedAt', 'desc');
            }
        $books = $query->paginate(8)->withQueryString();
        $categories = Category::orderBy('CategoryID')->get();
        return view('admin.books.index', compact('books','categories'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'BookID'=> 'required|string|max:10|unique:books,BookID',
            'Title'=> 'required|string|max:200',
            'AuthorName'=>'nullable|string|max:100',
            'ISBN'=> 'required|string|max:30',
            'Publisher'=>'nullable|string|max:100',
            'CategoryID'=>'required|exists:categories,CategoryID',
            'Description'=>'nullable|string|max:500',
            'CoverImage'=>'nullable|string|max:100',
            
        ],
        [
            'BookID.required'=>'Vui lòng nhập mã sách',
            'Title.required'=>'Vui lòng thêm tựa đề sách',
            'ISBN.required'=>'Vui lòng thêm số ISBN',
            'CategoryID.required'=>'Vui lòng chọn thể loại sách',
            'CategoryID.exists'=>'Vui lòng chọn thể loại sách',

            'BookID.unique'=>'Mã sách đã tồn tại, vui lòng nhập lại'
        ]);

        Book::create($validated);
        return redirect()->route('admin.books.index')->with('success','Thêm sách thành công');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.detail', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);
        $validated = $request->validate([
            'Title'=> 'required|string|max:200',
            'AuthorName'=>'nullable|string|max:100',
            'ISBN'=> 'required|string|max:30',
            'Publisher'=>'nullable|string|max:100',
            'CategoryID'=>'required|exists:categories,CategoryID',
            'Description'=>'nullable|string|max:500',
            'CoverImage'=>'nullable|string|max:100',
        ]);
        $book->update($validated);
        return redirect()->route('admin.books.index')->with('success','Cập nhật sách thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();
        return redirect()->route('admin.books.index')->with('success','Xoá sách thành công!');
    }

    public function export()
    {
        return Excel::download(new BooksExport, 'danh_sach_sach.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file'=>'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);
        try
        {
            Excel::import(new BooksImport, $request->file('file'));
        }
        catch(ValidationException $e)
        {
            $message = [];
            foreach($e->failures() as $failure)
                {
                    $message[] = 'Dòng '.$failure->row().':'.implode(', ',$failure->errors());
                }
            return back()->withErrors($message);
        }
        return redirect()->route('admin.books.index')->with('success','Import sách mới từ Excel thành công');
    }
}

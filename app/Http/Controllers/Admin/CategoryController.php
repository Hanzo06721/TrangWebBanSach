<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();
        if($request->filled('keyword'))
        {
                $keyword = $request->input('keyword');
                $query->where(function($q) use ($keyword)
                {
                    $q->where('CategoryID','like',"%{$keyword}%")->orWhere('CategoryName', 'like', "%{$keyword}%");
                });
        }

        if($request->filled('status'))
        {
                $query->where('Status', $request->input('status'));
        }

        $categories = $query->orderBy('CategoryID')->paginate(10)->withQueryString();
        return view('admin.categories.index',compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'CategoryID'=> 'required|string|max:10|unique:categories,CategoryID',
            'CategoryName'=>'required|string|max:100',
            'Description'=>'nullable|string|max:500',
            'Status'=>'required|in:0,1',
        ],
        [
            'CategoryID.required'=>'Vui lòng nhập mã danh mục',
            'CategoryID.unique'=>'Mã danh mục đã tồn tại',
            'CategoryName.required'=>'Vui lòng nhập tên danh mục',
            'Status.in'=>'Trạng thái không hợp lệ'
        ]
        );
        Category::create($validated);
        return redirect()->route('admin.categories.index')->with('success','Thêm danh mục thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return View('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail(   $id );
        $validated= $request->validate([
            'CategoryName'=> 'required|string|max:100',
            'Description'=> 'nullable|string|max:500',
            'Status'=>'required|in:0,1'
        ],
        [
            'CategoryName.required'=>'Vui lòng nhập tên danh mục',
            'Status.in'=>'Trạng thái không hợp lệ'
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success','Cập nhật danh mục thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        if($category->books()->exists())
            {
                return redirect()->route('admin.categories.index')->with('error','Không thể xoá danh mục đã có sách');
            }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success','Xoá danh mục thành công!');
    }
}

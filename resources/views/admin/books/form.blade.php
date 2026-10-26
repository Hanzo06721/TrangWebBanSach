<div class="mb-3">
    <label class="form-label">Mã sách</label>
    <input class="form-control" type="text" name="BookID" value="{{ old('BookID', $book->BookID ?? '') }}" 
    {{ isset($book)? 'readonly' : ''}} required> 
</div>

<div class="mb-3">
    <label class="form-label">Tựa đề sách</label>
    <input class="form-control" type="text" name="Title" value="{{ old('Title', $book->BookName ?? '') }}"
    required>
</div>

<div class="mb-3">
    <label class="form-label">Tên tác giả</label>
    <input class="form-control" type="text" name="AuthorName" value="{{ old('AuthorName', $book->AuthorName ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">ISBN</label>
    <input class="form-control" type="text" name="ISBN" value="{{ old('ISBN', $book->ISBN ?? '') }}"
    required>
</div>

<div class="mb-3">
    <label class="form-label">Nhà xuất bản</label>
    <input class="form-control" type="text" name="Publisher" value="{{ old('Publisher', $book->Publisher ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="Description" class="form-control" rows="3">
        {{ old('Description', $book->Description??'') }}
    </textarea>
</div>

<div class="mb-3">
    <label class="form-label">Đường truyền ảnh bìa</label>
    <input class="form-control" type="text" name="CoverImage" value="{{ old('CoverImage', $book->CoverImage ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Thể loại</label>
    <select name="CategoryID" class="form-select" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach($categories as $category)
            <option value="{{ $category->CategoryID }}" {{old('CategoryID') === $category->CategoryID ? 'selected': ''}}>
                {{ $category->CategoryName }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($book)?'Cập nhật':'Thêm sách' }}
</button>
<a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
    Quay lại
</a>
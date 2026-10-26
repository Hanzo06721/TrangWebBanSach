<div class="mb-3">
    <label class="form-label">Mã danh mục</label>
    <input class="form-control" type="text" name="CategoryID" value="{{ old('CategoryID', $category->CategoryID ?? '') }}" 
    {{ isset($category)? 'readonly' : ''}} required> 
</div>

<div class="mb-3">
    <label class="form-label">Tên danh mục</label>
    <input class="form-control" type="text" name="CategoryName" value="{{ old('CategoryName', $category->CategoryName ?? '') }}"
    required>
</div>

<div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="Description" class="form-control" rows="3">
        {{ old('Description', $category->Description??'') }}
    </textarea>
</div>

<div class="mb-3">
    <label class="form-label">Trạng thái</label>
    <select name="Status" class="form-select" required>
        <option value="1"{{ (string) old('Status', isset($category)? (int)$category->Status:1)==='1'? 'selected':'' }}>Hoạt động</option>
        <option value="0"{{ (string) old('Status', isset($category)? (int)$category->Status:1)==='0'? 'selected':'' }}>Ngừng hoạt động</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">
    {{ isset($category)?'Cập nhật':'Thêm danh mục' }}
</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
    Quay lại
</a>
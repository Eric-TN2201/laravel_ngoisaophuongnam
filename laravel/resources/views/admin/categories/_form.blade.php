<div class="form-group">
    <label for="category-name">Tên danh mục</label>
    <input type="text" class="form-control {{ $errors->has('name') || $errors->has('slug') ? 'is-invalid' : '' }}"
        id="category-name" name="name" value="{{ old('name', $category->name ?? '') }}">

    @if ($errors->has('name'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('name') }}
        </div>
    @endif

    @if ($errors->has('slug'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('slug') }}
        </div>
    @endif
</div>
<div class="form-group mt-3">
    <label for="category-status">Trạng thái</label>
    <select id="category-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="1" {{ (string) old('status', $category->status ?? '1') === '1' ? 'selected' : '' }}>Hoạt
            động</option>
        <option value="0" {{ (string) old('status', $category->status ?? '1') === '0' ? 'selected' : '' }}>Tạm dừng
        </option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="category-image">Ảnh danh mục</label>
    <input type="file" id="category-image" name="thumbnail"
        class="form-control {{ $errors->has('thumbnail') ? 'is-invalid' : '' }}" accept="image/*">
    @if (isset($category) && $category->thumbnail)
        <div class="mt-2">
            <img src="{{ Storage::url($category->thumbnail) }}" alt="{{ $category->name }}"
            style="max-width: 150px;" style="object-fit: cover;">
        </div>
    @endif
    @if ($errors->has('thumbnail'))
        <div class="invalid-feedback text-md d-block">
            {{ $errors->first('thumbnail') }}
        </div>
    @endif
</div>
<div>
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.category.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

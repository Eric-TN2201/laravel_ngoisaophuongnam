<div class="form-group">
    <label for="sub-category-category">Danh mục cha</label>
    <select id="sub-category-category" name="category_id" class="form-control {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
        <option value="">-- Chọn danh mục --</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ (int) old('category_id', $subCategory->category_id ?? 0) === (int) $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    @if ($errors->has('category_id'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('category_id') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="sub-category-name">Tên danh mục con</label>
    <input type="text" class="form-control {{ $errors->has('name') || $errors->has('slug') ? 'is-invalid' : '' }}" id="sub-category-name"
        name="name" value="{{ old('name', $subCategory->name ?? '') }}">

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
    <label for="sub-category-status">Trạng thái</label>
    <select id="sub-category-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="1" {{ (string) old('status', $subCategory->status ?? 1) === '1' ? 'selected' : '' }}>Hoạt động</option>
        <option value="0" {{ (string) old('status', $subCategory->status ?? 1) === '0' ? 'selected' : '' }}>Tạm dừng</option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.sub-category.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

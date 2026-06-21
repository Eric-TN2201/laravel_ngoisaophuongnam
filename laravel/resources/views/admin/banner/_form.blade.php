<div class="form-group">
    <label for="banner-name">Tên banner</label>
    <input type="text" id="banner-name" name="title"
        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
        value="{{ old('title', $banner->title ?? '') }}">

    @if ($errors->has('title'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('title') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="banner-link">URL</label>
    <input type="url" id="banner-link" name="link"
        class="form-control {{ $errors->has('link') ? 'is-invalid' : '' }}"
        value="{{ old('link', $banner->link ?? '') }}" placeholder="https://example.com">

    @if ($errors->has('link'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('link') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="banner-image">Hình ảnh</label>
    <input type="file" id="banner-image" name="image"
        class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" accept="image/*">

    @if (isset($banner) && $banner->image)
        <div class="mt-2">
            <img src="{{ Storage::url($banner->image) }}" width="120" height="120" style="object-fit: cover;">
        </div>
    @endif

    @if ($errors->has('image'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('image') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="banner-status">Trạng thái</label>
    <select id="banner-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="1" {{ (string) old('status', $banner->status ?? '1') === '1' ? 'selected' : '' }}>Hoạt động
        </option>
        <option value="0" {{ (string) old('status', $banner->status ?? '1') === '0' ? 'selected' : '' }}>Tạm dừng
        </option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="mt-3">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.banner.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

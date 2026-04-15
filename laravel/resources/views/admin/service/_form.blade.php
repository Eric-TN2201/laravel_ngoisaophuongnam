@csrf

<div class="form-group">
    <label for="service-title">Tiêu đề</label>
    <input type="text" id="service-title" name="title"
        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
        value="{{ old('title', $service->title ?? '') }}">

    @if ($errors->has('title'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('title') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="service-image">Hình ảnh</label>
    <input type="file" id="service-image" name="image"
        class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" accept="image/*">

    @if (isset($service) && $service->image)
        <div class="mt-2">
            <img src="{{ Storage::url($service->image) }}" width="120" height="120" style="object-fit: cover;">
        </div>
    @endif

    @if ($errors->has('image'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('image') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="service-description">Mô tả</label>
    <textarea id="service-description" name="description"
        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="6">{{ old('description', $service->description ?? '') }}</textarea>

    @if ($errors->has('description'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('description') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="service-status">Trạng thái</label>
    <select id="service-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="0" {{ old('status', $service->status ?? 0) === 0 ? 'selected' : '' }}>Chưa đăng</option>
        <option value="1" {{ old('status', $service->status ?? 0) === 1 ? 'selected' : '' }}>Đã đăng</option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="mt-4 mb-3">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.service.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        license_key: 'gpl',
        selector: '#service-description',
        plugins: 'autolink charmap codesample emoticons link lists image media',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
        height: 400,
        language: 'vi',
    });
</script>

@csrf

<div class="form-group">
    <label for="news-title">Tiêu đề</label>
    <input type="text" id="news-title" name="title"
        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
        value="{{ old('title', $news->title ?? '') }}">

    @if ($errors->has('title'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('title') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="news-banner">Banner</label>
    <input type="file" id="news-banner" name="banner"
        class="form-control {{ $errors->has('banner') ? 'is-invalid' : '' }}" accept="image/*">

    @if (isset($news) && $news->banner)
        <div class="mt-2">
            <img src="{{ Storage::url($news->banner) }}" width="120" height="120" style="object-fit: cover;">
        </div>
    @endif

    @if ($errors->has('banner'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('banner') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="news-description">Nội dung</label>
    <textarea id="news-description" name="description"
        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="6">{{ old('description', $news->description ?? '') }}</textarea>

    @if ($errors->has('description'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('description') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="news-address">Địa chỉ</label>
    <input type="text" id="news-address" name="address"
        class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
        value="{{ old('address', $news->address ?? '') }}">

    @if ($errors->has('address'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('address') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="news-time-start">Thời gian bắt đầu</label>
    <input type="datetime-local" id="news-time-start" name="time_start"
        class="form-control {{ $errors->has('time_start') ? 'is-invalid' : '' }}"
        value="{{ old('time_start', isset($news->time_start) ? $news->time_start->format('Y-m-d\TH:i') : '') }}">

    @if ($errors->has('time_start'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('time_start') }}
        </div>
    @endif
</div>

<div class="form-group mt-3">
    <label for="news-status">Trạng thái</label>
    <select id="news-status" name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
        <option value="0" {{ old('status', $news->status ?? 0) === 0 ? 'selected' : '' }}>Chưa đăng</option>
        <option value="1" {{ old('status', $news->status ?? 0) === 1 ? 'selected' : '' }}>Đã đăng</option>
    </select>

    @if ($errors->has('status'))
        <div class="invalid-feedback text-md">
            {{ $errors->first('status') }}
        </div>
    @endif
</div>

<div class="mt-4 mb-3">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.news.index') }}" class="text-primary ml-2">Trở lại</a>
</div>

<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        license_key: 'gpl',
        selector: '#news-description',
        plugins: 'autolink charmap codesample emoticons link lists image media',
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image media link | removeformat',
        height: 600,
        language: 'vi',
        images_upload_url: '{{ route('admin.news.upload') }}',
        automatic_uploads: true,
        images_upload_handler: function (blobInfo) {
            return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '{{ route('admin.news.upload') }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                xhr.onload = function() {
                    if (xhr.status !== 200) {
                        reject('HTTP Error: ' + xhr.status);
                        return;
                    }
                    try {
                        var json = JSON.parse(xhr.responseText);
                        // console.log(json);

                        if (!json || typeof json.location !== 'string') {
                            reject('Invalid JSON: ' + xhr.responseText);
                            return;
                        }
                        resolve(json.location);
                    } catch (e) {
                        reject('Error parsing response: ' + e.message);
                    }
                };
                xhr.onerror = function() {
                    reject('Upload failed');
                };
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });
        }
    });
</script>

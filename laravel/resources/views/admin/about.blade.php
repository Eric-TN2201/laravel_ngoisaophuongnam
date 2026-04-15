@extends('layouts.admin')

@section('title', 'Lời giới thiệu')

{{-- @section('content_header')
        <h1>Thêm sản phẩm</h1>
    @stop --}}

@section('content_header_subtitle', 'Lời giới thiệu')

@section('content')
    <div class="card">
        <div class="card-body row">
            <div class="col-12 col-lg-8">
                <form action="{{ route('admin.story.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="story_content">Nội dung</label>
                        <textarea id="story_content" name="story_content" class="form-control" rows="10">{{ old('story_content', $story ?? '') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Lưu</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- TinyMCE or CKEditor integration -->
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            license_key: 'gpl',
            selector: '#story_content',
            plugins: 'autolink charmap codesample emoticons link lists searchreplace wordcount image',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | insertTwoCol',
            height: 400,
            language: 'vi',
            valid_elements: '*[*]',
            extended_valid_elements: 'div[class|style],img[src|alt|class|style]',
            images_upload_url: '{{ route("admin.story.upload") }}',
            automatic_uploads: true,
            images_upload_handler: function(blobInfo, progress) {
                return new Promise(function(resolve, reject) {
                    var formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    formData.append('_token', '{{ csrf_token() }}');

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route("admin.story.upload") }}');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var json = JSON.parse(xhr.responseText);
                            resolve(json.location);
                        } else {
                            reject('Upload thất bại: ' + xhr.status);
                        }
                    };
                    xhr.onerror = function() {
                        reject('Upload thất bại do lỗi mạng');
                    };
                    xhr.send(formData);
                });
            },
            content_style: `
                .two-col { display: flex; gap: 20px; margin: 16px 0; }
                .two-col .col-left, .two-col .col-right { flex: 1; min-width: 0; }
                @media (max-width: 768px) { .two-col { flex-direction: column; } }
            `,

            setup: function(editor) {

                // 🔥 NÚT CUSTOM - 2 CỘT
                editor.ui.registry.addButton('insertTwoCol', {
                    text: '2 Cột',
                    tooltip: 'Chèn bố cục 2 cột',
                    onAction: function() {
                        editor.insertContent(`
                            <div class="two-col" style="display:flex;gap:10px;margin:16px 0">
                                <div class="col-left" style="flex:1;min-width:0">
                                    <p>Nội dung cột trái...</p>
                                </div>
                                <div class="col-right" style="flex:1;min-width:0">
                                    <p>Nội dung cột phải...</p>
                                </div>
                            </div>
                        `);
                    }
                });

            }
        });
    </script>
@endsection

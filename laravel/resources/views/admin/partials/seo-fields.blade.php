@php
    $titleField = $titleField ?? 'meta_title';
    $descriptionField = $descriptionField ?? 'meta_description';
    $seoRecord = $seoRecord ?? null;
    $previewTitle = old($titleField, $seoTitle ?? $seoRecord?->meta_title ?? '');
    $previewDescription = old($descriptionField, $seoDescription ?? $seoRecord?->meta_description ?? '');
    $fallbackTitle = $fallbackTitle ?? $seoRecord?->name ?? $seoRecord?->title ?? '';
    $pageRoute = $seoPageRoute ?? match (explode('.', request()->route()->getName())[1] ?? '') {
        'product' => 'product.show',
        'category', 'sub-category' => 'product.category',
        'news' => 'news.show',
        'service' => 'service.show',
    };
    $initialSeo = \App\Support\Seo::preview($pageRoute, [
        'name' => old('name', old('title', $fallbackTitle)),
        'description' => old('description', $seoRecord?->description ?? ''),
        'meta_title' => $previewTitle, 'meta_description' => $previewDescription,
    ]);
@endphp
<fieldset class="border rounded bg-light p-3 my-4" data-seo-editor
    data-page-route="{{ $pageRoute }}" data-preview-url="{{ route('admin.seo.preview') }}">
    <legend class="w-auto h5 px-2 font-weight-bold">{{ $seoLabel ?? 'SEO' }}</legend>
    <div class="form-group">
        <label for="{{ $titleField }}">Tiêu đề SEO</label>
        <input id="{{ $titleField }}" name="{{ $titleField }}" value="{{ $previewTitle }}" maxlength="255" class="form-control" data-seo-title>
        @error($titleField)<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label for="{{ $descriptionField }}">Mô tả SEO</label>
        <textarea id="{{ $descriptionField }}" name="{{ $descriptionField }}" maxlength="500" rows="3" class="form-control" data-seo-description>{{ $previewDescription }}</textarea>
        @error($descriptionField)<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <small class="text-muted">Để trống để dùng nội dung tự động. Xem trước mang tính minh họa; Google có thể hiển thị khác.</small>
    <div class="border rounded bg-white p-3 mt-2">
        <div class="text-primary h5" data-seo-preview-title>{{ $initialSeo['title'] }}</div>
        <div data-seo-preview-description>{{ $initialSeo['description'] }}</div>
        <small class="text-muted" data-seo-count>{{ mb_strlen($initialSeo['title']) }} ký tự tiêu đề · {{ mb_strlen($initialSeo['description']) }} ký tự mô tả hiển thị</small>
    </div>
</fieldset>
@once
    <script src="{{ asset('js/seo-preview.js') }}" defer></script>
@endonce

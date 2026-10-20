@extends('layouts.admin')

@section('title', 'SEO website')
@section('content_header_subtitle', 'SEO website')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-9">
            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-header">SEO các trang chính</div>
                <div class="card-body">
                    <p class="text-muted">Quản lý tiêu đề và mô tả của các trang chính. SEO từng sản phẩm, danh mục, tin tức và dịch vụ được chỉnh tại trang sửa nội dung tương ứng.</p>
                    <form method="POST" action="{{ route('admin.seo.update') }}">
                        @csrf
                        @method('PUT')
                        @foreach (\App\Support\Seo::STATIC_PAGES as $seoRoute => $seoPageLabel)
                            @php($seoPrefix = 'seo_'.str_replace('.', '_', $seoRoute))
                            @include('admin.partials.seo-fields', [
                                'titleField' => $seoPrefix.'_title',
                                'descriptionField' => $seoPrefix.'_description',
                                'seoTitle' => $settings[$seoPrefix.'_title'] ?? '',
                                'seoDescription' => $settings[$seoPrefix.'_description'] ?? '',
                                'fallbackTitle' => $seoPageLabel,
                                'seoLabel' => $seoPageLabel,
                                'seoPageRoute' => $seoRoute,
                            ])
                        @endforeach
                        <button type="submit" class="btn btn-primary">Lưu SEO</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

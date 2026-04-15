@extends('layouts.client')

@section('title', isset($subCategory) && $subCategory ? $subCategory->name : $category->name)

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Sản phẩm</a></li>
                    @if (isset($subCategory) && $subCategory)
                        <li class="breadcrumb-item">
                            <a href="{{ route('product.category', ['category' => $category->slug]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $subCategory->name }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                    @endif
                </ol>
            </nav>

            {{-- Title --}}
            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">
                    {{ isset($subCategory) && $subCategory ? $subCategory->name : $category->name }}
                </h2>
            </div>

            {{-- Sub-category filter --}}
            @if (isset($subCategories) && $subCategories->count())
                <div class="mb-4 text-center">
                    <a href="{{ route('product.category', ['category' => $category->slug]) }}"
                        class="btn btn-sm rounded-pill px-3 mb-2 {{ !isset($subCategory) || !$subCategory ? 'main-btn' : 'btn-outline-secondary' }}">
                        Tất cả
                    </a>

                    @foreach ($subCategories as $sub)
                        <a href="{{ route('product.category', ['category' => $category->slug, 'subCategory' => $sub->slug]) }}"
                            class="btn btn-sm rounded-pill px-3 mb-2 {{ isset($subCategory) && $subCategory && $subCategory->id === $sub->id ? 'main-btn' : 'btn-outline-secondary' }}">
                            {{ $sub->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Product grid --}}
            <div id="product-list"></div>
            @if ($products->count())
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3 mb-4 px-2 fade-in-up">
                            <x-product.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->fragment('product-list')->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="text-muted">Không có sản phẩm nào trong mục này.</p>
                    <x-common.button onclick="window.location='{{ route('product.index') }}'">
                        Quay lại danh mục
                    </x-common.button>
                </div>
            @endif
        </div>
    </section>

    <x-product.quick-view />
@endsection

@push('scripts')
    <script src="{{ asset('js/quick-view.js') }}"></script>
@endpush

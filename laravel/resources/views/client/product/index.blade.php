@extends('layouts.client')

@section('title', 'Sản phẩm')

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                </ol>
            </nav>

            {{-- Danh mục --}}
            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Danh mục sản phẩm</h2>
            </div>
            <div class="row mb-4">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-4 col-lg-3 mb-4 fade-in-up">
                        <div class="card h-100 hover-card category-card" style="cursor:pointer;"
                            onclick="window.location='{{ route('product.category', ['category' => $category->slug]) }}'">
                            <img src="{{ $category->thumbnail ? asset('storage/' . $category->thumbnail) : asset('images/no-image-available.jpg') }}"
                                class="card-img-top product-thumb-img" alt="{{ $category->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title mb-0">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tất cả sản phẩm --}}
            <div id="product-list" class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Tất cả sản phẩm</h2>
            </div>
            @if ($products->count())
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3 mb-4 fade-in-up">
                            <x-product.product-card :product="$product" />
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->fragment('product-list')->links() }}
                </div>
            @else
                <p class="text-center text-muted">Chưa có sản phẩm nào.</p>
            @endif
        </div>
    </section>

    <x-product.quick-view />
@endsection

@push('scripts')
    <script src="{{ asset('js/quick-view.js') }}"></script>
@endpush

@extends('layouts.client')

@section('title', $product->meta_title ?? $product->name)

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Sản phẩm</a></li>
                    @if ($product->category)
                        <li class="breadcrumb-item">
                            <a href="{{ route('product.category', ['category' => $product->category->slug]) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>

            {{-- Product Detail --}}
            <div class="row mb-4">
                {{-- Image --}}
                <div class="col-md-5 mb-3">
                    <div class="product-detail-img-wrap">
                        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/no-image-available.jpg') }}"
                            class="img-fluid rounded shadow-sm w-100" alt="{{ $product->name }}">
                    </div>
                </div>

                {{-- Info --}}
                <div class="col-md-7">
                    <h1 class="h3 mb-3">{{ $product->name }}</h1>

                    <div class="mb-3">
                        <p><strong>Danh mục:</strong> <span>{{ $product->category->name }}</span></p>
                        @if ($product->subCategory)
                            <p><strong>Phân loại:</strong> <span>{{ $product->subCategory->name }}</span></p>
                        @endif
                    </div>

                    @if ($product->description)
                        <div class="product-detail-desc">
                            {!! $product->description !!}
                        </div>
                    @endif

                    <div class="mt-4">
                        <x-common.button onclick="window.location='tel:{{ setting('company_phone') }}'">
                            <i class="fas fa-phone-alt mr-1"></i> Liên hệ đặt hàng
                        </x-common.button>
                    </div>
                </div>
            </div>

            {{-- Related Products --}}
            @if (isset($relatedProducts) && $relatedProducts->count())
                <div class="mt-4">
                    <h2 class="h4 mb-0 text-center title-line mb-3">Sản phẩm liên quan</h2>
                    <div class="row">
                        @foreach ($relatedProducts as $related)
                            <div class="col-6 col-md-4 col-lg-3 mb-4">
                                <x-product.product-card :product="$related" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-product.quick-view />
@endsection

@push('scripts')
    <script src="{{ asset('js/quick-view.js') }}"></script>
@endpush

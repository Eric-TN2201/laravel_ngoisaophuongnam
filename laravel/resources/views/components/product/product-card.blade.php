@props(['product'])
@php
    $isLongCategory = Str::length($product->category->name) > 28;
@endphp
<div class="card h-100 hover-card quick-view-btn" data-name="{{ $product->name }}"
    data-category="{{ $product->category->name }}"
    data-subcategory="{{ $product->subCategory ? $product->subCategory->name : 'Không' }}"
    data-image="{{ asset('storage/' . $product->thumbnail) }}" data-link="{{ route('product.show', $product) }}"
    data-description="{!! $product->description ? Str::limit(strip_tags($product->description), 300) : '' !!}">
    <div class="card-img-top d-flex align-items-center justify-content-center overflow-hidden bg-light"
        style="aspect-ratio: 1 / 1; min-height: 180px;">
        <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/no-image-available.jpg') }}"
            class="product-thumb-img" alt="{{ $product->name }}"
            style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="card-body py-3 px-2 text-center">
        <p class="card-text small mb-1 product-category-line" title="{{ $product->category->name }}">
            <span class="product-category-marquee{{ $isLongCategory ? ' is-long' : '' }}">{{ $product->category->name }}</span>
        </p>
        <h5 class="card-title">{{ $product->name }}</h5>
        <div class="d-flex flex-column">
            <x-common.button onclick="window.location='{{ route('product.show', ['product' => $product->slug]) }}'">Xem
                chi tiết</x-common.button>
        </div>
    </div>
</div>

@props(['product'])
<div class="card h-100 hover-card quick-view-btn" data-name="{{ $product->name }}"
    data-category="{{ $product->category->name }}"
    data-subcategory="{{ $product->subCategory ? $product->subCategory->name : 'Không' }}"
    data-image="{{ asset('storage/' . $product->thumbnail) }}" data-link="{{ route('product.show', $product) }}"
    data-description="{!! $product->description ? Str::limit(strip_tags($product->description), 300) : '' !!}">
    <img src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : asset('images/no-image-available.jpg') }}"
        class="card-img-top product-thumb-img" alt="{{ $product->name }}">
    <div class="card-body text-center">
        <p class="card-text small mb-1">{{ $product->category->name }}</p>
        <h5 class="card-title">{{ $product->name }}</h5>
        <div class="d-flex flex-column">
            <x-common.button onclick="window.location='{{ route('product.show', ['product' => $product->slug]) }}'">Xem
                chi tiết</x-common.button>
        </div>
    </div>
</div>

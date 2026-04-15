@props(['news', 'index', 'reverse' => true])
@php
    $isEven = $index % 2 === 0;
@endphp
<div class="row align-items-center h-100 hover-card {{ $reverse && !$isEven ? 'flex-row-reverse' : '' }}">
    <div class="col-md-6">
        <img src="{{ $news->banner ? asset('storage/' . $news->banner) : asset('images/no-image-available.jpg') }}"
            class="img-fluid rounded product-thumb-img" alt="{{ $news->title }}">
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
        <h5 class="card-title">{{ $news->title }}</h5>
        <p class="card-text small text-muted mb-1">
            <i class="far fa-calendar-alt mr-1"></i>
            {{ $news->created_at ? $news->created_at->format('d-m-Y') : '' }}
        </p>
        <p class="card-text small">{!! \Illuminate\Support\Str::limit(strip_tags($news->description), 500) !!}</p>
        <div class="d-flex justify-content-center">
            <x-common.button onclick="window.location='{{ route('news.show', $news) }}'">Xem chi tiết</x-common.button>
        </div>
    </div>
</div>

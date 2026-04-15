@props(['service', 'index', 'reverse' => true])
@php
    $isEven = $index % 2 === 0;
@endphp
<div class="row align-items-center h-100 hover-card {{ $reverse && !$isEven ? 'flex-row-reverse' : '' }}">
    <div class="col-md-6">
        <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('images/no-image-available.jpg') }}"
            class="img-fluid rounded product-thumb-img" alt="{{ $service->title }}">
    </div>
    <div class="col-md-6 mt-3 mt-md-0">
        <h5 class="card-title">{{ $service->title }}</h5>
        <p class="card-text small text-muted mb-1">
            <i class="far fa-calendar-alt mr-1"></i>
            {{ $service->created_at ? $service->created_at->format('d-m-Y') : '' }}
        </p>
        <p class="card-text small">{!! \Illuminate\Support\Str::limit(strip_tags($service->description), 500) !!}</p>
        <div class="d-flex justify-content-center">
            <x-common.button onclick="window.location='{{ route('service.show', $service) }}'">Xem chi
                tiết</x-common.button>
        </div>
    </div>
</div>

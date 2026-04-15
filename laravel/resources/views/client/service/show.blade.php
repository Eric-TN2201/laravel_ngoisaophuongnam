@extends('layouts.client')

@section('title', $service->title)

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Dịch vụ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $service->title }}</li>
                </ol>
            </nav>

            <article class="news-detail">
                <h1 class="h3 mb-2">{{ $service->title }}</h1>

                <div class="mb-3 text-muted small">
                    <span>
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ $service->created_at->format('d/m/Y') }}
                    </span>
                </div>

                @if ($service->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid rounded shadow-sm w-100"
                            alt="{{ $service->title }}">
                    </div>
                @endif

                <div class="news-detail-content">
                    {!! $service->description !!}
                </div>
            </article>
        </div>
    </section>
@endsection

@extends('layouts.client')

@section('title', $news->title)

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $news->title }}</li>
                </ol>
            </nav>

            {{-- Article --}}
            <article class="news-detail">
                <h1 class="h3 mb-2">{{ $news->title }}</h1>

                <div class="mb-3 text-muted small">
                    @if ($news->time_start)
                        <span class="mr-3">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ $news->time_start->format('d/m/Y H:i') }}
                        </span>
                    @endif
                    @if ($news->address)
                        <span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $news->address }}
                        </span>
                    @endif
                </div>

                @if ($news->banner)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $news->banner) }}" class="img-fluid rounded shadow-sm w-100"
                            alt="{{ $news->title }}">
                    </div>
                @endif

                <div class="news-detail-content">
                    {!! $news->description !!}
                </div>
            </article>

            {{-- Tin tức mới nhất --}}
            @if (isset($latestNews) && $latestNews->count())
                <div class="mt-5">
                    <h2 class="h4 mb-0 text-center title-line mb-3">Tin tức khác</h2>
                    <div class="row">
                        @foreach ($latestNews as $item)
                            <div class="col-12 col-md-6 col-lg-3 mb-4">
                                <div class="card h-100 hover-card news-card" style="cursor:pointer;"
                                    onclick="window.location='{{ route('news.show', $item) }}'">
                                    <img src="{{ $item->banner ? asset('storage/' . $item->banner) : asset('images/no-image-available.jpg') }}"
                                        class="card-img-top news-thumb-img" alt="{{ $item->title }}">
                                    <div class="card-body">
                                        <p class="card-text small text-muted mb-1">
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $item->time_start ? $item->time_start->format('d/m/Y') : '' }}
                                        </p>
                                        <h5 class="card-title small">{{ $item->title }}</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

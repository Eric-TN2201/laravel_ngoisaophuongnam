@extends('layouts.client')

@section('title', 'Dịch vụ')

@section('content')
    <section class="mb-3">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dịch vụ</li>
                </ol>
            </nav>

            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Dịch vụ</h2>
            </div>

            @if ($services->count())
                <div class="row">
                    @foreach ($services as $service)
                        <div class="col-12 mb-4 fade-in-left">
                            <x-service.service-card :service="$service" :index="$loop->index" :reverse="false" />
                        </div>
                        @if (!$loop->last)
                            <hr class="mb-4" style="border-top: 1px solid #b6b6b6; width: 60%; margin: 0 auto;">
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-center text-muted py-5">Chưa có dịch vụ nào.</p>
            @endif
        </div>
    </section>
@endsection

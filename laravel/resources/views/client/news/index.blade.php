@extends('layouts.client')

@section('title', 'Tin tức')

@section('content')
    <section class="mb-3" style="min-height: 60vh;">
        <div class="container-fix">
            {{-- Breadcrumb --}}
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tin tức</li>
                </ol>
            </nav>

            <div class="mb-3">
                <h2 class="h4 mb-0 text-center title-line">Tin tức</h2>
            </div>

            @if ($newsItems->count())
                <div class="row">
                    @foreach ($newsItems as $item)
                        <div class="mb-4 fade-in-left">
                            <x-news.news-card :news="$item" :index="$loop->index" :reverse="false" />
                        </div>
                        @if (!$loop->last)
                            <hr class="mb-4" style="border-top: 1px solid #b6b6b6; width: 60%; margin: 0 auto;">
                        @endif
                    @endforeach
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $newsItems->links() }}
                </div>
            @else
                <p class="text-center text-muted py-5">Chưa có tin tức nào.</p>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.detail-view-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
@endpush

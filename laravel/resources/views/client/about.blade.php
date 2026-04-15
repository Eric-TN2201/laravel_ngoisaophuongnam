@extends('layouts.client')

@section('title', 'Giới thiệu')

@section('content')
    <section class="mb-3 p-3">
        <div class="container-fix">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Giới thiệu</li>
                </ol>
            </nav>
            <div>
                {{-- <div style="background: #fff; max-width: 900px; margin: 0 auto; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08); padding: 40px;"> --}}
                <h1>Giới thiệu</h1>
                <div class="about-story-content">{!! $story !!}</div>
                {{-- </div> --}}
            </div>
        </div>
    </section>
@endsection

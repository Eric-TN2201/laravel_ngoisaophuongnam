@extends('layouts.client')

@section('title', '404 - Không tìm thấy trang')

@section('content')
    <section class="py-4">
        <div class="container-fix">
            <h1 class="h3 mb-4">404 - Không tìm thấy trang</h1>
            <div class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                <div class="text-center">
                    <h1 class="display-1 fw-bold text-muted">404</h1>
                    <h2 class="mb-3">Không tìm thấy trang</h2>
                    <p class="text-muted mb-4">Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home mr-1"></i> Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

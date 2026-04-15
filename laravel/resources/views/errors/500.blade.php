@extends('layouts.client')

@section('title', '500 - Lỗi hệ thống')

@section('content')
    <section class="d-flex align-items-center justify-content-center" style="min-height: 60vh;">
        <div class="text-center">
            <h1 class="display-1 fw-bold text-muted">500</h1>
            <h2 class="mb-3">Lỗi hệ thống</h2>
            <p class="text-muted mb-4">Đã xảy ra lỗi từ phía máy chủ. Vui lòng thử lại sau.</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home mr-1"></i> Quay về trang chủ
            </a>
        </div>
    </section>
@endsection

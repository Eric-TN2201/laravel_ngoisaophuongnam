@extends('layouts.admin')

    @section('title', 'Trang chủ')

    @section('content_header')
        <h1>Trang chủ</h1>
    @stop

    @section('content')
        <div class="row g-3">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $productCount ?? 0 }}</h3>
                        <p class="mb-0">Tổng số sản phẩm</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-white" href="{{ route('admin.product.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-info text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $categoryCount ?? 0 }}</h3>
                        <p class="mb-0">Danh mục</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-white" href="{{ route('admin.category.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $subCategoryCount ?? 0 }}</h3>
                        <p class="mb-0">Danh mục con</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-dark" href="{{ route('admin.sub-category.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-secondary text-dark h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $newsCount ?? 0 }}</h3>
                        <p class="mb-0">Tin tức/ Sự kiện</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-dark" href="{{ route('admin.news.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-success text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $activeCount ?? 0 }}</h3>
                        <p class="mb-0">Sản phẩm đang hoạt động</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-white" href="{{ route('admin.product.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                <div class="card bg-danger text-white h-100">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $pausedCount ?? 0 }}</h3>
                        <p class="mb-0">Sản phẩm đang tạm dừng</p>
                    </div>
                    <div class="card-footer bg-transparent text-center">
                        <a class="text-white" href="{{ route('admin.product.index') }}">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        {{-- Add here extra stylesheets --}}
        {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @stop

    @section('js')
        <script>
            // small script placeholder if needed
        </script>
    @stop

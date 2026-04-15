@extends('layouts.admin')

    @section('title', 'Thêm sản phẩm')

    {{-- @section('content_header')
        <h1>Thêm sản phẩm</h1>
    @stop --}}

    @section('content_header_subtitle', 'Thêm sản phẩm')
    @section('content')
        <div class="card">
            <div class="card-body row">
                <div class="col-12 col-lg-8">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.product.store') }}">
                        @csrf
                        @include('admin.products._form')
                    </form>
                </div>
            </div>
        </div>
    @stop

    @section('css')
        {{-- Add here extra stylesheets --}}
    @stop

    @section('js')
        <script>
            @if (session('success'))
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'Thành công',
                    body: '{{ session('success') }}',
                    autohide: true,
                    delay: 2500
                })
            @endif

            @if (session('error'))
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Lỗi',
                    body: '{{ session('error') }}',
                    autohide: true,
                    delay: 3000
                })
            @endif
        </script>
    @stop


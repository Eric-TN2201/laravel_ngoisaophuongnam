@extends('layouts.admin')

    @section('title', 'Thêm danh mục con')

    {{-- @section('content_header')
        <h1>Thêm danh mục con</h1>
    @stop --}}

    @section('content_header_subtitle', 'Danh mục con')
    @section('content')
        <div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <form method="POST" action="{{ route('admin.sub-category.store') }}">
                        @csrf
                        @include('admin.sub-categories._form')
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


@extends('layouts.admin')

    @section('title', 'Thêm banner')

    {{-- @section('content_header')
    <h1>Thêm banner</h1>
@stop --}}
    @section('content_header_subtitle', 'Thêm banner')

    @section('content')
        <div class="card">
            <div class="card-body row">
                <div class="col-12 col-lg-6">
                    <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('admin.banner._form')
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('css')
        {{-- Add here extra stylesheets --}}
        {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
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

@extends('layouts.admin')

    @section('title', 'Cập nhật dịch vụ')

    @section('content_header_subtitle', 'Cập nhật dịch vụ')
    @section('content')
        <div class="card">
            <div class="card-body row">
                <div class="col-12 col-lg-8">
                    <form method="POST" enctype="multipart/form-data" action="{{ route('admin.service.update', $service) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.service._form')
                    </form>
                </div>
            </div>
        </div>
    @stop

    @section('css')
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


<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @extends('adminlte::page')

    {{-- Extend and customize the browser title --}}

    @section('title')
        {{ config('adminlte.title') }}
        @hasSection('subtitle')
            | @yield('subtitle')
        @endif
    @stop

    {{-- Extend and customize the page content header --}}

    @section('content_header')
        {{-- @hasSection('content_header_title') --}}
        <h1 class="text-muted">
            <a href="{{ route('admin.home') }}">
                <small>Trang chủ</small>
            </a>

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
        {{-- @endif --}}
    @stop

    {{ $slot }}

    @section('footer')
        <div class="float-right">
            Version: {{ config('app.version', '1.0.0') }}
        </div>

        <strong>
            <a href="{{ config('app.company_url', '#') }}">
                {{ setting('company_name', 'My company') }}
            </a>
        </strong>
    @stop
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Task Manager'))</title>

    <!-- Bootstrap 5.3.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    @vite(['resources/css/app.css'])
    @stack('styles')
</head>
<body class="bg-light">
<div id="app">
    <!-- Header -->
    @include('layouts.partials.header')

    <!-- Flash сообщения -->
    @include('flash::message')

    <!-- Основной контент -->
    <section class="py-5 mt-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->
@include('layouts.partials.footer')

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

@vite(['resources/js/app.js'])
</body>
</html>

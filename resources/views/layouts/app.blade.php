<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', __('Task Manager'))</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">

        <!-- Bootstrap 5.3.3 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
{{--        @include('layouts.partials.header')--}}
    </head>
    <body>
    <div id="app">
        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Flash сообщения ПОД header -->
        <div class="flash-messages-container mt-5 pt-4">
            @include('flash::message')
        </div>

        <!-- Основной контент -->
        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Менеджер задач')</title>

    <!-- Шрифт Nunito -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800&display=swap" rel="stylesheet">

    <!-- Основные стили -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Дополнительные стили -->
{{--    <style>--}}
{{--        {!! file_get_contents(resource_path('css/flash-messages.css')) !!}--}}
{{--        {!! file_get_contents(resource_path('css/createedit.css')) !!}--}}
{{--    </style>--}}

    <!-- Стили для конкретных страниц -->
    @stack('styles')
</head>
<body>
<div id="app">
    <!-- Подключаем верхнюю часть -->
    @include('layouts.partials.header')

    <!-- Основной контент -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Подключаем нижнюю часть -->
    @include('layouts.partials.footer')
</div>

<!-- Подключаем скрипты -->
@include('layouts.partials.scripts')
</body>
</html>

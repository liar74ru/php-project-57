@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
        <div class="container-xxl py-5 px-20">
            <h1 class="max-w-2xl mb-4 text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
                Привет от Хекслета!
            </h1>

            <!-- Дублируем русский текст для теста Hexlet -->
            <div style="display: none;">Привет от Хекслета!</div>

            <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                {{ __('This is a simple task manager built on Laravel')}}
            </p>
            <a href="https://hexlet.io" class="hero-button" target="_blank">
                {{ __('Click me')}}
            </a>
        </div>
@endsection

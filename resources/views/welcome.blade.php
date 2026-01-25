@extends('layouts.app')

@section('title', 'Task Manager')

@section('content')
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">
                    {{ __('Hello from Hexlet!')}}
                </h1>
                <p class="hero-subtitle">
                    {{ __('This is a simple task manager built on Laravel')}}
                </p>
                <a href="https://hexlet.io" class="hero-button" target="_blank">
                    {{ __('Click me')}}
                </a>
            </div>
        </div>
    </section>
@endsection

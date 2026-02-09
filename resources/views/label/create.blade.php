@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="col">
                    <h1 class="h1 mb-5">{{ __('Create label') }}</h1>
                </div>

                @auth
                    <form method="POST" action="{{ route('labels.store') }}">
                        @csrf
                        <div class="flex flex-col">
                            <div>
                                <label for="name">{{ __('Name') }}</label>
                            </div>
                            <div class="mt-2">
                                <input class="rounded border-gray-300 w-3/4" type="text" name="name" id="name">
                            </div>
                            {{-- Вывод ошибки под полем --}}
                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            <div class="mt-2">
                                <label for="description">{{ __('Description') }}</label>
                            </div>
                            <div>
                                <textarea class="rounded border-gray-300 w-3/4 h-32" name="description" id="description"></textarea>
                                @error('description')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mt-2">
                                <button class="auth-button" type="submit">{{ __('Create') }}</button>
                                <a href="{{ route('labels.index') }}" class="auth-button-grey">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                @endauth

            </div>
        </div>
    </section>

@endsection

@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="col">
                    <h1 class="h1 mb-5">{{ __('Create status') }}</h1>
                </div>

                @auth
                    <form method="POST" action="{{ route('task_statuses.store') }}">
                        @csrf

                        <div class="mb-3">
                            <div>
                                <label for="name">{{ __('Name') }}</label>
                            </div>
                            <div class="mt-2">
                                <input class="form-control w-50 @error('name') is-invalid @enderror"
                                       type="text"
                                       name="name"
                                       id="name"
                                       value="{{ $name ?? old('name') }}"
                                       required>

                                {{-- Вывод ошибки под полем --}}
                                @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <button class="auth-button">
                            {{ __('Create') }}
                        </button>
                        <a href="{{ route('task_statuses.index') }}" class="auth-button-grey">{{ __('Cancel') }}</a>
                    </form>
                @else
                    <hr>
                    <div>
                        <h2 class="h3 mb-0">{{ __('You must be logged in!') }}</h2>
                    </div>
                @endauth

            </div>
        </div>
    </section>

@endsection

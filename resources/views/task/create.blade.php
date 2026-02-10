@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">
                <h1 class="h1 mb-5">{{ __('Create task') }}</h1>

                @auth
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        <div class="flex flex-col">
                            {{-- Поле Name --}}
                            <div>
                                <label for="name">{{ __('Name') }}</label>
                            </div>
                            <div class="mt-2">
                                <input class="rounded border-gray-300 w-1/3"
                                       type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name') }}">
                            </div>
                            @error('name')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            {{-- Поле Description --}}
                            <div class="mt-2">
                                <label for="description">{{ __('Description') }}</label>
                            </div>
                            <div>
                                <textarea class="rounded border-gray-300 w-1/3 h-32"
                                          name="description"
                                          id="description">{{ old('description') }}</textarea>
                            </div>

                            {{-- Поле Status --}}
                            <div class="mt-2">
                                <label for="status_id">{{ __('Status') }}</label>
                            </div>
                            <div>
                                <select class="rounded border-gray-300 w-1/3"
                                        name="status_id"
                                        id="status_id">
                                    <option value="">{{ '' }}</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status->id }}"
                                            @selected(old('status_id') == $status->id)>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            {{-- Поле Assignee --}}
                            <div class="mt-2">
                                <label for="assigned_to_id">{{ __('Assignee') }}</label>
                            </div>
                            <div>
                                <select class="rounded border-gray-300 w-1/3"
                                        name="assigned_to_id"
                                        id="assigned_to_id">
                                    <option value="">{{ '' }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            @selected(old('assigned_to_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('assigned_to_id')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            {{-- Поле Labels (multiple select) --}}
                            <div class="mt-2">
                                <label for="labels">{{ __('Labels') }}</label>
                            </div>
                            <div>
                                <select class="rounded border-gray-300 w-1/3 h-32"
                                        name="labels[]"
                                        id="labels"
                                        multiple>
                                    @isset($labels)
                                        @foreach($labels as $label)
                                            <option value="{{ $label->id }}"
                                                @selected(in_array($label->id, old('labels', [])))>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            @error('labels')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                            @error('labels.*')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror

                            {{-- Кнопки --}}
                            <div class="mt-2">
                                <button class="auth-button" type="submit">{{ __('Create') }}</button>
                                <a href="{{ route('tasks.index') }}" class="auth-button-grey">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                @endauth
            </div>
        </div>
    </section>
@endsection

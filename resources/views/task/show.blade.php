@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <section class="bg-white dark:bg-gray-900">
        <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
            <div class="grid col-span-full">
                <h2 class="h2 mb-5">
                    {{ __('View task') }}: {{ $task->name }}
                    @auth()
                        <a href="{{ route('tasks.edit', $task) }}">⚙</a>
                    @endauth
                </h2>
                <p><span class="font-black">{{ __('Name') }}:</span> {{ $task->name }}</p>
                <p><span class="font-black">{{ __('Status') }}:</span> {{ $task->status->name  }}</p>
                <p><span class="font-black">{{ __('Description') }}:</span> {{ $task->description }}</p>
                <p><span class="font-black">{{ __('Labels') }}:</span></p>
                <div>
                    <x-badge>документация</x-badge>
                    <x-badge>ошибка</x-badge>
                    <x-badge>дубликат</x-badge>
                    <x-badge>доработка</x-badge>
                </div>
            </div>
        </div>
    </section>
@endsection

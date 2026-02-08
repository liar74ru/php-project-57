@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <div class="container py-5 px-20">
        <!-- Заголовок и кнопка создания -->
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">{{ __('Tasks') }}</h1>
            </div>
        </div>

        <!-- Фильтры -->
        <!-- Фильтры и кнопки на одной строке -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-3">
                <div class="d-flex align-items-end gap-2">
                    <!-- Форма с фильтрами -->
                    <form method="GET" action="{{ route('tasks.index') }}" class="d-flex align-items-end gap-2 flex-grow-1">
                        <!-- Статус -->
                        <x-filter-select
                            name="filter[status_id]"
                            :label="__('Status')"
                            :options="$statuses"
                            :value="$currentValue['status_id'] ?? '' "
                        />

                        <!-- Автор -->
                        <x-filter-select
                            name="filter[created_by_id]"
                            :label="__('Author')"
                            :options="$users"
                            :value="$currentValue['created_by_id']  ?? '' "
                        />

                        <!-- Исполнитель -->
                        <x-filter-select
                            name="filter[assigned_to_id]"
                            :label="__('Assignee')"
                            :options="$users"
                            :value="$currentValue['assigned_to_id']  ?? '' "
                        />

                        <!-- Кнопка "Применить" -->
                        <div style="padding-top: 1.6rem;">
                            <button type="submit" class="auth-button">
                                <i class="bi bi-filter me-1"></i> {{ __('Apply') }}
                            </button>
                        </div>
                    </form>

                    <!-- Кнопка "Создать задачу" -->
                    <div class="col-auto">
                        @auth
                            <a href="{{ route('tasks.create') }}" class="auth-button">
                                <i class="bi bi-plus-circle me-1"></i> {{ __('Create task') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>


        <!-- Таблица задач -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 50px;">{{ __('ID') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Author') }}</th>
                            <th>{{ __('Assignee') }}</th>
                            <th>{{ __('Creation date') }}</th>
                            @auth
                                <th class="text-end pe-4">{{ __('Actions') }}</th>
                            @endauth
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td class="ps-4">{{ $task->id }}</td>
                                <td>
                                        <span class="badge rounded-pill"
                                              style="background-color: {{ $task->status->color ?? '#6c757d' }}; padding: 0.5em 1em;">
                                            {{ $task->status->name }}
                                        </span>
                                </td>
                                <td>
                                    <a href="{{ route('tasks.show', $task->id) }}"
                                       class="text-decoration-none">
                                        {{ $task->name }}
                                    </a>
                                </td>
                                <td>{{ $task->creator->name ?? __('Unknown') }}</td>
                                <td>
                                    @if($task->assignee)
                                        {{ $task->assignee->name }}
                                    @else
                                        <span class="text-muted">{{ __('Not assigned') }}</span>
                                    @endif
                                </td>
                                <td>{{ $task->created_at->format('d.m.Y') }}</td>
                                @auth
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if (Auth::user()->name == $task->creator->name)
                                                <form method="POST"
                                                      action="{{ route('tasks.destroy', $task->id) }}"
                                                      class="d-inline m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger btn-sm rounded me-2"
                                                            onclick="return confirm('{{ __('Delete task «:name»?', ['name' => $task->name]) }}')">
                                                        <i class="bi bi-trash me-1"></i> {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('tasks.edit', $task) }}"
                                               class="btn btn-outline-secondary btn-sm rounded">
                                                <i class="bi bi-pencil me-1"></i> {{ __('Edit') }}
                                            </a>

                                        </div>
                                    </td>
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->check() ? 7 : 6 }}" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        {{ __('No tasks found') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <x-pagination :paginator="$tasks" />
    </div>
@endsection

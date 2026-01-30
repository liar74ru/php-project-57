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
                        <div style="width: 22%; min-width: 160px;">
                            <label for="status_id" class="form-label small mb-1">{{ __('Status') }}</label>
                            <select class="form-select form-select-sm" name="filter[status_id]" id="status_id">
                                <option value="">{{ __('Status') }}</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ request('filter.status_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Автор -->
                        <div style="width: 22%; min-width: 160px;">
                            <label for="created_by_id" class="form-label small mb-1">{{ __('Author') }}</label>
                            <select class="form-select form-select-sm" name="filter[created_by_id]" id="created_by_id">
                                <option value="">{{ __('Author') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('filter.created_by_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Исполнитель -->
                        <div style="width: 22%; min-width: 160px;">
                            <label for="assigned_to_id" class="form-label small mb-1">{{ __('Assignee') }}</label>
                            <select class="form-select form-select-sm" name="filter[assigned_to_id]" id="assigned_to_id">
                                <option value="">{{ __('Assignee') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('filter.assigned_to_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Кнопка "Применить" -->
                        <div style="padding-top: 1.6rem;">
                            <button type="submit" class="auth-button">
                                <i class="bi bi-filter me-1"></i> {{ __('Apply') }}
                            </button>
                        </div>
                    </form>

                    <!-- Кнопка "Создать задачу" -->
                    <div style="padding-top: 1.6rem;">
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
                                            <a href="{{ route('tasks.edit', $task->id) }}"
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

        <!-- Пагинация -->
        @if($tasks->hasPages())
            <div class="mt-4">
                <nav aria-label="{{ __('Pagination Navigation') }}">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                        <div class="mb-3 mb-md-0">
                            <p class="small text-muted mb-0">
                                {{ __('Showing') }}
                                <span class="fw-semibold">{{ $tasks->firstItem() }}</span>
                                {{ __('to') }}
                                <span class="fw-semibold">{{ $tasks->lastItem() }}</span>
                                {{ __('of') }}
                                <span class="fw-semibold">{{ $tasks->total() }}</span>
                                {{ __('results') }}
                            </p>
                        </div>

                        <div>
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if($tasks->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tasks->previousPageUrl() }}" rel="prev">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach(range(1, $tasks->lastPage()) as $page)
                                    @if($page == $tasks->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $tasks->url($page) }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if($tasks->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $tasks->nextPageUrl() }}" rel="next">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        @endif
    </div>
@endsection

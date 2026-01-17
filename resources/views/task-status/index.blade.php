@extends('layouts.app')

@section('title', 'Менеджер задач')

@section('content')
    <div class="container py-5 px-20">
        <div class="row mb-5">
            <div class="col">
                <h1 class="h3 mb-0">Статусы</h1>
            </div>
            <div class="col-auto">
                @auth
                    <!-- Для авторизованных -->
                    <a href="{{ route('task_statuses.create') }}" class="auth-button">Создать статус</a>
                @endauth

            </div>
        </div>
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 60px;">ID</th>
                                <th>Имя</th>
                                <th>Дата создания</th>
                                @auth
                                <th class="text-end pe-4">Действия</th>
                                @endauth
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($statuses as $status)
                                <tr>
                                    <td class="ps-4">{{ $status->id }}</td>
                                    <td>
                                    <span class="badge rounded-pill"
                                          style="background-color: {{ $status->color ?? '#6c757d' }}; padding: 0.5em 1em;">
                                        {{ $status->name }}
                                    </span>
                                    </td>
                                    <td>
                                        {{ $status->created_at->format('d.m.Y') }}
                                    </td>
                                    @auth
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('task_statuses.edit', $status->id) }}"
                                               class="btn btn-outline-secondary btn-sm rounded me-2">
                                                <i class="bi bi-pencil"></i> Изменить
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('task_statuses.destroy', $status->id) }}"
                                                  class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm rounded"
                                                        onclick="return confirm('Удалить статус «{{ $status->name }}»?')">
                                                    <i class="bi bi-trash me-1"></i> Удалить
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endauth
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection

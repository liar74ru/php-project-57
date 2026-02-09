@extends('layouts.app')

@section('title', __('Task Manager'))

@section('content')
    <div class="container py-5 px-20">
        <div class="row mb-5">
            <div class="col">
                <h1 class="h3 mb-0">{{ __('Label') }}</h1>
            </div>
            <div class="col-auto">
                @auth
                    <!-- Для авторизованных -->
                    <a href="{{ route('labels.create') }}" class="auth-button">{{ __('Create label') }}</a>
                @endauth

            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 60px;">{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Creation date') }}</th>
                            @auth
                                <th class="text-end pe-4">{{ __('Actions') }}</th>
                            @endauth
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($labels as $label)
                            <tr>
                                <td class="ps-4">{{ $label->id }}</td>
                                <td>
                                    {{ $label->name }}
                                    {{--                                    <x-badge>{{ $label->name }}</x-badge>--}}
{{--                                    <span style="display: none;">{{ $label->name }}</span>--}}
                                </td>
                                <td>
                                    {{ $label->description }}
                                </td>
                                <td>
                                    {{ $label->created_at->format('d.m.Y') }}
                                </td>
                                @auth
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('labels.edit', $label->id) }}"
                                               class="btn btn-outline-secondary btn-sm rounded me-2">
                                                <i class="bi bi-pencil"></i> {{ __('Edit') }}
                                            </a>
                                            <a href="{{ route('labels.destroy', $label->id) }}"
                                               onclick="event.preventDefault();
            if (confirm('{{ __('Delete status «:name»?', ['name' => $label->name]) }}')) {
                document.getElementById('delete-label-form-{{ $label->id }}').submit();
            }"
                                               class="btn btn-outline-danger btn-sm rounded">
                                                <i class="bi bi-trash me-1"></i> {{ __('Delete') }}
                                            </a>

                                            <!-- Скрытая форма для DELETE запроса -->
                                            <form id="delete-label-form-{{ $label->id }}"
                                                  method="POST"
                                                  action="{{ route('labels.destroy', $label->id) }}"
                                                  class="d-none">
                                                @csrf
                                                @method('DELETE')
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

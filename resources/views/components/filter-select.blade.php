{{-- resources/views/components/filter-select.blade.php --}}
@props([
    'name',        // Имя поля, например: 'filter[status_id]'
    'label',       // Лейбл, например: __('Status')
    'options',     // Коллекция или массив
    'value' => null, // Текущее значение
])

@php
    // Получаем текущее значение из запроса, если не передано явно
    $currentValue = $value ?? request($name);
    // Генерируем ID из имени (заменяем [] на _)
    $fieldId = str_replace(['[', ']'], ['_', ''], $name);
@endphp

<div style="width: 22%; min-width: 160px;">
    <label for="{{ $fieldId }}" class="form-label small mb-1">{{ $label }}</label>
    <select class="form-select form-select-sm" name="{{ $name }}" id="{{ $fieldId }}">
        <option value="">{{ $label }}</option>
        @foreach($options as $option)
            @if(is_object($option))
                <option value="{{ $option->id }}"
                    {{ (string)$currentValue === (string)$option->id ? 'selected' : '' }}>
                    {{ $option->name }}
                </option>
            @else
                <option value="{{ $key }}"
                    {{ (string)$currentValue === (string)$key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endif
        @endforeach
    </select>
</div>

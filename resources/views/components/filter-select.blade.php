{{-- resources/views/components/filter-select.blade.php --}}
@props([
    'name',        // Имя поля, например: 'filter[status_id]'
    'label',       // Лейбл, например: __('Status')
    'options',     // Коллекция или массив
    'value' => null, // Текущее значение
])

@php
    $currentValue = $value ?? request($name);
    $fieldId = str_replace(['[', ']'], ['_', ''], $name);
@endphp

<div style="width: 22%; min-width: 160px;">
    <label for="{{ $fieldId }}" class="form-label small mb-1">{{ $label }}</label>
    <select class="form-select form-select-sm" name="{{ $name }}" id="{{ $fieldId }}">
        <option value="">{{ $label }}</option>

        @if(is_iterable($options))
            @foreach($options as $key => $option)
                @if(is_object($option))
                    @php
                        $optionValue = $option->id ?? $option->getKey();
                        $optionLabel = $option->name ?? $option->title ?? $option;
                        $isSelected = (string)$currentValue === (string)$optionValue;
                    @endphp
                @else
                    @php
                        $optionValue = $key;
                        $optionLabel = $option;
                        $isSelected = (string)$currentValue === (string)$key;
                    @endphp
                @endif

                <option value="{{ $optionValue }}" @selected($isSelected)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @endif
    </select>
</div>

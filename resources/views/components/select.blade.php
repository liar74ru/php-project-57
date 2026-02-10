@props([
    'statuses' => [],
    'selected' => null,
    'name' => 'status_id',
    'id' => 'status_id',
    'placeholder' => '',
    'width' => 'w-1/3',
])

<select
    class="rounded border-gray-300 {{ $width }}"
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $attributes }}
>
    {{-- Пустая опция --}}
    <option value="">{{ $placeholder }}</option>

    {{-- Опции статусов --}}
    @foreach($statuses as $status)
        <option
            value="{{ $status->id }}"
            @selected(old($name, $selected) == $status->id)
        >
            {{ $status->name }}
        </option>
    @endforeach
</select>

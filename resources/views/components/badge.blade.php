@props([
    'color' => null, // Если не указан, определяем автоматически
    'showIcon' => true,
])

@php
    // Определяем цвет по содержимому слота, если не указан явно
    if (!$color) {
        $slotText = trim($slot->toHtml());
        $colorMap = [
            'документация' => 'blue',
            'documentation' => 'blue',
            'ошибка' => 'red',
            'error' => 'red',
            'bug' => 'red',
            'дубликат' => 'yellow',
            'duplicate' => 'yellow',
            'доработка' => 'green',
            'enhancement' => 'green',
            'feature' => 'green',
            'новая функция' => 'purple',
            'new feature' => 'purple',
            'исправление' => 'indigo',
            'fix' => 'indigo',
            'hotfix' => 'pink',
        ];

        $color = $colorMap[strtolower($slotText)] ?? 'gray';
    }

    // Цветовые схемы
    $colorClasses = [
        'blue' => 'bg-blue-200 text-blue-700',
        'green' => 'bg-green-200 text-green-700',
        'red' => 'bg-red-200 text-red-700',
        'yellow' => 'bg-yellow-200 text-yellow-700',
        'purple' => 'bg-purple-200 text-purple-700',
        'pink' => 'bg-pink-200 text-pink-700',
        'indigo' => 'bg-indigo-200 text-indigo-700',
        'gray' => 'bg-gray-200 text-gray-700',
    ];

    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div {{ $attributes->merge(['class' => "text-xs inline-flex items-center font-bold leading-sm px-3 py-1 rounded-full {$colorClass}"]) }}>
    @if($showIcon)
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
    @endif
    {{ $slot }}
</div>

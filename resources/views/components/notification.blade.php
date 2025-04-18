@props(['type' => 'info', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'error' => 'bg-red-100 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'info' => 'bg-blue-100 border-blue-500 text-blue-700',
    ];

    $icons = [
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'info' => 'fas fa-info-circle',
    ];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2" class="fixed top-4 right-4 z-50">
    <div class="flex items-center p-4 rounded-lg border-l-4 {{ $colors[$type] }} shadow-lg max-w-md">
        <div class="flex-shrink-0">
            <i class="{{ $icons[$type] }} text-xl"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button @click="show = false"
                    class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2
                               {{ $type === 'success' ? 'focus:ring-green-500 focus:ring-offset-green-50' : '' }}
                               {{ $type === 'error' ? 'focus:ring-red-500 focus:ring-offset-red-50' : '' }}
                               {{ $type === 'warning' ? 'focus:ring-yellow-500 focus:ring-offset-yellow-50' : '' }}
                               {{ $type === 'info' ? 'focus:ring-blue-500 focus:ring-offset-blue-50' : '' }}">
                    <span class="sr-only">Đóng</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

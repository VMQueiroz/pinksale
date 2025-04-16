@props(['id', 'maxWidth', 'show' => false])

@php
$id = $attributes->get('name', $id ?? md5($slot));
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth ?? '2xl'];
@endphp

<div
    x-data="{
        show: @js($show),
        id: '{{ $id }}'
    }"
    x-show="show"
    x-on:close.window="show = false"
    x-on:open-modal.window="if ($event.detail === id) show = true"
    x-on:close-modal.window="if ($event.detail.modal === id) { 
        show = false; 
        $el.querySelector('[x-show]')?.removeAttribute('style');
        $dispatch('close-modal', { modal: id });
    }"
    class="fixed mt-16 inset-0 overflow-y-auto px-0 py-6 sm:px-0 z-50 md:pl-8"
    style="display: none;"
>
    <div 
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="$dispatch('close-modal', { modal: id })"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div
        x-show="show"
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        {{ $slot }}
    </div>
</div>


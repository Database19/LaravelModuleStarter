@props(['name', 'title' => '', 'maxWidth' => 'lg'])

@php
    $maxWidthClasses = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth] ?? 'sm:max-w-2xl';
@endphp

@once
<div
    x-data="{
        show: false,
        init() {
            window.addEventListener('open-modal', e => {
                if (e.detail.name === '{{ $name }}') {
                    this.show = true;
                    document.body.style.overflow = 'hidden';
                }
            });
            window.addEventListener('close-modal', e => {
                this.show = false;
                document.body.style.overflow = 'auto';
            });
        }
    }"
    x-show="show"
    x-cloak
    x-on:keydown.escape.window="show = false; document.body.style.overflow = 'auto';"
    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title-{{ $name }}"
>
    <!-- Overlay -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75"
    ></div>

    <!-- Modal -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="bg-white rounded-lg shadow-xl w-full {{ $maxWidthClasses }} overflow-hidden z-50"
        @click.away="show = false; document.body.style.overflow = 'auto';"
        @click.stop
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800" id="modal-title-{{ $name }}">{{ $title }}</h2>

            <button x-on:click="show = false; document.body.style.overflow = 'auto';" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-2">
            {{ $slot }}
        </div>
    </div>
</div>
@endonce

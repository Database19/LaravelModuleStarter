<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Tailwind CSS & Alpine JS --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    {{-- Komponen ini bisa Anda buat jika perlu --}}
    {{-- <x-loading-overlay /> --}}
    @include('sweetalert::alert')

    @if(session('success'))
        <div class="bg-green-500 text-white text-center py-2 px-4 text-sm sm:text-base">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white text-center py-2 px-4 text-sm sm:text-base">{{ session('error') }}</div>
    @endif

    <div x-data="{ sidebarOpen: false }" class="h-full bg-slate-100">
        <!-- Off-canvas sidebar for mobile -->
        <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 lg:hidden" role="dialog" aria-modal="true" x-cloak>
            <!-- Overlay -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75"
                 @click="sidebarOpen = false"></div>

            <!-- Sidebar Panel -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-slate-800 text-white">

                @include('layouts._sidebar-content')
            </div>
            <div class="flex-shrink-0 w-14"></div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            <div class="flex flex-col flex-grow bg-slate-800 overflow-y-auto">
                @include('layouts._sidebar-content')
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 lg:pl-64">
            <!-- Header -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button type="button"
                        class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
                        @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>
            </div>

            <main class="flex-1">
                <!-- Page Content -->
                <div class="py-6 px-4 sm:px-6 lg:px-8">
                    <div class="bg-white p-4 rounded-lg shadow">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

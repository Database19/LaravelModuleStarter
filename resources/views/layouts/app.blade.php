<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <x-loading-overlay />
    @include('sweetalert::alert')
    @if(session('success'))
        <div class="bg-green-500 text-white text-center p-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white text-center p-2">{{ session('error') }}</div>
    @endif
    <div id="app" class="flex h-screen bg-gray-50">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-xl font-semibold text-gray-900">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-3 main-content-scrollbar">
                <div class="bg-white p-4 rounded-lg shadow">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

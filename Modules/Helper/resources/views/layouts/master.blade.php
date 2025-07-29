<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>CRUD Helper - {{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="CRUD Generator Helper">
    <meta name="keywords" content="CRUD, Generator, Laravel, Helper">
    <meta name="author" content="ERP System">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- TailwindCSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('helper.crud-helper.index') }}" class="flex items-center space-x-2">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 w-8 h-8 rounded-lg flex items-center justify-center">
                            <i class="fas fa-magic text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">CRUD Helper</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

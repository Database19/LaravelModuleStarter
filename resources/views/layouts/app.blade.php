<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            {{-- Sales Dropdown --}}
                            @can('manage-sales')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="salesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Sales
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="salesDropdown">
                                    <li><a class="dropdown-item" href="#">Leads Management</a></li>
                                    <li><a class="dropdown-item" href="#">Quotation</a></li>
                                    <li><a class="dropdown-item" href="#">Order Management</a></li>
                                    <li><a class="dropdown-item" href="#">Invoice Management</a></li>
                                    <li><a class="dropdown-item" href="#">Customer Management</a></li>
                                </ul>
                            </li>
                            @endcan
                    
                            {{-- Accounting Dropdown --}}
                            @can('manage-accounting')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="accountingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Accounting
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="accountingDropdown">
                                    <li><a class="dropdown-item" href="#">Accounts Payable</a></li>
                                    <li><a class="dropdown-item" href="#">Accounts Receivable</a></li>
                                    <li><a class="dropdown-item" href="#">General Ledger</a></li>
                                    <li><a class="dropdown-item" href="#">Budgeting</a></li>
                                    <li><a class="dropdown-item" href="#">Tax Management</a></li>
                                    <li><a class="dropdown-item" href="#">Financial Reporting</a></li>
                                </ul>
                            </li>
                            @endcan
                    
                            {{-- Inventory Dropdown --}}
                            @can('manage-inventory')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="inventoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Inventory
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="inventoryDropdown">
                                    <li><a class="dropdown-item" href="#">Stock Management</a></li>
                                    <li><a class="dropdown-item" href="#">Warehouse Management</a></li>
                                    <li><a class="dropdown-item" href="#">Stock Movement</a></li>
                                    <li><a class="dropdown-item" href="#">Reorder Level Monitoring</a></li>
                                    <li><a class="dropdown-item" href="#">Batch/Serial Tracking</a></li>
                                </ul>
                            </li>
                            @endcan
                    
                            {{-- Maintenance Dropdown --}}
                            @can('manage-maintenance')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="maintenanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Maintenance
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="maintenanceDropdown">
                                    <li><a class="dropdown-item" href="#">Scheduled Maintenance</a></li>
                                    <li><a class="dropdown-item" href="#">Preventive Maintenance</a></li>
                                    <li><a class="dropdown-item" href="#">Corrective Maintenance</a></li>
                                    <li><a class="dropdown-item" href="#">Asset Management</a></li>
                                    <li><a class="dropdown-item" href="#">Technician Scheduling</a></li>
                                </ul>
                            </li>
                            @endcan
                    
                            {{-- Configuration --}}
                            @can('manage-maintenance')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('configuration.index') }}">Configuration</a>
                                </li>
                            @endcan
                        @endauth
                    </ul>                    

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

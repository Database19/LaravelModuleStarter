<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Register') }} - {{ config('app.name', 'Laravel') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden lg:flex">

            <!-- Kolom Kiri (Branding) -->
            <div class="hidden lg:flex lg:w-1/2 bg-gray-800 text-white p-12 flex-col justify-center items-center text-center">
                <div class="mb-6">
                    <svg class="w-24 h-24 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm-1.125 4.125a2.25 2.25 0 012.25-2.25h.008a2.25 2.25 0 012.25 2.25v.008a2.25 2.25 0 01-2.25 2.25h-.008a2.25 2.25 0 01-2.25-2.25v-.008z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold mb-3">{{ config('app.name', 'Laravel') }}</h1>
                <p class="text-gray-300">Solusi ERP terintegrasi untuk mendorong pertumbuhan bisnis Anda.</p>
            </div>

            <!-- Kolom Kanan (Formulir) -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6 text-center lg:text-left">{{ __('Buat Akun Baru') }}</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nama') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')<span class="text-red-600 text-sm mt-1"><strong>{{ $message }}</strong></span>@enderror
                    </div>

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Alamat Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')<span class="text-red-600 text-sm mt-1"><strong>{{ $message }}</strong></span>@enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
                        @error('password')<span class="text-red-600 text-sm mt-1"><strong>{{ $message }}</strong></span>@enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700">{{ __('Konfirmasi Password') }}</label>
                        <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="text-center mt-4">
                        <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('login') }}">
                            {{ __('Sudah punya akun? Login di sini') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

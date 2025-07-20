<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }} - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        {{-- Logo Aplikasi --}}
        <div>
            <a href="/">
                <svg class="w-20 h-20 fill-current text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8zm-6 7.2c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624 1.177 1.194 2.538 2.576 5.512 2.576 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C10.337 13.382 8.976 12 6.001 12z" />
                </svg>
            </a>
        </div>

        {{-- Kotak Form Login --}}
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-6">{{ __('Login ke Akun Anda') }}</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Alamat Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Alamat Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                           class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">

                    @error('email')
                        <span class="text-red-600 text-sm mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">

                    @error('password')
                        <span class="text-red-600 text-sm mt-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Remember Me & Lupa Password -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            {{ __('Ingat Saya') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('password.request') }}">
                            {{ __('Lupa Password Anda?') }}
                        </a>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

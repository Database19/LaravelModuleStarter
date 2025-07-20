<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Module</title>

    {{-- Asumsi Anda memuat Tailwind CSS dan Alpine.js dari CDN atau file kompilasi Anda --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Asumsi Anda memuat Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body class="bg-gray-100">
    {{-- Notifikasi Sukses atau Error --}}
    @if(session('success'))
        <div class="bg-green-500 text-white text-center p-2">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white text-center p-2">{{ session('error') }}</div>
    @endif

    <div id="app">
        @yield('content')
    </div>
</body>
</html>

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
<body class="h-full" x-data="profileModalManager()">
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
                <x-super-admin-company-switcher />
                @include('layouts._sidebar-content')
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 lg:pl-64">
            <!-- Header -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <!-- Tombol Buka Sidebar (Mobile) -->
                <button type="button"
                        class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none lg:hidden"
                        @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>

                <!-- Konten Header (Fleksibel & Responsif) -->
                <div class="flex-1 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <!-- Judul Halaman -->
                    <div class="flex-1">
                        <h1 class="text-lg font-semibold text-gray-900">
                            @yield('header', 'Dashboard')
                        </h1>
                    </div>

                    <!-- Area Kanan Header (Profil Pengguna) -->
                    <div class="ml-4 flex items-center md:ml-6">
                        {{-- PANGGIL KOMPONEN PROFIL PENGGUNA DI SINI --}}
                        <x-user-profile />
                    </div>
                </div>
            </div>
            <x-modal name="profile-edit-modal" title="Edit Profile" max-width="lg">
                <form @submit.prevent="submitProfileForm()" id="profile-form" action="{{ route('profile.update') }}">
                    <x-form-edit-profile />

                    {{-- TAMBAHKAN BAGIAN TOMBOL INI --}}
                    <div class="mt-6 flex justify-end gap-x-3 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-lg">
                        <button @click.prevent="$dispatch('close-modal')" type="button" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </x-modal>

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

    <script>
    // Global modal helper functions
    window.openModal = function(name) {
        window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: name } }));
    };

    window.closeModal = function() {
        window.dispatchEvent(new CustomEvent('close-modal'));
    };

    // Global refresh function for DataTables
    window.refreshDataTable = function(tableId) {
        if (window.dataTablesInstances && window.dataTablesInstances[tableId]) {
            window.dataTablesInstances[tableId].ajax.reload(null, false);
        }
    };

    function profileModalManager() {
      return {
        formData: { name: '', email: '' },

        async openProfileModal() {
          try {
            const response = await fetch('{{ route("profile.edit") }}');
            if (!response.ok) throw new Error('Network response was not ok.');
            const data = await response.json();
            this.formData = data;
          } catch (error) {
            Swal.fire('Error!', 'Gagal memuat data profil.', 'error');
          }
        },

        async submitProfileForm() {
          try {
            const form = document.getElementById('profile-form');
            const response = await fetch(form.action, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
              },
              body: new FormData(form),
            });

            const result = await response.json();
            if (!response.ok) {
                const errors = Object.values(result.errors).map(e => `<li>${e}</li>`).join('');
                Swal.fire('Gagal!', `<ul class="text-left list-disc pl-5">${errors}</ul>`, 'error');
                throw new Error('Validation failed');
            }

            this.$dispatch('close-modal');
            await Swal.fire('Berhasil!', result.message, 'success');
            window.location.reload(); // Reload untuk update nama di header
          } catch (error) {
             if (error.message !== 'Validation failed') {
                Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
            }
          }
        },
      };
    }
    </script>
    @stack('scripts')
</body>
</html>

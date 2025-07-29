<div x-data="{ open: false }" class="relative">
    {{-- Tombol Pemicu Dropdown --}}
    <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <span class="sr-only">Open user menu</span>
        {{-- Ganti dengan avatar jika ada, atau gunakan inisial --}}
        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-600">
            <span class="text-sm font-medium leading-none text-white">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
        </span>
        <span class="hidden ml-3 text-gray-700 text-sm font-medium lg:block">
            <span class="sr-only">Open user menu for </span>{{ Auth::user()->name }}
        </span>
        <svg class="hidden ml-1 h-5 w-5 text-gray-400 lg:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Konten Dropdown --}}
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
         role="menu" aria-orientation="vertical" tabindex="-1"
         x-cloak
    >
        <button type="button"
            {{-- Pastikan @click memanggil $dispatch dan fungsi openProfileModal() --}}
            @click.prevent="$dispatch('open-modal', { name: 'profile-edit-modal', title: 'Edit Profile' }); openProfileModal()"
            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            role="menuitem" tabindex="-1">
            Edit Profile
        </button>

        <!-- Link Logout -->
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
           role="menuitem" tabindex="-1">
            Sign out
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</div>

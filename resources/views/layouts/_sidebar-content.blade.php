{{-- Logo dan Nama Aplikasi --}}
<div class="flex items-center justify-between flex-shrink-0 px-4 py-4 border-b border-slate-700">
    <div class="flex items-center">
        <svg class="h-8 w-8 text-purple-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <span class="ml-3 text-2xl font-bold text-white">{{ config('app.name', 'ERP') }}</span>
    </div>
    <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
        <span class="sr-only">Tutup sidebar</span>
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

{{-- Navigasi Menu --}}
<nav class="mt-5 flex-grow px-2 space-y-1 overflow-y-auto custom-scrollbar">
    @if(isset($dynamicMenuItems))
        @foreach($dynamicMenuItems as $group => $menus)
            <div class="px-2 mt-6 first:mt-0">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider">{{ $group }}</h3>
            </div>
            @foreach($menus as $menu)
                @php
                    // Logika untuk menentukan apakah menu utama (parent) aktif.
                    // Sebuah parent aktif jika salah satu anaknya (submenu) aktif.
                    $isParentActive = false;
                    if (!$menu->children->isEmpty()) {
                        foreach ($menu->children as $submenu) {
                            if (request()->routeIs($submenu->route . '*')) {
                                $isParentActive = true;
                                break;
                            }
                        }
                    } else {
                        // Jika tidak punya anak, cek rutenya sendiri.
                        $isParentActive = request()->routeIs($menu->route . '*');
                    }
                @endphp

                @if($menu->children->isEmpty())
                    {{-- Menu Tanpa Submenu --}}
                    <a href="{{ $menu->route !== '#' ? route($menu->route) : '#' }}"
                       class="{{ $isParentActive ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        {!! $menu->icon_svg !!}
                        <span class="ml-3">{{ $menu->name }}</span>
                    </a>
                @else
                    {{-- Menu dengan Submenu (Dropdown) --}}
                    <div x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="w-full {{ $isParentActive ? 'bg-slate-900 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md justify-between">
                            <span class="flex items-center">
                                {!! $menu->icon_svg !!}
                                <span class="ml-3">{{ $menu->name }}</span>
                            </span>
                            <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="mt-1 space-y-1 pl-9">
                            @foreach($menu->children as $submenu)
                                @php
                                    // Logika untuk menentukan apakah submenu ini aktif.
                                    $isSubmenuActive = request()->routeIs($submenu->route . '*');
                                @endphp
                                <a href="{{ $submenu->route !== '#' ? route($submenu->route) : '#' }}"
                                   class="{{ $isSubmenuActive ? 'bg-slate-700/50 text-white' : 'text-slate-400 hover:text-white' }} group flex items-center py-2 px-2 text-sm font-medium rounded-md">
                                    {{ $submenu->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
    @endif
</nav>

{{-- User Profile di Bawah --}}
<div class="flex-shrink-0 flex border-t border-slate-700 p-4 mt-auto">
    <div class="flex-shrink-0 group block w-full">
        <div class="flex items-center">
            <div>
                <img class="inline-block h-9 w-9 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()?->name) }}&color=A78BFA&background=374151" alt="">
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ auth()->user()?->name }}</p>
                <a href="#" class="text-xs font-medium text-slate-400 group-hover:text-slate-200">View profile</a>
            </div>
        </div>
    </div>
</div>

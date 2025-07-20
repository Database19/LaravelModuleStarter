@php
    use Illuminate\Support\Str;
@endphp

<aside class="w-64 flex-shrink-0 bg-gray-800 text-white flex flex-col p-4 h-screen overflow-hidden">
            <!-- Logo/Brand Name -->
            <div class="flex items-center justify-center h-16 border-b border-gray-700 flex-shrink-0">
                <h1 class="text-2xl font-bold tracking-wider">{{ config('app.name', 'Laravel') }}</h1>
            </div>

            <!-- Scrollable Navigation Area -->
            <nav class="flex-1 mt-6 space-y-6 overflow-y-auto pr-2 custom-scrollbar">
                    @auth
                        @foreach($dynamicMenuItems as $groupName => $items)
                            <div class="mt-6">
                                <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider" id="menu-group-{{ Str::slug($groupName) }}">
                                    {{ $groupName }}
                                </h3>
                                <div class="mt-1 space-y-1" role="group" aria-labelledby="menu-group-{{ Str::slug($groupName) }}">

                                    @foreach($items as $item)
                                        @can($item->permission_name)

                                            {{-- CEK APAKAH MENU INI PUNYA SUBMENU --}}
                                            @if($item->children->isNotEmpty())

                                                {{-- JIKA YA: Buat dropdown dengan Alpine.js --}}
                                                <div x-data="{ open: {{ request()->routeIs(explode('.', $item->children->first()->route)[0].'.*') ? 'true' : 'false' }} }">
                                                    <button @click="open = !open"
                                                        class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                                        {{ request()->routeIs(explode('.', $item->children->first()->route)[0].'.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                                        <span class="flex items-center">
                                                            {!! $item->icon_svg !!}
                                                            <span class="ml-3">{{ $item->name }}</span>
                                                        </span>
                                                        <svg class="h-5 w-5 transform transition-transform duration-150" :class="{'rotate-90': open}" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>

                                                    {{-- Daftar Submenu --}}
                                                    <div x-show="open" x-transition class="mt-1 ml-4 pl-4 border-l border-gray-600 space-y-1">
                                                        @foreach($item->children as $child)
                                                            @can($child->permission_name)
                                                                <a href="{{ route($child->route) }}"
                                                                class="w-full flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                                                {{ request()->routeIs($child->route) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                                                    {{ $child->name }}
                                                                </a>
                                                            @endcan
                                                        @endforeach
                                                    </div>
                                                </div>

                                            @else

                                                {{-- JIKA TIDAK: Tampilkan sebagai link biasa --}}
                                                {{-- <a href="{{ $item->route ? route($item->route) : '#' }}"
                                                class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150
                                                {{ request()->routeIs($item->route) ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                                    {!! $item->icon_svg !!}
                                                    <span class="ml-3">{{ $item->name }}</span>
                                                </a> --}}

                                            @endif
                                        @endcan
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- AcL --}}
                        @can('manage-acl')
                        {{-- <div>
                            <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</h3>
                            <div class="mt-2 space-y-1">
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <span>Pengguna</span>
                                </a>
                                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    <span>Peran & Hak Akses</span>
                                </a>

                                <a href="{{ route('admin.menu.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ request()->routeIs('admin.menu.*') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                    <span>Manage Menu</span>
                                </a>
                            </div>
                        </div> --}}
                        @endcan
                    @endauth
                </nav>

            <!-- User Profile / Logout - Fixed at bottom -->
            @auth
            <div class="mt-auto border-t border-gray-700 pt-4 flex-shrink-0">
                 <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><polyline points="16 17 21 12 16 7" /><line x1="21" x2="9" y1="12" y2="12" /></svg>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
            @endauth
        </aside>

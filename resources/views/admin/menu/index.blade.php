@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Manajemen Menu</h1>
            <p class="mt-2 text-sm text-gray-700">Kelola struktur navigasi sidebar aplikasi.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.menu.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">
                Tambah Menu Item
            </a>
        </div>
    </div>

    <div class="mt-8 space-y-8">
        @foreach($menuGroups as $groupName => $items)
            <div>
                <h2 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">{{ $groupName }}</h2>
                <ul class="space-y-4">
                    @foreach($items as $parent)
                        <li class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="text-gray-400">{!! $parent->icon_svg !!}</span>
                                    <span class="ml-3 font-semibold text-gray-800">{{ $parent->name }}</span>
                                    <span class="ml-2 text-xs text-gray-500">({{ $parent->route ?? 'Parent Menu' }})</span>
                                </div>
                                <div class="space-x-4">
                                    <a href="{{ route('admin.menu.edit', $parent->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <x-delete-button :action="route('admin.menu.destroy', $parent->id)" />
                                </div>
                            </div>
                            @if($parent->children->isNotEmpty())
                                <ul class="mt-3 ml-6 pl-6 border-l-2 border-gray-200 space-y-2">
                                    @foreach($parent->children as $child)
                                        <li class="flex items-center justify-between">
                                            <div>
                                                <span class="font-medium text-gray-700">{{ $child->name }}</span>
                                                <span class="ml-2 text-xs text-gray-500">({{ $child->route }})</span>
                                            </div>
                                            <div class="space-x-4">
                                                <a href="{{ route('admin.menu.edit', $child->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <x-delete-button :action="route('admin.menu.destroy', $child->id)" />
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
     <div class="px-4 sm:px-0 mb-4">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Menu Item: {{ $menu->name }}</h3>
    </div>
    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.menu._form', ['menu' => $menu, 'permissions' => $permissions, 'parentMenus' => $parentMenus])
    </form>
</div>
@endsection

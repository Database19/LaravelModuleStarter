@extends('layouts.app')
@section('content')
<form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.roles._form', ['role' => $role])
</form>
@endsection

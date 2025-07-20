@extends('layouts.app')

@section('header', 'Edit Pengguna')

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.users._form', ['user' => $user])
</form>
@endsection

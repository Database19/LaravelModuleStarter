@extends('layouts.app')

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    @include('admin.users._form')
</form>
@endsection

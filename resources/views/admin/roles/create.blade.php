@extends('layouts.app')
@section('content')
<form action="{{ route('admin.roles.store') }}" method="POST">
    @csrf
    @include('admin.roles._form')
</form>
@endsection

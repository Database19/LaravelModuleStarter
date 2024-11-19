@extends('master::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('master.name') !!}</p>
@endsection

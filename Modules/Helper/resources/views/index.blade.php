@extends('helper::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('helper.name') !!}</p>
@endsection

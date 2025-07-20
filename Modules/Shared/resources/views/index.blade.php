@extends('shared::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('shared.name') !!}</p>
@endsection

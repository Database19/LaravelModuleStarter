@extends('transaction::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('transaction.name') !!}</p>
@endsection

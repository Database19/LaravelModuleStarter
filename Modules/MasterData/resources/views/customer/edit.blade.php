@extends('layouts.app')
@section('content')
<div class="mx-auto py-10 sm:px-6 lg:px-8">
     <div class="px-4 sm:px-0 mb-4">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Customer: {{ $customer->name }}</h3>
    </div>
    <form action="{{ route('master-data.customer.update', $customer) }}" method="POST">
        @csrf
        @method('PUT')
        @include('masterdata::customer._form', ['customer' => $customer])
    </form>
</div>
@endsection

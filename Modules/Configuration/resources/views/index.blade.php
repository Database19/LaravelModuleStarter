@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <p>Module: {!! config('configuration.name') !!}</p>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="container">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="d-flex justify-content-center align-items-center p-3" style="height: 100%;">
                                            <span class="fs-5 fw-bold text-center">User Manager</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="d-flex justify-content-center align-items-center p-3" style="height: 100%;">
                                            <span class="fs-5 fw-bold text-center">User Roles</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="d-flex justify-content-center align-items-center p-3" style="height: 100%;">
                                            <span class="fs-5 fw-bold text-center">User Permission</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

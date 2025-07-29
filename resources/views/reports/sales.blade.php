@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales Analytics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>Monthly Sales</h4>
                                    <h2>$0</h2>
                                    <p>This Month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>Total Customers</h4>
                                    <h2>0</h2>
                                    <p>Active Customers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>Pending Orders</h4>
                                    <h2>0</h2>
                                    <p>Awaiting Processing</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Sales Report Actions</h4>
                            <div class="btn-group" role="group">
                                <a href="{{ route('sales.orders.index') }}" class="btn btn-primary">
                                    <i class="fas fa-list"></i> View All Orders
                                </a>
                                <a href="{{ route('sales.customers.index') }}" class="btn btn-success">
                                    <i class="fas fa-users"></i> Customer List
                                </a>
                                <a href="{{ route('sales.quotations.index') }}" class="btn btn-info">
                                    <i class="fas fa-file-alt"></i> Quotations
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

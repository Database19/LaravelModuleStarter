@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Executive Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Key Performance Indicators -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Total Revenue</h4>
                                            <h2>$0</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Total Orders</h4>
                                            <h2>0</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Active Users</h4>
                                            <h2>0</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>Inventory Value</h4>
                                            <h2>$0</h2>
                                        </div>
                                        <div>
                                            <i class="fas fa-boxes fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quick Access Links -->
                        <div class="col-md-12">
                            <h4>Quick Access</h4>
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('sales.orders.index') }}" class="btn btn-outline-primary btn-block">
                                        <i class="fas fa-chart-line"></i><br>Sales Reports
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('accounting.reports.index') }}" class="btn btn-outline-success btn-block">
                                        <i class="fas fa-calculator"></i><br>Financial Reports
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('inventory.products.index') }}" class="btn btn-outline-warning btn-block">
                                        <i class="fas fa-boxes"></i><br>Inventory Reports
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('humanresource.employees.index') }}" class="btn btn-outline-info btn-block">
                                        <i class="fas fa-users"></i><br>HR Reports
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('manufacturing.boms.index') }}" class="btn btn-outline-secondary btn-block">
                                        <i class="fas fa-cogs"></i><br>Production Reports
                                    </a>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <a href="{{ route('purchasing.purchase-orders.index') }}" class="btn btn-outline-dark btn-block">
                                        <i class="fas fa-shopping-basket"></i><br>Purchase Reports
                                    </a>
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

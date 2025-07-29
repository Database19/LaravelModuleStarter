@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Custom Reports</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Report Builder</h4>
                            <p class="text-muted">Create custom reports based on your specific needs.</p>

                            <form>
                                <div class="form-group">
                                    <label>Report Type</label>
                                    <select class="form-control">
                                        <option>Sales Report</option>
                                        <option>Financial Report</option>
                                        <option>Inventory Report</option>
                                        <option>HR Report</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Date Range</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="Start Date">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="End Date">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Filters</label>
                                    <select class="form-control" multiple>
                                        <option>Department</option>
                                        <option>Product Category</option>
                                        <option>Customer Type</option>
                                        <option>Sales Rep</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-chart-bar"></i> Generate Report
                                </button>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <h4>Saved Reports</h4>
                            <p class="text-muted">Your previously saved custom reports.</p>

                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Monthly Sales Summary</h6>
                                        <small>3 days ago</small>
                                    </div>
                                    <p class="mb-1">Comprehensive sales analysis for last month.</p>
                                    <small>Last run: July 23, 2025</small>
                                </div>

                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Inventory Turnover</h6>
                                        <small>1 week ago</small>
                                    </div>
                                    <p class="mb-1">Product performance and stock analysis.</p>
                                    <small>Last run: July 19, 2025</small>
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

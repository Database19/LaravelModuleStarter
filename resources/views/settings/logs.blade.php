@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">System Logs</h3>
                    <div>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-broom"></i> Clear Logs
                        </button>
                        <button class="btn btn-info btn-sm">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Levels</option>
                                <option>Emergency</option>
                                <option>Alert</option>
                                <option>Critical</option>
                                <option>Error</option>
                                <option>Warning</option>
                                <option>Notice</option>
                                <option>Info</option>
                                <option>Debug</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Search logs...">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="15%">Timestamp</th>
                                    <th width="10%">Level</th>
                                    <th width="15%">Channel</th>
                                    <th width="60%">Message</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-07-26 14:30:15</td>
                                    <td><span class="badge badge-success">INFO</span></td>
                                    <td>auth</td>
                                    <td>User admin logged in successfully</td>
                                </tr>
                                <tr>
                                    <td>2025-07-26 14:25:03</td>
                                    <td><span class="badge badge-warning">WARNING</span></td>
                                    <td>database</td>
                                    <td>Slow query detected: SELECT * FROM products took 2.5 seconds</td>
                                </tr>
                                <tr>
                                    <td>2025-07-26 14:20:45</td>
                                    <td><span class="badge badge-danger">ERROR</span></td>
                                    <td>mail</td>
                                    <td>Failed to send email: SMTP connection timeout</td>
                                </tr>
                                <tr>
                                    <td>2025-07-26 14:15:22</td>
                                    <td><span class="badge badge-info">INFO</span></td>
                                    <td>request</td>
                                    <td>API request to /api/products completed in 250ms</td>
                                </tr>
                                <tr>
                                    <td>2025-07-26 14:10:12</td>
                                    <td><span class="badge badge-secondary">DEBUG</span></td>
                                    <td>cache</td>
                                    <td>Cache key 'user.permissions.123' was refreshed</td>
                                </tr>
                                <tr>
                                    <td>2025-07-26 14:05:33</td>
                                    <td><span class="badge badge-dark">CRITICAL</span></td>
                                    <td>system</td>
                                    <td>Low disk space warning: Only 2GB remaining</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

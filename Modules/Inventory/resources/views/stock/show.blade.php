@extends('layouts.app')

@section('title', 'Stock Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Stock Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.stock.edit', $stock->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Stock ID:</th>
                                    <td>{{ $stock->id }}</td>
                                </tr>
                                <tr>
                                    <th>Product Name:</th>
                                    <td>{{ $stock->product->name ?? $stock->product_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Product Code:</th>
                                    <td>{{ $stock->product->code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Warehouse:</th>
                                    <td>{{ $stock->warehouse->name ?? $stock->warehouse_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Warehouse Code:</th>
                                    <td>{{ $stock->warehouse->code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Quantity:</th>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ number_format($stock->quantity) }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Unit Price:</th>
                                    <td>Rp {{ number_format($stock->unit_price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Value:</th>
                                    <td>
                                        <span class="fs-5 fw-bold text-success">
                                            Rp {{ number_format($stock->total_value, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td>{{ $stock->updated_at ? $stock->updated_at->format('d M Y H:i') : ($stock->last_updated ? $stock->last_updated->format('d M Y H:i') : 'N/A') }}</td>
                                </tr>
                                <tr>
                                    <th>Created By:</th>
                                    <td>{{ $stock->createdBy->name ?? $stock->creator->name ?? 'System Admin' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($stock->quantity > 10)
                                            <span class="badge bg-success">In Stock</span>
                                        @elseif($stock->quantity > 0)
                                            <span class="badge bg-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($stock->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $stock->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Stock Movement History (Mock) -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Recent Stock Movements</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Quantity</th>
                                            <th>Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ now()->subDays(1)->format('d M Y') }}</td>
                                            <td><span class="badge bg-success">Stock In</span></td>
                                            <td>+50</td>
                                            <td>PO-001</td>
                                        </tr>
                                        <tr>
                                            <td>{{ now()->subDays(3)->format('d M Y') }}</td>
                                            <td><span class="badge bg-warning">Stock Out</span></td>
                                            <td>-25</td>
                                            <td>SO-002</td>
                                        </tr>
                                        <tr>
                                            <td>{{ now()->subDays(5)->format('d M Y') }}</td>
                                            <td><span class="badge bg-info">Adjustment</span></td>
                                            <td>+5</td>
                                            <td>ADJ-001</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('inventory.stock.edit', $stock->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Stock
                    </a>
                    <form method="POST"
                          action="{{ route('inventory.stock.destroy', $stock->id) }}"
                          style="display: inline;"
                          onsubmit="return confirm('Are you sure you want to delete this stock item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Stock
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

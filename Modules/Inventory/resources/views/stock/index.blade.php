@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Stock Management</h3>
                    <a href="{{ route('inventory.stock.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Stock
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Warehouse</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stocks as $stock)
                                <tr>
                                    <td>{{ $stock->id }}</td>
                                    <td>{{ $stock->product->name ?? $stock->product_name ?? 'N/A' }}</td>
                                    <td>{{ $stock->warehouse->name ?? $stock->warehouse_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ number_format($stock->quantity) }}</span>
                                    </td>
                                    <td>Rp {{ number_format($stock->unit_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($stock->total_value, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('inventory.stock.show', $stock->id) }}"
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('inventory.stock.edit', $stock->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('inventory.stock.destroy', $stock->id) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this stock item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-3x mb-3"></i><br>
                                        No stock items found.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($stocks instanceof \Illuminate\Pagination\LengthAwarePaginator && $stocks->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $stocks->firstItem() }} to {{ $stocks->lastItem() }} of {{ $stocks->total() }} results
                            </div>
                            <div>
                                {{ $stocks->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush

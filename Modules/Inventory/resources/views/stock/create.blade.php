@extends('layouts.app')

@section('title', 'Create New Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Stock</h3>
                    <div class="card-tools">
                        <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('inventory.stock.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_id" class="form-label">Product <span class="text-danger">*</span></label>
                                    <select class="form-select @error('product_id') is-invalid @enderror"
                                            id="product_id" name="product_id" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="warehouse_id" class="form-label">Warehouse <span class="text-danger">*</span></label>
                                    <select class="form-select @error('warehouse_id') is-invalid @enderror"
                                            id="warehouse_id" name="warehouse_id" required>
                                        <option value="">Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity"
                                           name="quantity"
                                           value="{{ old('quantity') }}"
                                           min="0"
                                           step="1"
                                           required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="unit_price" class="form-label">Unit Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                               class="form-control @error('unit_price') is-invalid @enderror"
                                               id="unit_price"
                                               name="unit_price"
                                               value="{{ old('unit_price') }}"
                                               min="0"
                                               step="0.01"
                                               required>
                                        @error('unit_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control"
                                              id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Optional notes about this stock entry">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Stock
                        </button>
                        <a href="{{ route('inventory.stock.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calculate total value when quantity or price changes
    $('#quantity, #unit_price').on('input', function() {
        var quantity = parseFloat($('#quantity').val()) || 0;
        var unitPrice = parseFloat($('#unit_price').val()) || 0;
        var totalValue = quantity * unitPrice;

        // You can display total value if needed
        console.log('Total Value: ' + totalValue);
    });
});
</script>
@endpush

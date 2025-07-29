@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">New POS Transaction</h4>
            </div>
            <div class="card-body">
                <form id="pos-form" action="{{ route('pointofsales.transactions.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer (Optional)</label>
                            <select class="form-select" id="customer_id" name="customer_id">
                                <option value="">Walk-in Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <h5>Products</h5>
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <select class="form-select" id="product-select">
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    data-price="{{ $product->selling_price }}"
                                                    data-code="{{ $product->code }}">
                                                {{ $product->code }} - {{ $product->name }} ({{ number_format($product->selling_price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" id="product-qty" placeholder="Qty" value="1" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" id="add-product">Add</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-tbody">
                                        <!-- Items will be added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Summary</h5>
                                    <div class="row mb-2">
                                        <div class="col-6">Subtotal:</div>
                                        <div class="col-6 text-end" id="subtotal-display">0.00</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label for="discount_amount">Discount:</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                   id="discount_amount" name="discount_amount"
                                                   value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label for="tax_amount">Tax:</label>
                                        </div>
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm"
                                                   id="tax_amount" name="tax_amount"
                                                   value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-6"><strong>Total:</strong></div>
                                        <div class="col-6 text-end"><strong id="total-display">0.00</strong></div>
                                    </div>

                                    <div class="mb-3" id="cash-section">
                                        <label for="received_amount">Cash Received:</label>
                                        <input type="number" class="form-control"
                                               id="received_amount" name="received_amount"
                                               min="0" step="0.01">
                                        <small class="text-muted">Change: <span id="change-display">0.00</span></small>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg w-100" id="submit-btn" disabled>
                                        Process Transaction
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let items = [];
let itemCounter = 0;

document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product-select');
    const productQty = document.getElementById('product-qty');
    const addProductBtn = document.getElementById('add-product');
    const itemsTable = document.getElementById('items-tbody');
    const paymentMethod = document.getElementById('payment_method');
    const cashSection = document.getElementById('cash-section');
    const receivedAmount = document.getElementById('received_amount');

    // Show/hide cash section based on payment method
    paymentMethod.addEventListener('change', function() {
        if (this.value === 'cash') {
            cashSection.style.display = 'block';
            receivedAmount.required = true;
        } else {
            cashSection.style.display = 'none';
            receivedAmount.required = false;
            receivedAmount.value = '';
        }
    });

    addProductBtn.addEventListener('click', function() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        if (!selectedOption.value) return;

        const qty = parseInt(productQty.value);
        if (qty <= 0) return;

        const item = {
            id: itemCounter++,
            product_id: selectedOption.value,
            name: selectedOption.dataset.name,
            code: selectedOption.dataset.code,
            quantity: qty,
            unit_price: parseFloat(selectedOption.dataset.price),
            discount_amount: 0
        };

        items.push(item);
        addItemToTable(item);
        updateSummary();

        productSelect.value = '';
        productQty.value = 1;
    });

    document.addEventListener('input', function(e) {
        if (e.target.matches('.item-qty, .item-discount')) {
            updateItemSubtotal(e.target);
        }
        if (e.target.matches('#discount_amount, #tax_amount, #received_amount')) {
            updateSummary();
        }
    });
});

function addItemToTable(item) {
    const tbody = document.getElementById('items-tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>${item.code} - ${item.name}</td>
        <td>
            <input type="number" class="form-control form-control-sm item-qty"
                   value="${item.quantity}" min="1" data-item-id="${item.id}">
            <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
        </td>
        <td>${item.unit_price.toFixed(2)}</td>
        <td>
            <input type="number" class="form-control form-control-sm item-discount"
                   value="${item.discount_amount}" min="0" step="0.01" data-item-id="${item.id}">
        </td>
        <td class="item-subtotal">${((item.quantity * item.unit_price) - item.discount_amount).toFixed(2)}</td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
    tbody.appendChild(row);
}

function updateItemSubtotal(input) {
    const itemId = input.dataset.itemId;
    const item = items.find(i => i.id == itemId);

    if (input.classList.contains('item-qty')) {
        item.quantity = parseInt(input.value);
        input.parentElement.innerHTML = `
            <input type="number" class="form-control form-control-sm item-qty"
                   value="${item.quantity}" min="1" data-item-id="${item.id}">
            <input type="hidden" name="items[${item.id}][product_id]" value="${item.product_id}">
            <input type="hidden" name="items[${item.id}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${item.id}][unit_price]" value="${item.unit_price}">
        `;
    } else if (input.classList.contains('item-discount')) {
        item.discount_amount = parseFloat(input.value);
        input.parentElement.innerHTML = `
            <input type="number" class="form-control form-control-sm item-discount"
                   value="${item.discount_amount}" min="0" step="0.01" data-item-id="${item.id}">
            <input type="hidden" name="items[${item.id}][discount_amount]" value="${item.discount_amount}">
        `;
    }

    const subtotal = (item.quantity * item.unit_price) - item.discount_amount;
    input.closest('tr').querySelector('.item-subtotal').textContent = subtotal.toFixed(2);

    updateSummary();
}

function removeItem(itemId) {
    items = items.filter(item => item.id !== itemId);
    document.querySelector(`[data-item-id="${itemId}"]`).closest('tr').remove();
    updateSummary();
}

function updateSummary() {
    const subtotal = items.reduce((sum, item) => {
        return sum + ((item.quantity * item.unit_price) - item.discount_amount);
    }, 0);

    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const total = subtotal - discount + tax;

    document.getElementById('subtotal-display').textContent = subtotal.toFixed(2);
    document.getElementById('total-display').textContent = total.toFixed(2);

    // Calculate change for cash payments
    const paymentMethod = document.getElementById('payment_method').value;
    if (paymentMethod === 'cash') {
        const received = parseFloat(document.getElementById('received_amount').value) || 0;
        const change = Math.max(0, received - total);
        document.getElementById('change-display').textContent = change.toFixed(2);
    }

    // Enable/disable submit button
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = items.length === 0;
}
</script>
@endsection

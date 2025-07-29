@extends('shared::layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Receipt - {{ $transaction->transaction_no }}</h4>
                <div>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="{{ route('pointofsales.receipts.pdf', $transaction) }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('pointofsales.transactions.show', $transaction) }}" class="btn btn-info">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body" id="receipt-content">
                <div class="text-center mb-4">
                    <h3>YOUR STORE NAME</h3>
                    <p class="mb-1">Your Store Address</p>
                    <p class="mb-1">Phone: (021) 123-4567</p>
                    <p class="mb-3">Email: info@yourstore.com</p>
                </div>

                <div class="receipt-header mb-3">
                    <div class="row">
                        <div class="col-6">
                            <strong>Receipt #:</strong> {{ $transaction->transaction_no }}
                        </div>
                        <div class="col-6 text-end">
                            <strong>Date:</strong> {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>Customer:</strong> {{ $transaction->customer->name ?? 'Walk-in Customer' }}
                        </div>
                        <div class="col-6 text-end">
                            <strong>Cashier:</strong> {{ $transaction->cashier->name }}
                        </div>
                    </div>
                </div>

                <hr>

                <div class="receipt-items">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>
                                        <div>{{ $item->product->name }}</div>
                                        <small class="text-muted">{{ $item->product->code }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                                @if($item->discount_amount > 0)
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            <small class="text-muted">Item Discount:</small>
                                        </td>
                                        <td class="text-end">
                                            <small class="text-muted">-{{ number_format($item->discount_amount, 2) }}</small>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="receipt-summary">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-end"><strong>Subtotal:</strong></td>
                            <td class="text-end" width="120">{{ number_format($transaction->subtotal, 2) }}</td>
                        </tr>
                        @if($transaction->discount_amount > 0)
                            <tr>
                                <td class="text-end">Discount:</td>
                                <td class="text-end">-{{ number_format($transaction->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                        @if($transaction->tax_amount > 0)
                            <tr>
                                <td class="text-end">Tax:</td>
                                <td class="text-end">{{ number_format($transaction->tax_amount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="border-top">
                            <td class="text-end"><strong>TOTAL:</strong></td>
                            <td class="text-end"><strong>{{ number_format($transaction->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>

                <hr>

                <div class="receipt-payment mb-4">
                    <h6>Payment Details:</h6>
                    @foreach($transaction->payments as $payment)
                        <div class="row">
                            <div class="col-6">{{ ucfirst($payment->payment_method) }}:</div>
                            <div class="col-6 text-end">{{ number_format($payment->amount, 2) }}</div>
                        </div>
                        @if($payment->change_amount > 0)
                            <div class="row">
                                <div class="col-6">Change:</div>
                                <div class="col-6 text-end">{{ number_format($payment->change_amount, 2) }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="text-center">
                    <p class="mb-1">Thank you for your purchase!</p>
                    <p class="mb-1">Please keep this receipt for your records</p>
                    <small class="text-muted">Transaction processed at {{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .card-header, .btn, .navbar, .sidebar {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 0 !important;
    }

    body {
        font-size: 12px;
    }

    .receipt-header, .receipt-items, .receipt-summary, .receipt-payment {
        margin-bottom: 10px;
    }
}
</style>
@endsection

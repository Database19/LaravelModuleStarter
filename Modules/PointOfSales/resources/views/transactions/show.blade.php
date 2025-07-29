@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Transaction Details - {{ $transaction->transaction_no }}</h4>
                <div>
                    <a href="{{ route('pointofsales.receipts.show', $transaction) }}" class="btn btn-secondary">
                        <i class="fas fa-receipt"></i> Receipt
                    </a>
                    <a href="{{ route('pointofsales.transactions.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Transaction Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Transaction No:</strong></td>
                                <td>{{ $transaction->transaction_no }}</td>
                            </tr>
                            <tr>
                                <td><strong>Date:</strong></td>
                                <td>{{ $transaction->transaction_date->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Type:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $transaction->type === 'sale' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Payment Status:</strong></td>
                                <td>
                                    <span class="badge badge-{{ $transaction->payment_status === 'paid' ? 'success' : ($transaction->payment_status === 'refunded' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->payment_status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Customer & Staff Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>Customer:</strong></td>
                                <td>{{ $transaction->customer->name ?? 'Walk-in Customer' }}</td>
                            </tr>
                            @if($transaction->customer)
                                <tr>
                                    <td><strong>Customer Phone:</strong></td>
                                    <td>{{ $transaction->customer->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Customer Email:</strong></td>
                                    <td>{{ $transaction->customer->email ?? '-' }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td><strong>Cashier:</strong></td>
                                <td>{{ $transaction->cashier->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5>Transaction Items</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $item->product->code }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ number_format($item->discount_amount, 2) }}</td>
                                    <td>{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Subtotal:</th>
                                <th>{{ number_format($transaction->subtotal, 2) }}</th>
                            </tr>
                            @if($transaction->discount_amount > 0)
                                <tr>
                                    <th colspan="5" class="text-end">Discount:</th>
                                    <th>-{{ number_format($transaction->discount_amount, 2) }}</th>
                                </tr>
                            @endif
                            @if($transaction->tax_amount > 0)
                                <tr>
                                    <th colspan="5" class="text-end">Tax:</th>
                                    <th>{{ number_format($transaction->tax_amount, 2) }}</th>
                                </tr>
                            @endif
                            <tr class="table-dark">
                                <th colspan="5" class="text-end">Total:</th>
                                <th>{{ number_format($transaction->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <hr>

                <h5>Payment Information</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Received</th>
                                <th>Change</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaction->payments as $payment)
                                <tr>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ number_format($payment->received_amount, 2) }}</td>
                                    <td>{{ number_format($payment->change_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'refunded' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->payment_date->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($transaction->notes)
                    <hr>
                    <h5>Notes</h5>
                    <p>{{ $transaction->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

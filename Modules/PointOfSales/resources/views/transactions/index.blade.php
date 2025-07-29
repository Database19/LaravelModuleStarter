@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">POS Transactions</h4>
                <div>
                    <a href="{{ route('pointofsales.transactions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Transaction
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Transaction No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->transaction_no }}</td>
                                    <td>{{ $transaction->transaction_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td>{{ $transaction->cashier->name }}</td>
                                    <td>{{ number_format($transaction->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $transaction->payment_status === 'paid' ? 'success' : ($transaction->payment_status === 'refunded' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('pointofsales.transactions.show', $transaction) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pointofsales.receipts.show', $transaction) }}" class="btn btn-sm btn-secondary">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                            @if($transaction->status === 'completed')
                                                <form action="{{ route('pointofsales.transactions.destroy', $transaction) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transactions found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

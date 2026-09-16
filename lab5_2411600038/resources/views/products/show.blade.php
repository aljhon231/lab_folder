@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="section-heading">
    <h1 class="h3 mb-0">{{ $product->name }}</h1>
    <div class="d-flex gap-2">
        <a class="btn btn-outline-success" href="{{ route('products.edit', $product) }}">Edit</a>
        <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger" type="submit">Delete</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                <p><strong>Category:</strong> {{ $product->category }}</p>
                <p><strong>Supplier:</strong> {{ $product->supplier }}</p>
                <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                <p><strong>Reorder level:</strong> {{ $product->reorder_level }}</p>
                <p><strong>Unit price:</strong> ₱{{ number_format($product->unit_price, 2) }}</p>
                <p><strong>Inventory value:</strong> ₱{{ number_format($product->inventoryValue(), 2) }}</p>
                <p>
                    <strong>Status:</strong>
                    <span class="badge {{ $product->isOutOfStock() ? 'badge-out' : ($product->isLowStock() ? 'badge-low' : 'badge-ok') }}">
                        {{ ucwords($product->stockStatus()) }}
                    </span>
                </p>
                <p class="mb-0"><strong>Description:</strong> {{ $product->description ?: 'None' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card dashboard-card">
            <div class="card-body">
                <h5>Stock transactions</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Reference</th>
                                <th>User</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->transactions as $transaction)
                                <tr>
                                    <td>{{ str_replace('_', ' ', $transaction->type) }}</td>
                                    <td>{{ $transaction->quantity }}</td>
                                    <td>{{ $transaction->reference }}</td>
                                    <td>{{ $transaction->user?->name ?? 'System' }}</td>
                                    <td>{{ $transaction->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No transactions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

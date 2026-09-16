@extends('layouts.app')

@section('title', 'Course Reports')

@section('content')
<div class="section-heading">
    <h1 class="h3 mb-0">Course report</h1>
    <a class="btn btn-primary" href="{{ route('reports.export') }}">Export CSV</a>
</div>

<p class="text-muted">Total course value: <strong>₱{{ number_format($totalValue, 2) }}</strong></p>

<div class="card dashboard-card mb-4">
    <div class="card-body">
        <h5>Low / out of stock</h5>
        <ul class="mb-0">
            @forelse ($lowStock as $product)
                <li>{{ $product->name }} ({{ $product->sku }}) — {{ $product->quantity }} left</li>
            @empty
                <li>All products are above reorder level.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="card dashboard-card mb-4">
    <div class="card-body">
        <h5>All courses</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Qty</th>
                        <th>Value</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>₱{{ number_format($product->inventoryValue(), 2) }}</td>
                            <td>{{ ucwords($product->stockStatus()) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body">
        <h5>Recent stock movements</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Type</th>
                        <th>Qty</th>
                        <th>Reference</th>
                        <th>When</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->product?->name }}</td>
                            <td>{{ str_replace('_', ' ', $transaction->type) }}</td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ $transaction->reference }}</td>
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
@endsection

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="welcome-section mb-4">
    <div>
        <p class="text-muted mb-1">{{ now()->format('l, F j, Y') }}</p>
        <h1 class="fw-bold mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Student portal overview of course inventory, stock levels, and academic records.</p>
    </div>
    <span class="live-pill"><span></span> System online</span>
</section>

@if ($alertProducts->isNotEmpty())
    <div class="academic-alert mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Course alert</strong>
            <span>{{ $alertProducts->count() }} course(s) are low or out of stock: <b>{{ $alertProducts->pluck('sku')->join(', ') }}</b>.</span>
        </div>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-boxes"></i></div>
                <p class="text-muted mb-1">Total Courses</p>
                <h2 class="stat-value">{{ $stats['total_products'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
                <p class="text-muted mb-1">Course Fees</p>
                <h2 class="stat-value">₱{{ number_format($stats['inventory_value'], 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon warning"><i class="bi bi-exclamation-triangle"></i></div>
                <p class="text-muted mb-1">Low Stock</p>
                <h2 class="stat-value">{{ $stats['low_stock'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon danger"><i class="bi bi-x-octagon"></i></div>
                <p class="text-muted mb-1">Out of Stock</p>
                <h2 class="stat-value">{{ $stats['out_of_stock'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card dashboard-card">
    <div class="card-body">
        <div class="section-heading">
            <h4 class="mb-0">Recently updated courses</h4>
            <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-sm">View all</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>SKU</th>
                        <th>Category</th>
                        <th>Seats</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentProducts as $product)
                        <tr class="{{ $product->isLowStock() || $product->isOutOfStock() ? 'attention-row' : '' }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <span class="badge {{ $product->isOutOfStock() ? 'badge-out' : ($product->isLowStock() ? 'badge-low' : 'badge-ok') }}">
                                    {{ ucwords($product->stockStatus()) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

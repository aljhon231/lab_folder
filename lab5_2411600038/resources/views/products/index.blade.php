@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="section-heading">
    <h1 class="h3 mb-0">Courses</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Course</a>
</div>

@if ($alertProducts->isNotEmpty())
    <div class="academic-alert mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Low stock items</strong>
            <span>{{ $alertProducts->pluck('name')->join(', ') }}</span>
        </div>
    </div>
@endif

<form class="row g-2 mb-4" method="GET">
    <div class="col-md-4">
        <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search course name, code, or category">
    </div>
    <div class="col-md-3">
        <select name="category" class="form-select">
            <option value="all">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-success w-100" type="submit">Filter</button>
    </div>
</form>

<div class="card dashboard-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Code</th>
                    <th>Category</th>
                    <th>Department</th>
                    <th>Fees</th>
                    <th>Seats</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="{{ $product->isLowStock() || $product->isOutOfStock() ? 'attention-row' : '' }}">
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->category }}</td>
                        <td>{{ $product->supplier }}</td>
                        <td>₱{{ number_format($product->unit_price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <span class="badge {{ $product->isOutOfStock() ? 'badge-out' : ($product->isLowStock() ? 'badge-low' : 'badge-ok') }}">
                                {{ ucwords($product->stockStatus()) }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('products.show', $product) }}">View</a>
                            <a class="btn btn-sm btn-outline-success" href="{{ route('products.edit', $product) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-4">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

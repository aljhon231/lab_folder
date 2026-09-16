@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<h1 class="h3 mb-4">Edit {{ $product->name }}</h1>
<div class="card dashboard-card">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')
            @include('products._form')
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary" type="submit">Update product</button>
                <a class="btn btn-outline-success" href="{{ route('products.index') }}">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

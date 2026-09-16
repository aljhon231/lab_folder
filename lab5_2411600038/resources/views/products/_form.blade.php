@php
    $isEdit = isset($product);
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Course name</label>
        <input id="name" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required maxlength="120">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="sku">Course code</label>
        <input id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}" required maxlength="50">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="category">Department</label>
        <select id="category" name="category" class="form-select" required>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(old('category', $product->category ?? '') === $category)>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="unit_price">Tuition fee (₱)</label>
        <input id="unit_price" name="unit_price" type="number" min="0" step="0.01" class="form-control" value="{{ old('unit_price', $product->unit_price ?? 0) }}" required>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" maxlength="1000">{{ old('description', $product->description ?? '') }}</textarea>
    </div>
</div>

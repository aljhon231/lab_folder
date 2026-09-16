<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->latest();

        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return view('products.index', [
            'products' => $products,
            'categories' => Product::categories(),
            'alertProducts' => $products->filter(fn (Product $product) => $product->isLowStock() || $product->isOutOfStock()),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Product::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProduct($request);
        $product = Product::create($data);

        if ($product->quantity > 0) {
            $this->recordTransaction($request, $product, 'stock_in', $product->quantity, 'Initial stock');
        }

        $redirect = redirect()->route('products.index')->with('success', 'Course was added successfully.');

        if ($product->isLowStock() || $product->isOutOfStock()) {
            $redirect->with('warning', $this->alertMessage($product));
        }

        return $redirect;
    }

    public function show(Product $product): View
    {
        $product->load(['transactions.user']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Product::categories(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedProduct($request, $product->id);
        $previousQuantity = $product->quantity;
        $product->update($data);

        $difference = (int) $data['quantity'] - $previousQuantity;
        if ($difference !== 0) {
            $this->recordTransaction(
                $request,
                $product,
                $difference > 0 ? 'stock_in' : 'stock_out',
                abs($difference),
                'Manual stock adjustment'
            );
        }

        $redirect = redirect()->route('products.index')->with('success', 'Product details were updated.');

        $fresh = $product->fresh();
        if ($fresh && ($fresh->isLowStock() || $fresh->isOutOfStock())) {
            $redirect->with('warning', $this->alertMessage($fresh));
        }

        return $redirect;
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Course was removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedProduct(Request $request, ?int $productId = null): array
    {
        $skuRule = 'required|string|max:50|unique:products,sku';

        if ($productId) {
            $skuRule .= ','.$productId;
        }

        return $request->validate(
            [
                'name' => 'required|string|max:120',
                'sku' => $skuRule,
                'description' => 'nullable|string|max:1000',
                'category' => 'required|string|in:'.implode(',', Product::categories()),
                'unit_price' => 'required|numeric|min:0',
            ],
            [
                'name.required' => 'Please enter the course name.',
                'sku.required' => 'A unique course code is required.',
                'sku.unique' => 'That course code is already assigned to another course.',
                'category.required' => 'Please choose a department.',
                'unit_price.min' => 'Tuition fee cannot be negative.',
            ]
        );
    }

    private function recordTransaction(Request $request, Product $product, string $type, int $quantity, string $reference): void
    {
        InventoryTransaction::create([
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'type' => $type,
            'quantity' => $quantity,
            'reference' => $reference,
        ]);
    }

    private function alertMessage(Product $product): string
    {
        if ($product->isOutOfStock()) {
            return $product->name.' is out of stock. Reorder from '.$product->supplier.'.';
        }

        return $product->name.' is at or below the reorder level ('.$product->quantity.' / '.$product->reorder_level.').';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->latest()->get();
        $alertProducts = $products->filter(fn (Product $product) => $product->isLowStock() || $product->isOutOfStock());

        $stats = [
            'total_products' => $products->count(),
            'low_stock' => $products->filter(fn (Product $product) => $product->isLowStock())->count(),
            'out_of_stock' => $products->filter(fn (Product $product) => $product->isOutOfStock())->count(),
            'inventory_value' => $products->sum(fn (Product $product) => $product->inventoryValue()),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'recentProducts' => $products->take(8),
            'alertProducts' => $alertProducts,
        ]);
    }
}

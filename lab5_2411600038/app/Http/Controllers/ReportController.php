<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->orderBy('name')->get();
        $transactions = InventoryTransaction::query()->with(['product', 'user'])->latest()->limit(20)->get();

        return view('reports.index', [
            'products' => $products,
            'transactions' => $transactions,
            'totalValue' => $products->sum(fn (Product $product) => $product->inventoryValue()),
            'lowStock' => $products->filter(fn (Product $product) => $product->isLowStock() || $product->isOutOfStock()),
        ]);
    }

    public function exportCsv(): StreamedResponse|Response
    {
        $filename = 'inventory-report-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'SKU', 'Category', 'Supplier', 'Quantity', 'Reorder Level', 'Unit Price', 'Inventory Value', 'Status']);

            Product::query()->orderBy('name')->get()->each(function (Product $product) use ($handle) {
                fputcsv($handle, [
                    $product->name,
                    $product->sku,
                    $product->category,
                    $product->supplier,
                    $product->quantity,
                    $product->reorder_level,
                    $product->unit_price,
                    number_format($product->inventoryValue(), 2, '.', ''),
                    $product->stockStatus(),
                ]);
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'sku',
    'description',
    'category',
    'quantity',
    'reorder_level',
    'unit_price',
    'supplier',
])]
class Product extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reorder_level' => 'integer',
            'unit_price' => 'decimal:2',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class)->latest();
    }

    public function isOutOfStock(): bool
    {
        return $this->quantity === 0;
    }

    public function isLowStock(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }

    public function stockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out of stock';
        }

        if ($this->isLowStock()) {
            return 'low stock';
        }

        return 'in stock';
    }

    public function inventoryValue(): float
    {
        return (float) $this->quantity * (float) $this->unit_price;
    }

    /**
     * @return list<string>
     */
    public static function categories(): array
    {
        return ['IT'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toLiveArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'category' => $this->category,
            'supplier' => $this->supplier,
            'unit_price' => (float) $this->unit_price,
            'quantity' => $this->quantity,
            'reorder_level' => $this->reorder_level,
            'status' => $this->stockStatus(),
            'inventory_value' => $this->inventoryValue(),
            'alert' => $this->isLowStock() || $this->isOutOfStock(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}

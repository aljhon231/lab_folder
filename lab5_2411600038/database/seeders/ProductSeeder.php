<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        $products = [
            ['name' => 'Database Management Systems', 'sku' => 'COURSE-101', 'description' => 'Database design, SQL, and system data modeling.', 'category' => 'IT', 'quantity' => 42, 'reorder_level' => 12, 'unit_price' => 1800.00, 'supplier' => 'School IT Office'],
            ['name' => 'Web Development', 'sku' => 'COURSE-102', 'description' => 'Front-end and back-end web application development.', 'category' => 'IT', 'quantity' => 35, 'reorder_level' => 10, 'unit_price' => 1950.00, 'supplier' => 'School IT Office'],
            ['name' => 'Computer Networks', 'sku' => 'COURSE-103', 'description' => 'LAN, WAN, routing, switching, and network services.', 'category' => 'IT', 'quantity' => 25, 'reorder_level' => 8, 'unit_price' => 1700.00, 'supplier' => 'School IT Office'],
            ['name' => 'Operating Systems', 'sku' => 'COURSE-104', 'description' => 'Process management, memory, storage, and OS structure.', 'category' => 'IT', 'quantity' => 28, 'reorder_level' => 9, 'unit_price' => 1850.00, 'supplier' => 'School IT Office'],
            ['name' => 'Software Engineering', 'sku' => 'COURSE-105', 'description' => 'Software lifecycle, requirements, and project practice.', 'category' => 'IT', 'quantity' => 21, 'reorder_level' => 7, 'unit_price' => 2100.00, 'supplier' => 'School IT Office'],
            ['name' => 'Human Computer Interaction', 'sku' => 'COURSE-106', 'description' => 'Usability, interface design, and user experience.', 'category' => 'IT', 'quantity' => 19, 'reorder_level' => 6, 'unit_price' => 1600.00, 'supplier' => 'School IT Office'],
            ['name' => 'Data Structures', 'sku' => 'COURSE-107', 'description' => 'Core data structures and algorithm foundations.', 'category' => 'IT', 'quantity' => 31, 'reorder_level' => 10, 'unit_price' => 1750.00, 'supplier' => 'School IT Office'],
            ['name' => 'Information Security', 'sku' => 'COURSE-108', 'description' => 'Cybersecurity, threat prevention, and secure systems.', 'category' => 'IT', 'quantity' => 16, 'reorder_level' => 5, 'unit_price' => 2200.00, 'supplier' => 'School IT Office'],
        ];

        foreach ($products as $row) {
            $product = Product::query()->updateOrCreate(['sku' => $row['sku']], $row);

            if ($product->quantity > 0 && $product->transactions()->count() === 0) {
                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'user_id' => $user?->id,
                    'type' => 'stock_in',
                    'quantity' => $product->quantity,
                    'reference' => 'Seed student portal data',
                ]);
            }
        }
    }
}

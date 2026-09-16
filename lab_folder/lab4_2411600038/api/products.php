<?php
header('Content-Type: application/json; charset=utf-8');

$products = [
    ["id"=>1,"name"=>"Laptop Pro 14","sku"=>"SKU-001","category"=>"Electronics","supplier"=>"TechSource","unit_price"=>52000,"quantity"=>12,"reorder_level"=>5],
    ["id"=>2,"name"=>"Wireless Mouse","sku"=>"SKU-002","category"=>"Electronics","supplier"=>"TechSource","unit_price"=>850,"quantity"=>8,"reorder_level"=>10],
    ["id"=>3,"name"=>"Mechanical Keyboard","sku"=>"SKU-003","category"=>"Electronics","supplier"=>"KeyWorks","unit_price"=>3200,"quantity"=>18,"reorder_level"=>6],
    ["id"=>4,"name"=>"Office Chair","sku"=>"SKU-004","category"=>"Furniture","supplier"=>"FurniHub","unit_price"=>7800,"quantity"=>4,"reorder_level"=>5],
    ["id"=>5,"name"=>"Standing Desk","sku"=>"SKU-005","category"=>"Furniture","supplier"=>"FurniHub","unit_price"=>12500,"quantity"=>9,"reorder_level"=>4],
    ["id"=>6,"name"=>"Printer Paper A4","sku"=>"SKU-006","category"=>"Office Supplies","supplier"=>"PaperPlus","unit_price"=>280,"quantity"=>35,"reorder_level"=>15],
    ["id"=>7,"name"=>"Ink Cartridge Black","sku"=>"SKU-007","category"=>"Office Supplies","supplier"=>"PrintMax","unit_price"=>1450,"quantity"=>3,"reorder_level"=>5],
    ["id"=>8,"name"=>"USB-C Hub","sku"=>"SKU-008","category"=>"Accessories","supplier"=>"GadgetPro","unit_price"=>2100,"quantity"=>0,"reorder_level"=>5],
    ["id"=>9,"name"=>"Webcam HD","sku"=>"SKU-009","category"=>"Accessories","supplier"=>"GadgetPro","unit_price"=>2900,"quantity"=>14,"reorder_level"=>6],
    ["id"=>10,"name"=>"Monitor 24-inch","sku"=>"SKU-010","category"=>"Electronics","supplier"=>"VisionTech","unit_price"=>9800,"quantity"=>7,"reorder_level"=>5]
];

echo json_encode([
    "success" => true,
    "data" => $products,
    "timestamp" => date(DATE_ATOM)
]);
?>

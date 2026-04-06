<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * High-Fidelity Catalog Search for ordering system.
     */
    public function search(Request $request): JsonResponse
    {
        $q = strtolower($request->get('q', ''));
        
        // Mock Catalog Database for 'Ultra-Premium Design' demonstration
        $catalog = [
            ['id' => 1, 'name' => 'Premium Ultra-Grow NPK', 'sku' => 'NPK-1001', 'base_price' => 1250.00, 'image_url' => 'https://images.unsplash.com/photo-1542332606-b3d27038670b?q=80&w=100&h=100&fit=crop'],
            ['id' => 2, 'name' => 'Bio-Elite Organic Pesticide', 'sku' => 'BIO-2022', 'base_price' => 855.00, 'image_url' => 'https://images.unsplash.com/photo-1590779033100-9f60705a2f3b?q=80&w=100&h=100&fit=crop'],
            ['id' => 3, 'name' => 'Precision Drip Irrigation Hub', 'sku' => 'IRR-3033', 'base_price' => 4500.00, 'image_url' => 'https://images.unsplash.com/photo-1563206767-5b18f218e0de?q=80&w=100&h=100&fit=crop'],
            ['id' => 4, 'name' => 'Genetic-Gold Tomato Seeds', 'sku' => 'SEED-4044', 'base_price' => 220.00, 'image_url' => 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?q=80&w=100&h=100&fit=crop'],
            ['id' => 5, 'name' => 'Eco-Pulse Solar Pump', 'sku' => 'SOL-5055', 'base_price' => 12500.00, 'image_url' => 'https://images.unsplash.com/photo-1508514177221-388b171f2c97?q=80&w=100&h=100&fit=crop'],
            ['id' => 6, 'name' => 'Titan-Shield Ground Cover', 'sku' => 'TIT-6066', 'base_price' => 450.00, 'image_url' => 'https://images.unsplash.com/photo-1574943320219-553eb213f72d?q=80&w=100&h=100&fit=crop']
        ];

        if (empty($q)) {
            return response()->json($catalog);
        }

        $filtered = array_filter($catalog, function($item) use ($q) {
            return str_contains(strtolower($item['name']), $q) || 
                   str_contains(strtolower($item['sku']), $q);
        });

        return response()->json(array_values($filtered));
    }
}

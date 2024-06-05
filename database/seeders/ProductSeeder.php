<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [ 'code' => 'D-0101', 'name' => 'Afinación básica v4', 'price' => 800.00,
            'description' => '- Cambio de bujías
            - Cambio de aceite
            - Cambio de filtros'],
            [ 'code' => 'D-0102', 'name' => 'Cambio de aceite', 'price' => 200, 'description' => 'Cambio de aceite'],
            [ 'code' => 'D-0103', 'name' => 'Afinación básica v6', 'price' => 1200.00,
            'description' => '- Cambio de bujías
            - Cambio de aceite
            - Cambio de filtros'],
            [ 'code' => 'D-0104', 'name' => 'Afinación básica v8', 'price' => 1500.00,
            'description' => '- Cambio de bujías
            - Cambio de aceite
            - Cambio de filtros'],
            [ 'code' => 'D-0105', 'name' => 'Cambio de frenos delanteros', 'price' => 450.00, 'description' => 'Cambio de frenos delanteros']
        ];

        foreach($data as $item) {
            Product::query()->firstOrCreate(
                [ 'code' => $item['code']],
                [ 'name' => $item['name'], 'price' => $item['price'], 'description' => $item['description'] ]
            );
        }
    }
}

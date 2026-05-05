<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['name' => 'Memoria Digital'],
            [
                'description' => 'Memorial digital con acceso privado para recordar y compartir la vida de tu ser querido. Pago único, sin suscripciones.',
                'price' => 1000.00,
                'currency' => 'mxn',
                'stripe_product_id' => null,
            ]
        );
    }
}

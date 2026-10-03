<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $id = fake()->unique()->numberBetween(90000, 99999);

        return [
            'id' => $id,
            'title' => 'Ardenforest pellets – palé de 70 sacos de 15 kg',
            'slug' => 'ardenforest-pellets-test-'.$id,
            'category' => 'pellets-de-madera',
            'ref' => 'TEST-'.$id,
            'price' => '320.00',
            'old_price' => null,
            'in_stock' => true,
            'is_active' => true,
            'color' => null,
            'hover_image' => null,
            'images' => ['wp-content/uploads/2022/01/er-01-scaled.png'],
            'short_description' => 'Pellets de madera para estufas y calderas de biomasa.',
            'description' => str_repeat('Pellets de madera para calefacción doméstica. Combustión limpia y alto poder calorífico. ', 8),
            'unit_measure_value' => 1050,
            'unit_measure_unit' => 'kg',
            'eprel_code' => null,
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $product = Product::create([
                'name' => ucfirst($faker->unique()->word),
                'price' => $faker->numberBetween(100, 100000),
            ]);

            $product->notes()->create([
              
                'content' => $faker->paragraph(),
            ]);
        }

    }
}

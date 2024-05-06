<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Str;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        
        $product = [
            [
                'id' => 1,
                'name' => 'Galaxy J6',
                'slug' => Str::slug('galaxy-j6'),
                'brand' => 'Samsung',
                'unit_id' => 1,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Nokia 205',
                'slug' => Str::slug('nokia-205'),
                'brand' => 'Nokia',
                'unit_id' => 1,
                'status' => 'active'
            ]

        ];
        Product::insert($product);
    }
}
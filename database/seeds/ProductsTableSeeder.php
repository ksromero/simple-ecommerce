<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Product::create([
    		'name' => $faker->text(15),
	        'description' => $faker->text(25),
	        'price' => $faker->randomFloat(NULL, 2, 8),
	        'cover_image' => $faker->imageUrl(),
    	]);
    }
}

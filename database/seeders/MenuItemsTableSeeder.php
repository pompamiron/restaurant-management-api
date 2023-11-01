<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    public function run()
    {
        MenuItem::create([
            'name' => 'Burger',
            'price' => 10.99,
        ]);

        MenuItem::create([
            'name' => 'Pizza',
            'price' => 12.99,
        ]);

        MenuItem::create([
            'name' => 'Salad',
            'price' => 8.50,
        ]);
    }
}
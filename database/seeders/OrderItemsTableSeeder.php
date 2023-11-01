<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class OrderItemsTableSeeder extends Seeder
{
    public function run()
    {
        OrderItem::create([
            'order_id' => 1,
            'menu_item_id' => 1,
            'quantity' => 2,
            'description' => 'Add extra cheese.',
        ]);

        OrderItem::create([
            'order_id' => 1,
            'menu_item_id' => 2,
            'quantity' => 1,
            'description' => 'Thin crust, extra mushrooms.',
        ]);

        OrderItem::create([
            'order_id' => 2,
            'menu_item_id' => 3,
            'quantity' => 3,
            'description' => 'With Caesar dressing on the side.',
        ]);
    }
}

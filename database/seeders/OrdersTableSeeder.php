<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        Order::create([
            'user_id' => 1,
            'description' => 'This is a test order for Table 5.',
            'order_type' => 'Dine-In',
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'Credit Card',
        ]);

        Order::create([
            'user_id' => 2,
            'description' => 'Takeout order for Customer John Doe.',
            'order_type' => 'To-Go',
            'status' => 'completed',
            'payment_status' => 'paid',
            'payment_method' => 'Cash',
        ]);

        Order::create([
            'user_id' => 3,
            'description' => 'A dine-in order with special requests.',
            'order_type' => 'Dine-In',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => null,
        ]);
    }
}

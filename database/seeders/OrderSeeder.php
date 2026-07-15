<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bike;
use App\Models\User;
use App\Models\Location;
use App\Models\Status;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = json_decode(file_get_contents(database_path('data/ORDERS.json')), true);

        foreach ($orders as $order)
        {
            // BOOKING
            if ($order['price'] <= 150)
            {
                DB::table('orders')->insert([
                    'price' => $order['price'],
                    'order_date' => $order['order_date'],
                    'payed_off' => $order['payed_off'],
                    'bike_id' => Bike::inRandomOrder()->value('id'),
                    'user_id' => User::inRandomOrder()->value('id'),
                    'status_id' => Status::inRandomOrder()->value('id'),
                    'location_id' => Location::inRandomOrder()->value('id'),
                    'rent_start' => $order['rent_start'],
                    'rent_end' => $order['rent_end'],
                    'dropoff_address' => NULL,
                ]);             
            }

            // PURCHASE
            else
            {
                DB::table('orders')->insert([
                    'price' => $order['price'],
                    'order_date' => $order['order_date'],
                    'payed_off' => $order['payed_off'],
                    'bike_id' => Bike::inRandomOrder()->value('id'),
                    'user_id' => User::inRandomOrder()->value('id'),
                    'status_id' => Status::inRandomOrder()->value('id'),
                    'location_id' => Location::inRandomOrder()->value('id'),
                    'location_id' => NULL,
                    'rent_start' => NULL,
                    'rent_end' => NULL,
                    'dropoff_address' => $order['dropoff_address'],
                ]);
            }
        }
    }
}

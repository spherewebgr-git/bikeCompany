<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bike;
use App\Models\User;
use App\Models\Location;
use App\Models\Status;
use App\Models\Card;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = json_decode(file_get_contents(database_path('data/ORDERS.json')), true);

        $faker = fake();

        foreach ($orders as $order)
        {
            $bike = Bike::with('provision')->inRandomOrder()->first();

            $placed = Carbon::instance($faker->dateTimeBetween('-2 year', 'now'));
            $start = $placed->copy()->addMinutes(rand(60, 60 * 24 * 30 * 6))->startOfHour(); // 1 hour to 6 months after order
            $end = $start->copy()->addMinutes(rand(60, 60 * 24 * 7 * 4))->startOfHour(); // 1 hour to 4 weeks after rent start

            DB::table('orders')->insert([
                'price' => $order['price'],
                'order_date' => $placed->toDateString(),
                'payed_off' => $order['payed_off'],
                'bike_id' => $bike->id,
                'card_id' => Card::inRandomOrder()->value('id'),
                'user_id' => User::inRandomOrder()->value('id'),
                'status_id' => Status::inRandomOrder()->value('id'),
                'location_id' => $bike->provision->id == 2 ? Location::inRandomOrder()->value('id') : NULL,

                'rent_start' => $bike->provision->id == 2 ? $start : NULL,
                'rent_end' => $bike->provision->id == 2 ? $end : NULL,
                'returned' => $bike->provision->id == 2 ? ($end->isPast() ? rand(0,1) < 0.99 : false) : NULL,

                'dropoff_address' => $bike->provision->id == 2 ? NULL : $order['dropoff_address'],
            ]);
        }
    }
}

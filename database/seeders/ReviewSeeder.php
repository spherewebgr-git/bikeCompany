<?php

namespace Database\Seeders;

use App\Models\Bike;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $bikes = Bike::all();

        if ($users->isEmpty() || $bikes->isEmpty()) {
            return;
        }

        $comments = [
            'Great bike, very comfortable and easy to ride.',
            'Really good bike. I would definitely recommend it.',
            'Good overall experience and the bike was in great condition.',
            'Very smooth ride and everything worked perfectly.',
            'The bike was good, but there is some room for improvement.',
        ];

        foreach ($users as $user) {

            $selectedBikes = $bikes
                ->shuffle()
                ->take(min(2, $bikes->count()));

            foreach ($selectedBikes as $bike) {

                Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'bike_id' => $bike->id,
                    ],
                    [
                        'rating' => rand(1, 5),
                        'comment' => $comments[
                        array_rand($comments)
                        ],
                    ]
                );
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $cards = json_decode(file_get_contents(database_path('data/USERS.json')), true);

        foreach ($cards as $card)
        {
            DB::table('cards')->insert([
                'number' => $card['card_number'],
                'exp_month' => $card['card_exp_month'],
                'exp_year' => $card['card_exp_year'],
                'cvv' => $card['card_cvv'],
                'user_id' => User::inRandomOrder()->value('id'),
            ]);
        }
    }
}

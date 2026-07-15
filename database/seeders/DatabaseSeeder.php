<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Provision;
use App\Models\Role;
use App\Models\Speed;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            TypeSeeder::class,
            BrandSeeder::class,
            SpeedSeeder::class,
            ProvisionSeeder::class,
            BikeSeeder::class,
            PriceSeeder::class,
            RoleSeeder::class,
        ]);

        $users = json_decode(file_get_contents(database_path('data/USERS.json')), true);

        foreach ($users as $user) {
            DB::table('users')->insert([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'password' => Hash::make($user['password']),
                'card_number' => $user['card_number'],
                'card_exp_month' => $user['card_exp_month'],
                'card_exp_year' => $user['card_exp_year'],
                'card_cvv' => $user['card_cvv'],
                'role_id' => Role::inRandomOrder()->value('id'),

            ]);
        }
    }
}

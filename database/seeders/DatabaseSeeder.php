<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Provision;
use App\Models\Role;
use App\Models\Speed;
use App\Models\Type;
use App\Models\User;
use App\Models\Location;
use App\Models\Status;
use App\Models\Order;
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
            RoleSeeder::class,
        ]);

        $users = json_decode(file_get_contents(database_path('data/USERS.json')), true);

        $counter = 0;
        $staff_role = Role::query()->where('name', "staff")->first();
        $customer_role = Role::query()->where('name', "customer")->first();

        foreach ($users as $user)
        {
            if ($counter < 3)
            {
                DB::table('users')->insert([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'password' => Hash::make($user['password']),
                'role_id' => $staff_role->id,
            ]);
            }

            else
            {
                DB::table('users')->insert([
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'password' => Hash::make($user['password']),
                    'role_id' => $customer_role->id,
                ]);
            }

            $counter ++;
        }

        $this->call([
            CardSeeder::class,
            TypeSeeder::class,
            BrandSeeder::class,
            SpeedSeeder::class,
            ProvisionSeeder::class,
            BikeSeeder::class,
            PriceSeeder::class,
            RoleSeeder::class,
            StatusSeeder::class,
            LocationSeeder::class,
            OrderSeeder::class,
            ImageSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}

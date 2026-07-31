<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Bike;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bikes = Bike::all();
        $images = File::files(public_path('images/bikes'));

        foreach ($bikes as $bike)
        {
            for ($i=0; $i < rand(1,4); $i++)
            {
                DB::table('images')->insert([
                    'bike_id' => $bike->id,
                    'image' => 'images/bikes/' . $images[array_rand($images)]->getFilename(),
                ]);
            }

        }
    }
}

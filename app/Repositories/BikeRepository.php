<?php

namespace App\Repositories;

use App\Models\Bike;

class BikeRepository
{

    /**
     * @param array $data
     * @return Bike
     */
    public function getSingle(array $data): Bike
    {
        return  Bike::where([
            'SKU' => $data['SKU'],
            'colour' => $data['colour'],
            'brand_id' => $data['brand_id'],
            'type_id' => $data['type_id'],
            'speed_id' => $data['speed_id'],
            'provision_id' => $data['provision_id'],
        ])->first();
    }

    /**
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {

     Bike::create([
            'SKU' => $data['SKU'],
            'colour' => $data['colour'],
            'brand_id' => $data['brand_id'],
            'type_id' => $data['type_id'],
            'speed_id' => $data['speed_id'],
            'provision_id' => $data['provision_id'],
            'quantity' => $data['quant'],
            'visible' => true,
        ]);
    }

    public function addPrice(int $id, mixed $price)
    {
        Price::create([
            'bike_id' => $id,
            'price' => $price,
        ]);
    }
}

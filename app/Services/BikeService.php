<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\BikeRepository;

class BikeService
{
    protected BikeRepository $bikeRepository;

    public function __construct(BikeRepository $bikeRepository)
    {
        $this->bikeRepository = $bikeRepository;
    }

    public function AddBikeIfDoesntExist(array $data, $images = null): void
    {
        $bike = $this->bikeRepository->getSingle($data);

        if ($bike)
        {
            $bike->increment('quantity');

            if ($bike->visible == false)
            {
                $bike->update(['visible' => true]);
            }
        }

        $this->bikeRepository->create($data);


        // attaching images to bike
        if ($images)
        {
            foreach ($images as $file)
            {
                $path = $file->store('bikes', 'public');
                Image::create(['bike_id' => $bike->id, 'image' => 'storage/' . $path,]);
            }
        }

        foreach ($request->price ?? [] as $price) // Use $request->price if it exists and is not null. Otherwise, use an empty array []
        {
            $this->bikeRepository->addPrice($bike->id, $price);
        }
    }

}

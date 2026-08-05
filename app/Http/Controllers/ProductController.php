<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Provision;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Speed;
use App\Models\Price;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function management(Request $request)
    {
        $bikes = Bike::with([
            'brand',
            'type',
            'speed',
            'provision',
            'images',
        ]);

        if ($request->filled('SKU'))
        {
            $bikes->where('SKU', 'like', "%{$request->SKU}%");
        }

        if ($request->filled('brand'))
        {
            $bikes->whereHas('brand', function ($q) use ($request)
            {
                $q->where('name', $request->brand);
            });
        }

        if ($request->filled('type'))
        {
            $bikes->whereHas('type', function ($q) use ($request)
            {
                $q->where('name', $request->type);
            });
        }

        if ($request->filled('provision'))
        {
            $bikes->whereHas('provision', function ($q) use ($request)
            {
                $q->where('name', $request->provision);
            });
        }

        if ($request->filled('gears'))
        {
            $bikes->whereHas('speed', function ($q) use ($request)
            {
                $q->where('gears', $request->gears);
            });
        }

        $bikes = $bikes->get();
        return response()->json([
            'bikes' => $bikes,
            'brands' => Brand::all(),
            'types' => Type::all(),
            'provisions' => Provision::all(),
            'speeds' => Speed::all()
        ]);
    }


    public function delete($id)
    {
        $bike = Bike::findOrFail($id);
        $bike->prices()->delete();
        $bike->delete();

        return response()->json([ 'message' => 'Bike deleted.' ]);
    }


    public function edit($id)
    {
        $provisions = Provision::all()->sortBy("name");
        $brands = Brand::all()->sortBy("name");
        $types = Type::all()->sortBy("name");
        $speeds = Speed::all()->sortBy("gears");

        $bike = Bike::query()->where('id', $id)->first();
        $prices = Price::query()->where('bike_id', $id)->get();

        return view('staff.bikes.bike-edit', compact(
            'bike',
            'provisions',
            'brands',
            'types',
            'speeds',
            'prices'
        ));
    }


    public function update($id, Request $request)
    {
        $bike = Bike::query()->where('id', $id)->first();

        $bike->update([
            'colour' => $request->colour,
            'brand_id' => $request->brand_id,
            'type_id' => $request->type_id,
            'speed_id' => $request->speed_id,
            'provision_id' => $request->provision_id,
            'visible' => $request->visible,
        ]);

        // Delete old prices
        Price::where('bike_id', $id)->delete();

        // And replace them with updated prices
        if ($request->pricebuy)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->pricebuy,
            ]);
        }

        if ($request->pricehour)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->pricehour,
            ]);
        }

        if ($request->priceday)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->priceday,
            ]);
        }

        if ($request->priceweek)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->priceweek,
            ]);
        }

        // Delete selected images
        if ($request->filled('delete_images')) {
            $images = Image::whereIn('id', $request->delete_images)->where('bike_id', $bike->id)->get();

            foreach ($images as $image)
            {
                // Delete the file from public/images/bikes:
                Storage::disk('public')->delete(str_replace('storage/', '', $image->image));
                $image->delete();
            }
        }

        // Add new images
        if ($request->hasFile('images'))
        {
            foreach ($request->file('images') as $file)
            {
                $path = $file->store('bikes', 'public');
                Image::create([ 'bike_id' => $bike->id, 'image' => 'storage/' . $path, ]);
            }
        }

        return redirect('dashboard/management/bikes');
    }


    public function quantity($id, Request $request)
    {
        $bike = Bike::query()->where('id', $id)->first();

        $bike->update(['quantity' => $request->quantity]);

        if ($request->quantity < 1)
        {
            $bike->update(['visible' => false]);
        }

        return redirect('dashboard/management/bikes');
    }


    public function create(Request $request)
    {
        $request->validate(
            [
                'SKU' => 'required',
                'brand_id' => 'required',
                'type_id' => 'required',
                'speed_id' => 'required',
                'provision_id' => 'required',
                'colour' => 'required',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                //'price' => 'required|array'
            ]
        );

        $bike = Bike::where([
            'SKU' => $request->SKU,
            'colour' => $request->colour,
            'brand_id' => $request->brand_id,
            'type_id' => $request->type_id,
            'speed_id' => $request->speed_id,
            'provision_id' => $request->provision_id,
        ])->first();


        if ($bike)
        {
            $bike->increment('quantity');

            if ($bike->visible == false)
            {
                $bike->update(['visible' => true]);
            }
        }
        else
        {
            $bike = Bike::create([
                'SKU' => $request->SKU,
                'colour' => $request->colour,
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'speed_id' => $request->speed_id,
                'provision_id' => $request->provision_id,
                'quantity' => $request->quant,
                'visible' => true,
            ]);

            if ($request->hasFile('images'))
            {
                foreach ($request->file('images') as $file)
                {
                    $path = $file->store('bikes', 'public');
                    Image::create([ 'bike_id' => $bike->id, 'image' => 'storage/' . $path, ]);
                }
            }

            foreach ($request->price ?? [] as $price)
            // Use $request->price if it exists and is not null. Otherwise, use an empty array []
            {
                Price::create([
                    'bike_id' => $bike->id,
                    'price' => $price,
                ]);
            }
        }

        return redirect('dashboard/management/bikes');
    }
}
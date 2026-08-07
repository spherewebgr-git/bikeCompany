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


    public function edit($id)
    {
        $bike = Bike::with([
            'brand',
            'type',
            'speed',
            'provision',
            'images',
            'prices',
        ])->findOrFail($id);

        return response()->json([
            'bike' => $bike,
            'brands' => Brand::orderBy('name')->get(),
            'types' => Type::orderBy('name')->get(),
            'speeds' => Speed::orderBy('gears')->get(),
            'provisions' => Provision::orderBy('name')->get(),
        ]);
    }


    public function update($id, Request $request)
    {
        $bike = Bike::findOrFail($id);

        $request->validate([
            'colour' => ['required', 'string', 'max:255'],
            'brand_id' => ['required', 'exists:brands,id'],
            'type_id' => ['required', 'exists:types,id'],
            'speed_id' => ['required', 'exists:speeds,id'],
            'provision_id' => ['required', 'exists:provisions,id'],
            'visible' => ['required', 'boolean'],
            'images.*' => ['image', 'max:2048'],
        ]);

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
                'description' => "€"
            ]);
        }

        if ($request->pricehour)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->pricehour,
                'description' => " €/hour"
            ]);
        }

        if ($request->priceday)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->priceday,
                'description' => " €/day"
            ]);
        }

        if ($request->priceweek)
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $request->priceweek,
                'description' => " €/week"
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

        return response()->json([ 'message' => 'Bike updated successfully' ]);
    }


    public function quantity($id, Request $request)
    {
        $bike = Bike::findOrFail($id);

        $request->validate([ 'quantity' => 'required|integer|min:0' ]);

        $bike->update([
            'quantity' => $request->quantity,
            'visible' => $request->quantity > 0,
        ]);

        return response()->json([ 'bike' => $bike ]);
    }

    public function create()
    {
        return response()->json([
            'brands' => Brand::orderBy('name')->get(),
            'types' => Type::orderBy('name')->get(),
            'speeds' => Speed::orderBy('gears')->get(),
            'provisions' => Provision::orderBy('name')->get(),
        ]);
    }


    public function add(Request $request)
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

            if ($request->pricebuy)
            {
                Price::create([
                    'bike_id' => $bike->id,
                    'price' => $request->pricebuy,
                    'description' => "€"
                ]);
            }

            if ($request->pricehour)
            {
                Price::create([
                    'bike_id' => $bike->id,
                    'price' => $request->pricehour,
                    'description' => " €/hour"
                ]);
            }

            if ($request->priceday)
            {
                Price::create([
                    'bike_id' => $bike->id,
                    'price' => $request->priceday,
                    'description' => " €/day"
                ]);
            }

            if ($request->priceweek)
            {
                Price::create([
                    'bike_id' => $bike->id,
                    'price' => $request->priceweek,
                    'description' => " €/week"
                ]);
            }
        }

        return response()->json([ "bike" => $bike ]);
    }
}
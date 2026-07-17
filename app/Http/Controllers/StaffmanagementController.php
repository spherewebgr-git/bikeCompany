<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Provision;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Speed;
use Illuminate\Http\Request;

class StaffmanagementController extends Controller
{
    public function management()
    {
        $bikes = Bike::with([
            'brand',
            'type',
            'speed',
            'provision',
            'prices'
        ])->get();

        $provisions = Provision::all();
        $brands = Brand::all();
        $types = Type::all();
        $speeds = Speed::all();

        return view('staff.bikes.management', compact(
            'bikes',
            'provisions',
            'brands',
            'types',
            'speeds'
        ));
    }

    public function filter(Request $request)
    {
        $bikes = Bike::query();

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

        return view('staff.bikes.management', [
            'bikes' => $bikes->get(),
            'brands' => Brand::all(),
            'types' => Type::all(),
            'provisions' => Provision::all(),
            'speeds' => Speed::all(),
        ]);
    }

    public function view($id)
    {
        $bike = Bike::query()->where('id', $id)->first();
        return view('staff.bikes.bike-view', ['bike'=>$bike]);
    }

    public function delete($id)
    {
        $bike = Bike::query()->where('id', $id)->first();
        $bike->delete();
        return redirect('dashboard/management/bikes');
    }

    public function edit($id)
    {
        $provisions = Provision::all();
        $brands = Brand::all();
        $types = Type::all();
        $speeds = Speed::all();

        $bike = Bike::query()->where('id', $id)->first();

        return view('staff.bikes.bike-edit', compact(
            'bike',
            'provisions',
            'brands',
            'types',
            'speeds'
        ));
    }
    
    public function update($id, Request $request)
    {
        $bike = Bike::query()->where('id', $id)->first();
        $bike->update($request->all());
        return redirect('dashboard/management/bikes');
    }

    public function create(Request $request)
    {
        $request->validate(
            [
                'brand' => 'required',
                'type' => 'required',
                'speed' => 'required',
                'provision' => 'required',
                'colour' => 'required',
                'image_path' => 'required'
            ]
        );

        Bike::create([
            'colour' => $request->colour,
            'image_path' => $request->image_path,
            'brand_id' => $request->brand,
            'type_id' => $request->type,
            'speed_id' => $request->speed,
            'provision_id' => $request->provision,
        ]);

        return redirect('dashboard/management/bikes');
    }
}

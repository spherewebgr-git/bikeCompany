<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Provision;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Type;
use App\Models\Speed;
use App\Models\Price;
use App\Models\Role;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class StaffmanagementController extends Controller
{

    // --------------- BIKES --------------- \\
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

    public function search(Request $request)
    {
        $bikes = Bike::query();

        if ($request->filled('SKU'))
        {
            $bikes->where('SKU', $request->SKU);
        }

        return view('staff.bikes.management', [
            'bikes' => $bikes->get(),
            'brands' => Brand::all(),
            'types' => Type::all(),
            'provisions' => Provision::all(),
            'speeds' => Speed::all(),
        ]);
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

        Price::where('bike_id', $id)->delete();
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
            'image_path' => $request->image_path,
            'brand_id' => $request->brand_id,
            'type_id' => $request->type_id,
            'speed_id' => $request->speed_id,
            'provision_id' => $request->provision_id,
        ]);

        // Delete old prices
        Price::where('bike_id', $id)->delete();

        // And replace them with updated prices
        foreach ($request->price ?? [] as $price)
        // Use $request->price if it exists and is not null. Otherwise, use an empty array []
        {
            Price::create([
                'bike_id' => $bike->id,
                'price' => $price,
            ]);
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
                'image_path' => 'required',
                'price' => 'required|array'
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
        }
        else
        {
            $bike = Bike::create([
                'SKU' => $request->SKU,
                'colour' => $request->colour,
                'image_path' => $request->image_path,
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'speed_id' => $request->speed_id,
                'provision_id' => $request->provision_id,
                'quantity' => 1,
            ]);

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


    // --------------- USERS --------------- \\
    public function users(Request $request)
    {
        $selectedRole = $request->query('role');

        $users = User::with('role')
            ->when($selectedRole, function ($query) use ($selectedRole) {
                $query->whereHas('role', function ($roleQuery) use ($selectedRole) {
                    $roleQuery->where('name', $selectedRole);
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('staff.users.index', compact('users', 'selectedRole'));
    }

    public function promoteToStaff(User $user): RedirectResponse
    {
        $staffRole = Role::where('name', 'staff')->firstOrFail();

        if($user->role_id === $staffRole->id){
            return back()->with('message', 'already promoted');
        }

        $user->update([
            'role_id' => $staffRole->id,
        ]);
        return back()->with(
            'message',
            'demoted.'
        );
    }

    public function demoteToCustomer(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors([
                'user' => 'cannot demote to yourself',
            ]);
        }

        $customerRole = Role::where('name', 'customer')->firstOrFail();

        if ($user->role_id === $customerRole->id) {
            return back()->with('message', 'already customer.');
        }

        $user->update([
            'role_id' => $customerRole->id,
        ]);

        return back()->with(
            'message',
            'demoted.'
        );
    }

    // --------------- CATEGORIES --------------- \\
    public function managecategories()
    {
        $provisions = Provision::all();
        $brands = Brand::all();
        $types = Type::all();
        $speeds = Speed::all();
        $statuses = Status::all();
        $locations = Location::all();


        return view('staff.categories.management', compact(
            'provisions',
            'brands',
            'types',
            'speeds',
            'statuses',
            'locations'
        ));
    }
}

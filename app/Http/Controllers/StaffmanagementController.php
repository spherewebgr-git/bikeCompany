<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use App\Models\Provision;
use App\Models\Brand;
use App\Models\Location;
use App\Models\Order;
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
            'provision'
        ])->get();

        $provisions = Provision::all()->sortBy("name");
        $brands = Brand::all()->sortBy("name");
        $types = Type::all()->sortBy("name");
        $speeds = Speed::all()->sortBy("gears");

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
            'brands' => Brand::all()->sortBy("name"),
            'types' => Type::all()->sortBy("name"),
            'provisions' => Provision::all()->sortBy("name"),
            'speeds' => Speed::all()->sortBy("gears"),
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
            'brands' => Brand::all()->sortBy("name"),
            'types' => Type::all()->sortBy("name"),
            'provisions' => Provision::all()->sortBy("name"),
            'speeds' => Speed::all()->sortBy("gears"),
        ]);
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
        $provisions = Provision::all()->sortBy("name");
        $brands = Brand::all()->sortBy("name");
        $types = Type::all()->sortBy("name");
        $speeds = Speed::all()->sortBy("gears");
        $statuses = Status::all()->sortBy("name");
        $locations = Location::all()->sortBy("name");


        return view('staff.categories.management', compact(
            'provisions',
            'brands',
            'types',
            'speeds',
            'statuses',
            'locations'
        ));
    }

    public function searchcategory(Request $request)
    {
        $provisions = Provision::query();
        $brands = Brand::query();
        $types = Type::query();
        $speeds = Speed::query();
        $statuses = Status::query();
        $locations = Location::query();

        if ($request->filled('gears'))
        {
            $speeds = Speed::where('gears', $request->gears);
        }
        elseif ($request->filled('provisions'))
        {
            $provisions = Provision::where('name', $request->provisions);
        }
        elseif ($request->filled('loactions'))
        {
            $loactions = Location::where('name', $request->loactions);
        }
        elseif ($request->filled('statuses'))
        {
            $statuses = Status::where('name', $request->statuses);
        }
        elseif ($request->filled('types'))
        {
            $types = Type::where('name', $request->types);
        }
        elseif ($request->filled('brands'))
        {
            $brands = Brand::where('name', $request->brands);
        }

        return view('staff.categories.management', [
            'provisions' => $provisions->orderBy('name')->get(),
            'brands' => $brands->orderBy('name')->get(),
            'types' => $types->orderBy('name')->get(),
            'speeds' => $speeds->orderBy('gears')->get(),
            'statuses' => $statuses->orderBy('name')->get(),
            'locations' => $locations->orderBy('name')->get(),
        ]);
    }

    public function deletecategory($id, string $category)
    {
        switch ($category)
        {
            case "provision":
                Provision::where('id', $id)->delete();
                break;
            case "brand":
                Brand::where('id', $id)->delete();
                break;
            case "type":
                Type::where('id', $id)->delete();
                break;
            case "gears":
                Speed::where('id', $id)->delete();
                break;
            case "status":
                Status::where('id', $id)->delete();
                break;
            case "location":
                Location::where('id', $id)->delete();
                break;
            default:
                abort(404);
        }

        return redirect('dashboard/management/categories');
    }

    public function newcategory(Request $request, string $category)
    {

        switch ($category)
        {
            case "provision":
                $request->validate(['provname' => 'required']);
                Provision::firstOrCreate(['name' => $request->provname]);
                break;
            case "brand":
                $request->validate(['brandname' => 'required']);
                Brand::firstOrCreate(['name' => $request->brandname]);
                break;
            case "type":
                $request->validate(['typename' => 'required']);
                Type::firstOrCreate(['name' => $request->typename]);
                break;
            case "gears":
                $request->validate(['gearamount' => 'required']);
                Speed::firstOrCreate(['gears' => $request->gearamount]);
                break;
            case "status":
                $request->validate(['statname' => 'required']);
                Status::firstOrCreate(['name' => $request->statname]);
                break;
            case "location":
                $request->validate(['locname' => 'required']);
                Location::firstOrCreate(['name' => $request->locname]);
                break;
            default:
                abort(404);
        }

        return redirect('dashboard/management/categories');
    }

    // --------------- ORDERS --------------- \\

    public function manageorders()
    {// NOTE: Add 'step' column in statuses table?
        $orders = Order::query()->where('status_id', '<>', 4)->get();
        
        $user = User::all();
        $bike = Bike::all();
        $location = Location::all()->sortBy("name");
        $status = Status::all()->sortBy("id");
        $provision = Provision::all()->sortBy("id");

        return view('staff.orders.management', compact(
            'orders',
            'user',
            'bike',
            'location',
            'status',
            'provision'
        ));
    }

    public function orderupdate($id, Request $request)
    {
        $order = Order::query()->where('id', $id)->first();
        $order->update([ 'status_id' => $request->stat ]);

        return redirect('dashboard/management/orders');
    }

    public function searchorder(Request $request)
    {
        $orders = Order::query()->where('status_id', '<>', 4);

        if ($request->filled('order'))
        {
            $orders->where('id', $request->order);
        }

        if ($request->filled('user'))
        {
            $orders->whereHas('user', function ($q) use ($request)
            {
                $q->where('first_name', 'LIKE', "%{$request->user}%")
                ->orWhere('last_name', 'LIKE', "%{$request->user}%")
                ->orWhere('phone', 'LIKE', "%{$request->user}%")
                ->orWhere('email', 'LIKE', "%{$request->user}%");

            });
        }

        if ($request->filled('product'))
        {
            $orders->whereHas('bike', function ($q) use ($request)
            {
                $q->where('SKU', 'LIKE', "%{$request->user}%")
                ->orWhere('serialnum', 'LIKE', "%{$request->user}%");

            });
        }

        if ($request->filled('provision'))
        {
            $orders->whereHas('bike.provision', function ($q) use ($request)
            {
                $q->where('id', $request->provision);
            });
        }

        if ($request->filled('pickup'))
        {
            $orders->whereHas('location', function ($q) use ($request)
            {
                $q->where('id', $request->pickup);
            });
        }

        if ($request->filled('status'))
        {
            $orders->whereHas('status', function ($q) use ($request)
            {
                $q->where('id', $request->status);
            });
        }

        if ($request->filled('payment'))
        {
            $orders->where('payed_off', $request->payment);
        }

        return view('staff.orders.management', [
            'orders' => $orders->get(),
            'user' => User::all(),
            'bike' => Bike::all(),
            'location' => Location::all()->sortBy("name"),
            'status' => Status::all()->sortBy("id"),
            'provision' => Provision::all()->sortBy("id"),
        ]);
    }

    public function filterorder(Request $request)
    {
    }
}

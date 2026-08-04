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
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderReadyMail;
use App\Mail\OrderDeliveredMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            $bikes->where('SKU', 'LIKE', "%{$request->SKU}%");
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

    // --------- ACTIVE RENTALS --------- \\

    public function activerentals()
    {
        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete)->where('rent_end', '<>', null)->where('returned', false); })->get();

        return view('staff.activerentals.track', [
            'orders' => $orders->sortBy("rent_end")
        ]);
    }

    public function activerentalsfilter(Request $request)
    {
        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete)->where('rent_end', '<>', null)->where('returned', false); });

        if ($request->input('return') === 'overdue')
        {
            $orders = $orders->where('rent_end', '<', \now());
        }

        if ($request->input('return') === 'pending')
        {
            $orders = $orders->where('rent_end', '>=', \now());
        }

        return view('staff.activerentals.track', [
            'orders' => $orders->orderBy("rent_end")->get()
        ]);
    }

    public function updatereturned(Order $order)
    {
        if ($order->returned) {
            return redirect()->back();
        }

        $order->returned = true;
        if ($order->rent_end != null) { $order->rent_end = \now(); }

        $order->save();

        $order->load([
            'user',
            'location',
            'bike.provision',
            'bike.brand',
            'bike.type',
            'bike.speed',
        ]);

        Mail::to($order->user->email)
            ->queue(new OrderDeliveredMail($order));

        return redirect()->back();
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
            'promoted.'
        );
    }

    public function demoteToCustomer(User $user): RedirectResponse
    {
        if (Auth::id() === $user->id)
        {
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
        $statuses = Status::all()->sortBy("step");
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
            $speeds = Speed::where('gears', 'LIKE', "%{$request->gears}%");
        }
        elseif ($request->filled('provisions'))
        {
            $provisions = Provision::where('name', "%{$request->provisions}%");
        }
        elseif ($request->filled('locations'))
        {
            $locations = Location::where('name', 'LIKE', "%{$request->locations}%");
        }
        elseif ($request->filled('statuses'))
        {
            $statuses = Status::where('name', 'LIKE', "%{$request->statuses}%");
        }
        elseif ($request->filled('types'))
        {
            $types = Type::where('name', 'LIKE', "%{$request->types}%");
        }
        elseif ($request->filled('brands'))
        {
            $brands = Brand::where('name', 'LIKE', "%{$request->brands}%");
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
                $request->validate(['statname' => 'required', 'statstep' => 'required']);
                Status::firstOrCreate(['name' => $request->statname, 'step' => $request->statstep]);
                break;

            case "location":
                $request->validate(['locname' => 'required']);
                $request->validate(['latitude' => 'required']);
                $request->validate(['longitude' => 'required']);
                Location::firstOrCreate([
                    'name' => $request->locname,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude
                ]);
                break;

            default:
                abort(404);
        }

        return redirect('dashboard/management/categories');
    }

    public function editcategory(Request $request, string $category)
    {
        switch ($category)
        {
            case "provision":
                $request->validate(['prov' => 'required']);
                $prov = Provision::query()->where('id', $request->provID);
                $prov->update(['name' => $request->prov]);
                break;

            case "brand":
                $request->validate(['brand' => 'required']);
                $brand = Brand::query()->where('id', $request->brandID);
                $brand->update(['name' => $request->brand]);
                break;

            case "type":
                $request->validate(['type' => 'required']);
                $type = Type::query()->where('id', $request->typeID);
                $type->update(['name' => $request->type]);
                break;

            case "gears":
                $request->validate(['gear' => 'required']);
                $speed = Speed::query()->where('id', $request->gearID);
                $speed->update(['gears' => $request->gear]);
                break;

            case "status":
                $request->validate(['stat' => 'required', 'step' => 'required']);
                $stat = Status::query()->where('id', $request->statID);
                $stat->update(['name' => $request->stat, 'step' => $request->step]);
                break;

            case "location":
                $request->validate(['loc' => 'required']);
                $request->validate(['lat' => 'required']);
                $request->validate(['long' => 'required']);

                $loc = Location::query()->where('id', $request->locID);
                $loc->update([
                    'name' => $request->loc,
                    'latitude' => $request->lat,
                    'longitude' => $request->long
                ]);
                break;

            default:
                abort(404);
        }

        return redirect('dashboard/management/categories');
    }

    // --------------- ORDERS --------------- \\

    public function manageorders()
    {
        $user = User::all();
        $bike = Bike::all();
        $location = Location::all()->sortBy("name");
        $provision = Provision::all()->sortBy("id");
        $status = Status::where('step', '>', 0)->orderBy("step")->get();

        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', '>', 0)->where('step', '<', $complete); })->get();

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

        // Κρατάμε το προηγούμενο status
        $previousStatus = strtolower($order->status->name);

        // Ενημερώνουμε το status
        $order->update([
            'status_id' => $request->stat
        ]);

        $complete = Status::max('step');

        // Ξαναφορτώνουμε τη σχέση status
        $order->load('status');

        $isReady = strtolower($order->status->name) == $complete-1; // "Ready"
        $wasAlreadyReady = $previousStatus == $complete-1; // "Ready"

        if ($isReady && !$wasAlreadyReady) {

            Mail::to($order->user->email)
                ->queue(new OrderReadyMail($order));
        }

        $order->load('status');

        $isComplete = strtolower($order->status->step) == $complete;
        $wasAlreadyComplete = $previousStatus == $complete;

        $isBuy = strtolower($order->bike->provision->name ?? '') === 'buy';

        if ($isComplete && !$wasAlreadyComplete && $isBuy) {

            Mail::to($order->user->email)
                ->queue(new OrderDeliveredMail($order));
        }

        if ($isComplete && $order->payed_off == false)
        {
            $order->update(['payed_off' => true]);
        }

        if ($isComplete && $order->bike->provision->name === 'buy')
        {
            $order->bike->quantity -- ;

            if ($order->bike->quantity < 1)
            {
                $order->bike->update(['visible' => false]);
            }
        }

        return redirect()->route('dashboard.management.orders');
    }

    public function searchorder(Request $request)
    {
        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', '>', 0)->where('step', '<', $complete); });

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
                $q->where('SKU', 'LIKE', "%{$request->product}%")->where('serialnum', null)
                ->orWhere('serialnum', 'LIKE', "%{$request->product}%");
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
            'status' => Status::where('step', '>', 0)->get()->sortBy("step"),
            'provision' => Provision::all()->sortBy("id"),
        ]);
    }

    // --------------- ORDERS --------------- \\

    public function history()
    {
        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); })->get();

        $user = User::all();
        $bike = Bike::all();
        $location = Location::all()->sortBy("name");
        $provision = Provision::all()->sortBy("id");

        return view('staff.history.completed-orders', compact(
            'orders',
            'user',
            'bike',
            'location',
            'provision'
        ));
    }

    public function searchhistory(Request $request)
    {
        $complete = Status::max('step');
        $orders = Order::whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); });

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
                $q->where('SKU', 'LIKE', "%{$request->product}%")->where('serialnum', null)
                ->orWhere('serialnum', 'LIKE', "%{$request->product}%");

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

        if ($request->filled('payment'))
        {
            $orders->where('payed_off', $request->payment);
        }

        return view('staff.history.completed-orders', [
            'orders' => $orders->get(),
            'user' => User::all(),
            'bike' => Bike::all(),
            'location' => Location::all()->sortBy("name"),
            'provision' => Provision::all()->sortBy("id"),
        ]);
    }

    // --------------- STATISTICS --------------- \\

    public function statistics()
    {
        $locations = Location::all();
        $locsales = [];

        foreach ($locations as $location)
        {
            $locsales[$location->name] =
            [
                'location' => $location->name,
                'profit' => 0.0,
                'sales' => 0,
            ];
        }

        $orders = Order::all();

        $totalorders = 0;
        $neworders = 0;
        $totalprofit = 0;
        $newprofit = 0;
        $rents = 0;
        $purchases = 0;

        foreach ($orders as $order)
        {
            $totalprofit += $order->price;
            $totalorders ++;

            if ($order->order_date->isToday())
            {
                $newprofit += $order->price;
                $neworders ++;
            }

            if ($order->rent_start !== null)
            {
                $rents ++;

                $loc = $order->location?->name;
                if ($loc)
                {
                    $locsales[$loc]['profit'] += $order->price;
                    $locsales[$loc]['sales'] ++;
                }
            }
            else
            {
                $purchases ++;
            }
        }

        $users = User::all();

        $totalusers = 0;
        $newusers = 0;

        foreach ($users as $user)
        {
            $totalusers ++;

            if ($user->created_at == \today())
            {
                $newusers ++;
            }
        }

        return view('staff.statistics.view', [
            'totalorders' => $totalorders,
            'neworders' => $neworders,
            'totalprofit' => $totalprofit,
            'newprofit' => $newprofit,
            'totalusers' => $totalusers,
            'newusers' => $newusers,
            'rents' => $rents,
            'purchases' => $purchases,
            'locsales' => array_values($locsales),
        ]);
    }
}

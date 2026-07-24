<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Bike;
use App\Models\Location;
use App\Models\Order;
use App\Models\Provision;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function orders(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');    
        $orders = Order::where('user_id', $user->id)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', '<>', $complete)->where('step', '<>', 0); })->get();
        
        $bike = Bike::all();
        $location = Location::all()->sortBy("name");
        $provision = Provision::all()->sortBy("id");
        $status = Status::all()->sortBy("step");

        return view('profile.myorders', compact(
            'orders',
            'bike',
            'location',
            'provision',
            'status'
        ));
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');    
        $orders = Order::where('user_id', $user->id)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); })->get();
        
        $bike = Bike::all();
        $location = Location::all()->sortBy("name");
        $provision = Provision::all()->sortBy("id");

        return view('profile.myhistory', compact(
            'orders',
            'bike',
            'location',
            'provision'
        ));
    }

    public function searchhistory(Request $request)
    {
        $user = $request->user();
        $complete = Status::max('step');
        $orders = Order::where('user_id', $user->id)->whereHas('status', function ($query) use ($complete)
        { $query->where('step', $complete); });

        if ($request->filled('date'))
        {
            $orders->where('order_date', $request->date);
        }

        if ($request->filled('order'))
        {
            $orders->where('id', $request->order);
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
            $orders->orWhere('dropoff_address', 'LIKE', "%{$request->pickup}%")
            ->orWhereHas('location', function ($q) use ($request)
            {
                $q->where('id', 'LIKE', "%{$request->pickup}%");
            });
        }

        if ($request->filled('price'))
        {
            $orders->where('price', 'LIKE', "%{$request->price}%");
        }

        return view('profile.myhistory', [
            'orders' => $orders->get(),
            'bike' => Bike::all(),
            'provision' => Provision::all()->sortBy("id"),
        ]);
    }
}

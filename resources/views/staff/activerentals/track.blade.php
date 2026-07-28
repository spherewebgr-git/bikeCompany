@extends('layouts.admin')
@section('content')
    <div id="ActiveRentals">
        <h2>Active Rentals</h2>
        <div class="container">

            <form action="{{ route('activerentals.filter') }}" method="GET" class="filter">
                <legend>Return Date:</legend>
                
                <input type="radio" id="any" name="return" value="any"/>
                <label for="any">Any</label>
                
                <input type="radio" id="overdue" name="return" value="overdue"/>
                <label for="overdue">Overdue</label>
                
                <input type="radio" id="pending" name="return" value="pending"/>
                <label for="pending">Pending</label>

                <button type="submit">Filter</button>
            </form>

            <div class="row">
                @foreach ($orders as $order)
                    <div class="active-single">

                        <img src={{ $order->bike->image_path }}>

                        <div class="bikeinfo">
                            <p class="ids">
                                Order ID: {{ $order->id }} - Product ID: 
                                @if ($order->bike->serialnum)
                                    {{ $order->bike->serialnum }}
                                @else
                                    {{ $order->bike->SKU }}
                                @endif
                            </p>

                            @if ($order->rent_end->isPast())
                                <p class="overdue"><b>OVERDUE:</b> {{ $order->rent_end }}</p>
                            @else
                                <p class="tobe"><b>Rent End:</b> {{ $order->rent_end }}</p>
                            @endif

                            <p class="location">
                                <i class="fa-solid fa-location-dot" style="color: #fe4819;"></i>
                                Picked up at our store at {{ $order->location->name }}
                            </p>

                            <p class="customer">
                                <i class="fa-solid fa-address-book" style="color: #6b6f82;"></i>
                                {{ $order->user->first_name }} {{ $order->user->last_name }}
                                <br>
                                <i class="fa-solid fa-phone" style="color: #6b6f82;"></i>
                                {{ $order->user->phone }}
                                <br>
                                <i class="fa-solid fa-envelope" style="color: #6b6f82;"></i>
                                {{ $order->user->email }}
                            </p>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
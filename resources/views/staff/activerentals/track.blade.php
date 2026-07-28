@extends('layouts.admin')
@section('content')
    <div id="ActiveRentals">
        <div class="container">
            <div class="row">
                <h2>Active Rentals</h2>

                <div class="active-container">
                    @foreach ($orders as $order)
                        <div class="active-single">

                            <img src={{ $order->bike->image_path }}>

                            <h4>Order ID: {{ $order->id }}</h4>

                            @if ($order->rent_end->isPast())
                                <h4 class="overdue">OVERDUE: {{ $order->rent_end }}</h4>
                            @else
                                <h4 class="tobe">Rent End: {{ $order->rent_end }}</h4>
                            @endif
                            

                            <h5>
                                <i class="fa-solid fa-address-book" style="color: #6b6f82;"></i>
                                {{ $order->user->first_name }} {{ $order->user->last_name }}
                                <br>
                                <i class="fa-solid fa-phone" style="color: #6b6f82;"></i>
                                {{ $order->user->phone }}
                                <br>
                                <i class="fa-solid fa-envelope" style="color: #6b6f82;"></i>
                                {{ $order->user->email }}
                            </h5>

                            <h6>Product ID: 
                                @if ($order->bike->serialnum)
                                    {{ $order->bike->serialnum }}
                                @else
                                    {{ $order->bike->SKU }}
                                @endif
                            </h6>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
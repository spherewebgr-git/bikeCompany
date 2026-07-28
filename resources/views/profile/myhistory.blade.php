<x-app-layout>
<div id="MyHistory">
    <div class="containers">
        <h2 class="section-heading">Order History</h2>

        <div class="row col-12">
            @include('components.historyfilters')
        </div>

        @if ($orders->first())
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-12 col-lg-6">
                        <div class="order-card">
                            <img src="{{ $order->bike->image_path }}" alt="bike"/>
                            
                            <div class="info">
                                <h4 class="model">
                                    {{ $order->bike->speed->gears }}-speed {{ $order->bike->colour }} {{ $order->bike->brand->name }} {{ $order->bike->type->name }} Bike
                                </h4>

                                <div class="group">
                                    <p class="orderid"><b>Order ID: </b>{{ $order->id }}</p>

                                    <p class="productid">
                                        <b>Product ID: </b>
                                        @if ($order->bike->serialnum)
                                            {{ $order->bike->serialnum }}
                                        @else
                                            {{ $order->bike->SKU }}
                                        @endif
                                    </p>
                                </div>

                                <hr>

                                <p class="date"><b>Placed at: </b>{{ $order->order_date }}</p>

                                <p class="quantity">
                                    <b>Quantity: </b>
                                    @if ($order->bike->quantity)
                                        {{ $order->bike->quantity }}
                                    @else
                                        1
                                    @endif
                                </p>

                                <p class="price"><b>Cost: </b>{{ $order->price }} €</p>

                                <hr>

                                <p class="provision" style="text-transform: capitalize;">
                                    <b>Usage Type: </b>{{ $order->bike->provision->name }}
                                </p>

                                <p class="address">
                                    <b>Pickup Location: </b>
                                    @if ($order->dropoff_address)
                                        {{ $order->dropoff_address }}
                                    @else
                                        Our Store at {{ $order->location->name }}
                                    @endif
                                </p>
                                
                                @if ($order->bike->serialnum)
                                    <a href="{{ route('checkout.create-rental', $order->bike) }}">
                                        Rent again
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>        
                @endforeach
            </div>
        @else  
            <div class="no-orders">
                <p> You don't have any completed orders yet,<br>or none of them match your filters!</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
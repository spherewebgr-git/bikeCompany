<x-app-layout>
<div id="MyOrders">
    <div class="container">
        <h2 class="section-heading">Pending Orders</h2>

        <div class="row col-12">
            @include('components.orderfilters')
        </div>

        @if ($orders->first())
            <div class="row g-4">
                @foreach ($orders as $order)
                    <div class="col-12">
                        <div class="order-card">
                            <div class="path">
                                @include ('components.orderstatus')
                            </div>

                            <div class="inner-card">
                                <img src="{{ asset($order->bike->images->first()->image) }}" alt="bike"/>

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
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-orders">
                <p> You don't have any pending orders yet,<br>or none of them match your filters!</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>

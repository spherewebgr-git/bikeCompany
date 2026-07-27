<x-app-layout>
<div id="MyHistory">
    <div class="containers">
        <h2 class="section-heading">Order History</h2>

            <div class="row col-12">
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Type</th>
                        <th>Location</th>
                    </tr>

                    <tr>
                        <td class="search">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <input type="date" id="orderdate" name="orderdate" placeholder="Search Date">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>

                        <td class="search orderid">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <input type="text" id="order" name="order" placeholder="ID">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>

                        <td class="search productcode">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <input type="text" id="product" name="product" placeholder="Search Product">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>

                        <td class="search">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <input type="number" id="price" name="price" placeholder="Search Price">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>

                        <td class="search">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <select name="provision" id="provision">
                                    <option value="" selected> Any </option>
                                    @foreach ($provision as $prov)
                                        <option value="{{ $prov->id }}">
                                            {{ $prov->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>

                        <td class="search">
                            <form method="GET" action="{{ route('profile.history.search') }}">
                                <input type="text" id="pickup" name="pickup" placeholder="Search Location">
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
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
                            </div>
                        </div>
                    </div>        
                @endforeach
            </div>
        @else  
            <div class="no-orders">
                <p> You don't have any completed orders yet!</p>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
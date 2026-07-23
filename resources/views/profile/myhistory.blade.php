<x-app-layout>
<div id="MyHistory">
    <div class="container">
        <h2 class="section-heading">Completed Orders</h2>

        @if ($orders->first())
            <div id="data-table-simple_wrapper" class="dataTables_wrapper">
                <table id="data-table-simple" class="display dataTable dtr-inline" role="grid">
                    <tr>
                        <th>Date</th>
                        <th>Image</th>
                        <th>Order #</th>
                        <th>Product</th>
                        <th>Amount</th>
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

                        <td class="search">
                            {{-- Image --}}
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
                            {{-- Amount --}}
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

                    @foreach ($orders as $order)
                        <tr class="data-row">
                            <td>
                                {{ $order->order_date }}
                            </td>
                            <td>
                                <img src="{{ $order->bike->image_path }}" alt="bike"/>
                            </td>
                            <td>
                                {{ $order->id }}
                            </td>
                            <td>
                                @if ($order->bike->serialnum)
                                    <b>Serial Number:</b><br>
                                    {{ $order->bike->serialnum }}
                                @else
                                    <b>S.K.U.:</b><br>
                                    {{ $order->bike->SKU }}
                                @endif
                            </td>
                            <td class="quantity">
                                @if ($order->bike->quantity)
                                    {{ $order->bike->quantity }}
                                @else
                                    1
                                @endif
                            </td>
                            <td>
                                {{ $order->price }}
                            </td>
                            <td>
                                {{ $order->bike->provision->name }}
                            </td>
                            <td>
                                @if ($order->dropoff_address)
                                    <i class="fa-solid fa-truck-arrow-right" style="color: #d40040;"></i>
                                    <i style="color: #d40040;">{{ $order->dropoff_address }}</i>
                                @else
                                    <i class="fa-solid fa-warehouse" style="color: #244ecb;"></i>
                                    <b style="color: #244ecb;">{{ $order->location->name }}</b>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @else 
            <p class="no-orders">
                You don't have any completed orders yet!
            </p>
        @endif
    </div>
</div>
</x-app-layout>
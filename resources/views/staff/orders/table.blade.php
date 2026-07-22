<div id="data-table-simple_wrapper" class="dataTables_wrapper">
    <table id="data-table-simple" class="display dataTable dtr-inline" role="grid">
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Location</th>
            <th>Status</th>
            <th>Payment</th>
        </tr>

        <tr>
            <td class="search orderid">
                <form method="GET" action="{{ route('order.search') }}">
                    <input type="text" id="order" name="order" placeholder="ID">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>

            <td class="search">
                <form method="GET" action="{{ route('order.search') }}">
                    <input type="text" id="user" name="user" placeholder="Search Customer">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>

            <td class="search productcode">
                <form method="GET" action="{{ route('order.search') }}">
                    <input type="text" id="product" name="product" placeholder="Search Product">
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>

            <td class="search">
                
            </td>

            <td class="search">
                <form method="GET" action="{{ route('order.search') }}">
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
                <form method="GET" action="{{ route('order.search') }}">
                    <select name="pickup" id="pickup">
                        <option value="" selected> Any </option>
                        @foreach ($location as $loc)
                            <option value="{{ $loc->id }}">
                                {{ $loc->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>

            <td class="search">
                <form method="GET" action="{{ route('order.search') }}">
                    <select name="status" id="status">
                        <option value="" selected> Any </option>
                        @foreach ($status as $stat)
                            <option value="{{ $stat->id }}">
                                {{ $stat->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>

            <td class="search">
                <form method="GET" action="{{ route('order.search') }}">
                    <select name="payment" id="payment">
                        <option value="" selected> Any </option>
                        <option value={{ 0 }}>Unpayed</option>
                        <option value={{ 1 }}>Payed</option>
                    </select>
                    <button type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </td>
        </tr>

        @foreach ($orders as $order)
            <tr class="data-row">
                <td>
                    {{ $order->id }}
                </td>
                <td>
                    <i class="fa-solid fa-address-book" style="color: #6b6f82;"></i>
                    <b>{{ $order->user->first_name }} {{ $order->user->last_name }}</b>
                    <br>
                    <i class="fa-solid fa-phone" style="color: #6b6f82;"></i>
                    {{ $order->user->phone }}
                    <br>
                    <i class="fa-solid fa-envelope" style="color: #6b6f82;"></i>
                    {{ $order->user->email }}
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
                <td>
                    <div class="order-actions">
                        <a href="#" onclick="showStatusForm({{ $order->id }}); return false;" class="stat">
                            <i class="fa-regular fa-pen-to-square"></i>
                            {{ $order->status->name }}
                        </a>
                    </div>

                    <form method="POST" action="{{ route('order.update', [$order->id]) }}" 
                    class="edit-status" id="status-form-{{ $order->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                        <label for="stat">Order Status:</label><br>
                        <select name="stat" id="stat_id" class="form-select">
                            @foreach ($status as $stat)
                                <option value="{{ $stat->id }}" @selected($order?->status?->id === $stat->id)>
                                    {{ $stat->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="buttons">
                            <button class="update" type="submit">Update</button>
                            <button class="cancel" type="button" onclick="document.getElementById('status-form-{{ $order->id }}').style.display='none'">
                                Cancel
                            </button>
                        </div>    
                    </form>
                </td>
                <td class="payment">
                    @if ($order->payed_off)
                        <i class="fa-solid fa-credit-card" style="color: #000;"></i>
                        <i class="fa-solid fa-circle-check" style="color: #4bcc00;"></i>
                    @else
                        <i class="fa-solid fa-hand-holding-dollar" style="color: #000;"></i>
                        <i class="fa-solid fa-clock" style="color: #ea842a;"></i>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>
<script>
    function showStatusForm(id)
    {
        document.getElementById('status-form-' + id).style.display = 'block';
    }
</script>
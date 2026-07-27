<div id="OrderFilters">
    <div class="contain">
    <hr>
        <div class="row">
            <div class="col-12">
                <form method="GET" action="{{ route('profile.history.search') }}">

                    <div class="labelfield">
                        <label>Order Date: <b>YYYY (-MM (-DD) )</b></label>
                        <input type="text" name="orderdate" placeholder="YYYY-MM-DD">
                    </div>

                    <div class="labelfield">
                        <label>Order ID:</label>
                        <input type="text" id="order" name="order" placeholder="Search Order ID">
                    </div>

                    <div class="labelfield">
                        <label>Product ID:</label>
                        <input type="text" id="product" name="product" placeholder="Search Product ID">
                    </div>

                    <div class="labelfield">
                        <label>Price:</label>
                        {{-- <input type="number" id="price" name="price" placeholder="Search Price"> --}}
                        <div id="price-slider"></div>

                        <div class="price-values">
                            <span>€<span id="min-price"></span></span>
                            <span>€<span id="max-price"></span></span>
                        </div>

                        <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                        <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 5000) }}">
                    </div>

                    <div class="labelfield">
                        <label>Usage:</label>
                        <select name="provision" id="provision">
                            <option value="" selected> Any </option>
                            @foreach ($provision as $prov)
                                <option value="{{ $prov->id }}">
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="labelfield">
                        <label>Pickup Location:</label>
                        <input type="text" id="pickup" name="pickup" placeholder="Search Location">
                    </div>

                    <div class="labelfield">
                        <button type="submit" class="Submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    <hr>
    </div>
</div>
<div id="OrderFilters">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <form method="GET" action="{{ route('profile.history.search') }}">
                    <input type="date" id="orderdate" name="orderdate" placeholder="Search Date">

                    <input type="text" id="order" name="order" placeholder="ID">

                    <input type="text" id="product" name="product" placeholder="Search Product">

                    <input type="number" id="price" name="price" placeholder="Search Price">

                    <select name="provision" id="provision">
                        <option value="" selected> Any </option>
                        @foreach ($provision as $prov)
                            <option value="{{ $prov->id }}">
                                {{ $prov->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="text" id="pickup" name="pickup" placeholder="Search Location">

                    <button type="submit" class="Submit">Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>
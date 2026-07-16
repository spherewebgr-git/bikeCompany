<div id="BikeFilters">
    <form method="GET" action="{{ route('bikes.filter') }}">
        <label for="provision">Provision:</label>
        <select name="provision" id="provision" class="form-select">
            <option value="" selected> Any </option>
            @foreach ($provisions as $provision)
                <option value="{{ $provision->name }}">
                    {{ $provision->name }}
                </option>
            @endforeach
        </select>

        <label for="brand">Brand:</label>
        <select name="brand" id="brand" class="form-select">
            <option value="" selected> Any </option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->name }}">
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>

        <label for="type">Type:</label>
        <select name="type" id="type" class="form-select">
            <option value="" selected> Any </option>
            @foreach ($types as $type)
                <option value="{{ $type->name }}">
                    {{ $type->name }}
                </option>
            @endforeach
        </select>

        <label for="gears">Gears:</label>
        <select name="gears" id="gears" class="form-select">
            <option value="" selected> Any </option>
            @foreach ($speeds as $speed)
                <option value="{{ $speed->gears }}">
                    {{ $speed->gears }}
                </option>
            @endforeach
        </select>

        <input type="submit" class="Filter" value="Filter">
    </form>
</div>
<div id="BikeFilters">
    <form method="POST" action="{{ is_null($bike) ? route('bike.create') : route('bike.update', [$bike])}}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <label for="image_path">Image URL:</label><br>
        <input type="text" id="image_path" name="image_path" value="{{ is_null($bike) ? "Bike Image URL" : $bike->image_path }}"><br>

        <label for="colour">Colour:</label><br>
        <input type="text" id="colour" name="colour" value="{{ is_null($bike) ? "Colour" : $bike->colour }}"><br>

        <label for="provision">Provision:</label><br>
        <select name="provision" id="provision" class="form-select">
            @foreach ($provisions as $provision)
                <option value="{{ $provision->id }}" @selected($bike?->provision?->name === $provision->name)>
                    {{ $provision->name }}
                </option>
            @endforeach
        </select><br>

        <label for="brand">Brand:</label><br>
        <select name="brand" id="brand" class="form-select">
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" @selected($bike?->brand?->name === $brand->name)>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select><br>

        <label for="type">Type:</label><br>
        <select name="type" id="type" class="form-select">
            @foreach ($types as $type)
                <option value="{{ $type->id }}" @selected($bike?->type?->name === $type->name)>
                    {{ $type->name }}
                </option>
            @endforeach
        </select><br>

        <label for="speed">Gears:</label><br>
        <select name="speed" id="speed" class="form-select">
            @foreach ($speeds as $speed)
                <option value="{{ $speed->id }}" @selected($bike?->speed?->gears === $speed->gears)>
                    {{ $speed->gears }}
                </option>
            @endforeach
        </select><br>

        <input type="submit" class="Filter" value={{ is_null($bike) ? "Add" : "Update"}}>
    </form>
</div>
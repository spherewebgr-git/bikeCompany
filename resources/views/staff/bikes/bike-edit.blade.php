<x-app-layout>
<div id="BikeEdit">
    <form method="POST" action="{{ is_null($bike) ? route('bike.create') : route('bike.update', [$bike])}}">

        <input type="hidden" name="_token" placeholder="{{ csrf_token() }}" />

        @if (is_null($bike))
            <div class="horizontal">
                <div class="vertical">
                    <label for="SKU">SKU:</label><br>
                    <input type="text" id="SKU" name="SKU" placeholder="SKU">
                </div>
                <div class="vertical">
                    <label for="colour">Colour:</label><br>
                    <input type="text" id="colour" name="colour" placeholder="Colour"><br>
                </div>
            </div>

            <label for="image_path">Image URL:</label><br>
            <input type="text" id="image_path" name="image_path" placeholder="Bike Image URL">
        @else
            <div class="horizontal">
                <div class="vertical">
                    <label for="SKU">SKU:</label><br>
                    <p>{{ $bike->SKU }}</p>
                </div>
                <div class="vertical">
                    <label for="colour">Colour:</label><br>
                    <input type="text" id="colour" name="colour" value="{{ $bike->colour }}"><br>
                </div>
            </div>

            <label for="image_path">Image URL:</label><br>
            <input type="text" id="image_path" name="image_path" value="{{ $bike->image_path }}">
        @endif

        <div class="horizontal">
            <div class="vertical">
                <label for="brand_id">Brand:</label><br>
                <select name="brand_id" id="brand_id" class="form-select">
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" @selected($bike?->brand?->id === $brand->id)>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="vertical">
                <label for="type_id">Type:</label><br>
                <select name="type_id" id="type_id" class="form-select">
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" @selected($bike?->type?->id === $type->id)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="vertical">
                <label for="speed_id">Gears:</label><br>
                <select name="speed_id" id="speed_id" class="form-select">
                    @foreach ($speeds as $speed)
                        <option value="{{ $speed->id }}" @selected($bike?->speed?->id === $speed->id)>
                            {{ $speed->gears }}
                        </option>
                    @endforeach
                </select><br>
            </div>
        </div>

        <div class="horizontal">
            @if (is_null($bike))
                <div class="vertical">
                    <label for="provision_id">Provision:</label><br>
                    <select name="provision_id" id="provision_id" class="form-select">
                        @foreach ($provisions as $provision)
                            <option value="{{ $provision->id }}" @selected($bike?->provision?->id === $provision->id)>
                                {{ $provision->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="vertical" id="quantity">
                    <label for="quantity">Quantity:</label><br>
                    <input type="number" id="quant" name="quant" placeholder="0"><br>
                </div>

                <div class="vertical" id="serial">
                    <label for="serialnum">Serial Number:</label><br>
                    <input type="text" id="serialnum" name="serialnum" placeholder="Serial Number"><br>
                </div>
            @else
                <div class="vertical">
                    <label for="provision_id">Provision:</label><br>
                    <p>{{ $bike->provision->name }}</p><br>
                </div>

                @if ($bike->provision->name === 'rent')
                    <div class="vertical" id="serial">
                        <label for="serialnum">Serial Number:</label><br>
                        <p>{{ $bike->serialnum }}</p><br>
                    </div>                    
                @endif

            @endif
        </div>

        <label for="price">Price(s):</label><br>
        @if (is_null($bike))
            <div id="add-price" class="add-price"></div>
        @else
            @foreach ($prices as $price)
                <input type="text" id="price" name="price[]" value="{{ $price->price }}"><br>
            @endforeach
        @endif

        <input type="submit" class="Submit" value={{ is_null($bike) ? "Add" : "Update"}}>
    </form>
</div>
</x-app-layout>
<script>
    const provision = document.getElementById('provision_id');
    const priceFields = document.getElementById('add-price');

    function addPriceFields()
    {
        priceFields.innerHTML = '';

        const selected = provision.options[provision.selectedIndex];
        const count = selected.textContent.trim().toLowerCase() === 'rent' ? 3 : 1;

        for (let i = 0; i < count; i++)
        {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'price[]'; // [] for array (multiple prices)
            input.className = 'form-control';
            input.placeholder = '0.0'

            priceFields.appendChild(input);
            priceFields.appendChild(document.createElement('br'));
        }

        if (count === 1) //buy
        {
            document.getElementById('quantity').style.display = 'block';
            document.getElementById('serial').style.display = 'none';
        }
        else
        {
            document.getElementById('quantity').style.display = 'none';
            document.getElementById('serial').style.display = 'block';
        }
    }

    provision.addEventListener('change', addPriceFields);
    addPriceFields(); // Render on page load
</script>
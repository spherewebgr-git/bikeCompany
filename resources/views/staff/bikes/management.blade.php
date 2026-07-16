<x-app-layout>
    <div class="container" id="StaffManagement">
        <h1 style="font-size: 30px;">Bike Database</h1>

        <a href="">
            <button class="Create">+ Insert New Bike</button>
        </a>

        <div class="filters">
            <form method="GET" action="{{ route('bikes.filter') }}" style="display: flex;">
                <label for="provision">Provision:</label><br>
                <select name="provision" id="provision" class="form-select">
                    <option value="" {{ request("provision") == "" ? "selected" : "" }}>
                        Any
                    </option>
                    @foreach ($provisions as $provision)
                        <option value="{{ $provision->name }}">
                            {{ $provision->name }}
                        </option>
                    @endforeach
                </select><br>

                <label for="brand">Brand:</label><br>
                <select name="brand" id="brand" class="form-select">
                    <option value="" {{ request("brand") == "" ? "selected" : "" }}>
                        Any
                    </option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->name }}">
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select><br>

                <label for="type">Type:</label><br>
                <select name="type" id="type" class="form-select">
                    <option value="" {{ request("type") == "" ? "selected" : "" }}>
                        Any
                    </option>
                    @foreach ($types as $type)
                        <option value="{{ $type->name }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select><br>

                <label for="gears">Gears:</label><br>
                <select name="gears" id="gears" class="form-select">
                    <option value="" {{ request("speed") == "" ? "selected" : "" }}>
                        Any
                    </option>
                    @foreach ($speeds as $speed)
                        <option value="{{ $speed->gears }}">
                            {{ $speed->gears }}
                        </option>
                    @endforeach
                </select><br>

                <input type="submit" class="Filter" value="Filter">
            </form>
        </div>

        <div class="bike-list">
            @include('staff.bikes.card')
        </div>
    </div>
</x-app-layout>
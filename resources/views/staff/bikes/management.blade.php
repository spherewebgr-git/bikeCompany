<x-app-layout>
    <div class="container" id="StaffManagement">
        <h1 class="management-title">Bike Database</h1>

        <a href="{{ route('bike.edit', ["new"]) }}">
            <button class="Create">+ Insert New Bike</button>
        </a>

        <div class="filter-search">
            <form method="GET" action="{{ route('bikes.search') }}">
                <input type="text" id="SKU" name="SKU" placeholder="Search SKUs">
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            @include('components.bikefilters')
        </div>

        @include('staff.bikes.table')

    </div>
</x-app-layout>

<x-app-layout>
    <div class="container" id="StaffManagement">
        <h1 style="font-size: 30px;">Bike Database</h1>

        <a href="{{ route('bike.edit', ["new"]) }}">
            <button class="Create">+ Insert New Bike</button>
        </a>

        @include('components.bikefilters')
        @include('staff.bikes.table')

    </div>
</x-app-layout>
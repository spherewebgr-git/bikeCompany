<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet/dist/leaflet.css"
/>
<script
    src="https://unpkg.com/leaflet/dist/leaflet.js">
</script>

<x-app-layout>


    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a> /
                <span>Checkout</span>
            </nav>
        </div>
    </div>

    <div class="page-body checkout-page">
        <div class="container">

            <div class="row">

                <div class="col-md-8">

                    <div class="blog-details checkout-card">

                        <div class="section-heading">
                            <h2>Checkout</h2>
                        </div>

                        <form action="{{ route('checkout.store-rental', $bike) }}"
                              method="POST"
                              class="contact-form checkout-form">

                            @csrf

                            <input type="hidden" name="rent_start" id="rent_start">
                            <input type="hidden" name="rent_end" id="rent_end">
                            <input type="hidden" name="price" id="price">
                            <input type="hidden" name="rental_type" id="rental_type_input" value="hour">

                            <div class="checkout-section">
                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-user"></i> Customer Information
                                </h4>
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-id-card"></i> First Name</label>
                                        <input type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-id-card"></i> Last Name</label>
                                        <input type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-envelope"></i> Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-solid fa-phone"></i> Phone</label>
                                        <input type="text" class="form-control" value="{{ $user->phone }}" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="checkout-section">
                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-location-dot"></i> Pick-up Location
                                </h4>

                                <h3>Select Location</h3>

                                <div id="map"></div>

                                <input
                                    type="hidden"
                                    name="location_id"
                                    id="location_id"
                                    required
                                >

                                <p>
                                    Selected:
                                    <span id="selected-location">
                                        None
                                    </span>
                                </p>
                            </div>

                            <div class="checkout-section">
                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-calendar-days"></i> Rental Period
                                </h4>

                                <div class="rental-type-select">
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="hour" checked>
                                        Ώρα ({{ $bike->prices[0]->price ?? '-' }})
                                    </label>
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="day">
                                        Ημέρα ({{ $bike->prices[1]->price ?? '-' }})
                                    </label>
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="week">
                                        Εβδομάδα ({{ $bike->prices[2]->price ?? '-' }})
                                    </label>
                                </div>

                                {{-- HOUR MODE: datetime + διάρκεια σε ώρες --}}
                                <div id="hour-mode" class="rental-mode">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Start</label>
                                            <input type="text" id="rent_start_display" class="form-control"
                                                   data-hour-price="{{ $bike->prices[0]?->price ?? 0 }}"
                                                   data-initial-start="{{ now()->toIso8601String() }}"
                                                   required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Duration (hours)</label>
                                            <input type="number" id="rental_duration" class="form-control"
                                                   min="1" max="72" value="1">
                                        </div>
                                    </div>
                                </div>

                                {{-- DAY/WEEK MODE: FullCalendar drag-select --}}
                                <div id="calendar-mode" class="rental-mode" style="display:none;">
                                    <div class="form-group" id="week-count-group" style="display:none;">
                                        <label for="week_count">Πόσες εβδομάδες</label>
                                        <input type="number" id="week_count" class="form-control" min="1" value="1">
                                    </div>
                                    <div class="form-group" id="day-count-group" style="display:none;">
                                        <label for="day_count">Πόσες ημέρες</label>
                                        <input
                                            type="number"
                                            id="day_count"
                                            class="form-control"
                                            min="1"
                                            value="1"
                                        >
                                    </div>

                                    <div id="calendar"
                                         data-day-price="{{ $bike->prices[1]?->price ?? 0 }}"
                                         data-week-price="{{ $bike->prices[2]?->price ?? 0 }}"
                                         data-availability-url="{{ route('bikes.availability', $bike) }}">
                                    </div>

                                </div>

                                <p id="price-preview">
                                    Σύνολο:
                                    <strong id="price-value">
                                        {{ $price }} €
                                    </strong>
                                </p>

                                @error('rent_start')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('rent_end')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="checkout-actions">
                                <button type="submit" id="submit-btn" class="btn btn-fill btn-lg">Place Order</button>
                            </div>

                        </form>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="sidebar-widget checkout-summary">

                        <div class="checkout-summary__bike">
                            <img src="{{ $bike->image_path }}"
                                 alt="{{ $bike->brand->name }}"
                                 class="checkout-summary__bike-img">
                            <span class="checkout-summary__bike-badge">{{ $bike->brand->name }}</span>
                        </div>

                        <h4 class="widget-title">
                            <i class="fa-solid fa-receipt"></i> Order Summary
                        </h4>
                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-motorcycle"></i> Bike</strong>
                            <span>{{ $bike->brand->name }}</span>
                        </div>
                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-table-list"></i> Type</strong>
                            <span>{{ $bike->type->name }}</span>
                        </div>
                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-palette"></i> Colour</strong>
                            <span>{{ $bike->colour }}</span>
                        </div>
                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-clock"></i> Duration</strong>
                            <span id="duration-summary">-</span>
                        </div>
                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-tag"></i> Total Price</strong>
                            <span id="price-summary">{{ $price }} €</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    @push('scripts')

        <script>

            document.addEventListener('DOMContentLoaded', function () {

                let map = L.map('map')
                    .setView([37.9780,23.7275],15);


                L.tileLayer(
                    'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                    {
                        attribution:'© OpenStreetMap'
                    }
                ).addTo(map);


                let locations = @json($locations);


                locations.forEach(location => {

                    let marker = L.marker([
                        location.latitude,
                        location.longitude
                    ])
                        .addTo(map)
                        .bindPopup(location.name);


                    marker.on('click', function(){

                        document.getElementById('location_id').value = location.id;

                        document.getElementById('selected-location').innerHTML =
                            location.name;

                    });

                });



                // CHECK LOCATION BEFORE SUBMIT
                document.querySelector('form').addEventListener('submit', function(e){

                    let location = document.getElementById('location_id').value;


                    if(!location){

                        e.preventDefault();

                        alert('Please select a delivery location on the map.');

                    }

                });


            });

        </script>

    @endpush

</x-app-layout>


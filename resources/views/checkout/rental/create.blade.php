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
                <a href="{{ route('bikes.rental') }}"> Rental Bikes</a> /
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

                        <form id="checkout-form"
                              action="{{ route('checkout.store-rental', $bike) }}"
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

                                <div class="selected-location-box">
                                    <span class="selected-label">
                                        <i class="fa-solid fa-location-dot"></i>
                                        Selected location:
                                    </span>
                                    <span id="selected-location" class="selected-value empty">
                                        None
                                    </span>
                                </div>

                                <div id="location-error" class="location-error">
                                    Please select a pickup location on the map.
                                </div>
                            </div>

                            <div class="checkout-section">
                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-calendar-days"></i> Rental Period
                                </h4>

                                <div class="rental-type-select">
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="hour" checked>
                                        Hour ({{ $bike->prices[0]->price ?? '-' }} {{$bike->prices[0]->description}})
                                    </label>
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="day">
                                        Day ({{ $bike->prices[1]->price ?? '-' }} {{$bike->prices[1]->description}})
                                    </label>
                                    <label>
                                        <input type="radio" name="rental_type_radio" value="week">
                                        Week ({{ $bike->prices[2]->price ?? '-' }} {{$bike->prices[2]->description}})
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
                                            <input type="number" id="rental_duration" class="form-control" min="1" max="72" value="1">
                                        </div>

                                        {{-- ΝΕΟ: Check availability button --}}
                                        <div class="col-md-12 form-group">
                                            <div class="availability-check">
                                                <div class="check-field">
                                                    <p class="availability-check__hint">
                                                        <i class="fa-regular fa-circle-check"></i>
                                                        Check availability for your selected date &amp; time before placing your order.
                                                    </p>

                                                    <button type="button" id="check-availability-btn" class="btn availability-check__button"
                                                            data-url="{{ route('checkout.check-rental', $bike) }}">
                                                        Check Availability
                                                    </button>
                                                    <div id="availability-check-result" class="availability-check__result"></div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="hour-availability-header">
                                        <span class="hour-availability-label">
                                            <i class="fa-regular fa-clock"></i> Hourly Availability (08:00 - 21:00)
                                        </span>
                                        <span class="hour-legend">
                                            <span class="hour-legend-item"><i class="legend-dot legend-dot-available"></i> Available</span>
                                            <span class="hour-legend-item"><i class="legend-dot legend-dot-blocked"></i> Booked</span>
                                        </span>
                                    </div>
                                    <div id="hour-availability-bar" class="hour-availability-bar"></div>
                                </div>


                                {{-- DAY/WEEK MODE: FullCalendar drag-select --}}
                                <div id="calendar-mode" class="rental-mode" style="display:none;">

                                    <div class="calendar-top-section">
                                        <div class="form-group" id="week-count-group" style="display:none;">
                                            <label for="week_count">Amount of weeks</label>
                                            <input type="number" id="week_count" class="form-control" min="1" value="1">
                                        </div>
                                        <div class="form-group" id="day-count-group" style="display:none;">
                                            <label for="day_count">Amount of days</label>
                                            <input
                                                type="number"
                                                id="day_count"
                                                class="form-control"
                                                min="1"
                                                value="1"
                                            >

                                        </div>

                                        <div class="calendar-legend">
                                        <span class="calendar-legend-item">
                                            <i class="legend-dot legend-dot-available"></i> Available
                                        </span>
                                            <span class="calendar-legend-item">
                                            <i class="legend-dot legend-dot-blocked"></i> Already booked
                                        </span>
                                        </div>
                                    </div>



                                    <div id="calendar"
                                         data-day-price="{{ $bike->prices[1]?->price ?? 0 }}"
                                         data-week-price="{{ $bike->prices[2]?->price ?? 0 }}"
                                         data-availability-url="{{ route('bikes.availability', $bike) }}">
                                    </div>

                                </div>

                                <p id="price-preview">
                                    Total Price:
                                    <strong id="price-value">
                                        {{ $price }}
                                    </strong>
                                </p>

                                @error('rent_start')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @error('rent_end')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="checkout-actions">
                                    <button type="submit" id="submit-btn" class="btn btn-fill btn-lg">Place Order</button>
                                </div>
                            </div>

                        </form>

                    </div>

                </div>

                <div class="order-summary col-md-4">

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

                        let selected = document.getElementById('selected-location');

                        selected.innerHTML = location.name;

                        selected.classList.remove('empty');
                        selected.classList.add('active');


                        document.getElementById('location-error').style.display = 'none';

                    });

                });



                // CHECK LOCATION BEFORE SUBMIT
                document.querySelector('#checkout-form').addEventListener('submit', function(e){

                    let location = document.getElementById('location_id').value;


                    if(!location){

                        e.preventDefault();


                        // εμφανίζει το μήνυμα
                        document.getElementById('location-error').style.display = 'block';


                        // scroll στον χάρτη
                        document.getElementById('map').scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });


                        return false;
                    }

                });


                function formatDisplayDate(mysqlDateStr) {
                    // 'YYYY-MM-DD HH:MM:SS' -> 'DD/MM/YYYY HH:MM'
                    const [datePart, timePart] = mysqlDateStr.split(' ');
                    const [y, m, d] = datePart.split('-');
                    const [hh, mm] = timePart.split(':');
                    return `${d}/${m}/${y} ${hh}:${mm}`;
                }

                // Availability Check Script
                document.getElementById('check-availability-btn').addEventListener('click', function () {

                    const url = this.dataset.url;
                    const resultBox = document.getElementById('availability-check-result');
                    const btn = this;

                    const rentStart = document.getElementById('rent_start').value;
                    const rentEnd = document.getElementById('rent_end').value;

                    btn.classList.remove('btn-available', 'btn-unavailable');

                    if (!rentStart || !rentEnd) {
                        resultBox.innerHTML = '<span class="text-danger">Please select a date and duration first.</span>';
                        return;
                    }

                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Checking...';
                    resultBox.innerHTML = '';

                    const params = new URLSearchParams({ rent_start: rentStart, rent_end: rentEnd });

                    const MIN_DELAY_MS = 600; // ΝΕΟ: ελάχιστη ψεύτικη καθυστέρηση
                    const started = Date.now();

                    fetch(`${url}?${params.toString()}`, {
                        headers: { 'Accept': 'application/json' },
                    })
                        .then(async response => {
                            const data = await response.json();

                            // ΝΕΟ: υπολογίζουμε πόσο χρόνο πρέπει ακόμα να περιμένουμε
                            const elapsed = Date.now() - started;
                            const remaining = Math.max(0, MIN_DELAY_MS - elapsed);

                            await new Promise(resolve => setTimeout(resolve, remaining));

                            if (data.available) {
                                resultBox.innerHTML = '<span class="text-success">✔ Available</span>';
                                btn.classList.add('btn-available');
                            } else {
                                const from = formatDisplayDate(rentStart);
                                const to = formatDisplayDate(rentEnd);
                                resultBox.innerHTML = `<span class="text-danger">✘ Not available from ${from} to ${to}.<br>Please try another time.</span>`;
                                btn.classList.add('btn-unavailable');
                            }
                        })
                        .catch(() => {
                            resultBox.innerHTML = '<span class="text-danger">Something went wrong while checking availability.</span>';
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        });
                });


            });

        </script>

    @endpush

</x-app-layout>


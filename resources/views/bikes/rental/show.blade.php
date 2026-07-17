<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.rental') }}">{{ __('Rental bikes') }}</a> /
                <span class="active">{{ $bike->brand->name }}</span>
            </nav>
        </div>
    </div>

    <section class="bike-single">
        <div class="nav-container">
            <div class="bike-single-grid row">

                <div class="bike-single-image col-sm-6">
                    <img src="{{ $bike->image_path }}" alt="{{ $bike->brand->name }}">
                </div>

                <div class="bike-single-right-section col-sm-6">

                    @include('bikes.partials.bike-info')

                    <div class="bike-single-action">
                        <p class="price">
                            {{ __('Price') }}: {{ $bike->price }} |
                            @foreach($bike->prices as $price)
                                <strong class="price-value">{{$price->price}}</strong> |
                            @endforeach
                        </p>


                        {{--                    {{ route('rentals.store', $bike) }}--}}
                        <form method="POST" action="">
                            @csrf

                            <div class="form-group input-group active">
                                <label for="rental_start">{{ __('Start date & time') }}</label>
                                <input type="datetime-local" id="rental_start" name="rental_start" class="form-control" required>
                            </div>

                            <div class="form-group input-group active">
                                <label for="rental_duration">{{ __('Duration (hours)') }}</label>
                                <input type="number" id="rental_duration" name="rental_duration" min="1" max="72" class="form-control" required>
                            </div>

                            <div class="buttons-section">
                                <button type="submit" class="btn btn-fill btn-md">{{ __('Rent this bike') }}</button>
                                <a href="{{ route('bikes.rental') }}" class="btn btn-trans btn-md">{{ __('Back to all bikes') }}</a>
                            </div>

                        </form>


                    </div>
                </div>


            </div>
        </div>
    </section>

</x-app-layout>

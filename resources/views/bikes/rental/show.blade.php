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
                            @foreach($bike->prices as $price)
                                <strong class="price-value">{{ $price->price }}{{$price->description}}</strong> |
                            @endforeach
                        </p>

                        <div class="buttons-section">
                            @auth
                                <a href="{{ route('checkout.create-rental', $bike) }}" class="btn btn-fill btn-md">{{ __('Rent this bike') }}</a>
                            @else
                                <a href="{{ route('login') }}?redirect={{ urlencode(route('bikes.rental.show', $bike)) }}"
                                   class="btn btn-fill btn-md">
                                    {{ __('Rent this bike') }}
                                </a>
                            @endauth
                            <a href="{{ route('bikes.rental') }}" class="btn btn-trans btn-md">{{ __('Back to all bikes') }}</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>

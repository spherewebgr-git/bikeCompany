<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.sale') }}">{{ __('Bikes for sale') }}</a> /
                <span class="active">{{ $bike->brand->name }}</span>
            </nav>
            <h2>{{ $bike->brand->name }}</h2>
        </div>
    </div>

    <section class="bike-single">
        <div class="nav-container container">

                <div class="bike-single-grid row">

                    <div class="bike-single-image col-sm-6">
                        <img src="{{ $bike->image_path }}" alt="{{ $bike->brand->name }}">
                    </div>

                    <div class="bike-single-right-section col-sm-6">
                        @include('bikes.partials.bike-info')

                        <div class="bike-single-action">
                            <p class="price">
                                {{ __('Price') }}:
                                @foreach($bike->prices as $price)
                                    <strong class="price-value">{{ $price->price }} €</strong>
                                @endforeach
                            </p>
                            <div class="buttons-section">
                                <a href="#" class="btn btn-fill btn-md">{{ __('Buy now') }}</a>
                                <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('Back to all bikes') }}</a>
                            </div>
                        </div>
                    </div>

                </div>

        </div>
    </section>

</x-app-layout>

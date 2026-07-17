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
        <div class="nav-container">
            <div class="bike-single-grid">

                @include('bikes.partials.bike-info')

                <div class="bike-single-action">
                    <p class="price">
                        @foreach($bike->prices as $price)
                            @if($bike->provision->name === 'buy')
                                <strong>{{ $price->price }} €</strong>
                            @else
                                <strong>{{ $price->price }}</strong>
                            @endif
                        @endforeach
                    </p>
                    <a href="#" class="btn btn-fill btn-md">{{ __('Buy now') }}</a>
                    <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('Back to all bikes') }}</a>
                </div>

            </div>
        </div>
    </section>

</x-app-layout>

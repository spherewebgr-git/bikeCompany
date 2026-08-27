<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.rental') }}">{{ __('Rental bikes') }}</a> /
                <span class="active">{{ $bike?->brand?->name ?? 'N/A' }}</span>
            </nav>
        </div>
    </div>

    <section class="bike-single">
        <div class="nav-container">
            <div class="bike-single-grid row">

                <div class="col-md-5 bike-card__image-col">

                    <div class="bikeGalleryWrapper">

                        <div class="swiper bikeGalleryThumbs">
                            <div class="swiper-wrapper">
                                @foreach($bike->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset($image->image) }}" alt="{{ $bike?->brand?->name ?? 'Bike' }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="swiper bikeGallery">
                            <div class="swiper-wrapper">
                                @foreach($bike->images as $image)
                                    <div class="swiper-slide">
                                        <img src="{{ asset($image->image) }}" alt="{{ $bike?->brand?->name ?? 'Bike' }}">
                                    </div>
                                @endforeach
                            </div>

                            <div class="swiper-button-next bike-gallery-next"></div>
                            <div class="swiper-button-prev bike-gallery-prev"></div>
                        </div>

                    </div>

                </div>

                <div class="bike-single-right-section col-sm-6">

                    @include('bikes.partials.bike-info')

                    <div class="bike-single-action">
                        <div class="price-block">
                            <span class="price-label">{{ __('Price') }}:</span>
                            <div class="price-list">
                                @foreach($bike->prices as $price)
                                    <div class="price-item">
                                        <span class="price-item__value">{{ $price->price }}</span>
                                        @if($price->description)
                                            <span class="price-item__desc">{{ $price->description }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

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

            <div
                data-reviews-root
                data-bike-id="{{ $bike->id }}"
                data-authenticated="{{ auth()->check() ? 'true' : 'false' }}"
            ></div>

        </div>
    </section>

    @vite('resources/js/reviews.jsx')

</x-app-layout>

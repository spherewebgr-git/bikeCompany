<article class="mb-60 bike-card">
    @if($bike->discount_percentage > 0)
        <span class="discount-ribbon">-{{ (int) $bike->discount_percentage }}%</span>
    @endif
    <div class="row bike-card__row">

        <div class="col-md-5 bike-card__image-col">
            <figure class="hover-img bike-card__image">


                <img
                    src="{{ asset($bike->images->first()->image) }}"
                    alt="{{ $bike?->brand?->name ?? 'N/A' }}"
                    class="img-responsive bike-card__img"
                >

                @auth
                    @php
                        $isWishlisted = auth()->user()
                            ->wishlistBikes()
                            ->where('bikes.id', $bike->id)
                            ->exists();
                    @endphp

                    <div
                        class="bike-card__wishlist"
                        data-wishlist-root
                        data-bike-id="{{ $bike->id }}"
                        data-wishlisted="{{ $isWishlisted ? 'true' : 'false' }}"
                    ></div>
                @else
                    @php
                        $bikeUrl = $bike->provision->name === 'buy'
                            ? route('bikes.sale.show', $bike)
                            : route('bikes.rental.show', $bike);
                    @endphp

                    <a
                        href="{{ route('login') }}?redirect={{ urlencode($bikeUrl) }}"
                        class="bike-card__wishlist-login"
                        title="{{ __('Log in to use Wishlist') }}"
                        aria-label="{{ __('Log in to use Wishlist') }}"
                    >
                        <i class="fa-regular fa-heart"></i>
                    </a>
                @endauth

                @auth
                    @php
                        $isCompared = auth()->user()
                            ->compareBikes()
                            ->where('bikes.id', $bike->id)
                            ->exists();
                    @endphp

                    <div
                        class="bike-card__compare"
                        data-compare-root
                        data-bike-id="{{ $bike->id }}"
                        data-compared="{{ $isCompared ? 'true' : 'false' }}"
                    ></div>
                @else
                    @php
                        $bikeUrl = $bike->provision->name === 'buy'
                            ? route('bikes.sale.show', $bike)
                            : route('bikes.rental.show', $bike);
                    @endphp

                    <a
                        href="{{ route('login') }}?redirect={{ urlencode($bikeUrl) }}"
                        class="bike-card__compare-login"
                        title="{{ __('Log in to use Compare') }}"
                        aria-label="{{ __('Log in to use Compare') }}"
                    >
                        <i class="fa-solid fa-code-compare"></i>
                    </a>
                @endauth

                <div class="img-hover-content bike-card__overlay">

                    @if($bike->provision->name === 'buy')
                        <a
                            href="{{ route('bikes.sale.show', $bike) }}"
                            class="link-popup bike-card__overlay-link"
                        >
                            <i class="icon-inside fa fa-link bike-card__overlay-icon"></i>
                        </a>
                    @elseif($bike->provision->name === 'rent')
                        <a
                            href="{{ route('bikes.rental.show', $bike) }}"
                            class="link-popup bike-card__overlay-link"
                        >
                            <i class="icon-inside fa fa-link bike-card__overlay-icon"></i>
                        </a>
                    @endif

                </div>

            </figure>
        </div>

        <div class="col-md-7 bike-card__content">



            <header class="article-heading bike-card__header">

                @if($bike->provision->name === 'buy')
                    <h4 class="title-text bike-card__title">
                        <a href="{{ route('bikes.sale.show', $bike) }}" class="bike-card__title-link">
                            {{ $bike?->brand?->name ?? 'N/A' }}
                        </a>
                    </h4>
                @elseif($bike->provision->name === 'rent')
                    <h4 class="title-text bike-card__title">
                        <a href="{{ route('bikes.rental.show', $bike) }}" class="bike-card__title-link">
                            {{ $bike?->brand?->name ?? 'N/A' }}
                        </a>
                    </h4>
                @endif



                <div class="meta-data bike-card__meta">

                    <span class="meta-cat bike-card__type">
                        <i class="fa fa-bicycle"></i>
                        {{ $bike->type->name }}
                    </span>

                    <span class="meta-time bike-card__speed">
                        <i class="fa fa-cog"></i>
                        {{ $bike->speed->gears }} speeds
                    </span>

                </div>

            </header>

            <div class="bike-card__details">

                <p class="bike-card__colour">
                    <strong>Colour:</strong> {{ $bike->colour }}
                </p>

                <p class="bike-card__provision">
                    <strong>Provision:</strong> {{ ucfirst($bike->provision->name) }}
                </p>

                <div class="bike-card__prices">
                    <div class="bike-card__price-tag">

                        <p class="bike-card__price-label">
                            <strong>{{ __('Price') }}:</strong>
                        </p>

                        <div class="bike-card__price-values">
                            @foreach($bike->prices as $price)
                                <span class="bike-card__price-item">


                                    @if($bike->provision->name === 'buy' && $bike->discount_percentage != 0)
                                        <div class="price-change">
                                            <span class="crossed-price">{{ $bike->prices->first()->price ?? '-' }} €</span>
                                            <span class="discounted-price">{{ $bike->getDiscountedPriceAttribute() }} €</span>
                                        </div>
                                    @else
                                        {{ $price->price }}

                                    @endif


                                    @if($price->description)
                                        <small class="bike-card__price-desc">{{ $price->description }}</small>
                                    @endif
                                </span>
                            @endforeach



                        </div>

                        <footer class="bike-card__footer">
                            @if($bike->provision->name === 'buy')
                                <a href="{{route('bikes.sale.show', $bike)}}" class="btn btn-md btn-trans bike-card__button">
                                    Details
                                </a>
                            @elseif($bike->provision->name === 'rent')
                                <a href="{{route('bikes.rental.show', $bike)}}" class="btn btn-md btn-trans bike-card__button">
                                    Details
                                </a>
                            @endif
                        </footer>

                    </div>

                </div>


            </div>



        </div>

    </div>
</article>

@vite('resources/js/wishlist.jsx')
@vite('resources/js/compare.jsx')



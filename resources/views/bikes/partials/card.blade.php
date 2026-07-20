<article class="mb-60 bike-card">
    <div class="row bike-card__row">

        <div class="col-md-5 bike-card__image-col">
            <figure class="hover-img bike-card__image">

                <img src="{{ $bike->image_path }}"
                     alt="{{ $bike->brand->name }}"
                     class="img-responsive bike-card__img">

                <div class="img-hover-content bike-card__overlay">

                    @if($bike->provision->name === 'buy')
                        <a href="{{ route('bikes.sale.show', $bike) }}" class="link-popup bike-card__overlay-link">
                            <i class="icon-inside fa fa-link bike-card__overlay-icon"></i>
                        </a>
                    @elseif($bike->provision->name === 'rent')
                        <a href="{{ route('bikes.rental.show', $bike) }}" class="link-popup bike-card__overlay-link">
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
                            {{ $bike->brand->name }}
                        </a>
                    </h4>
                @elseif($bike->provision->name === 'rent')
                    <h4 class="title-text bike-card__title">
                        <a href="{{ route('bikes.rental.show', $bike) }}" class="bike-card__title-link">
                            {{ $bike->brand->name }}
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
                                    {{ $price->price }}{{ $bike->provision->name === 'buy' ? ' €' : '' }}
                                </span>
                            @endforeach
                        </div>

                    </div>
                </div>

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
</article>

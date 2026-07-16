<article class="mb-60 bike-card">
    <div class="row bike-card__row">

        <div class="col-md-5 bike-card__image-col">
            <figure class="hover-img bike-card__image">

                <img src="{{ $bike->image_path }}"
                     alt="{{ $bike->brand->name }}"
                     class="img-responsive bike-card__img">

                <div class="img-hover-content bike-card__overlay">
                    <a href="" class="link-popup bike-card__overlay-link">
                        <i class="icon-inside fa fa-link bike-card__overlay-icon"></i>
                    </a>
                </div>

            </figure>
        </div>

        <div class="col-md-7 bike-card__content">

            <header class="article-heading bike-card__header">

                <h4 class="title-text bike-card__title">
                    <a href="" class="bike-card__title-link">
                        {{ $bike->brand->name }}
                    </a>
                </h4>

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
                            <strong>Price:</strong>
                        </p>

                        <div class="bike-card__price-values">
                            @foreach($bike->prices as $price)
                                @if($bike->provision->name === 'buy')
                                    <strong>{{ $price->price }} €</strong>
                                @else
                                    <strong>{{ $price->price }}</strong>
                                @endif
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

            <footer class="bike-card__footer">
                <a href="" class="btn btn-md btn-trans bike-card__button">
                    Details
                </a>
            </footer>

        </div>

    </div>
</article>

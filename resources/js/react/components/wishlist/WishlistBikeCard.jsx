export default function WishlistBikeCard({
                                             bike,
                                             isRemoving,
                                             onRemove,
                                         }) {
    return (
        <article className="mb-60 bike-card wishlist-bike-card">

            <div className="row bike-card__row">

                <div className="col-md-5 bike-card__image-col">

                    <figure className="bike-card__image">

                        {bike.image ? (
                            <img
                                src={bike.image}
                                alt={`${bike.brand ?? ''} ${bike.type ?? 'Bike'}`}
                                className="img-responsive bike-card__img"
                            />
                        ) : (
                            <div className="wishlist-bike-card__placeholder">
                                <i className="fa-solid fa-bicycle" />
                            </div>
                        )}

                        <button
                            type="button"
                            className="wishlist-bike-card__remove"
                            onClick={() => onRemove(bike.id)}
                            disabled={isRemoving}
                            title="Remove from Wishlist"
                            aria-label="Remove from Wishlist"
                        >
                            <i
                                className={
                                    isRemoving
                                        ? 'fa-solid fa-spinner fa-spin'
                                        : 'fa-solid fa-heart'
                                }
                                aria-hidden="true"
                            />
                        </button>

                    </figure>

                </div>

                <div className="col-md-7 bike-card__content">

                    <header className="article-heading bike-card__header">

                        <h4 className="title-text bike-card__title">
                            <a
                                href={bike.show_url}
                                className="bike-card__title-link"
                            >
                                {bike.brand ?? 'N/A'}
                            </a>
                        </h4>

                        <div className="meta-data bike-card__meta">

                            <span className="meta-cat bike-card__type">
                                <i className="fa fa-bicycle" />
                                {bike.type ?? 'N/A'}
                            </span>

                            <span className="meta-time bike-card__speed">
                                <i className="fa fa-cog" />
                                {bike.gears ?? '-'} speeds
                            </span>

                        </div>

                    </header>

                    <div className="bike-card__details">

                        <p className="bike-card__colour">
                            <strong>Colour:</strong>
                            {bike.colour ?? 'N/A'}
                        </p>

                        <p className="bike-card__provision">
                            <strong>Provision:</strong>
                            {bike.provision ?? 'N/A'}
                        </p>

                        <div className="bike-card__prices">

                            <div className="bike-card__price-tag">

                                <p className="bike-card__price-label">
                                    <strong>Price:</strong>
                                </p>

                                <div className="bike-card__price-values">

                                    {bike.prices?.length > 0 ? (
                                        bike.prices.map(function (price) {
                                            return (
                                                <span
                                                    className="bike-card__price-item"
                                                    key={price.id}
                                                >
                                                    {price.price} €

                                                    {price.description && (
                                                        <small className="bike-card__price-desc">
                                                            {price.description}
                                                        </small>
                                                    )}
                                                </span>
                                            );
                                        })
                                    ) : (
                                        <span className="bike-card__price-item">
                                            Price not available
                                        </span>
                                    )}

                                </div>

                                <footer className="bike-card__footer">

                                    <a
                                        href={bike.show_url}
                                        className="btn btn-md btn-trans bike-card__button"
                                    >
                                        Details
                                    </a>

                                </footer>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </article>
    );
}

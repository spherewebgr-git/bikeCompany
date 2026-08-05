export default function WishlistBikeCard({
                                             bike,
                                             isRemoving,
                                             onRemove,
                                         }) {
    return (
        <article className="wishlist-bike-card">

            <a
                href={bike.show_url}
                className="wishlist-bike-card__image"
            >
                {bike.image ? (
                    <img
                        src={`${bike.image}`}
                        alt={
                            `${bike.brand ?? ''} ${bike.type ?? 'Bike'}`
                        }
                    />
                ) : (
                    <div className="wishlist-bike-card__placeholder">
                        <i className="fa-solid fa-bicycle" />
                    </div>
                )}
            </a>

            <div className="wishlist-bike-card__content">

                <div className="wishlist-bike-card__heading">

                    <div>
                        <h3>
                            {bike.brand ?? 'Bike'}
                        </h3>

                        <p>
                            {bike.type ?? 'Type not available'}
                        </p>
                    </div>

                    <button
                        type="button"
                        className="wishlist-bike-card__remove"
                        onClick={() => onRemove(bike.id)}
                        disabled={isRemoving}
                        aria-label="Remove bike from wishlist"
                        title="Remove from wishlist"
                    >
                        <i className="fa-solid fa-heart" />
                    </button>

                </div>

                <ul className="wishlist-bike-card__details">

                    <li>
                        <strong>SKU:</strong>
                        <span>{bike.sku ?? '-'}</span>
                    </li>

                    <li>
                        <strong>Colour:</strong>
                        <span>{bike.colour ?? '-'}</span>
                    </li>

                    <li>
                        <strong>Gears:</strong>
                        <span>{bike.gears ?? '-'}</span>
                    </li>

                    <li>
                        <strong>Availability:</strong>

                        <span>
                            {bike.quantity > 0
                                ? `${bike.quantity} in stock`
                                : 'Out of stock'}
                        </span>
                    </li>

                </ul>

                <div className="wishlist-bike-card__footer">

                    <strong className="wishlist-bike-card__price">
                        {bike.price
                            ? `${bike.price} €`
                            : 'Price not available'}
                    </strong>

                    <a
                        href={bike.show_url}
                        className="btn btn-fill btn-md"
                    >
                        View Bike
                    </a>

                </div>

            </div>

        </article>
    );
}

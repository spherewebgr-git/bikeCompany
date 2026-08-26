import {useEffect, useState} from 'react';

import {
    addToWishlist,
    removeFromWishlist,
} from '../../services/wishlistService';

export default function WishlistButton({
                                           bikeId,
                                           initiallyWishlisted,
                                       }) {
    const [isWishlisted, setIsWishlisted] = useState(
        initiallyWishlisted
    );

    const [isLoading, setIsLoading] = useState(false);

    const [message, setMessage] = useState('');

    async function handleClick() {
        if (isLoading) {
            return;
        }

        setIsLoading(true);

        try {
            const data = isWishlisted
                ? await removeFromWishlist(bikeId)
                : await addToWishlist(bikeId);

            setIsWishlisted(data.wishlisted);

            if (data.wishlisted) {
                setMessage(
                    'Bike added to wishlist'
                );
            } else {
                setMessage(
                    'Bike removed from wishlist'
                );
            }

        } catch (error) {
            console.error(
                error.message || 'Wishlist request failed.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        if (!message) {
            return;
        }

        const timer = setTimeout(() => {
            setMessage('');
        }, 3000);

        return () => {
            clearTimeout(timer);
        };
    }, [message]);

    const buttonLabel = isWishlisted
        ? 'Remove from Wishlist'
        : 'Add to Wishlist';

    return (
        <div className="wishlist-button-wrapper">
        <button
            type="button"
            onClick={handleClick}
            disabled={isLoading}
            className={
                isWishlisted
                    ? 'wishlist-button active'
                    : 'wishlist-button'
            }
            title={buttonLabel}
            aria-label={buttonLabel}
        >
            <i
                className={
                    isLoading
                        ? 'fa-solid fa-spinner fa-spin'
                        : isWishlisted
                            ? 'fa-solid fa-heart'
                            : 'fa-regular fa-heart'
                }
                aria-hidden="true"
            />
        </button>

            {message && (
                <div className="wishlist-message">

                    <span>
                        {message}
                    </span>

                    {isWishlisted && (
                        <a
                            href="/profile/wishlist"
                            className="wishlist-message__link"
                        >
                            View wishlist
                        </a>
                    )}

                </div>
            )}

        </div>
    );
}

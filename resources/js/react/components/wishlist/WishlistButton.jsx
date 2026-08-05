import { useState } from 'react';

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
        } catch (error) {
            console.error(
                error.message || 'Wishlist request failed.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    const buttonLabel = isWishlisted
        ? 'Remove from Wishlist'
        : 'Add to Wishlist';

    return (
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
    );
}

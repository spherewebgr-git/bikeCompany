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

    const [message, setMessage] = useState('');

    async function handleClick() {
        if (isLoading) {
            return;
        }

        setIsLoading(true);
        setMessage('');

        try {
            let data;

            if (isWishlisted) {
                data = await removeFromWishlist(bikeId);
            } else {
                data = await addToWishlist(bikeId);
            }

            setIsWishlisted(data.wishlisted);
            setMessage(data.message);
        } catch (error) {
            setMessage(
                error.message || 'Something went wrong.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    return (
        <div className="wishlist-button-container">

            <button
                type="button"
                onClick={handleClick}
                disabled={isLoading}
                className={
                    isWishlisted
                        ? 'wishlist-button active'
                        : 'wishlist-button'
                }
            >
                <i
                    className={
                        isWishlisted
                            ? 'fa-solid fa-heart'
                            : 'fa-regular fa-heart'
                    }
                />

                <span>
                    {isLoading
                        ? 'Please wait...'
                        : isWishlisted
                            ? 'Remove from Wishlist'
                            : 'Add to Wishlist'}
                </span>
            </button>

            {message && (
                <p className="wishlist-message">
                    {message}
                </p>
            )}

        </div>
    );
}

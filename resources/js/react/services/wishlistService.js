import { getCsrfToken } from '../helpers/csrf';

async function wishlistRequest(bikeId, method) {
    const response = await fetch(
        `/profile/wishlist/${bikeId}`,
        {
            method: method,
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw new Error(
            data.message || 'Wishlist request failed.'
        );
    }

    return data;
}

export function addToWishlist(bikeId) {
    return wishlistRequest(bikeId, 'POST');
}

export function removeFromWishlist(bikeId) {
    return wishlistRequest(bikeId, 'DELETE');
}

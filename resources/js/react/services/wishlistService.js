import { getCsrfToken } from '../helpers/csrf';

async function parseJsonResponse(response) {
    const contentType = response.headers.get('content-type');

    if (!contentType?.includes('application/json')) {
        throw new Error(
            'The server returned an invalid response.'
        );
    }

    const data = await response.json();

    if (!response.ok) {
        throw new Error(
            data.message || 'Wishlist request failed.'
        );
    }

    return data;
}

async function wishlistRequest(bikeId, method) {
    const response = await fetch(
        `/profile/wishlist/${bikeId}`,
        {
            method,
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        }
    );

    return parseJsonResponse(response);
}

export async function getWishlistItems(itemsUrl) {
    const response = await fetch(itemsUrl, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    return parseJsonResponse(response);
}

export function addToWishlist(bikeId) {
    return wishlistRequest(bikeId, 'POST');
}

export function removeFromWishlist(bikeId) {
    return wishlistRequest(bikeId, 'DELETE');
}

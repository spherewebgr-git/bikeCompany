import { apiRequest } from '../helpers/api';

export function getWishlistItems(itemsUrl) {
    return apiRequest(itemsUrl);
}

export function addToWishlist(bikeId) {
    return apiRequest(
        `/profile/wishlist/${bikeId}`,
        'POST'
    );
}

export function removeFromWishlist(bikeId) {
    return apiRequest(
        `/profile/wishlist/${bikeId}`,
        'DELETE'
    );
}

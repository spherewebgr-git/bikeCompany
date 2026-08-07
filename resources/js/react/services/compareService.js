import { apiRequest } from '../helpers/api';

export function getCompareItems(itemsUrl) {
    return apiRequest(itemsUrl);
}

export function addToCompare(bikeId) {
    return apiRequest(
        `/profile/compare/${bikeId}`,
        'POST'
    );
}

export function removeFromCompare(bikeId) {
    return apiRequest(
        `/profile/compare/${bikeId}`,
        'DELETE'
    );
}

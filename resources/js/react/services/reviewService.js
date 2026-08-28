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
            data.message || 'Review request failed.'
        );
    }

    return data;
}


export async function getReviews(bikeId) {
    const response = await fetch(
        `/bikes/${bikeId}/reviews`,
        {
            method: 'GET',

            credentials: 'same-origin',

            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        }
    );

    return parseJsonResponse(response);
}


export async function createReview(
    bikeId,
    rating,
    comment
) {
    const response = await fetch(
        `/bikes/${bikeId}/reviews`,
        {
            method: 'POST',

            credentials: 'same-origin',

            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },

            body: JSON.stringify({
                rating,
                comment,
            }),
        }
    );

    return parseJsonResponse(response);
}


export async function updateReview(
    reviewId,
    rating,
    comment
) {
    const response = await fetch(
        `/reviews/${reviewId}`,
        {
            method: 'PATCH',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            body: JSON.stringify({
                rating,
                comment,
            }),
        }
    );

    const data = await response.json();

    if (!response.ok) {
        throw new Error(
            data.message ||
            'Review could not be updated.'
        );
    }

    return data;
}


export async function deleteReview(reviewId) {
    const response = await fetch(
        `/reviews/${reviewId}`,
        {
            method: 'DELETE',

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

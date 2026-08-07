import { getCsrfToken } from './csrf';

export async function apiRequest(url, method = 'GET') {
    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    if (method !== 'GET') {
        headers['X-CSRF-TOKEN'] = getCsrfToken();
    }

    const response = await fetch(url, {
        method,
        credentials: 'same-origin',
        headers,
    });

    const contentType = response.headers.get('content-type');

    if (!contentType?.includes('application/json')) {
        throw new Error('The server returned an invalid response.');
    }

    const data = await response.json();

    if (!response.ok) {
        throw new Error(
            data.message || 'Request failed.'
        );
    }

    return data;
}

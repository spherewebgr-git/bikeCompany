export function getCsrfToken() {
    const csrfMeta = document.querySelector(
        'meta[name="csrf-token"]'
    );

    if (!csrfMeta) {
        throw new Error('CSRF token was not found.');
    }

    return csrfMeta.getAttribute('content');
}

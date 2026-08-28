import { useEffect, useState } from 'react';
import { getCsrfToken } from '../helpers/csrf';


export default function ManageReviews() {
    const [reviews, setReviews] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        loadReviews();
    }, []);

    async function loadReviews() {
        try {
            setIsLoading(true);

            const response = await fetch(
                '/dashboard/management/reviews/items',
                {
                    credentials: 'same-origin',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                }
            );

            if (!response.ok) {
                throw new Error('Could not load reviews.');
            }

            const data = await response.json();

            setReviews(data.reviews ?? []);
        } catch (error) {
            setError(error.message);
        } finally {
            setIsLoading(false);
        }
    }

    async function handleDelete(reviewId) {
        const confirmed = window.confirm(
            'Are you sure you want to delete this review?'
        );

        if (!confirmed) {
            return;
        }

        try {
            const response = await fetch(
                `/dashboard/management/reviews/${reviewId}`,
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

            if (!response.ok) {
                throw new Error('Could not delete review.');
            }

            setReviews(currentReviews =>
                currentReviews.filter(
                    review => review.id !== reviewId
                )
            );
        } catch (error) {
            setError(error.message);
        }
    }

    if (isLoading) {
        return <p>Loading reviews...</p>;
    }

    return (
        <div className="manage-reviews">
            <h1>Manage Reviews</h1>

            {error && (
                <div className="manage-reviews__error">
                    {error}
                </div>
            )}

            {reviews.length === 0 ? (
                <p>No reviews found.</p>
            ) : (
                <table className="manage-reviews__table">
                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Bike</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    {reviews.map(review => (
                        <tr key={review.id}>
                            <td>
                                {review.customer?.first_name}{' '}
                                {review.customer?.last_name}
                            </td>

                            <td>
                                {review.customer?.email}
                            </td>

                            <td>
                                {review.bike?.brand}{' '}
                                {review.bike?.type}
                                <br />
                                <small>
                                    {review.bike?.sku}
                                </small>
                            </td>

                            <td>
                                {'★'.repeat(review.rating)}
                                {'☆'.repeat(5 - review.rating)}
                            </td>

                            <td>
                                {review.comment || '-'}
                            </td>

                            <td>
                                {review.created_at}
                            </td>

                            <td>
                                <button
                                    type="button"
                                    onClick={() =>
                                        handleDelete(review.id)
                                    }
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            )}
        </div>
    );
}

import { useEffect, useMemo, useState } from 'react';
import { getCsrfToken } from '../helpers/csrf';

export default function ManageReviews() {
    const [reviews, setReviews] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState('');

    const [searchTerm, setSearchTerm] = useState('');
    const [ratingFilter, setRatingFilter] = useState('');

    useEffect(() => {
        loadReviews();
    }, []);

    async function loadReviews() {
        try {
            setIsLoading(true);
            setError('');

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

    async function deleteReview(reviewId) {
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
                currentReviews.filter(review => review.id !== reviewId)
            );

            if (window.swal) {
                window.swal(
                    'Deleted!',
                    'The review has been deleted.',
                    'success'
                );
            }
        } catch (error) {
            setError(error.message);

            if (window.swal) {
                window.swal(
                    'Error',
                    error.message,
                    'error'
                );
            }
        }
    }

    function handleDelete(reviewId) {
        if (window.swal) {
            window.swal({
                title: 'Are you sure?',
                text: 'This review will be permanently deleted.',
                icon: 'warning',
                buttons: ['Cancel', 'Delete'],
                dangerMode: true,
            }).then(confirmed => {
                if (confirmed) {
                    deleteReview(reviewId);
                }
            });

            return;
        }

        const confirmed = window.confirm(
            'Are you sure you want to delete this review?'
        );

        if (confirmed) {
            deleteReview(reviewId);
        }
    }

    const filteredReviews = useMemo(() => {
        const search = searchTerm.toLowerCase().trim();

        return reviews.filter(review => {
            const customerName = `${review.customer?.first_name ?? ''} ${review.customer?.last_name ?? ''}`
                .toLowerCase();

            const customerEmail =
                review.customer?.email?.toLowerCase() ?? '';

            const bike =
                `${review.bike?.brand ?? ''} ${review.bike?.type ?? ''} ${review.bike?.sku ?? ''}`
                    .toLowerCase();

            const comment =
                review.comment?.toLowerCase() ?? '';

            const matchesSearch =
                !search ||
                customerName.includes(search) ||
                customerEmail.includes(search) ||
                bike.includes(search) ||
                comment.includes(search);

            const matchesRating =
                !ratingFilter ||
                Number(review.rating) === Number(ratingFilter);

            return matchesSearch && matchesRating;
        });
    }, [reviews, searchTerm, ratingFilter]);

    if (isLoading) {
        return (
            <div className="manage-reviews">
                <p>Loading reviews...</p>
            </div>
        );
    }

    return (
        <div className="manage-reviews">

            <div className="manage-reviews__header">
                <div>
                    <h2>Manage Reviews</h2>
                    <p>
                        View and manage customer reviews.
                    </p>
                </div>

                <div className="manage-reviews__count">
                    {filteredReviews.length} Reviews
                </div>
            </div>

            {error && (
                <div className="manage-reviews__error">
                    {error}
                </div>
            )}

            <div className="manage-reviews__filters">

                <div className="manage-reviews__search">
                    <input
                        type="text"
                        placeholder="Search customer, email, bike or comment..."
                        value={searchTerm}
                        onChange={event =>
                            setSearchTerm(event.target.value)
                        }
                    />
                </div>

                <div className="manage-reviews__rating-filter">
                    <select
                        value={ratingFilter}
                        onChange={event =>
                            setRatingFilter(event.target.value)
                        }
                        className="browser-default"
                    >
                        <option value="">
                            All ratings
                        </option>

                        <option value="5">
                            5 Stars
                        </option>

                        <option value="4">
                            4 Stars
                        </option>

                        <option value="3">
                            3 Stars
                        </option>

                        <option value="2">
                            2 Stars
                        </option>

                        <option value="1">
                            1 Star
                        </option>
                    </select>
                </div>

                {(searchTerm || ratingFilter) && (
                    <button
                        type="button"
                        className="manage-reviews__clear"
                        onClick={() => {
                            setSearchTerm('');
                            setRatingFilter('');
                        }}
                    >
                        Clear
                    </button>
                )}

            </div>

            {filteredReviews.length === 0 ? (
                <div className="manage-reviews__empty">
                    No reviews found.
                </div>
            ) : (
                <div className="manage-reviews__table-wrapper">

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
                        {filteredReviews.map(review => (
                            <tr key={review.id}>

                                <td>
                                    <div className="customer-name">
                                        {review.customer?.first_name}{' '}
                                        {review.customer?.last_name}
                                    </div>
                                </td>

                                <td>
                                    {review.customer?.email}
                                </td>

                                <td>
                                    <div className="bike-name">
                                        {review.bike?.brand}{' '}
                                        {review.bike?.type}
                                    </div>

                                    <span className="bike-sku">
                                        {review.bike?.sku}
                                    </span>
                                </td>

                                <td>
                                    <div className="review-stars">
                                        {'★'.repeat(review.rating)}
                                        <span>
                                            {'☆'.repeat(5 - review.rating)}
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div className="review-comment">
                                        {review.comment || '-'}
                                    </div>
                                </td>

                                <td>
                                    {review.created_at}
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        className="review-delete-btn"
                                        onClick={() =>
                                            handleDelete(review.id)
                                        }
                                    >
                                        <i className="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </td>

                            </tr>
                        ))}
                        </tbody>

                    </table>

                </div>
            )}

        </div>
    );
}

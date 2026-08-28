import { useEffect, useState } from 'react';

import ReviewForm from './ReviewForm';
import StarRating from './StarRating';

import {
    getReviews,
    createReview,
    updateReview,
    deleteReview,
} from '../../services/reviewService';

export default function BikeReviews({
                                        bikeId,
                                        isAuthenticated,
                                    }) {
    const [reviews, setReviews] = useState([]);
    const [averageRating, setAverageRating] = useState(0);
    const [reviewCount, setReviewCount] = useState(0);

    const [isLoading, setIsLoading] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);

    const [errorMessage, setErrorMessage] = useState('');

    // Edit state
    const [editingReviewId, setEditingReviewId] = useState(null);
    const [editRating, setEditRating] = useState(0);
    const [editComment, setEditComment] = useState('');
    const [isUpdating, setIsUpdating] = useState(false);

    useEffect(() => {
        loadReviews();
    }, [bikeId]);

    async function loadReviews() {
        try {
            setIsLoading(true);
            setErrorMessage('');

            const data = await getReviews(bikeId);

            setReviews(data.reviews ?? []);
            setAverageRating(data.average_rating ?? 0);
            setReviewCount(data.count ?? 0);

        } catch (error) {
            setErrorMessage(
                error.message ||
                'Reviews could not be loaded.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    async function handleCreateReview({
                                          rating,
                                          comment,
                                      }) {
        try {
            setIsSubmitting(true);
            setErrorMessage('');

            await createReview(
                bikeId,
                rating,
                comment
            );

            await loadReviews();

        } catch (error) {
            setErrorMessage(
                error.message ||
                'Review could not be submitted.'
            );

            throw error;
        } finally {
            setIsSubmitting(false);
        }
    }

    function handleStartEdit(review) {
        setEditingReviewId(review.id);
        setEditRating(review.rating);
        setEditComment(review.comment ?? '');
        setErrorMessage('');
    }

    function handleCancelEdit() {
        setEditingReviewId(null);
        setEditRating(0);
        setEditComment('');
    }

    async function handleUpdateReview(reviewId) {
        if (!editRating) {
            setErrorMessage(
                'Please select a rating.'
            );

            return;
        }

        try {
            setIsUpdating(true);
            setErrorMessage('');

            await updateReview(
                reviewId,
                editRating,
                editComment
            );

            handleCancelEdit();

            await loadReviews();

        } catch (error) {
            setErrorMessage(
                error.message ||
                'Review could not be updated.'
            );
        } finally {
            setIsUpdating(false);
        }
    }

    async function handleDeleteReview(reviewId) {
        const confirmed = window.confirm(
            'Are you sure you want to delete this review?'
        );

        if (!confirmed) {
            return;
        }

        try {
            setErrorMessage('');

            await deleteReview(reviewId);

            await loadReviews();

        } catch (error) {
            setErrorMessage(
                error.message ||
                'Review could not be deleted.'
            );
        }
    }

    if (isLoading) {
        return (
            <div className="bike-reviews__loading">
                <i className="fa-solid fa-spinner fa-spin" />
                <span>Loading reviews...</span>
            </div>
        );
    }

    return (
        <section className="bike-reviews">

            <header className="bike-reviews__header">

                <div>
                    <h2>Customer Reviews</h2>

                    <div className="bike-reviews__summary">

                        <StarRating
                            value={Math.round(averageRating)}
                            readOnly
                        />

                        <span>
                            {averageRating} / 5
                        </span>

                        <span>
                            ({reviewCount} reviews)
                        </span>

                    </div>
                </div>

            </header>

            {errorMessage && (
                <div className="alert alert-danger">
                    {errorMessage}
                </div>
            )}

            {isAuthenticated ? (
                <ReviewForm
                    onSubmit={handleCreateReview}
                    isLoading={isSubmitting}
                />
            ) : (
                <div className="bike-reviews__login-message">

                    <p>
                        You need to be logged in to write a review.
                    </p>

                    <a
                        href="/login"
                        className="btn btn-fill btn-md"
                    >
                        Log in
                    </a>

                </div>
            )}

            <div className="bike-reviews__list">

                {reviews.length === 0 ? (
                    <div className="bike-reviews__empty">

                        <p>
                            No reviews yet. Be the first to review this bike.
                        </p>

                    </div>
                ) : (
                    reviews.map(function (review) {

                        const isEditing =
                            editingReviewId === review.id;

                        return (
                            <article
                                key={review.id}
                                className="review-item"
                            >

                                {isEditing ? (

                                    <div className="review-item__edit">

                                        <h4>
                                            Edit your review
                                        </h4>

                                        <div className="review-item__edit-rating">

                                            <label>
                                                Your rating
                                            </label>

                                            <StarRating
                                                value={editRating}
                                                onChange={setEditRating}
                                            />

                                        </div>

                                        <div className="review-item__edit-comment">

                                            <label htmlFor={`edit-comment-${review.id}`}>
                                                Comment
                                            </label>

                                            <textarea
                                                id={`edit-comment-${review.id}`}
                                                value={editComment}
                                                onChange={event =>
                                                    setEditComment(
                                                        event.target.value
                                                    )
                                                }
                                                rows="4"
                                                placeholder="Tell us about your experience..."
                                            />

                                        </div>

                                        <div className="review-item__edit-actions">

                                            <button
                                                type="button"
                                                className="btn btn-fill btn-sm"
                                                disabled={isUpdating}
                                                onClick={() =>
                                                    handleUpdateReview(
                                                        review.id
                                                    )
                                                }
                                            >
                                                {isUpdating
                                                    ? 'Saving...'
                                                    : 'Save'
                                                }
                                            </button>

                                            <button
                                                type="button"
                                                className="btn btn-trans btn-sm"
                                                disabled={isUpdating}
                                                onClick={handleCancelEdit}
                                            >
                                                Cancel
                                            </button>

                                        </div>

                                    </div>

                                ) : (

                                    <>
                                        <div className="review-item__header">

                                            <div>
                                                <strong>
                                                    {review.user?.first_name}{' '}
                                                    {review.user?.last_name}
                                                </strong>

                                                <span>
                                                    {review.created_at}
                                                </span>
                                            </div>

                                            <StarRating
                                                value={review.rating}
                                                readOnly
                                            />

                                        </div>

                                        {review.comment && (
                                            <p className="review-item__comment">
                                                {review.comment}
                                            </p>
                                        )}

                                        {review.is_owner && (
                                            <div className="review-item__actions">

                                                <button
                                                    type="button"
                                                    className="btn btn-trans btn-sm"
                                                    onClick={() =>
                                                        handleStartEdit(
                                                            review
                                                        )
                                                    }
                                                >
                                                    Edit
                                                </button>

                                                <button
                                                    type="button"
                                                    className="btn btn-trans btn-sm"
                                                    onClick={() =>
                                                        handleDeleteReview(
                                                            review.id
                                                        )
                                                    }
                                                >
                                                    Delete
                                                </button>

                                            </div>
                                        )}
                                    </>

                                )}

                            </article>
                        );
                    })
                )}

            </div>

        </section>
    );
}

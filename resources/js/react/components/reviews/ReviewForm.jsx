import { useState } from 'react';

import StarRating from './StarRating';

export default function ReviewForm({
    onSubmit,
    isLoading = false,
                                   }) {
    const [rating, setRating] = useState(0);
    const [comment, setComment] = useState('');

    async function handleSubmit(event) {
        event.preventDefault();

        if (rating === 0) {
            return;
        }

        await onSubmit({
            rating,
            comment,
        });

        setRating(0);
        setComment('');
    }

    return (
        <form
            className="review-form"
            onSubmit={handleSubmit}
        >
            <h3>Write a review</h3>

            <div className="review-form__rating">
                <span>Your rating</span>

                <StarRating
                    value={rating}
                    onChange={setRating}
                />
            </div>

            <div className="review-form__comment">
                <label htmlFor="review-comment">
                    Comment
                </label>

                <textarea
                    id="review-comment"
                    value={comment}
                    onChange={(event) =>
                        setComment(event.target.value)
                    }
                    placeholder="Tell us about your experience with this bike..."
                    rows="5"
                    maxLength="1000"
                />
            </div>

            <button
                type="submit"
                className="btn btn-fill btn-md"
                disabled={isLoading || rating === 0}
            >
                {isLoading
                    ? 'Submitting...'
                    : 'Submit Review'
                }
            </button>
        </form>
    );
}

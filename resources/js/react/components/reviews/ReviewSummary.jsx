import { useEffect, useState } from 'react';

import StarRating from './StarRating';
import { getReviews } from '../../services/reviewService';

export default function ReviewSummary({ bikeId }) {

    const [averageRating, setAverageRating] = useState(0);
    const [reviewCount, setReviewCount] = useState(0);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {

        async function loadReviewSummary() {
            try {
                const data = await getReviews(bikeId);

                setAverageRating(
                    Number(data.average_rating) || 0
                );

                setReviewCount(
                    Number(data.count) || 0
                );

            } catch (error) {
                console.error(
                    'Review summary error:',
                    error
                );
            } finally {
                setIsLoading(false);
            }
        }

        loadReviewSummary();

    }, [bikeId]);

    if (isLoading) {
        return null;
    }

    return (
        <div className="review-summary">

            <StarRating
                value={Math.round(averageRating)}
                readOnly
            />

            <span className="review-summary__average">
                {averageRating} / 5
            </span>

            <span className="review-summary__count">
                ({reviewCount} reviews)
            </span>

        </div>
    );
}

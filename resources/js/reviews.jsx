import React from 'react';
import { createRoot } from 'react-dom/client';

import BikeReviews from './react/components/reviews/BikeReviews';
import ReviewSummary from './react/components/reviews/ReviewSummary';
import ManageReviews from './react/pages/ManageReviews';


const reviewsRoot = document.getElementById('reviews-react-root');

if (reviewsRoot) {
    createRoot(reviewsRoot).render(
        <ManageReviews />
    );
}

// Full reviews section
document
    .querySelectorAll('[data-reviews-root]')
    .forEach(function (element) {

        const bikeId = Number(
            element.dataset.bikeId
        );

        const isAuthenticated =
            element.dataset.authenticated === 'true';

        createRoot(element).render(
            <BikeReviews
                bikeId={bikeId}
                isAuthenticated={isAuthenticated}
            />
        );
    });


// Review summary for BikeCard
document
    .querySelectorAll('[data-review-summary-root]')
    .forEach(function (element) {

        const bikeId = Number(
            element.dataset.bikeId
        );

        createRoot(element).render(
            <ReviewSummary bikeId={bikeId} />
        );
    });

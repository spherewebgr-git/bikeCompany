import React from 'react';
import { createRoot } from 'react-dom/client';

import BikeReviews from './react/components/reviews/BikeReviews';

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

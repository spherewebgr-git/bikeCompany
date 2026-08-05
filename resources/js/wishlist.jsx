import React from 'react';
import { createRoot } from 'react-dom/client';
import WishlistButton from './react/components/wishlist/WishlistButton';

document
    .querySelectorAll('[data-wishlist-root]')
    .forEach(function (element) {
        const bikeId = Number(
            element.dataset.bikeId
        );

        const initiallyWishlisted =
            element.dataset.wishlisted === 'true';

        createRoot(element).render(
            <WishlistButton
                bikeId={bikeId}
                initiallyWishlisted={initiallyWishlisted}
            />
        );
    });

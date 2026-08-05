import React from 'react';
import { createRoot } from 'react-dom/client';

import WishlistPage
    from './react/pages/WishlistPage';

const wishlistPageRoot = document.getElementById(
    'wishlist-page-root'
);

if (wishlistPageRoot) {
    const itemsUrl =
        wishlistPageRoot.dataset.itemsUrl;

    createRoot(wishlistPageRoot).render(
        <WishlistPage
            itemsUrl={itemsUrl}
        />
    );
}

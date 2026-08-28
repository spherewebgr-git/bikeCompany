import { useEffect, useState } from 'react';

import WishlistBikeCard
    from '../components/wishlist/WishlistBikeCard';

import {
    getWishlistItems,
    removeFromWishlist,
} from '../services/wishlistService';

export default function WishlistPage({ itemsUrl })
{
    const [bikes, setBikes] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState('');
    const [removingBikeId, setRemovingBikeId] = useState(null);

    useEffect(() => {
        async function loadWishlist() {
            try {
                const data = await getWishlistItems(itemsUrl);

                setBikes(data.bikes);
            } catch (error) {
                setErrorMessage(
                    error.message ||
                    'The wishlist could not be loaded.'
                );
            } finally {
                setIsLoading(false);
            }
        }

        loadWishlist();
    }, [itemsUrl]);

    async function handleRemove(bikeId) {
        if (removingBikeId !== null) {
            return;
        }

        setRemovingBikeId(bikeId);
        setErrorMessage('');

        try {
            await removeFromWishlist(bikeId);

            setBikes(function (currentBikes) {
                return currentBikes.filter(function (bike) {
                    return bike.id !== bikeId;
                });
            });
        } catch (error) {
            setErrorMessage(
                error.message ||
                'The bike could not be removed.'
            );
        } finally {
            setRemovingBikeId(null);
        }
    }

    if (isLoading) {
        return (
            <div className="wishlist-page-state">
                <i className="fa-solid fa-spinner fa-spin" />

                <p>Loading your wishlist...</p>
            </div>
        );
    }

    return (
        <div className="wishlist-page">

            <header className="wishlist-page__header">

                <div>
                    <h2 class="section-heading">
                        My Wishlist
                    </h2>
                </div>

                <span className="wishlist-page__count">
                    {bikes.length}
                    {bikes.length === 1 ? ' bike' : ' bikes'}
                </span>

            </header>

            {errorMessage && (
                <div className="alert alert-danger">
                    {errorMessage}
                </div>
            )}

            {bikes.length === 0 ? (
                <div className="wishlist-empty">

                    <div className="wishlist-empty__icon">
                        <i className="fa-regular fa-heart" />
                    </div>

                    <h2>Your wishlist is empty</h2>

                    <p>
                        Browse our bikes and save your favourites
                        so you can find them easily later.
                    </p>

                    <div className="wishlist-empty__actions">

                        <a
                            href="/bikes/sale"
                            className="btn btn-fill btn-md"
                        >
                            <i className="fa-solid fa-bicycle" />
                            Bikes for Sale
                        </a>

                        <a
                            href="/bikes/rental"
                            className="btn btn-trans btn-md"
                        >
                            <i className="fa-regular fa-clock" />
                            Rental Bikes
                        </a>

                    </div>

                </div>
            ) : (
                <div className="row row--grid wishlist-grid">

                    {bikes.map(function (bike) {
                        return (
                            <WishlistBikeCard
                                key={bike.id}
                                bike={bike}
                                isRemoving={
                                    removingBikeId === bike.id
                                }
                                onRemove={handleRemove}
                            />
                        );
                    })}

                </div>
            )}

        </div>
    );
}

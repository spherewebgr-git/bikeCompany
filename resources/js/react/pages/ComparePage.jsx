import { useEffect, useState } from 'react';

import {
    getCompareItems,
    removeFromCompare,
} from '../services/compareService.js';
import WishlistBikeCard from "@/react/components/wishlist/WishlistBikeCard.jsx";
import CompareTable from "@/react/components/compare/CompareTable.jsx";

export default function ComparePage({
                                        itemsUrl,
                                    }) {
    const [bikes, setBikes] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState('');
    const [removingBikeId, setRemovingBikeId] = useState(null);

    useEffect(() => {
        async function loadCompareItems() {
            try {
                const data = await getCompareItems(itemsUrl);

                setBikes(data.bikes);
            } catch (error) {
                setErrorMessage(
                    error.message ||
                    'The bikes could not be loaded.'
                );
            } finally {
                setIsLoading(false);
            }
        }

        loadCompareItems();
    }, [itemsUrl]);

    async function handleRemove(bikeId) {
        if (removingBikeId !== null) {
            return;
        }

        setRemovingBikeId(bikeId);
        setErrorMessage('');

        try {
            await removeFromCompare(bikeId);

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
            <div className="compare-page-state">
                <i className="fa-solid fa-spinner fa-spin" />
                <p>Loading bikes...</p>
            </div>
        );
    }

    return (
        <div className="compare-page">

            <header className="compare-page__header">

                <div>
                    <h2 className="section-heading">
                        Compare Bikes
                    </h2>

                    <p>
                        Compare up to two bikes side by side.
                    </p>
                </div>

                <span className="compare-page__count">
                    {bikes.length} / 3
                </span>

            </header>

            {errorMessage && (
                <div className="alert alert-danger">
                    {errorMessage}
                </div>
            )}

            {bikes.length === 0 ? (
                <div className="compare-empty">

                    <div className="compare-empty__icon">
                        <i className="fa-solid fa-code-compare" />
                    </div>

                    <h2>No bikes selected</h2>

                    <p>
                        Add bikes to comparison from the bike listings
                        and they will appear here.
                    </p>

                    <div className="compare-empty__actions">

                        <a
                            href="/bikes/sale"
                            className="btn btn-fill btn-md"
                        >
                            Bikes for Sale
                        </a>

                        <a
                            href="/bikes/rental"
                            className="btn btn-trans btn-md"
                        >
                            Rental Bikes
                        </a>

                    </div>

                </div>
            ) : (
                <CompareTable
                    bikes={bikes}
                    removingBikeId={removingBikeId}
                    onRemove={handleRemove}
                />
            )}

        </div>
    );
}

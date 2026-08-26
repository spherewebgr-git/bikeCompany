import { useEffect, useState } from 'react';

import {
    addToCompare,
    removeFromCompare,
} from '../../services/compareService';

export default function CompareButton({
                                          bikeId,
                                          initiallyCompared,
                                      }) {
    const [isCompared, setIsCompared] = useState(
        initiallyCompared
    );

    const [isLoading, setIsLoading] = useState(false);

    const [message, setMessage] = useState('');

    async function handleClick() {
        if (isLoading) {
            return;
        }

        setIsLoading(true);
        setMessage('');

        try {
            const data = isCompared
                ? await removeFromCompare(bikeId)
                : await addToCompare(bikeId);

            setIsCompared(data.compared);

            if (data.compared) {
                setMessage(
                    'Bike added to compare list.'
                );
            } else {
                setMessage(
                    'Bike removed from compare list.'
                );
            }

        } catch (error) {
            setMessage(
                error.message || 'Compare request failed.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        if (!message) {
            return;
        }

        const timer = setTimeout(() => {
            setMessage('');
        }, 3000);

        return () => {
            clearTimeout(timer);
        };
    }, [message]);

    const buttonLabel = isCompared
        ? 'Remove from Compare'
        : 'Add to Compare';

    return (
        <div className="compare-button-wrapper">

            <button
                type="button"
                onClick={handleClick}
                disabled={isLoading}
                className={
                    isCompared
                        ? 'compare-button active'
                        : 'compare-button'
                }
                title={buttonLabel}
                aria-label={buttonLabel}
            >
                <i
                    className={
                        isLoading
                            ? 'fa-solid fa-spinner fa-spin'
                            : 'fa-solid fa-code-compare'
                    }
                    aria-hidden="true"
                />
            </button>

            {message && (
                <div className="compare-message">

                    <span>
                        {message}
                    </span>

                    {isCompared && (
                        <a
                            href="/profile/compare"
                            className="compare-message__link"
                        >
                            View comparison
                        </a>
                    )}

                </div>
            )}

        </div>
    );
}

import { useState } from 'react';

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

    async function handleClick() {
        if (isLoading) {
            return;
        }

        setIsLoading(true);

        try {
            const data = isCompared
                ? await removeFromCompare(bikeId)
                : await addToCompare(bikeId);

            setIsCompared(data.compared);
        } catch (error) {
            console.error(
                error.message || 'Compare request failed.'
            );
        } finally {
            setIsLoading(false);
        }
    }

    const buttonLabel = isCompared
        ? 'Remove from Compare'
        : 'Add to Compare';

    return (
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
    );
}

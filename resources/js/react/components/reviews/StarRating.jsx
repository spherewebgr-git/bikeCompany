import { useState } from 'react';

export default function StarRating({
                                       value,
                                       onChange,
                                       readOnly = false,
                                   }) {
    const [hoverValue, setHoverValue] = useState(0);

    function handleMouseEnter(star) {
        if (readOnly) {
            return;
        }

        setHoverValue(star);
    }

    function handleMouseLeave() {
        if (readOnly) {
            return;
        }

        setHoverValue(0);
    }

    function handleClick(star) {
        if (readOnly) {
            return;
        }

        onChange(star);
    }

    const activeValue = hoverValue || value;

    return (
        <div className="star-rating">
            {[1, 2, 3, 4, 5].map(function (star) {
                const isActive = star <= activeValue;

                return (
                    <button
                        key={star}
                        type="button"
                        className={
                            isActive
                                ? 'star-rating__star active'
                                : 'star-rating__star'
                        }
                        onMouseEnter={() =>
                            handleMouseEnter(star)
                        }
                        onMouseLeave={handleMouseLeave}
                        onClick={() =>
                            handleClick(star)
                        }
                        disabled={readOnly}
                        aria-label={`${star} star rating`}
                    >
                        <i
                            className={
                                isActive
                                    ? 'fa-solid fa-star'
                                    : 'fa-regular fa-star'
                            }
                        />
                    </button>
                );
            })}
        </div>
    );
}

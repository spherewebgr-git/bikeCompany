import { useState, useEffect, useRef, useCallback } from 'react';
import summerSale from '../../../../images/summer-sale.jpg';

const defaultBanners = [
    {
        title: 'Bike Discounts',
        description: 'Upgradge your ride with unbeatable prices',
        image: summerSale,
        bannerColor: '#1b5780',
        contentColor: '#5bb2e1',
        buttonText: 'Check It Out',
        buttonLink: '/bikes/sale?discount=1',
    },
];

const AUTOPLAY_MS = 5000;

function PromoBanner() {
    const [banners, setBanners] = useState(defaultBanners);
    const [activeIndex, setActiveIndex] = useState(0);
    const timerRef = useRef(null);

    useEffect(() => {
        fetch('/api/promo-banner')
            .then((res) => res.json())
            .then((data) => {
                if (Array.isArray(data) && data.length > 0) {
                    setBanners(data);
                } else if (data && typeof data === 'object') {
                    setBanners((prev) => [{ ...prev[0], ...data }]);
                }
            })
            .catch(() => {
                // αν αποτύχει το fetch, μένουν τα defaults — καμία αλλαγή στο UX
            });
    }, []);

    const goTo = useCallback((index) => {
        setActiveIndex((index + banners.length) % banners.length);
    }, [banners.length]);

    const next = useCallback(() => goTo(activeIndex + 1), [activeIndex, goTo]);
    const prev = useCallback(() => goTo(activeIndex - 1), [activeIndex, goTo]);

    useEffect(() => {
        if (banners.length <= 1) return undefined;
        timerRef.current = setInterval(next, AUTOPLAY_MS);
        return () => clearInterval(timerRef.current);
    }, [banners.length, next, activeIndex]);
    // ^ το activeIndex στο deps array κάνει reset το interval σε κάθε manual/auto αλλαγή,
    // ώστε το progress bar να ξεκινάει πάντα από μηδέν συγχρονισμένο με το slide

    const handleClick = (link) => {
        window.location.href = link;
    };

    return (
        <div className="promo-banner-slider">
            <div className="promo-banner-track" style={{ transform: `translateX(-${activeIndex * 100}%)` }}>
                {banners.map((banner, i) => (
                    <div
                        key={i}
                        className="promo-banner"
                        style={{
                            '--banner-color': banner.bannerColor,
                            '--content-color': banner.contentColor,
                        }}
                    >
                        <div className="container-fluid">
                            <div className="row">
                                <div className="promo-banner-content col-lg-12">
                                    <div className="banner-left-section col-lg-4">
                                        <h2>{banner.title}</h2>
                                        <p>{banner.description}</p>
                                        <button
                                            className="btn btn-trans btn-md"
                                            onClick={() => handleClick(banner.buttonLink)}
                                        >
                                            {banner.buttonText}
                                            <i className="fa fa-arrow-right"></i>
                                        </button>
                                    </div>

                                    <div className="banner-right-section col-lg-8">
                                        <img src={banner.image} alt="sale-image" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ))}
            </div>

            {banners.length > 1 && (
                <>
                    <button
                        className="promo-banner-arrow promo-banner-arrow--prev"
                        onClick={prev}
                        aria-label="Previous banner"
                    >
                        <i className="fa fa-chevron-left"></i>
                    </button>
                    <button
                        className="promo-banner-arrow promo-banner-arrow--next"
                        onClick={next}
                        aria-label="Next banner"
                    >
                        <i className="fa fa-chevron-right"></i>
                    </button>

                    <div className="promo-banner-progress">
                        {banners.map((_, i) => (
                            <button
                                key={i}
                                className="promo-banner-progress-item"
                                onClick={() => goTo(i)}
                                aria-label={`Go to banner ${i + 1}`}
                            >
                                <span
                                    className={`promo-banner-progress-fill ${i === activeIndex ? 'is-active' : ''} ${i < activeIndex ? 'is-done' : ''}`}
                                    style={i === activeIndex ? { animationDuration: `${AUTOPLAY_MS}ms` } : undefined}
                                />
                            </button>
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}

export default PromoBanner;

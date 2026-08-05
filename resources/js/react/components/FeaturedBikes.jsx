import { useEffect, useState } from "react";

export default function FeaturedBikes() {
    const [bikes, setBikes] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch('/api/featured-bikes')
            .then(res => res.json())
            .then(data => {
                setBikes(data);
                setLoading(false);
            });
    }, []);

    if (loading) return null;
    if (bikes.length === 0) return null;

    return (
        <section className="bike-trail">
            <div className="bike-trail__heading">
                <h6>Fresh from the shop</h6>
                <h2 className="title-text">Featured Bikes</h2>
            </div>

            <div className="bike-trail__row">
                {bikes.map((bike, i) => {
                    const detailUrl = bike.provision?.name === "buy"
                        ? `/bikes-sale/${bike.id}`
                        : `/bikes-rental/${bike.id}`;

                    return (

                        <a href={detailUrl}
                    key={bike.id}
                    className={`bike-slice ${i % 2 === 0 ? 'bike-slice--a' : 'bike-slice--b'}`}
                >
                <div
                    className="bike-slice__img"
                    style={{ backgroundImage: `url(${bike.images?.[0]?.image ?? ''})` }}
                ></div>
                    <div className="bike-slice__overlay"></div>
                    <div className="bike-slice__info">
                        <span className="bike-slice__num">{String(i + 1).padStart(2, '0')}</span>
                        <h3>{bike.brand?.name ?? 'N/A'}</h3>
                        <p><i className="fa fa-bicycle"></i> {bike.type?.name}</p>
                        <p><i className="fa fa-cog"></i> {bike.speed?.gears} speeds</p>
                        <span className="bike-slice__cta">
                                    View bike <span className="bike-slice__arrow">→</span>
                                </span>
                    </div>
                </a>
                );
                })}
            </div>

            <div className="bike-trail__more">
                <a href="/bikes-sale" className="btn btn-trans btn-md">See all bikes</a>
            </div>
        </section>
    );
}

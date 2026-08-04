import PageHero from '../components/PageHero.jsx';
import aboutImage from '../../../images/about-image.jpg';


export default function AboutUs()
{
    return (
        <div className="app-wrapper">
            <main className="page-content">
                <PageHero
                    title="About Us"
                    description="Learn how we started and what drive us to keep rides on the road"
                    backgroundImage={aboutImage}
                />
                <section className="about-us" id="about">
                    <div className="nav-container">
                        <div className="about-grid">
                            <div className="about-media">
                                <img src={aboutImage} alt="Trail Bike workshop" />
                                <div className="about-tag">
                                    <span className="about-tag__line">Tuning since</span>
                                    <span className="about-tag__year">10+ yrs</span>
                                </div>
                            </div>

                            <div className="about-copy">
                                <span className="eyebrow">Trail Bike Workshop</span>
                                <h2 className="about-heading">
                                    Built on trails.<br />
                                    <span>Tuned by hand.</span>
                                </h2>
                                <p>We started as a handful of friends who couldn't stay off two wheels. Now we're a full workshop and store — inspecting, tuning, and servicing every bike before it leaves our hands, so you can focus on the ride.</p>
                                <p>From city cruisers to full-suspension trail bikes, our catalog keeps growing. Whatever you ride, we're here with the right bike and honest advice.</p>

                                <ul className="about-values">
                                    <li><i className="fa fa-check-circle"></i> Quality checked</li>
                                    <li><i className="fa fa-clock"></i> Flexible rentals</li>
                                    <li><i className="fa fa-life-ring"></i> Local support</li>
                                    <li><i className="fa fa-refresh"></i> Fresh stock</li>
                                </ul>
                            </div>
                        </div>

                        <div className="about-specs">
                            <div className="about-specs__item">
                                <span className="about-specs__label">Bikes available</span>
                                <span className="about-specs__value">150+</span>
                            </div>
                            <div className="about-specs__item">
                                <span className="about-specs__label">Happy riders</span>
                                <span className="about-specs__value">2,000+</span>
                            </div>
                            <div className="about-specs__item">
                                <span className="about-specs__label">Years of experience</span>
                                <span className="about-specs__value">10</span>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    );
}

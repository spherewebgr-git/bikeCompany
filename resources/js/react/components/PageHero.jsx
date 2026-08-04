

export default function PageHero({ title, description, backgroundImage, backgroundVideo, eyebrow }) {
    return (
        <section className="page-hero">
            {backgroundVideo ? (
                <video
                    className="page-hero__bg page-hero__bg--video"
                    src={backgroundVideo}
                    autoPlay
                    muted
                    loop
                    playsInline
                    poster={backgroundImage} // δείχνεται όσο φορτώνει το video, ή σε browsers που δεν το υποστηρίζουν
                />
            ) : (
                <div
                    className="page-hero__bg"
                    style={{ backgroundImage: `url(${backgroundImage})` }}
                ></div>
            )}

            <div className="page-hero__overlay"></div>

            <div className="page-hero__content">
                <div className="nav-container">
                    {eyebrow && <span className="page-hero__eyebrow">{eyebrow}</span>}
                    <h1 className="page-hero__title">{title}</h1>
                    <p className="page-hero__description">{description}</p>
                </div>
            </div>
        </section>
    );
}

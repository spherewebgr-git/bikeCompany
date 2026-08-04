

export default function PageHero({title, description, backgroundImage}) {
    return(
      <section
      className="page-hero"
      style={{ backgroundImage: `url(${backgroundImage}`}}>
          <div className="page-hero__overlay">
              <div className="nav-container">
                  <h1 className="page-hero__title">{title}</h1>
                  <p className="page-hero__description">{description}</p>
              </div>
          </div>
      </section>
    );
}

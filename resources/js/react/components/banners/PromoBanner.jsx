import summerSale from '../../../../images/summer-sale.jpg';

function PromoBanner({
    title = 'Bike Discounts',
    description = 'Upgradge your ride with unbeatable prices',
    image = summerSale,
    bannerColor = '#1b5780',
    contentColor = '#5bb2e1',
    buttonText = 'Check It Out',
                     }) {
    return (
        <div className="promo-banner"
             style={{
                 '--banner-color': bannerColor,
                 '--content-color': contentColor,
             }}
        >

            <div className="container-fluid">

                <div className="row">

                    <div class="promo-banner-content col-lg-12">

                        <div className="banner-left-section col-lg-4">
                            <h2>{title}</h2>

                            <p>
                                {description}
                            </p>
                            <button className="btn btn-trans btn-md">
                                {buttonText}
                                <i className="fa fa-arrow-right"></i>
                            </button>
                        </div>

                        <div className="banner-right-section col-lg-8">
                            <img src={image} alt="sale-image"/>
                        </div>

                    </div>


                </div>

            </div>



        </div>
    );
}

export default PromoBanner;

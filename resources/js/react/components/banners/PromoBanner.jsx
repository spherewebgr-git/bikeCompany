import summerSale from '../../../../images/summer-sale.jpg';

function PromoBanner() {
    return (
        <div className="promo-banner">

            <div className="container-fluid">

                <div className="row">

                    <div class="promo-banner-content col-lg-12">

                        <div className="banner-left-section col-lg-4">
                            <h2>Bike Discounts</h2>

                            <p>
                                Upgrade your ride with unbeatable prices!
                            </p>
                            <button className="btn btn-trans btn-md">
                                Check It Out
                                <i className="fa fa-arrow-right"></i>
                            </button>
                        </div>

                        <div className="banner-right-section col-lg-8">
                            <img src={summerSale} alt="sale-image"/>
                        </div>

                    </div>


                </div>

            </div>



        </div>
    );
}

export default PromoBanner;

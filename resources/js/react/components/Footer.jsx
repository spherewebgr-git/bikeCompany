import logoLight from '../../../images/bikeco-light-logo.png';

export default function Footer() {
    const year = new Date().getFullYear();

    return (
        <div className="footer-bottom">
            <div className="nav-container">
                <div className="row">
                    <div className="col-md-4 footer-col">
                        <a href="/">
                            <img src={logoLight} alt="Trail Bike" style={{ height: '80px', width: 'auto' }} />
                        </a>
                        <p>Your trail, your bike. Quality bikes for sale and rent, serviced and ready to ride.</p>
                        <div className="social-icon">
                            <ul>
                                <li><a href="#"><i className="fa-brands fa-facebook"></i></a></li>
                                <li><a href="#"><i className="fa-brands fa-instagram"></i></a></li>
                                <li><a href="#"><i className="fa-brands fa-twitter"></i></a></li>
                            </ul>
                        </div>
                    </div>

                    <div className="col-md-4 footer-col">
                        <h5>Quick Links</h5>
                        <ul className="footer-links">
                            <li><a href="/">Home</a></li>
                            <li><a href="/bikes-sale">Buy Bikes</a></li>
                            <li><a href="/bikes-rental">Rental Bikes</a></li>
                            <li><a href="/contact-us">Contact Us</a></li>
                        </ul>
                    </div>

                    <div className="col-md-4 footer-col" id="contact">
                        <h5>Get in Touch</h5>
                        <ul className="footer-contact">
                            <li><i className="fa fa-map-marker"></i> 123 Trail Street, Bike City</li>
                            <li><i className="fa fa-phone"></i> +30 210 1234567</li>
                            <li><i className="fa fa-envelope"></i> info@trailbike.com</li>
                        </ul>
                    </div>
                </div>

                <div className="footer-divider"></div>
                <p className="copyright text-center">&copy; {year} Trail Bike — All rights reserved</p>
            </div>
        </div>
    );
}

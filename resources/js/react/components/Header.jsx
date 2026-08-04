import { useState } from 'react';
import logoDark from '../../../images/bikeco-dark-logo.png';


export default function Header() {
    const [open, setOpen] = useState(false);

    return(
        <nav className="main-nav">
            <div className="menu-header container-fluid">
                <div className="nav-wrapper">
                    <div className="nav-logo">
                        <a href="/">
                            <img
                                src={logoDark}
                                alt="Trail Bike"
                                style={{ height: '60px', width: 'auto' }}
                            />
                        </a>
                    </div>

                    <div className="nav-links">
                        <a href="/" className="nav-link">Home</a>
                        <a href="/bikes-sale" className="nav-link">Bikes for sale</a>
                        <a href="/bikes-rental" className="nav-link">Rental Bikes</a>
                        <a href="/contact-us" className="nav-link">Contact Us</a>
                    </div>

                    <div className="nav-user">
                        <a href="/login" className="btn btn-trans btn-md">Log in</a>
                        <a href="/register" className="btn btn-fill btn-md">Register</a>
                    </div>

                    <div className="nav-toggle">
                        <button aria-label="Toggle menu" onClick={() => setOpen(!open)}>
                            <svg className="hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                {open ? (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                ) : (
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {open && (
                <div className="mobile-menu block">
                    <div className="mobile-links">
                        <a href="/bikes-rental" className="btn btn-trans btn-md no-float">Rental Bikes</a>
                        <a href="/bikes-sale" className="btn btn-trans btn-md no-float">Buy Bikes</a>
                    </div>
                    <div className="mobile-user-links">
                        <a href="/login" className="btn btn-trans btn-md no-float">Log in</a>
                        <a href="/register" className="btn btn-fill btn-md no-float">Register</a>
                    </div>
                </div>
            )}
        </nav>
    );
}

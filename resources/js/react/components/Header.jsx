import { useState } from 'react';
import { Link } from "react-router-dom";
import logoDark from '../../../images/bikeco-dark-logo.png';


export default function Header({ user, logout })
{
    const [open, setOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);

    return(
        <nav className="main-nav">
            <div className="menu-header container-fluid">
                <div className="nav-wrapper">
                    <div className="nav-logo">
                        <Link to="/">
                            <img src={logoDark} alt="Trail Bike" style={{ height: '60px', width: 'auto' }}/>
                        </Link>
                    </div>

                    <div className="nav-links">
                        <Link to="/" className="nav-link">Home</Link>
                        <Link to="/bikes-sale" className="nav-link">Bikes for sale</Link>
                        <Link to="/bikes-rental" className="nav-link">Rental Bikes</Link>
                        <Link to="/contact-us" className="nav-link">Contact Us</Link>
                    </div>

                    <div className="nav-user">
                        {user?.role?.name === "staff" &&
                        (<Link to="/dashboard/management/bikes" className="btn btn-trans btn-md">Dashboard</Link>)}

                        {user ? (
                            <>
                                <div className="profile-menu">
                                    <button className="btn btn-trans btn-md" onClick={() => setProfileOpen(!profileOpen)}>
                                        Profile
                                    </button>

                                    {profileOpen && (
                                        <div id="profile-menu-dropdown">
                                            <ul className="profile-links">
                                                <li>
                                                    <Link to="/profile">Account</Link>
                                                </li>

                                                <li>
                                                    <Link to="/profile/orders">My Orders</Link>
                                                </li>

                                                <li>
                                                    <Link to="/profile/history">History</Link>
                                                </li>
                                            </ul>
                                        </div>
                                    )}
                                </div>

                                <button onClick={logout} className="btn btn-fill btn-md">
                                    Log Out
                                </button>
                            </>
                        ) : (
                            <>
                                <Link to="/login" className="btn btn-trans btn-md">
                                    Log in
                                </Link>

                                <Link to="/register" className="btn btn-fill btn-md">
                                    Register
                                </Link>
                            </>
                        )}
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
                        <Link to="/bikes-rental" className="btn btn-trans btn-md no-float">Rental Bikes</Link>
                        <Link to="/bikes-sale" className="btn btn-trans btn-md no-float">Buy Bikes</Link>
                    </div>
                    <div className="mobile-user-links">
                        <Link to="/login" className="btn btn-trans btn-md no-float">Log in</Link>
                        <Link to="/register" className="btn btn-fill btn-md no-float">Register</Link>
                    </div>
                </div>
            )}
        </nav>
    );
}

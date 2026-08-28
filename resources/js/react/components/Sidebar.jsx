import { Link } from "react-router-dom";

export default function Sidebar()
{
    return (
        <aside className="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
            <div className="brand-sidebar">
                <h1 className="logo-wrapper">
                    <Link className="brand-logo darken-1" to="/about-us-react"> {/* TODO: to="/"> */}
                        <img src="/images/bikeco-light-logo.png" alt="logo"/>
                    </Link>
                </h1>
            </div>

            <ul className="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow">
{/* MANAGEMENT PAGES */}
                <li className="navigation-header">
                    <a className="navigation-header-text">Management</a>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/products">
                        <i className="fa-solid fa-bicycle"></i>
                        <span className="menu-title">Bike Management</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/calendar">
                        <i className="fa-solid fa-calendar"></i>
                        <span className="menu-title">Calendar Blocked Dates</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/categories">
                        <i className="fa-solid fa-table"></i>
                        <span className="menu-title">Categories Management</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/featured-bikes">
                        <i className="fa-solid fa-home"></i>
                        <span className="menu-title">Homepage Management</span>
                    </Link>
                </li>

                <li className="bold">
                    <link className="waves-effect waves-cyan" to="/admin/manage/featured-bikes">
                        <i className="fa-solid fa-bullhorn"></i>
                        <span className="menu-title">Promo Banner</span>
                    </link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/pending-orders">
                        <i className="fa-solid fa-store"></i>
                        <span className="menu-title">Order Management</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/manage/users">
                        <i className="fa-solid fa-users-gear"></i>
                        <span className="menu-title">User Management</span>
                    </Link>
                </li>

{/* TRACKING PAGES */}
                <li className="navigation-header">
                    <a className="navigation-header-text">Tracking</a>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/track/activerentals">
                        <i className="fa-regular fa-alarm-clock"></i>
                        <span className="menu-title">Active Rentals</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/track/past-orders">
                        <i className="fa-solid fa-clock-rotate-left"></i>
                        <span className="menu-title">Past Orders</span>
                    </Link>
                </li>

                <li className="bold">
                    <Link className="waves-effect waves-cyan" to="/admin/track/statistics">
                        <i className="fa-solid fa-chart-line"></i>
                        <span className="menu-title">Statistics</span>
                    </Link>
                </li>
            </ul>
        </aside>
    );
}

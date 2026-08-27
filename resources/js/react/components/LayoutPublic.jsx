import { Outlet } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
import "../../../css/template.scss";
import "@fortawesome/fontawesome-free/css/all.min.css";

// TODO: <Header user={user} logout={logout}/>
export default function LayoutPublic({ user })
{
    return (
        <div className="public-layout">
            <Header user={user}/>
            <Outlet/>
            <Footer />
        </div>
    );
}

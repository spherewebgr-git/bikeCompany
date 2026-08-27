import { Outlet } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
// TODO: <Header user={user} logout={logout}/>
export default function LayoutPublic({ user })
{
    return (
        <>
            <Header user={user}/>
            <Outlet/>
            <Footer />
        </>
    );
}

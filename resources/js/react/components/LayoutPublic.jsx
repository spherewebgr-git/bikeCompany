import { Outlet } from "react-router-dom";
import Header from "./Header";
import Footer from "./Footer";
// TODO: <Header user={user} logout={logout}/>
export default function LayoutPublic({ children })
{
    return (
        <>
            <Header />
            <Outlet/>
            <Footer />
        </>
    );
}

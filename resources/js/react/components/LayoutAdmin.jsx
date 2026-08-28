import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar";
import "../../../css/materialize.scss";
import "../../../css/style.scss";

export default function LayoutAdmin({ children })
{
    return (
        <div className="admin-layout">
            <Sidebar />

            <div className="admin-container">
                <div className="section">
                    <Outlet/>
                </div>
            </div>

        </div>
    );
}
import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar";
import "../../../css/materialize.scss";
import "../../../css/style.scss";

export default function LayoutAdmin({ children })
{
    return (
        <div className="admin-layout">
            <Sidebar />

            <div id="main">
                <div className="row">
                    <div className="col s12">
                        <div className="container">
                            <div className="section">

                                <Outlet/>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
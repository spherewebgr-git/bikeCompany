import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar";

export default function LayoutAdmin({ children })
{
    return (
        <>
            <Sidebar />

            <div id="main">
                <div class="row">
                    <div class="col s12">
                        <div class="container">
                            <div class="section">

                                <Outlet/>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

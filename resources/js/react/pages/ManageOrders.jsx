import { useEffect, useState } from "react";
import OrderTable from "../components/manage-orders/OrderTable";
import "../../../css/order-management.scss";

export default function ManageOrders ()
{
    const [data, setData] = useState({
        orders: [],
        users: [],
        bikes: [],
        provisions: [],
        statuses: [],
        locations: [],
    });

    const loadOrders = async () =>
    {
        const response = await fetch("/api/admin/manage/pending-orders");

        if (!response.ok)
        {
            console.error("Could not load orders");
            return;
        }

        const results = await response.json();
        setData(results);
    };

    useEffect(() => { loadOrders(); }, []);

    return (
        <div id="OrderManagement">
            <h2>Pending Orders</h2>
            <div className="orders">
                <OrderTable pending={true} data={data} onResults={setData}/>
            </div>
        </div>
    );
}
import { useEffect, useState } from "react";
import OrderTable from "../components/manage-orders/OrderTable";
import "../../../css/order-management.scss";

export default function TrackOrders ()
{
    const [data, setData] = useState({
        orders: [],
        users: [],
        bikes: [],
        provisions: [],
        locations: [],
    });

    const loadOrders = async () =>
    {
        const response = await fetch("/api/admin/track/past-orders");

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
        <div id="CompletedOrders">
            <h2>Order History</h2>
            <div className="orders">
                <OrderTable pending={false} data={data} onResults={setData}/>
            </div>
        </div>
    );
}
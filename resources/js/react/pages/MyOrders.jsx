import { useEffect, useState } from "react";
import OrderFilters from "../components/customer-orders/OrderFilters";
import OrderStatus from "../components/customer-orders/OrderStatus";
import "../../../css/myorders.scss";

export default function MyOrders ()
{
    const [data, setData] = useState({
        orders: [],
        provisions: [],
        statuses: [],
    });

    const loadOrders = async () =>
    {
            const response = await fetch("/api/profile/myorders", {
        credentials: "include",
    });

        if (!response.ok)
        {
            console.error("Could not load orders");
            return;
        }

        const results = await response.json();
        setData(results);
    };

    useEffect(() => { loadOrders(); }, []);

    const filterOrders = async (params = {}) =>
    {
        const query = new URLSearchParams(params);
        const response = await fetch( `/api/profile/myorders/search?${query}` );
        const results = await response.json();
        setData(results);
    };


    return (
        <div id="MyOrders">
            <div className="container">
                <h2>Pending Orders</h2>

                <div className="row col-12">
                    <OrderFilters provisions={data.provisions} filterOrders={filterOrders}/>
                </div>

                {data.orders.length > 0 ? (
                    <div className="row g-4">
                        {data.orders.map(order => (
                            <div key={order.id} className="col-12">
                                <div className="order-card">
                                    <div className="path">
                                        <OrderStatus statuses={data.statuses} order={order}/>
                                    </div>

                                    <div className="inner-card">
                                        {order.bike.images?.length > 0 && (
                                            <img src={ order.bike.images?.[0]?.image } alt="bike"/>
                                        )}

                                        <div className="info">
                                            <h4 className="model">
                                                { order.bike.speed.gears }-speed { order.bike.colour } { order.bike.brand.name } { order.bike.type.name } Bike
                                            </h4>

                                            <div className="group">
                                                <p className="orderid"><b>Order ID: </b>{ order.id }</p>

                                                <p className="productid">
                                                    <b>Product ID: </b>
                                                    {order.bike.serialnum ? order.bike.serialnum : order.bike.SKU}
                                                </p>
                                            </div>

                                            <hr />

                                            <p className="date">
                                                <b>Placed at: </b>
                                                { order.order_date.replace("Z", "").replace("T", " ").replace(".000000", "") }
                                            </p>

                                            <p className="quantity">
                                                <b>Quantity: </b>
                                                {order.bike.quantity ? order.bike.quantity : "1"}
                                            </p>

                                            <p className="price"><b>Cost: </b>{ order.price } €</p>

                                            <hr />

                                            <p className="provision">
                                                <b>Usage Type: </b>{ order.bike.provision.name }
                                            </p>

                                            <p className="address">
                                                <b>Pickup Location: </b>
                                                {order.dropoff_address ?
                                                    order.dropoff_address
                                                :
                                                    "Our Store at " + order.location.name
                                                }
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="no-orders">
                        <p> You don't have any pending orders yet,<br />or none of them match your filters!</p>
                    </div>
                )}
            </div>
        </div>
    );
}
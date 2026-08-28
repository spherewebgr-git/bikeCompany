import { useState } from "react";
import OrderSearch from "./OrderSearch";

export default function OrderTable({pending, data, onResults})
{
    const payment = pending ? 
        [{id: 0, name: "Unpayed"}, {id: 1, name: "Payed"}] :
        [{id: 0, name: "At Reception"}, {id: 1, name: "Online"}];
    
    const [orderToEdit, setOrderToEdit] = useState(null);
    const [stat, setStat] = useState("");

    const openEditor = (order) =>
    {
        setOrderToEdit(order.id);
        setStat(order.status.id); 
    };

    const closeEditor = () =>
    {
        setOrderToEdit(null);
        setStat("");
    };

    const updateStatus = async (e) =>
    {
        e.preventDefault();

        const res = await fetch(`/api/admin/manage/pending-orders/update/${orderToEdit}`,
        {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({stat: stat}),
        });

        await onResults();
        closeEditor();
    };

    return (
        <div>
            <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Amount</th>
                    <th>Provision</th>
                    <th>Location</th>
                    {pending && (<th>Status</th>)}
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td className="search orderid">
                        <OrderSearch type="text" name="order" placeholder="ID" pending={pending} onResults={onResults}/>
                    </td>

                    <td className="search">
                        <OrderSearch type="text" name="user" placeholder="Search Customer" pending={pending} onResults={onResults}/>
                    </td>

                    <td className="search productcode">
                        <OrderSearch type="text" name="product" placeholder="Search Product" pending={pending} onResults={onResults}/>
                    </td>

                    <td className="search">
                    </td>

                    <td className="search provision">
                        <OrderSearch name="provision" list={ data.provisions } pending={pending} onResults={onResults}/>
                    </td>

                    <td className="search">
                        <OrderSearch name="location" list={ data.locations } pending={pending} onResults={onResults}/>
                    </td>

                    {pending && (
                    <td className="search">
                        <OrderSearch name="status" list={ data.statuses } pending={pending} onResults={onResults}/>
                    </td>
                    )}

                    <td className="search">
                        <OrderSearch name="payment" list={ payment } pending={pending} onResults={onResults}/>
                    </td>
                </tr>

                {data.orders.map(order => (
                    <tr className="data-row" key={ order.id }>
                        <td>
                            { order.id }
                        </td>

                        <td>
                            <i className="fa-solid fa-address-book c-grey"></i>
                            <b>{ order.user.first_name } { order.user.last_name }</b>
                            <br />
                            <i className="fa-solid fa-phone c-grey"></i>
                            { order.user.phone }
                            <br />
                            <i className="fa-solid fa-envelope c-grey"></i>
                            { order.user.email }
                        </td>

                        <td>
                            {order.bike.serialnum ? (
                                <>
                                    <b>Serial Number:</b>
                                    <br />
                                    { order.bike.serialnum }
                                </>
                            ) : (
                                <>
                                    <b>S.K.U.:</b>
                                    <br />
                                    { order.bike.SKU }
                                </>
                            )}
                        </td>

                        <td className="quantity">
                            {order.bike.quantity ?? 1}
                        </td>

                        <td>
                            { order.bike.provision.name }
                        </td>

                        <td>
                            {order.dropoff_address ? (
                                <>
                                <i className="fa-solid fa-truck-arrow-right c-red"></i>
                                <i className="c-red">{ order.dropoff_address }</i>
                                </>
                            ) : (
                                <>
                                <i className="fa-solid fa-warehouse c-blue"></i>
                                <b className="c-blue">{ order.location.name }</b>
                                </>
                            )}
                        </td>

                        {pending && (
                        <td>
                            <div className="order-actions">
                                <button type="button" onClick={() => openEditor(order)} className="stat">
                                    <i className="fa-regular fa-pen-to-square"></i>
                                    { order.status.name }
                                </button>
                            </div>

                            {orderToEdit == order.id && (
                            <form className="order-status" onSubmit={updateStatus}>
                                <label htmlFor="status">Order Status:</label><br />
                                <select name="status" id="status" className="form-select" value={stat} onChange={(e) => setStat(e.target.value)}>
                                    <option value="">Any</option>
                                    {data.statuses.map(status => (
                                        <option key={status.id} value={status.id}>
                                            {status.name}
                                        </option>
                                    ))}
                                </select>

                                <div className="buttons">
                                    <button className="update" type="submit">
                                        Update
                                    </button>
                                    <button className="cancel" type="button" onClick={closeEditor}>
                                        Cancel
                                    </button>
                                </div>    
                            </form>
                            )}
                        </td>
                        )}

                        <td className="payment">
                            {order.payed_off ? (
                                <>
                                <i className="fa-solid fa-credit-card c-black"></i>
                                <i className="fa-solid fa-circle-check c-green"></i>
                                </>
                            ) : (
                                <>
                                <i className="fa-solid fa-hand-holding-dollar c-black"></i>
                                <i className={`fa-solid ${pending ? "fa-clock c-orange" : "fa-circle-check c-green"}`}></i>
                                </>
                            )}
                        </td>
                    </tr>
                ))}
            </tbody>
            </table>
        </div>
    );
}
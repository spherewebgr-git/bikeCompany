import { useEffect, useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLocationDot, faPhone, faEnvelope, faAddressBook } from "@fortawesome/free-solid-svg-icons";
import "../../../css/active-rentals.scss";

export default function ActiveRentals()
{
    const [orders, setOrders] = useState([]);
    useEffect(() => {
        fetch("/api/active-rentals")
        .then((res) => res.json())
        .then((data) => setOrders(data))
        .catch((err) => console.error(err));
    }, []);
    
    const [filter, setFilter] = useState("any");
    useEffect(() => {
        fetch(`/api/active-rentals?return=${filter}`)
            .then((res) => res.json())
            .then((data) => setOrders(data))
            .catch(console.error);
    }, [filter]);

    return (
        <div id="ActiveRentals">
            <h2>Active Rentals</h2>
            <div className="container">
                {/* REDO: react form */}
                <form className="filter">
                    <legend>Return Date:</legend>
                    
                    <input type="radio" name="return" value="any"
                    checked={filter === "any"} onChange={(e) => setFilter(e.target.value)}/>
                    <label for="any">Any</label>
                    
                    <input type="radio" name="return" value="overdue"
                    checked={filter === "overdue"} onChange={(e) => setFilter(e.target.value)}/>
                    <label for="overdue">Overdue</label>
                    
                    <input type="radio" name="return" value="pending"
                    checked={filter === "pending"} onChange={(e) => setFilter(e.target.value)}/>
                    <label for="pending">Pending</label>
                </form>

                <div className="row">
                    {orders.map((order) => (
                        <div className="active-single" key={order.id}>

                            <img src={order.bike?.images?.[0]?.image} alt="bike"/>

                            <div className="bikeinfo">
                                <p className="ids">
                                    Order ID: { order.id } - Product ID: 
                                    { order.bike.serialnum ?? order.bike.SKU }
                                </p>

                                {new Date(order.rent_end) < new Date() ? (
                                    <p className="overdue">
                                        <b>OVERDUE: </b>
                                        {order.rent_end.replace("Z", "").replace("T", " ").replace(".000000", "")}
                                    </p>
                                ) : (
                                    <p className="tobe">
                                        <b>Rent End: </b>
                                        {order.rent_end.replace("Z", "").replace("T", " ").replace(".000000", "")}
                                    </p>
                                )}  

                                <p className="location">
                                    <FontAwesomeIcon icon={faLocationDot} style={{ color: "#fe4819" }}/>
                                    Picked up at our store at { order.location.name }
                                </p>

                                <p className="customer">
                                    <FontAwesomeIcon icon={faAddressBook} style={{ color: "#6b6f82" }}/>
                                    { order.user.first_name } { order.user.last_name }
                                    <br/>
                                    <FontAwesomeIcon icon={faPhone} style={{ color: "#6b6f82" }}/>
                                    { order.user.phone }
                                    <br/>
                                    <FontAwesomeIcon icon={faEnvelope} style={{ color: "#6b6f82" }}/>
                                    { order.user.email }
                                </p>
                            </div>

                            {/* REDO: react form */}
                            <form action="{ route('activerentals.update', order) }" method="POST" className="returned">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <label>
                                    Returned
                                    <input type="checkbox" name="returned" value="1" onChange="this.form.submit()"/>
                                </label>
                            </form>

                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}


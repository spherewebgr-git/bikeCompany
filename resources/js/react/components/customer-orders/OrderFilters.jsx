import { useState } from "react";
import "../../../../css/orderfilters.scss";

export default function OrderFilters({provisions, filterOrders})
{
    const [filters, setFilters] = useState({
        orderdate: "",
        order: "",
        product: "",
        price: "",
        provision: "",
        pickup: "",
    });

    const handleChange = (e) =>
    {
        setFilters({...filters, [e.target.name]: e.target.value,});
    };

    const handleSubmit = (e) =>
    {
        e.preventDefault();
        filterOrders(filters);
    };

    return (
        <div id="OrderFilters">
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <form onSubmit={handleSubmit}>

                            <div className="labelfield">
                                <label>Order Date: <b>YYYY (-MM (-DD) )</b></label>
                                <input type="text" name="orderdate" placeholder="YYYY or YYYY-MM or YYYY-MM-DD"
                                    value={filters.orderdate} onChange={handleChange}/>
                            </div>

                            <div className="labelfield">
                                <label>Order ID:</label>
                                <input type="number" id="order" name="order" placeholder="Search Order ID"
                                    value={filters.order} onChange={handleChange}/>
                            </div>

                            <div className="labelfield">
                                <label>Product ID:</label>
                                <input type="number" id="product" name="product" placeholder="Search Product ID"
                                    value={filters.product} onChange={handleChange}/>
                            </div>

                            <div className="labelfield">
                                <label>Price:</label>
                                <input type="number" id="price" name="price" placeholder="Search Price"
                                    value={filters.price} onChange={handleChange}/>
                            </div>

                            <div className="labelfield">
                                <label>Usage:</label>
                                <select name="provision" id="provision" value={filters.provision}
                                onChange={handleChange}>
                                    <option value="" selected> Any </option>
                                    {provisions.map(provision => (
                                        <option key={provision.id} value={provision.id}>
                                            {provision.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            <div className="labelfield">
                                <label>Pickup Location:</label>
                                <input type="text" id="pickup" name="pickup" placeholder="Search Location"
                                value={filters.pickup} onChange={handleChange}/>
                            </div>

                            <div className="labelfield">
                                <button type="submit" className="Submit">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    );
}
import { useEffect, useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import BikeFilters from "../components/manage-bikes/BikeFilters";
import BikeTable from "../components/manage-bikes/BikeTable";
import "../../../css/bike-management.scss";

export default function ActiveRentals()
{
    const navigate = useNavigate();

    const [bikes, setBikes] = useState([]);

    const [filters, setFilters] = useState({
        brands: [],
        types: [],
        provisions: [],
        speeds: [],
    });

    useEffect(() =>
    {
        fetch("/api/admin/manage/products").then(res => res.json()).then(data =>
        {
            setBikes(data.bikes);

            setFilters
            ({
                brands: data.brands,
                types: data.types,
                provisions: data.provisions,
                speeds: data.speeds,
            });
        });
    }, []);

    const [sku, setSku] = useState("");
    const loadBikes = async (params = {}) =>
    {
        const query = new URLSearchParams(params);
        const res = await fetch( `/api/admin/manage/products?${query}` );
        const data = await res.json();

        setBikes(data.bikes);
    };

    return (
        <>
            <div id="BikeManagement">
                <h2>Bike Database</h2>

                <button className="Create" onClick={() => navigate(`/admin/manage/products/create`)}>
                    + Insert New Bike
                </button>

                <div className="filter-search">
                    <form className="search" onSubmit={(e) => { e.preventDefault(); loadBikes({ SKU: sku }); }}>
                        <input value={sku} onChange={(e) => setSku(e.target.value)} placeholder="Search SKUs"/>
                        <button type="submit">
                            <i className="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <BikeFilters filters={filters} onFilter={loadBikes}/>
                </div>

                <BikeTable bikes={bikes} loadBikes={loadBikes}/>
            </div>
        </>
    );
}

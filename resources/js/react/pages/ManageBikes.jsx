import { useEffect, useState } from "react";
import BikeFilters from "../components/manage-bikes/BikeFilters";
import BikeTable from "../components/manage-bikes/BikeTable";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";
import "../../../css/bike-management.scss";

export default function ActiveRentals()
{

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

    const filterBikes = async (filters) =>
    {
        const params = new URLSearchParams(filters);
        const res = await fetch(`/api/admin/manage/products?${params}`);
        const bikes = await res.json();

        setBikes(bikes);
    };

    const [sku, setSku] = useState("");
    const search = async (sku) =>
    {
        const res = await fetch(`/api/admin/manage/products?SKU=${sku}`);
        const bikes = await res.json();
        setBikes(bikes);
    };

    return (
        <>
            <div className="container" id="BikeManagement">
                <h2 className="section-heading">Bike Database</h2>

                <a href="">
                    <button className="Create">+ Insert New Bike</button>
                </a>

                <div className="filter-search">
                    <form onSubmit={(e) => { e.preventDefault(); search(sku); }}>
                        <input value={sku} onChange={(e) => setSku(e.target.value)} placeholder="Search SKUs"/>
                        <button type="submit">
                            <i className="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <BikeFilters filters={filters} onFilter={filterBikes}/>
                </div>

                <BikeTable bikes={bikes}/>
            </div>
        </>
    );
}

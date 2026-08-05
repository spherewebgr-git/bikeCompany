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

    const loadBikes = async (params = {}) =>
    {
        const query = new URLSearchParams(params);
        const res = await fetch( `/api/admin/manage/products?${query}` );
        const data = await res.json();

        setBikes(data.bikes);
    };

    return (
        <>
            <div className="container" id="BikeManagement">
                <h2 className="section-heading">Bike Database</h2>

                <a href="">
                    <button className="Create">+ Insert New Bike</button>
                </a>

                <div className="filter-search">
                    <form onSubmit={(e) => { e.preventDefault(); loadBikes({ SKU: sku }); }}>
                        <input value={sku} onChange={(e) => setSku(e.target.value)} placeholder="Search SKUs"/>
                        <button type="submit">
                            <i className="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    <BikeFilters filters={filters} onFilter={loadBikes(selectedFilters)}/>
                </div>

                <BikeTable bikes={bikes}/>
            </div>
        </>
    );
}

import { useEffect, useState } from "react";
import "../styles/manage-categories.scss";
import CategoryTable from "../components/manage-categories/CategoryTable";

export default function ManageCategories()
{
    const [data, setData] = useState({
        brands: [],
        types: [],
        provisions: [],
        speeds: [],
        statuses: [],
        locations: [],
    });

    const loadCategories = async () =>
    {
        const response = await fetch("/api/admin/manage/categories");

        if (!response.ok)
        {
            console.error("Could not load categories");
            return;
        }

        const results = await response.json();
        
        setData(results);
    };

    useEffect(() => { loadCategories(); }, []);

    return (
        <div id="ManageCategories">
            <div>
                <h2>Edit Categories</h2>
                <div className="row">
                    <CategoryTable title="Bike Speeds" placeholder="Gear Amount" data={data.speeds} type="number" cat="gears" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Provision Types" placeholder="Provision Type" data={data.provisions} type="text" cat="provision" onChanged={loadCategories}/>

                    <CategoryTable title="Store Locations" placeholder="Location" data={data.locations} type="text" cat="location" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Order Statuses" placeholder="Status" data={data.statuses} type="text" cat="status" edit={true} del={true} onChanged={loadCategories}/>

                    <CategoryTable title="Bike Types" placeholder="Bike Type" data={data.types} type="text" cat="type" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Bike Brands" placeholder="Bike Brand" data={data.brands} type="text" cat="brand" edit={true} onChanged={loadCategories}/>
                </div>
            </div>
        </div>
    );
}
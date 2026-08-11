import { useEffect, useState } from "react";
import "../../../css/category-management.scss";
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
        <div id="StaffCategories">
            <div className="container">
                <h1>Edit Categories</h1>
                <div className="row">
                    <CategoryTable title="Bike Speeds" placeholder="Search gear amount" data={data.speeds} type="number" cat="gears" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Provision Types" placeholder="Search provision types" data={data.provisions} type="text" cat="provision" onChanged={loadCategories}/>

                    <CategoryTable title="Store Locations" placeholder="Search locations" data={data.locations} type="text" cat="location" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Order Statuses" placeholder="Search status" data={data.statuses} type="text" cat="status" edit={true} del={true} onChanged={loadCategories}/>

                    <CategoryTable title="Bike Types" placeholder="Search bike types" data={data.types} type="text" cat="type" edit={true} onChanged={loadCategories}/>

                    <CategoryTable title="Bike Brands" placeholder="Search bike brands" data={data.brands} type="text" cat="brand" edit={true} onChanged={loadCategories}/>
                </div>
            </div>
        </div>
    );
}
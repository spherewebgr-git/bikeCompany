import { useEffect, useState } from "react";
import CategorySearch from "./CategorySearch";
import CategoryItem from "./CategoryItem";

export default function CategoryTable({title, placeholder, data, type, cat, edit = false, del = false, onChanged})
{
    const [items, setItems] = useState(data);
    useEffect(() => { setItems(data); }, [data]);
    const handleSearchResults = (results) => { setItems(results); };

    return (
        <div className={`${cat} table col-sm-6 col-md-3`}>
            <h5>{title}</h5>

            <CategorySearch category={cat} placeholder={placeholder} onResults={handleSearchResults}/>

            {items.map((item) =>
            {
                let value = item.name;
                if (cat === "gears") { value = item.gears; }

                return (
                    <CategoryItem
                        key={item.id}
                        valueID={item.id}
                        value={value}
                        type={type}
                        category={cat}
                        st={item.step}
                        lg={item.longitude}
                        lt={item.latitude}
                        editable={edit}
                        deletable={del}
                        onChanged={onChanged}
                    />
                );
            })}

            <button className="new-category">
                + Add New
            </button>
        </div>
    )
}
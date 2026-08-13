import { useEffect, useState } from "react";
import CategorySearch from "./CategorySearch";
import CategoryItem from "./CategoryItem";

export default function CategoryTable({title, placeholder, data, type, cat, edit = false, del = false, onChanged})
{
    const [items, setItems] = useState(data);
    useEffect(() => { setItems(data); }, [data]);
    const handleSearchResults = (results) => { setItems(results); };

    const [creating, setCreating] = useState(false);

    return (
        <div className={`${cat} table col-sm-6 col-md-3`}>
            <h5>{title}</h5>

            <CategorySearch category={cat} placeholder={"Search "+placeholder} onResults={handleSearchResults}/>

            {items.map((item) =>
            {
                const value = cat === "gears" ? item.gears : item.name;

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

            {creating && (
                <form onSubmit={handleCreate}>
                    {category === "status" && (
                        <>
                        <input type="number" placeholder={placeholder+" Step"} value={Step}
                        onChange={(e) => setStep(e.target.value)}/>
                        <br />
                        </>
                    )}

                    <input type="text" placeholder={placeholder} value={Input} onChange={(e) => setInput(e.target.value)}/>

                    {category === "location" && (
                        <>
                        <input type="number" step="0.0000001" placeholder="Longitude" value={Longitude}
                        onChange={(e) => setLongitude(e.target.value)}/>
                        <br />
                        <input type="number" step="0.0000001" placeholder="Latitude" value={Latitude}
                        onChange={(e) => setLatitude(e.target.value)}/>
                        <br />
                        </>
                    )}

                    <button className="confirm" type="submit">
                        Confirm
                    </button>

                    <button className="cancel" type="button" onClick={() => setCreating(false)}>
                        Cancel
                    </button>
                </form>
            )}

            <button className="new-category" onClick={() => setCreating(true)}>
                + Add New
            </button>
        </div>
    )
}
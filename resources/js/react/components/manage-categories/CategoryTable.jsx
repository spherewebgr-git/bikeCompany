import { useEffect, useState } from "react";
import CategorySearch from "./CategorySearch";
import CategoryItem from "./CategoryItem";

export default function CategoryTable({title, placeholder, data, type, cat, edit = false, del = false, onChanged})
{
    const [items, setItems] = useState(data);
    useEffect(() => { setItems(data); }, [data]);
    const handleSearchResults = (results) => { setItems(results); };

    const [creating, setCreating] = useState(false);
    const [Input, setInput] = useState("");
    const [Step, setStep] = useState("");
    const [Lat, setLat] = useState("");
    const [Long, setLong] = useState("");

    const handleCreate = async (e) =>
    {
        e.preventDefault();

        let values;
        if (cat === "gears")
        {
            values = { gears: Input, };
        }
        else if (cat === "status")
        {
            values = 
            {
                name: Input,
                step: Step,
            };
        }
        else if (cat === "location")
        {
            values =
            {
                name: Input,
                latitude: Lat,
                longitude: Long,
            };
        }
        else
        {
            values = { name: Input, };
        }

        const response = await fetch(`/api/admin/manage/categories/${cat}`,
        {
            method: "POST",
            headers:
            {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            body: JSON.stringify(values),
        });

        if (!response.ok)
        {
            const error = await response.json();
            console.error("Could not create category:", error);
            return;
        }

        setCreating(false);
        setInput("");
        setStep("");
        setLat("");
        setLong("");
        onChanged();
    };

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
                <form className="createform" onSubmit={handleCreate}>
                    {cat === "status" && (
                        <>
                        <input type="number" placeholder={placeholder+" Step"} value={Step}
                        onChange={(e) => setStep(e.target.value)}/>
                        <br />
                        </>
                    )}

                    <input type={type} placeholder={placeholder} value={Input} onChange={(e) => setInput(e.target.value)}/>
                    <br />

                    {cat === "location" && (
                        <>
                        <input type="number" step="0.0000001" placeholder="Longitude" value={Long}
                        onChange={(e) => setLong(e.target.value)}/>
                        <br />
                        <input type="number" step="0.0000001" placeholder="Latitude" value={Lat}
                        onChange={(e) => setLat(e.target.value)}/>
                        <br />
                        </>
                    )}

                    <div className="buttons">
                        <button className="confirm" type="submit">
                            Confirm
                        </button>

                        <button className="cancel" type="button" onClick={() => setCreating(false)}>
                            Cancel
                        </button>
                    </div>
                </form>
            )}

            <button className="new-category" onClick={() => setCreating(true)}>
                + Add 
            </button>
        </div>
    )
}
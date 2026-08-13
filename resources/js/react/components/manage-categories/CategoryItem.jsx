import { useState } from "react";

export default function CategoryItem ({valueID, value, type, category, st = null, lg = null, lt = null, editable = false, deletable = false, onChanged})
{
    const [editing, setEditing] = useState(false);
    const [editValue, setEditValue] = useState(value);
    
    const [editStep, setEditStep] = useState(st); // Status -> Step
    const [editLong, setEditLong] = useState(lg); // Location -> Longitude
    const [editLat, setEditLat] = useState(lt); // Location -> Latitude

    const handleEdit = async (e) =>
    {
        e.preventDefault();

        let values;

        if (category === "gears")
        {
            values = { gears: editValue, };
        }
        else if (category === "status")
        {
            values =
            {
                name: editValue,
                step: editStep,
            };
        }
        else if (category === "location")
        {
            values =
            {
                name: editValue,
                longitude: editLong,
                latitude: editLat,
            };
        }
        else
        {
            values = { name: editValue, };
        }

        const response = await fetch(`/api/admin/manage/categories/${category}/${valueID}`,
        {
            method: "PATCH",
            headers:
            {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            body: JSON.stringify(values),
        });

        if (!response.ok)
        {
            console.error("Could not edit category item");
            return;
        }

        setEditing(false);
        onChanged();
    };

    const [deleting, setDeleting] = useState(false);
    const handleDelete = async () =>
    {
        const response = await fetch(`/api/admin/manage/categories/${category}/${valueID}`,
        {
            method: "DELETE",
            headers: { "Accept": "application/json", },
        });

        if (!response.ok)
        {
            console.error("Failed to delete category item");
            return;
        }

        setDeleting(false);
        onChanged();
    };

    return (
        <>
            <div className="cat-value">
                <p>{ category == "status" && (st+". ")}{value}</p>

                { editable === true && (
                    <button className="edit" onClick={() => setEditing(true)}>
                        <i className="fa-regular fa-pen-to-square"></i>
                    </button>
                )}

                { deletable === true && (
                    <button className="delete" onClick={() => setDeleting(true)}>
                        <i className="fa-regular fa-trash-can"></i>
                    </button>
                )}
            </div>

            { editing === true && (
                <form className="editform" onSubmit={handleEdit}>
                    <input type={type} id={category} name={category} value={editValue}
                    onChange={(e) => setEditValue(e.target.value)} />

                    {category == "status" && (
                        <input type="number" id="step" name="step" value={editStep}
                        onChange={(e) => setEditStep(e.target.value)}/>
                    )}

                    {category == "location" && (
                        <>
                            <input type="number" step="0.0000001" id="long" name="long" value={editLong}
                            onChange={(e) => setEditLong(e.target.value)}/>

                            <input type="number" step="0.0000001" id="lat" name="lat" value={editLat}
                            onChange={(e) => setEditLat(e.target.value)}/>
                        </>
                    )}

                    <div className="buttons">
                        <button className="confirm" type="submit">
                            Confirm
                        </button>
                        <button className="cancel" type="button" onClick={() => setEditing(false)}>
                            Cancel
                        </button>
                    </div>
                </form>
            )}

            { deleting === true && (
                <div className="delconfirm">
                    <h3>Warning!</h3>
                    <p>You are about to delete "{value}" from this category</p>
                    <div className="buttons">
                        <button className="delete" type="button" onClick={handleDelete}>
                            Delete
                        </button>                        
                        <button className="cancel" type="button" onClick={() => setDeleting(false)}>
                            Cancel
                        </button>
                    </div>
                </div>
            )}
        </>
    );
}
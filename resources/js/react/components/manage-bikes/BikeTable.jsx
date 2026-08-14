import { useState, useEffect, useRef } from "react";
import { useNavigate } from "react-router-dom";

export default function BikeTable({ bikes, loadBikes })
{
    const navigate = useNavigate();

    const [bikeToEdit, setBikeToEdit] = useState(null);
    const [quantity, setQuantity] = useState(null);

    const openEditor = (bike) =>
    {
        setBikeToEdit(bike.id);
        setQuantity(bike.quantity);
    };

    const closeEditor = () =>
    {
        setBikeToEdit(null);
        setQuantity("");
    };

    const updateQuantity = async (e) =>
    {
        e.preventDefault();

        const res = await fetch(`/api/admin/manage/products/${bikeToEdit}/quantity`,
        {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ quantity }),
        });

        await loadBikes();
        closeEditor();
    };

    const formRef = useRef(null);
    useEffect(() =>
    {
        function handleClick(e)
        {
            if (bikeToEdit && formRef.current && !formRef.current.contains(e.target))
            {
                closeEditor();
            }
        }

        document.addEventListener("mousedown", handleClick);
        return () => { document.removeEventListener("mousedown", handleClick); };
    }, [bikeToEdit]);


    return (
        <div id="data-table-simple_wrapper" className="dataTables_wrapper">
            <table id="data-table-simple" className="display dataTable dtr-inline" role="grid">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Image</th>
                        <th>Provision</th>
                        <th>Model</th>
                        <th>Gears</th>
                        <th>Colour</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                {bikes.map(bike => (
                    <tr key={bike.id}>

                        <td className="bike-SKU">{bike.SKU}</td>

                        <td>
                            {bike.images?.length > 0 && (
                                <img className="bikephoto" src={ bike.images?.[0]?.image } alt="bike"/>
                            )}
                        </td>

                        <td className="bike-provision">{bike.provision?.name}</td>

                        <td className="bike-model">
                            {bike.brand?.name}: {bike.type?.name}
                        </td>

                        <td className="bike-gears">{bike.speed?.gears}</td>

                        <td>{bike.colour}</td>

                        <td>
                        {bike.quantity && (
                            <div className="bikeactions">
                                <button className="quant" onClick={() => openEditor(bike)}>
                                    { bike.quantity }
                                    <i className="fa-regular fa-pen-to-square"></i>
                                </button>
                            </div>
                        )}

                        {bikeToEdit == bike.id && (
                            <form className="update-quantity" onSubmit={updateQuantity} ref={formRef}>
                                <input type="number" value={quantity} onChange={(e) => setQuantity(e.target.value)}/>
                                <br />
                                <button type="submit" className="update">Update</button>
                                <button type="button" className="cancel" onClick={closeEditor}>
                                    Cancel
                                </button>
                            </form>
                        )}
                        </td>

                        <td>
                            <div className="bikeactions">
                                <button onClick={() => navigate(`/admin/manage/products/edit/${bike.id}`)} className="edit">
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
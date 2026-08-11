import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";

export default function EditBike()
{
    const { id } = useParams();
    const navigate = useNavigate();

    const [bike, setBike] = useState(null);
    const [deleteImages, setDeleteImages] = useState([]);

    const handleImageToggle = (id) =>
    {
        setDeleteImages((prev) => prev.includes(id)
            ? prev.filter((imageId) => imageId !== id)
            : [...prev, id]
        );
    };
    const [brands, setBrands] = useState([]);
    const [types, setTypes] = useState([]);
    const [speeds, setSpeeds] = useState([]);
    const [provisions, setProvisions] = useState([]);

    const [hourPrice, setHourPrice] = useState("");
    const [dayPrice, setDayPrice] = useState("");
    const [weekPrice, setWeekPrice] = useState("");
    const [buyPrice, setBuyPrice] = useState("");

    useEffect(() =>
    {
        fetch(`/api/admin/manage/products/edit/${id}`)
            .then(res => res.json())
            .then(data => {
                setBike(data.bike);
                setBrands(data.brands);
                setTypes(data.types);
                setSpeeds(data.speeds);
                setProvisions(data.provisions);

                const prices = data.bike.prices;
                setHourPrice(prices.find(price => price.description === " €/hour")?.price ?? "");
                setDayPrice(prices.find(price => price.description === " €/day")?.price ?? "");
                setWeekPrice(prices.find(price => price.description === " €/week")?.price ?? "");
                setBuyPrice(prices.find(price => price.description === "€")?.price ?? "");
            });
    }, [id]);


    if (!bike)
    {
        return (
            <>
                <span className="loading"></span>
                <br />
                <p>Loading product info...</p>
            </>
        );
    }

    const isRent = bike.provision.name === "rent";

    const handleSubmit = async (e) =>
    {
        e.preventDefault();

        const formData = new FormData();
        formData.append("colour", bike.colour);
        formData.append("brand_id", bike.brand_id);
        formData.append("type_id", bike.type_id);
        formData.append("speed_id", bike.speed_id);
        formData.append("provision_id", bike.provision_id);
        formData.append("visible", bike.visible ? 1 : 0);

        deleteImages.forEach((id) => { formData.append("delete_images[]", id); });

        if (isRent)
        {
            formData.append("pricehour", hourPrice);
            formData.append("priceday", dayPrice);
            formData.append("priceweek", weekPrice);
        }
        else
        {
            formData.append("pricebuy", buyPrice);
        }

        const files = document.getElementById("images").files;
        for (let i = 0; i < files.length; i++)
        {
            formData.append("images[]", files[i]);
        }

        const response = await fetch(`/api/admin/manage/products/update/${id}`,
        {
            method: "PATCH",
            body: formData,
        });

        if (response.ok) { window.location.href = "/admin/manage/products"; }
    };

    return (
        <div id="BikeEdit">
            <form className="edit-bike-info" onSubmit={handleSubmit}>
                <h2 className="section-heading">Edit Product Details</h2>

                <div className="horizontal">
                    <div className="vertical">
                        <label htmlFor="S.K.U.">SKU:</label><br />
                        <input className="read" value={bike.SKU} readOnly/>
                    </div>

                    <div className="vertical">
                        <label htmlFor="visible">Visible to customers?</label><br />
                        <select id="visible" name="visible" className="form-select" value={bike.visible ? "1" : "0"}
                        onChange={(e) => setBike({...bike, visible: Number(e.target.value)})}>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="colour">Colour:</label><br />
                        <input type="text" id="colour" name="colour" value={bike.colour}
                        onChange={(e) => setBike({...bike, colour: e.target.value})}/>
                    </div>
                </div>

                <label htmlFor="images">Images:</label><br />
                <div className="selected-photos">
                    {bike.images.map((image) => (
                        <div className="image-check" key={image.id}>
                            <img src={image.image} alt="bike" width={150} />

                            <div className="del-photo">
                                <input type="checkbox" checked={deleteImages.includes(image.id)} 
                                onChange={() => handleImageToggle(image.id)}/>
                                <i className="fa-regular fa-trash-can"></i>
                            </div>
                        </div>
                    ))}
                </div>

                <input type="file" id="images" name="images" multiple accept="image/*"/>

                <div className="horizontal">
                    <div className="vertical">
                        <label htmlFor="brand_id">Brand:</label><br />
                        <select id="brand_id" value={bike.brand_id} 
                        onChange={(e) => setBike({...bike, brand_id: Number(e.target.value)})} className="form-select">
                            {brands.map(brand => (
                                <option key={brand.id} value={brand.id}>
                                    {brand.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="type_id">Type:</label><br />
                        <select id="type_id" value={bike.type_id} 
                        onChange={(e) => setBike({...bike, type_id: Number(e.target.value)})} className="form-select">
                            {types.map(type => (
                                <option key={type.id} value={type.id}>
                                    {type.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="speed_id">Gears:</label><br />
                        <select id="speed_id" value={bike.speed_id} 
                        onChange={(e) => setBike({...bike, speed_id: Number(e.target.value)})} className="form-select">
                            {speeds.map(speed => (
                                <option key={speed.id} value={speed.id}>
                                    {speed.gears}
                                </option>
                            ))}
                        </select>
                    </div>
                </div>

                <div className="horizontal">
                    <div className="vertical">
                        <label htmlFor="provision_id">Provision:</label><br />
                        <select className="read" value={bike.provision_id} disabled>
                            <option value={bike.provision_id}>
                                {bike.provision.name}
                            </option>
                        </select>
                    </div>
                </div>

                {isRent ? (
                    <div className="vertical">
                        <label htmlFor="serialnum">Serial Number:</label><br />
                        <input className="read" value={bike.serialnum} readOnly/>
                        <br />

                        <label htmlFor="pricehour">Per hour:</label><br />
                        <input type="text" id="pricehour" value={hourPrice} onChange={(e) => setHourPrice(e.target.value)}/>
                        <br />

                        <label htmlFor="priceday">Per day:</label><br />
                        <input type="text" id="priceday" value={dayPrice} onChange={(e) => setDayPrice(e.target.value)} />
                        <br />

                        <label htmlFor="priceweek">Per week:</label><br />
                        <input type="text" id="priceweek" value={weekPrice} onChange={(e) => setWeekPrice(e.target.value)} />
                    </div>
                ):(
                    <div className="vertical">
                        <label htmlFor="pricebuy">Price:</label><br />
                        <input type="text" id="pricebuy" value={buyPrice} onChange={(e) => setBuyPrice(e.target.value)}/>
                    </div>
                )}

                <button type="submit" className="Submit">
                    Update
                </button>

                <button type="button" className="Cancel" onClick={() => navigate(`/admin/manage/products`)}>
                    Cancel
                </button>
            </form>
        </div>
    );
}
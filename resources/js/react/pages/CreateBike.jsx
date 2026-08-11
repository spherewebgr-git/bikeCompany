import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";

export default function CreateBike()
{
    const navigate = useNavigate();

    const [brands, setBrands] = useState([]);
    const [types, setTypes] = useState([]);
    const [speeds, setSpeeds] = useState([]);
    const [provisions, setProvisions] = useState([]);
    const [images, setImages] = useState([]);

    const [bike, setBike] = useState({
        SKU: "",
        colour: "",
        brand_id: "",
        type_id: "",
        speed_id: "",
        provision_id: "",
        quantity: 0,
        serialnum: "",
        visible: true,
    });

    const [hourPrice, setHourPrice] = useState("");
    const [dayPrice, setDayPrice] = useState("");
    const [weekPrice, setWeekPrice] = useState("");
    const [buyPrice, setBuyPrice] = useState("");

    useEffect(() =>
    {
        fetch("/api/admin/manage/products/create")
            .then(res => res.json())
            .then(data => {
                setBrands(data.brands);
                setTypes(data.types);
                setSpeeds(data.speeds);
                setProvisions(data.provisions);
            });
    }, []);

    const updateBike = (field, value) =>
    {
        setBike(prev => ({
            ...prev,
            [field]: value
        }));
    };

    console.log(provisions);
    const selectedProvision = provisions.find(provision => provision.id === Number(bike.provision_id));
    const isRent = selectedProvision?.name === "rent";

    const handleSubmit = async (e) =>
    {
        e.preventDefault();

        const formData = new FormData();
        formData.append("SKU", bike.SKU);
        formData.append("colour", bike.colour);
        formData.append("brand_id", bike.brand_id);
        formData.append("type_id", bike.type_id);
        formData.append("speed_id", bike.speed_id);
        formData.append("provision_id", bike.provision_id);
        formData.append("visible", bike.visible ? 1 : 0);

        if (isRent)
        {
            formData.append("serialnum", bike.serialnum);
            formData.append("pricehour", hourPrice);
            formData.append("priceday", dayPrice);
            formData.append("priceweek", weekPrice);
        }
        else
        {
            formData.append("quant", bike.quantity);
            formData.append("pricebuy", buyPrice);
        }

        images.forEach(file => { formData.append("images[]", file); });

        const response = await fetch(
            "/api/admin/manage/products/add",
            {
                method: "POST",
                body: formData,
            }
        );

        if (response.ok) { navigate("/admin/manage/products"); }
    };


    return (
        <div id="BikeEdit">
            <form className="edit-bike-info" onSubmit={handleSubmit}>
                <h2 className="section-heading">Add a Product to the Database</h2>
                
                <div className="horizontal">
                    <div className="vertical">
                        <label htmlFor="S.K.U.">SKU:</label><br />
                        <input type="text" id="SKU" name="SKU" placeholder="S.K.U." value={bike.sku} 
                        onChange={(e) => updateBike("SKU", e.target.value)}/>
                    </div>

                    <div className="vertical">
                        <label htmlFor="visible">Visible to customers?</label><br />
                        <select id="visible" name="visible" className="form-select"
                        onChange={(e) => updateBike("visible", Number(e.target.value))}>
                            <option value="1">YES</option>
                            <option value="0">NO</option>
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="colour">Colour:</label><br />
                        <input type="text" id="colour" name="colour" placeholder="Colour" value={bike.colour}
                        onChange={(e) => updateBike("colour", e.target.value)}/>
                    </div>
                </div>

                <label htmlFor="images">Images:</label><br />
                <input type="file" id="images" name="images" multiple accept="image/*"
                onChange={(e) => setImages([...e.target.files])}/>

                <div className="horizontal">
                    <div className="vertical">
                        <label htmlFor="brand_id">Brand:</label><br />
                        <select id="brand_id" onChange={(e) => updateBike("brand_id", Number(e.target.value))} className="form-select">
                            {brands.map(brand => (
                                <option key={brand.id} value={brand.id}>
                                    {brand.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="type_id">Type:</label><br />
                        <select id="type_id" onChange={(e) => updateBike("type_id", Number(e.target.value))} className="form-select">
                            {types.map(type => (
                                <option key={type.id} value={type.id}>
                                    {type.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="vertical">
                        <label htmlFor="speed_id">Gears:</label><br />
                        <select id="speed_id" onChange={(e) => updateBike("speed_id", Number(e.target.value))} className="form-select">
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
                        <select name="provision_id" id="provision_id" className="form-select"
                        onChange={(e) => updateBike("provision_id", e.target.value)}>
                            {provisions.map(provision => (
                                <option key={provision.id} value={provision.id}>
                                    {provision.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    {isRent ? (
                        <div className="vertical">
                            <label htmlFor="serialnum">Serial Number:</label><br />
                            <input type="text" id="serialnum" name="serialnum" placeholder="Serial Number" value={bike.serialnum}/>
                        </div>
                    ) : (
                        <div className="vertical">
                            <label htmlFor="quantity">Quantity:</label><br />
                            <input type="number" id="quant" name="quant" value={bike.quantity}/>
                        </div>
                    )}
                </div>

                {isRent ? (
                    <div className="vertical">
                        <label htmlFor="pricehour">Per hour:</label><br />
                        <input type="text" id="pricehour" value={bike.pricehour} placeholder="0.0"
                        onChange={(e) => setHourPrice(e.target.value)}/>
                        <br />

                        <label htmlFor="priceday">Per day:</label><br />
                        <input type="text" id="priceday" value={bike.priceday} placeholder="0.0"
                        onChange={(e) => setDayPrice(e.target.value)} />
                        <br />

                        <label htmlFor="priceweek">Per week:</label><br />
                        <input type="text" id="priceweek" value={bike.priceweek} placeholder="0.0"
                        onChange={(e) => setWeekPrice(e.target.value)} />
                    </div>
                ):(
                    <div className="vertical">                        
                        <label htmlFor="pricebuy">Price:</label><br />
                        <input type="text" id="pricebuy" value={bike.pricebuy} placeholder="0.0"
                        onChange={(e) => setBuyPrice(e.target.value)}/>
                    </div>
                )}

                <button type="submit" className="Submit">
                    Create
                </button>

                <button type="button" className="Cancel" onClick={() => navigate(`/admin/manage/products`)}>
                    Cancel
                </button>
            </form>
        </div>
    );
}
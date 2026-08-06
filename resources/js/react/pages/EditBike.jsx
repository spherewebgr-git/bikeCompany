export default function BikeEdit({ id })
{
    const [bike, setBike] = useState(null);
    const [brands, setBrands] = useState([]);
    const [types, setTypes] = useState([]);
    const [speeds, setSpeeds] = useState([]);
    const [provisions, setProvisions] = useState([]);

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
            });
    }, [id]);


    if (!bike) { return <p>Loading...</p>; }


    return (
        <div id="BikeEdit">
            <form class="edit-bike-info">
                <label>SKU:</label>
                <input value={bike.SKU} readOnly/>

                <label>Colour:</label>
                <input value={bike.colour} onChange={(e) => setBike({...bike, colour: e.target.value})}/>

                <select value={bike.brand_id} onChange={(e) => setBike({...bike, brand_id: Number(e.target.value)})}>
                    {brands.map(brand => (
                        <option key={brand.id} value={brand.id}>
                            {brand.name}
                        </option>
                    ))}
                </select>
            </form>
        </div>
    );
}
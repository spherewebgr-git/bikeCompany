import { useState } from "react";
import "../../../../css/bikefilters.scss";

export default function BikeFilters({ filters, onFilter })
{
    const [selectedFilters, setSelectedFilters] = useState({
        provision: "",
        brand: "",
        type: "",
        gears: "",
    });

    const handleChange = (e) =>
    {
        setSelectedFilters({...selectedFilters, [e.target.name]: e.target.value,});
    };

    const handleSubmit = (e) =>
    {
        e.preventDefault();
        onFilter(selectedFilters);
    };

    return (
        <div id="BikeFilters">
            <form className="filters" onSubmit={handleSubmit}>
                <label htmlFor="provision">Provision</label>
                <select name="provision" id="provision" className="form-select" value={selectedFilters.provision} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.provisions.map((provision) => (
                        <option key={provision.id} value={provision.name}>
                            {provision.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="brand">Brand</label>
                <select name="brand" id="brand" className="form-select" value={selectedFilters.brand} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.brands.map((brand) => (
                        <option key={brand.id} value={brand.name}>
                            {brand.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="type">Type:</label>
                <select name="type" id="type" className="form-select" value={selectedFilters.type} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.types.map((type) => (
                        <option key={type.id} value={type.name}>
                            {type.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="gears">Gears:</label>
                <select name="gears" id="gears" className="form-select" value={selectedFilters.gears} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.speeds.map((speed) =>
                    (
                        <option key={speed.id} value={speed.gears}>
                            {speed.gears}
                        </option>
                    ))}
                </select>

                <input type="submit" className="Filter" value="Filter"/>
            </form>
        </div>
    );
}
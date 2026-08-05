import { useState } from "react";
import "../../../../css/bikefilters.scss";

export default function BikeFilters({ filters })
{
    const [filters, setFilters] = useState
    ({
        provision: initialFilters.provision || "",
        brand: initialFilters.brand || "",
        type: initialFilters.type || "",
        gears: initialFilters.gears || "",
    });

    const handleChange = (e) =>
    {
        setFilters({...filters, [e.target.name]: e.target.value,});
    };

    const handleSubmit = (e) =>
    {
        e.preventDefault();
        onFilter(filters);
    };

    return (
        <div id="BikeFilters">
            <form onSubmit={handleSubmit}>
                <label htmlFor="provision">Provision</label>
                <select name="provision" id="provision" className="form-select" value={filters.provision} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.provisions.map((provision) => (
                        <option key={provision.id} value={provision.name}>
                            {provision.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="brand">Brand</label>
                <select name="brand" id="brand" className="form-select" value={filters.brand} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.brands.map((brand) => (
                        <option key={brand.id} value={brand.name}>
                            {brand.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="type">Type:</label>
                <select name="type" id="type" className="form-select" value={filters.type} onChange={handleChange}>
                    <option value="">Any</option>

                    {filters.types.map((type) => (
                        <option key={type.id} value={type.name}>
                            {type.name}
                        </option>
                    ))}
                </select>

                <label htmlFor="gears">Gears:</label>
                <select name="gears" id="gears" className="form-select" value={filters.gears} onChange={handleChange}>
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
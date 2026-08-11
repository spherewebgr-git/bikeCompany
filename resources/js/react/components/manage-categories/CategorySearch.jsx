import { useState } from "react";

export default function CategorySearch({category, placeholder})
{
    const [search, setSearch] = useState("");

    const handleSearch = async (event) =>
    {
        event.preventDefault();
        const response = await fetch(`/api/manage/categories/search?category=${category}&query=${encodeURIComponent(search)}`);
        const results = await response.json();
    };

    return (
        <form onSubmit={handleSearch}>
            <input type="text" value={search} onChange={(event) => setSearch(event.target.value)} placeholder={placeholder}/>
            <button type="submit">
                <i className="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    );
}
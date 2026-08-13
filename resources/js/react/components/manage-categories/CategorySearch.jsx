import { useState } from "react";

export default function CategorySearch({category, placeholder, onResults})
{
    const [search, setSearch] = useState("");

    const handleSearch = async (e) =>
    {
        e.preventDefault();

        const params = new URLSearchParams({
            category: category,
            query: search,
        });

        const response = await fetch(`/api/admin/manage/categories/search?${params}`);
        if (!response.ok)
        {
            console.error("Search failed");
            return;
        }

        const results = await response.json();
        onResults(results);
    };

    return (
        <form onSubmit={handleSearch}>
            <input type="text" value={search} onChange={(e) => setSearch(e.target.value)} placeholder={placeholder}/>
            <button type="submit">
                <i className="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    );
}
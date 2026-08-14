import { useState } from "react";

export default function OrderSearch({type=null, name, placeholder=null, list=null, onResults})
{
    const [search, setSearch] = useState("");

    const handleSearch = async (e) =>
    {
        e.preventDefault();

        const params = new URLSearchParams();
        if (search) { params.append(name, search); }
        const response = await fetch(`/api/admin/manage/pending-orders/search?${params}`);
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
                {list ? ( 
                    <select name={name} id={name} value={search} onChange={(e) => setSearch(e.target.value)}>
                        <option value=""> Any </option>
                        {list.map(record => (
                            <option key={record.id} value={record.id}>
                                {record.name}
                            </option>
                        ))}
                    </select>
                ) : (
                    <input value={search} onChange={(e) => setSearch(e.target.value)} type={type} id={name} name={name} placeholder={placeholder}/>
                )}

                <button type="submit">
                    <i className="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
    );
}

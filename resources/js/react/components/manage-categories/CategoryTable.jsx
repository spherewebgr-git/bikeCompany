import CategorySearch from "./CategorySearch";
import CategoryItem from "./CategoryItem";

export default function CategoryTable({title})
{
    return (
        <div className="gears table col-sm-6 col-md-3">
            <h5>{title}</h5>

            <CategorySearch category="gears" placeholder="Search gear amount"/>

            <CategoryItem valueID={1} value={3} type="number" category="gear"/>

            <button className="new-category">
                + Add New
            </button>
        </div>
    )
}
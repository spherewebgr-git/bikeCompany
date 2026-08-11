import "../../../css/category-management.scss";
import CategoryTable from '../components/manage-categories/CategoryTable';

export default function ManageCategories()
{
    return (
        <div id="StaffCategories">
            <div class="container">
                <h1>Edit Categories</h1>
                <div class="row">
                    <CategoryTable title="Bike Speeds"/>
                </div>
            </div>
        </div>
    );
}
export default function BikeTable({ bikes })
{
    return (
        <div id="data-table-simple_wrapper" className="dataTables_wrapper">
            <table id="data-table-simple" className="display dataTable dtr-inline" role="grid">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Image</th>
                        <th>Provision</th>
                        <th>Model</th>
                        <th>Gears</th>
                        <th>Colour</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                {bikes.map(bike => (
                    <tr key={bike.id}>

                        <td>{bike.SKU}</td>

                        <td>
                            {bike.images?.length > 0 && (
                                <img className="bikephoto" src={ bike.images?.[0]?.image } alt=""/>
                            )}
                        </td>

                        <td>{bike.provision?.name}</td>

                        <td>
                            {bike.brand?.name}: {bike.type?.name}
                        </td>

                        <td>{bike.speed?.gears}</td>

                        <td>{bike.colour}</td>

                        <td>{bike.quantity}</td>

                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}
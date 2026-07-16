<div class="bike-table"></div>
    <table>
        <tr>
            <th>Image</th>
            <th>Provision</th>
            <th>Brand</th>
            <th>Type</th>
            <th>Gears</th>
            <th>Colour</th>
            <th>Actions</th>
        </tr>
        @foreach ($bikes as $bike)
            <tr>
                <td>
                    <img class="bikephoto" src="{{ $bike->image_path }}"/>
                </td>
                <td>
                    {{ $bike->provision->name }}
                </td>
                <td>
                    {{ $bike->brand->name }}
                </td>
                <td>
                    {{ $bike->type->name }}
                </td>
                <td>
                    {{ $bike->speed->gears }}
                </td>
                <td>
                    {{ $bike->colour }}
                </td>
                <td>
                    <div class="bikeactions">
                        <a href="{{ route('bike.view',[$bike->id]) }}" class="view">View</a>
                        <a href="{{ route('bike.edit',[$bike->id]) }}" class="edit">Edit</a>
                        <a href="{{ route('bike.delete',[$bike->id]) }}" class="delete">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>
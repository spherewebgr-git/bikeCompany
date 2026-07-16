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
                    <img src="{{ $bike->image_path }}"/>
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
                    <a href="" class="view">View</a>
                    <a href="" class="edit">Edit</a>
                    <a href="{{ route('bike.delete',[$bike->id]) }}" class="delete">Delete</a>
                </td>
            </tr>
        @endforeach
    </table>
</div>
<div class="bike-table"></div>
    <table>
        <tr>
            <th>SKU</th>
            <th>Image</th>
            <th>Provision</th>
            <th>Brand</th>
            <th>Type</th>
            <th>Gears</th>
            <th>Colour</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        @foreach ($bikes as $bike)
            <tr>
                <td>
                    {{ $bike->SKU }}
                </td>
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
                        <a href="#" onclick="showQuantityForm({{ $bike->id }}); return false;" class="quant">
                            {{ $bike->quantity }}
                            <i class="fa-regular fa-pen-to-square" style="color: rgb(0, 0, 0);"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('bike.update', [$bike]) }}" 
                    class="edit-quantity" id="quantity-form-{{ $bike->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="number" id="quantity" name="quantity" value="{{ $bike->quantity }}"><br>
                        <button>
                            <input class="update" type="submit" value="Update">
                        </button>
                        <button class="cancel" type="button" onclick="document.getElementById('quantity-form-{{ $bike->id }}').style.display='none'">
                            Cancel
                        </button>
                    </form>
                </td>
                <td>
                    <div class="bikeactions">
                        <!-- <a href="{{ route('bike.view',[$bike->id]) }}" class="view">View</a> -->
                        <a href="{{ route('bike.edit',[$bike->id]) }}" class="edit">Edit</a>
                        <a href="{{ route('bike.delete',[$bike->id]) }}" class="delete">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>
<script>
    function showQuantityForm(id)
    {
        document.getElementById('quantity-form-' + id).style.display = 'block';
    }
</script>
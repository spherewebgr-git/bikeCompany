{{-- <div class="bike-table"> --}}
<div id="data-table-simple_wrapper" class="dataTables_wrapper">
    <table id="data-table-simple" class="display dataTable dtr-inline" role="grid">
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
        @foreach ($bikes as $bike)
            <tr>
                <td class="bike-SKU">
                    {{ $bike->SKU }}
                </td>
                <td>
                    <img class="bikephoto" src="{{ $bike->image_path }}"/>
                </td>
                <td class="bike-provision">
                    {{ $bike->provision->name }}
                </td>
                <td class="bike-model">
                    {{ $bike->brand->name }}: {{ $bike->type->name }}
                </td>
                <td class="bike-gears">
                    {{ $bike->speed->gears }}
                </td>
                <td>
                    {{ $bike->colour }}
                </td>
                <td>
                    <div class="bikeactions">
                        <a href="#" onclick="showQuantityForm({{ $bike->id }}); return false;" class="quant">
                            {{ $bike->quantity }}
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>
                    </div>

                    <form method="POST" action="{{ route('bike.update', [$bike]) }}" 
                    class="edit-quantity" id="quantity-form-{{ $bike->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <input type="number" id="quantity" name="quantity" value="{{ $bike->quantity }}"><br>
                        <button class="update" type="submit">Update</button>
                        <button class="cancel" type="button" onclick="document.getElementById('quantity-form-{{ $bike->id }}').style.display='none'">
                            Cancel
                        </button>
                    </form>
                </td>
                <td>
                    <div class="bikeactions">
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
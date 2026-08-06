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
                    <img class="bikephoto" src="{{ asset($bike->images->first()->image) }}"/>
                </td>
                <td class="bike-provision">
                    {{ $bike->provision->name }}
                </td>
                <td class="bike-model">
                    {{ $bike?->brand?->name ?? 'N/A' }}: {{ $bike->type->name }}
                </td>
                <td class="bike-gears">
                    {{ $bike->speed->gears }}
                </td>
                <td>
                    {{ $bike->colour }}
                </td>
                <td>
                    @if ($bike->quantity)
                        <div class="bikeactions">
                            <a href="#" onclick="showQuantityForm({{ $bike->id }}); return false;" class="quant">
                                {{ $bike->quantity }}
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        </div>

                        <form method="POST" action="{{ route('bike.quantity', [$bike]) }}"
                        class="edit-quantity" id="quantity-form-{{ $bike->id }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="number" id="quantity" name="quantity" value="{{ $bike->quantity }}"><br>
                            <button class="update" type="submit">Update</button>
                            <button class="cancel" type="button" onclick="document.getElementById('quantity-form-{{ $bike->id }}').style.display='none'">
                                Cancel
                            </button>
                        </form>
                    @endif
                </td>
                <td>
                    <div class="bikeactions">
                        <a href="{{ route('bike.edit',[$bike->id]) }}" class="edit">Edit</a>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>
<script>
    function showQuantityForm(id)
    { // Hide any other open forms first
        document.querySelectorAll('.edit-quantity').forEach(form => { form.style.display = 'none'; });
        document.getElementById('quantity-form-' + id).style.display = 'block';
    }

    document.addEventListener('click', function (event)
    {
        document.querySelectorAll('.edit-quantity').forEach(form =>
        {
            const trigger = form.previousElementSibling; // .bikeactions div

            if (form.style.display === 'block'
                && !form.contains(event.target) // Checks if the click was inside the form
                && !trigger.contains(event.target)) // Checks if the click was on the button that opens the form
            { form.style.display = 'none'; }
        });
    });
</script>

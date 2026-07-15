<div class="bike-card">

    <div class="bike-image">

        <img src="{{ $bike->image_path }}" alt="">

    </div>

    <div class="bike-info">

        <h3>{{ $bike->brand->name }}</h3>

        <p>{{ $bike->type->name }}</p>

        <p>{{ $bike->colour }}</p>

        <p>{{ $bike->speed->gears }}</p>

        <p class="price">
            @foreach($bike->prices as $price)
                {{ $price->price }}<br>
            @endforeach
        </p>

        <span class="provision">
            {{ $bike->provision->name }}
        </span>

    </div>

    <div class="bike-actions">

        <a href="">Details</a>

        <a href="">Edit</a>

        <form>

            <button>Delete</button>

        </form>

    </div>

</div>

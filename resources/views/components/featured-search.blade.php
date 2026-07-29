<div id="FeaturedSearch">
    <form method="GET" action="{{ route('featured-bikes.search') }}">
        <input type="text" id="featsearch" name="featsearch" placeholder="Search Brand, Type, Colour, or Gears">
        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
</div>
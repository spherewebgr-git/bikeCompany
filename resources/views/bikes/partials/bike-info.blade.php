
<div class="bike-single-info">
    <h3 class="title-text">{{ $bike?->brand?->name ?? 'N/A' }}</h3>

    <ul class="bike-specs">
        <li><i class="fa fa-bicycle"></i> {{ __('Type') }}: {{ $bike->type->name }}</li>
        <li><i class="fa fa-cog"></i> {{ __('Gears') }}: {{ $bike->speed->gears }}</li>
        <li><i class="fa fa-paint-brush "></i> {{ __('Colour') }}: {{ $bike->colour }}</li>
    </ul>

    {{ $slot ?? '' }}
</div>

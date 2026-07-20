<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.rental') }}">{{ __('Rental Bikes') }}</a>
            </nav>
        </div>
    </div>

    <div class="page-body">
        <div class="container">

            <h2 class="section-heading">Available Rentals</h2>

            <div class="blogs-list">

                <div class="bike-filters" x-data="{ open: {{ request()->hasAny(['brand','type','speed','color','min_price','max_price','sort']) ? 'true' : 'false' }} }">

                    <button type="button"
                            class="filter-toggle"
                            @click="open = !open">
                        <span>Filters</span>
                        <span x-text="open ? '▲' : '▼'"></span>
                    </button>

                    <div class="bike-filters__content"
                         x-show="open"
                         x-transition>

                        <form action="{{ route('bikes.rental') }}" method="GET" class="bike-filters__form">

                            <div class="select-filters">
                                <select name="brand">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected(request('brand') == $brand->id)>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="type">
                                    <option value="">All Types</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" @selected(request('type') == $type->id)>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="speed">
                                    <option value="">All Speeds</option>
                                    @foreach($speeds as $speed)
                                        <option value="{{ $speed->id }}" @selected(request('speed') == $speed->id)>
                                            {{ $speed->gears }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="color">
                                    <option value="">All Colors</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color }}" @selected(request('color') == $color)>
                                            {{ $color }}
                                        </option>
                                    @endforeach
                                </select>

                                <a href="{{ route('bikes.rental') }}" class="btn btn-fill">
                                    Clear All Filters
                                </a>
                                <button type="submit" class="btn btn-fill">
                                    Filter
                                </button>
                            </div>

                        </form>
                    </div>

                </div>

                <div class="row">
                    @foreach($bikes as $bike)
                        @include('bikes.partials.card')
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    {{ $bikes->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>

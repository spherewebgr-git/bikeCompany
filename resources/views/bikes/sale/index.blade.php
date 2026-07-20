<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.sale') }}">{{ __('Buy Bikes') }}</a>
            </nav>
        </div>
    </div>

    <div class="page-body">
        <div class="container">



            <h2 class="section-heading">Our Bikes for Sale</h2>

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

                        <form action="{{ route('bikes.sale') }}" method="GET" class="bike-filters__form">


                            <div class="sort-filter">
                                Sort by:
                                <select name="sort">
                                    <option value="">Sort By</option>

                                    <option value="price_asc" @selected(request('sort') == 'price_asc')>
                                        Price: Low to High
                                    </option>

                                    <option value="price_desc" @selected(request('sort') == 'price_desc')>
                                        Price: High to Low
                                    </option>
                                </select>
                            </div>


                            <div class="price-filter">
                                <label>Price Range</label>

                                <div id="price-slider"></div>

                                <div class="price-values">
                                    <span>€<span id="min-price"></span></span>
                                    <span>€<span id="max-price"></span></span>
                                </div>

                                <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', 0) }}">
                                <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', 5000) }}">
                            </div>


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

                                <a href="{{ route('bikes.sale') }}" class="btn btn-fill">
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

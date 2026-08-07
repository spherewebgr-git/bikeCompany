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

            <div class="blogs-list" x-data="{
                gridView: localStorage.getItem('bikesView') === 'grid',
                setView(view) {
                    this.gridView = view === 'grid';
                    localStorage.setItem('bikesView', view);
                }
            }">

                <div class="bike-filters" x-data="{ open: {{ request()->hasAny(['search','brand','type','speed','color','min_price','max_price','sort']) ? 'true' : 'false' }} }">

                    <form action="{{ route('bikes.rental') }}"
                          method="GET"
                          class="bike-search-form"
                          x-data="{
          query: '{{ request('search') }}',
          suggestions: [],
          open: false,
          async fetchSuggestions() {
              if (this.query.length < 2) {
                  this.suggestions = [];
                  this.open = false;
                  return;
              }
              const res = await fetch(`{{ route('bikes.rental.suggestions') }}?q=${encodeURIComponent(this.query)}`);
              this.suggestions = await res.json();
              this.open = this.suggestions.length > 0;
          },
          select(value) {
              this.query = value;
              this.open = false;
              $refs.searchInput.focus();
          }
      }"
                          @click.outside="open = false">

                        <div class="bike-search-form__field">
                            <input type="text"
                                   name="search"
                                   x-ref="searchInput"
                                   x-model="query"
                                   @input.debounce.300ms="fetchSuggestions()"
                                   @focus="if (suggestions.length) open = true"
                                   autocomplete="off"
                                   placeholder="Search brand, type, speed, colour...">

                            <ul class="bike-search-suggestions" x-show="open" x-transition>
                                <template x-for="s in suggestions" :key="s">
                                    <li @click="select(s)" x-text="s"></li>
                                </template>
                            </ul>
                        </div>

                        <button type="submit">Search</button>

                    </form>

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



                @if($bikes->count() == 0)
                    <div class="no-bikes-message">
                        <h4>There are no bikes matching your search.</h4>
                    </div>

                @else
                    <div class="view-toggle">
                        <button type="button" :class="{ 'is-active': !gridView }" @click="setView('list')">
                            <i class="fa fa-bars"></i> List
                        </button>
                        <button type="button" :class="{ 'is-active': gridView }" @click="setView('grid')">
                            <i class="fa fa-th-large"></i> Grid
                        </button>
                    </div>

                    <div class="row" :class="{ 'row--grid': gridView }">
                        @foreach($bikes as $bike)
                            @include('bikes.partials.card')
                        @endforeach
                    </div>

                    <div class="pagination-wrapper">
                        {{ $bikes->links() }}
                    </div>
                @endif

                </div>

        </div>
    </div>

</x-app-layout>

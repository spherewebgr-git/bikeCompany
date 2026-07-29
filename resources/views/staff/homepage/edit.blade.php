{{-- resources/views/admin/featured-bikes/edit.blade.php --}}
@extends('layouts.admin')

@section('content')

    <div class="page-body">
        <div class="container" style="max-width:1200px; margin:0 auto; padding:40px 0;">

            <h2 class="checkout-title">Featured Bikes (max 6)</h2>

            @if(session('status'))
                <div class="card-panel green lighten-4 green-text text-darken-4">
                    {{ session('status') }}
                </div>
            @endif

            @error('bike_ids')
            <div class="card-panel red lighten-4 red-text text-darken-4">
                {{ $message }}
            </div>
            @enderror

            <form action="{{ route('featured-bikes.update') }}" method="POST" id="featured-form">
                @csrf
                @method('PUT')

                <input type="hidden" name="bike_ids" id="bike-ids-input">

                <div class="row">

                    {{-- Available Bikes --}}
                    <div class="col s12 m6">
                        <h5>Available Bikes</h5>

                        <div class="filter-search">
                            <form method="GET" action="{{ route('featured-bikes.edit') }}">
                                <input
                                    type="text"
                                    id="SKU"
                                    name="SKU"
                                    placeholder="Search SKUs"
                                    value="{{ request('SKU') }}"
                                >

                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>

                            @include('components.bikefilters', [
                                'action' => route('featured-bikes.edit')
                            ])
                        </div>

                        <ul id="available-list" class="bike-sort-list">
                            @foreach($availableBikes as $bike)
                                <li class="bike-sort-item" data-id="{{ $bike->id }}">
                                    <img src="{{ $bike->image_path }}" alt="">
                                    <span>{{ $bike->brand->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Featured Bikes --}}
                    <div class="col s12 m6">
                        <h5>
                            Featured (max 6) —
                            <span id="selected-count">
                            {{ $featuredBikes->count() }}
                        </span>/6
                        </h5>

                        <ul id="selected-list" class="bike-sort-list bike-sort-list--target">
                            @foreach($featuredBikes as $bike)
                                <li class="bike-sort-item" data-id="{{ $bike->id }}">
                                    <img src="{{ $bike->image_path }}" alt="">
                                    <span>{{ $bike->brand->name }}</span>
                                </li>
                                <button type="button" class="remove-bike-btn" draggable="false" aria-label="Αφαίρεση" title="Αφαίρεση">
                                    <i class="fa-solid fa-xmark" draggable="false"></i>
                                </button>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Save --}}
                    <div class="col s12 center-align" style="margin-top:30px;">
                        <button type="submit" class="btn btn-fill btn-md">
                            Save
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-featured-bikes.js')
    @endpush

@endsection

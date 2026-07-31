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

            @include('components.featured-search')

            <form action="{{ route('featured-bikes.update') }}" method="POST" id="featured-form">
                @csrf
                @method('PUT')

                <input type="hidden" name="bike_ids" id="bike-ids-input">

                <div class="row">

                    {{-- Available Bikes --}}
                    <div class="col s12 m12">
                        <h5>Available Bikes</h5>
                    </div>

                    <div class="col s12 m6">
                        <ul id="available-list" class="bike-sort-list">
                            @if ($availableBikes->isEmpty())
                                <p class="no-bikes-message" style="text-align: center; margin-top: 80px;">
                                    No bikes matched your search
                                </p>

                            @endif
                                @if(!$availableBikes->isEmpty())
                                    @foreach($availableBikes as $bike)
                                        <li class="bike-sort-item" data-id="{{ $bike->id }}">
                                            <img src="{{ $bike->image_path }}" alt="">
                                            <span>{{ $bike?->brand?->name ?? 'N/A'}} | {{$bike->type->name}} | {{$bike->colour}} | {{$bike->speed->gears}}</span>
                                        </li>
                                    @endforeach
                                @endif
                        </ul>
                    </div>


                    {{-- Featured Bikes --}}
                    <div class="col s12 m6 right-section">
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
                                    <span>{{ $bike?->brand?->name ?? 'N/A'}} | {{$bike->type->name}} | {{$bike->colour}} | {{$bike->speed->gears}}</span>

                                </li>

                            @endforeach
                        </ul>
                        {{-- Save --}}
                        <div class="col s12 save-btn" style="margin-top:30px;">
                            <button type="submit" class="btn btn-fill btn-md">
                                Save
                            </button>
                        </div>
                    </div>


                </div>
            </form>

        </div>
    </div>

    @push('scripts')
        @vite('resources/js/admin-featured-bikes.js')
    @endpush

@endsection

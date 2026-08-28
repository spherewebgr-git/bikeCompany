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
                            @foreach($availableBikes as $bike)
                                <li class="bike-sort-item" data-id="{{ $bike->id }}">
                                    <img src="{{ asset($bike->images->first()->image) }}" alt="">
                                    <span>{{ $bike->brand->name}} | {{$bike->type->name}} | {{$bike->colour}} | {{$bike->speed->gears}}</span>
                                </li>
                            @endforeach
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
                                    <img src="{{ asset($bike->images->first()->image) }}" alt="">
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

    <div id="PromoBannerEdit">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
              action="{{ $banner
                  ? route('dashboard.promo-banner.update', $banner)
                  : route('dashboard.promo-banner.store') }}"
              enctype="multipart/form-data">

            @csrf

            @if($banner)
                @method('PUT')
            @endif

            <label for="title">Title:</label><br>

            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $banner?->title) }}"
            ><br>

            <label for="description">Description:</label><br>

            <textarea
                id="description"
                name="description"
            >{{ old('description', $banner?->description) }}</textarea><br>

            <label for="image">Image:</label><br>

            @if($banner?->image)
                <img
                    src="{{ asset('storage/' . $banner->image) }}"
                    width="200"
                ><br>
            @endif

            <input
                type="file"
                id="image"
                name="image"
                accept="image/*"
            ><br>

            <label for="banner_color">Banner Color:</label><br>

            <input
                type="color"
                id="banner_color"
                name="banner_color"
                value="{{ old('banner_color', $banner?->banner_color ?? '#1b5780') }}"
            ><br>

            <label for="content_color">Content Color:</label><br>

            <input
                type="color"
                id="content_color"
                name="content_color"
                value="{{ old('content_color', $banner?->content_color ?? '#5bb2e1') }}"
            ><br>

            <label for="button_text">Button Text:</label><br>

            <input
                type="text"
                id="button_text"
                name="button_text"
                value="{{ old('button_text', $banner?->button_text ?? 'Check It Out') }}"
            ><br>

            <label for="button_link">Button Link:</label><br>

            <input
                type="text"
                id="button_link"
                name="button_link"
                value="{{ old('button_link', $banner?->button_link ?? '/bikes/sale?discount=1') }}"
            ><br>

            <input
                type="submit"
                class="Submit"
                value="Save"
            >

        </form>
    </div>

    @push('scripts')
        @vite('resources/js/admin-featured-bikes.js')
    @endpush

@endsection


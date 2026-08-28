@extends('layouts.admin')
@section('content')
    <div id="PromoBannerEdit">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ $banner ? route('dashboard.promo-banner.update', $banner) : route('dashboard.promo-banner.store') }}" enctype="multipart/form-data">
            @csrf
            @if($banner) @method('PUT') @endif

            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="{{ old('title', $banner?->title) }}"><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description">{{ old('description', $banner?->description) }}</textarea><br>

            <label for="image">Image:</label><br>
            @if($banner?->image)
                <img src="{{ asset('storage/' . $banner->image) }}" width="200"><br>
            @endif
            <input type="file" id="image" name="image" accept="image/*"><br>

            <label for="banner_color">Banner Color:</label><br>
            <input type="color" id="banner_color" name="banner_color" value="{{ old('banner_color', $banner?->banner_color ?? '#1b5780') }}"><br>

            <label for="content_color">Content Color:</label><br>
            <input type="color" id="content_color" name="content_color" value="{{ old('content_color', $banner?->content_color ?? '#5bb2e1') }}"><br>

            <label for="button_text">Button Text:</label><br>
            <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $banner?->button_text ?? 'Check It Out') }}"><br>

            <label for="button_link">Button Link:</label><br>
            <input type="text" id="button_link" name="button_link" value="{{ old('button_link', $banner?->button_link ?? '/bikes/sale?discount=1') }}"><br>

            <label for="sort_order">Sort Order:</label><br>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}"><br>

            <label for="is_active">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }}>
                Active
            </label><br>

            <input type="submit" class="Submit" value="Save">
        </form>
    </div>
@endsection

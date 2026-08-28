@extends('layouts.admin')
@section('content')
    <div id="PromoBannerIndex">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('dashboard.promo-banner.create') }}" class="btn">+ New Banner</a>

        <table>
            <thead>
            <tr>
                <th>Order</th>
                <th>Title</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->sort_order }}</td>
                    <td>{{ $banner->title }}</td>
                    <td>{{ $banner->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('dashboard.promo-banner.edit', $banner) }}">Edit</a>
                        <form method="POST" action="{{ route('dashboard.promo-banner.destroy', $banner) }}" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this banner?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

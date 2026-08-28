@extends('layouts.admin')

@section('content')

    <div id="reviews-react-root"></div>

@endsection

@push('scripts')
    @vite('resources/js/reviews.jsx')
@endpush

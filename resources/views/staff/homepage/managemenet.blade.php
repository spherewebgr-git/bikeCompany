@extends('layouts.admin')
@section('content')
    <div class="container" id="HomepageManagement">
        <h2 class="section-heading">Featured Bikes</h2>

        <div class="featured-bikes">
            @include('staff.homepage.edit')
        </div>
    </div>
@endsection

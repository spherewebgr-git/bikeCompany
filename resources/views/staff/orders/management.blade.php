@extends('layouts.admin')
@section('content')
    <div class="container" id="OrderManagement">
        <h2 class="section-heading">Pending Orders</h2>

        <div class="orders">
            @include('staff.orders.table')
        </div>
    </div>
@endsection
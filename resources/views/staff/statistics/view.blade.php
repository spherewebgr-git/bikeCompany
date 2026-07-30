@extends('layouts.admin')
@section('content')
    <div id="BusinessStatistics">
        <div class="container">
            <h2>Sales Information</h2>
            <div class="row">
                <div class="statistic-card gradient-45deg-light-blue-cyan white-text">
                    <div class="category">
                        <i class="material-icons background-round mt-5">add_shopping_cart</i>
                        <p>Orders</p>
                    </div>
                    <div class="info">
                        <h5 class="mb-0 white-text">{{ $neworders }}</h5>
                        <p class="no-margin">New</p>
                        <p>{{ $totalorders }}</p>
                        </div>
                </div>
                    <div class="statistic-card gradient-45deg-red-pink white-text">
                        <div class="category">
                            <i class="material-icons background-round mt-5">perm_identity</i>
                            <p>Clients</p>
                        </div>
                        <div class="info">
                            <h5 class="mb-0 white-text">{{ $newusers }}</h5>
                            <p class="no-margin">New</p>
                            <p>{{ $totalusers }}</p>
                        </div>
                    </div>
                    
                    <div class="statistic-card gradient-45deg-amber-amber white-text">
                        <div class="category">
                            <i class="material-icons background-round mt-5">timeline</i>
                            <p>Rented</p>
                        </div>
                        <div class="info">
                            <h5 class="mb-0 white-text">{{ $rents * 100 / $totalorders }}%</h5>
                            <p class="no-margin">Of Orders</p>
                            <p>{{ $rents }}</p>
                        </div>
                    </div>

                    <div class="statistic-card gradient-45deg-purple-violet white-text">
                        <div class="category">
                            <i class="material-icons background-round mt-5">timeline</i>
                            <p>Purchases</p>
                        </div>
                        <div class="info">
                            <h5 class="mb-0 white-text">{{ $purchases * 100 / $totalorders }}%</h5>
                            <p class="no-margin">Of Orders</p>
                            <p>{{ $purchases }}</p>
                        </div>
                    </div>
                    
                    <div class="statistic-card gradient-45deg-green-teal white-text">
                        <div class="category">
                            <i class="material-icons background-round mt-5">attach_money</i>
                            <p>Profit</p>
                        </div>
                        <div class="info">
                            <h5 class="mb-0 white-text">{{ $newprofit }}</h5>
                            <p class="no-margin">Today</p>
                            <p>{{ $totalprofit }} €</p>
                        </div>
                    </div>

                    <div id="location-stats">
                        <div class="row">
                            <div class="loc-card gradient-45deg-deep-purple-purple">
                                <h6><b>{{ $locsales[0]['location'] }}</b></h6>
                                <h6>{{ $locsales[0]['sales'] }} Total Sales</h6><br>
                                <p>Total Profit: </p>
                                <h5>{{ $locsales[0]['profit'] }} €</h5>
                            </div>

                            <div class="loc-card gradient-45deg-light-green-amber">
                                <h6><b>{{ $locsales[1]['location'] }}</b></h6>
                                <h6>{{ $locsales[1]['sales'] }} Total Sales</h6><br>
                                <p>Total Profit: </p>
                                <h5>{{ $locsales[1]['profit'] }} €</h5>
                            </div>

                            <div class="loc-card gradient-45deg-orange-deep-orange">
                                <h6><b>{{ $locsales[2]['location'] }}</b></h6>
                                <h6>{{ $locsales[2]['sales'] }} Total Sales</h6><br>
                                <p>Total Profit: </p>
                                <h5>{{ $locsales[2]['profit'] }} €</h5>
                            </div>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection
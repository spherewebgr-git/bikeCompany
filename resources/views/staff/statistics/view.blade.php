@extends('layouts.admin')
@section('content')
    <div id="BusinessStatistics">
        <div class="container">
            <h2>Sales Information</h2>
            @include('components.featured-search')
            <div class="row">
                <div id="card-stats" class="pt-0">
                    <div class="row">
                        <div class="col s3 card gradient-45deg-light-blue-cyan white-text">
                            <div class="padding-4">
                                <div class="row">
                                    <div class="col s7 m7">
                                        <i class="material-icons background-round mt-5">add_shopping_cart</i>
                                        <p>Orders</p>
                                    </div>
                                    <div class="col s5 m5 right-align">
                                        <h5 class="mb-0 white-text">690</h5>
                                        <p class="no-margin">New</p>
                                        <p>6,00,00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="col s3 card gradient-45deg-red-pink white-text">
                                <div class="padding-4">
                                    <div class="row">
                                        <div class="col s7 m7">
                                            <i class="material-icons background-round mt-5">perm_identity</i>
                                            <p>Clients</p>
                                        </div>
                                        <div class="col s5 m5 right-align">
                                            <h5 class="mb-0 white-text">1885</h5>
                                            <p class="no-margin">New</p>
                                            <p>1,12,900</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col s3 card gradient-45deg-amber-amber white-text">
                                <div class="padding-4">
                                    <div class="row">
                                        <div class="col s7 m7">
                                            <i class="material-icons background-round mt-5">timeline</i>
                                            <p>Sales</p>
                                        </div>
                                        <div class="col s5 m5 right-align">
                                            <h5 class="mb-0 white-text">80%</h5>
                                            <p class="no-margin">Growth</p>
                                            <p>3,42,230</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col s3 card gradient-45deg-green-teal white-text">
                                <div class="padding-4">
                                    <div class="row">
                                        <div class="col s7 m7">
                                            <i class="material-icons background-round mt-5">attach_money</i>
                                            <p>Profit</p>
                                        </div>
                                        <div class="col s5 m5 right-align">
                                            <h5 class="mb-0 white-text">$890</h5>
                                            <p class="no-margin">Today</p>
                                            <p>$25,000</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                </div>
                
                <div class="all-statistics">

                </div>
            </div>
        </div>
    </div>
@endsection
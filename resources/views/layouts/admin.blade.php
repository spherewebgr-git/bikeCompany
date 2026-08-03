<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" sizes="96x158" href="{{Vite::asset('resources/images/bikeco-favicon.png')}}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet/dist/leaflet.css"
    />

    @viteReactRefresh
    @vite(['resources/css/admin.scss', 'resources/js/admin.js'])

</head>

<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 2-columns" data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">
    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-light">
                <div class="nav-wrapper">

                    <div class="header-search-wrapper hide-on-med-and-down">
                        <i class="material-icons">search</i>
                        <input class="header-search-input z-depth-2" type="text" name="Search" placeholder="Search Database" data-search="template-list">
                        <ul class="search-list collection display-none"></ul>
                    </div>

                    <ul class="navbar-list right">
                        <li class="dropdown-language">
                            <a class="waves-effect waves-block waves-light translation-button" href="#" data-target="translation-dropdown">
                                <span class="flag-icon flag-icon-gb"></span>
                            </a>
                        </li>
                        <li class="hide-on-med-and-down">
                            <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);">
                                <i class="material-icons">settings_overscan</i>
                            </a>
                        </li>
                        <li class="hide-on-large-only search-input-wrapper">
                            <a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);">
                            <i class="material-icons">search</i>
                        </a>
                        </li>
                        <li>
                            <a class="waves-effect waves-block waves-light sidenav-trigger" href="#" data-target="slide-out-right">
                                <i class="material-icons">format_indent_increase</i>
                            </a>
                        </li>
                    </ul>
                </div>

                <nav class="display-none search-sm">
                    <div class="nav-wrapper">
                        <form id="navbarForm">
                            <div class="input-field search-input-sm">
                                <input class="search-box-sm mb-0" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">
                                <label class="label-icon" for="search">
                                    <i class="material-icons search-sm-icon">search</i>
                                </label>
                                <i class="material-icons search-sm-close">close</i>
                                <ul class="search-list collection search-list-sm display-none"></ul>
                            </div>
                        </form>
                    </div>
                </nav>
            </nav>
        </div>
    </header>

    <aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-dark sidenav-active-rounded">
        <div class="brand-sidebar">
            <h1 class="logo-wrapper">
                <a class="brand-logo darken-1" href="{{ route('home') }}">
                    <img class="hide-on-med-and-down " src="http://[::1]:5173/resources/images/bikeco-light-logo.png" alt="logo"/>
                    <img class="show-on-medium-and-down hide-on-med-and-up" src="http://[::1]:5173/resources/images/bikeco-light-logo.png" alt="logo"/>
                </a>
            </h1>
        </div>

        <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="accordion">
{{-- MANAGEMENT PAGES --}}
            <li class="navigation-header">
                <a class="navigation-header-text">Management</a>
                <i class="navigation-header-icon material-icons">more_horiz</i>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.bikes') }}">
                    <i class="fa-solid fa-bicycle" style="color: #fff;"></i>
                    <span class="menu-title">Bike Management</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('blocked-dates.index') }}">
                    <i class="fa-solid fa-calendar" style="color: #fff;"></i>
                    <span class="menu-title">Calendar Blocked Dates</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.categories') }}">
                    <i class="fa-solid fa-table" style="color: #fff;"></i>
                    <span class="menu-title">Categories Management</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('featured-bikes.edit') }}">
                    <i class="fa-solid fa-home" style="color: #fff;"></i>
                    <span class="menu-title">Homepage Management</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.orders') }}">
                    <i class="fa-solid fa-store" style="color: #fff;"></i>
                    <span class="menu-title">Order Management</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('staff.users.index') }}">
                    <i class="fa-solid fa-users-gear" style="color: #fff;"></i>
                    <span class="menu-title">User Management</span>
                </a>
            </li>

{{-- TRACKING PAGES --}}
            <li class="navigation-header">
                <a class="navigation-header-text">Tracking</a>
                <i class="navigation-header-icon material-icons">more_horiz</i>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.activerentals') }}">
                    <i class="fa-regular fa-alarm-clock" style="color: #fff;"></i>
                    <span class="menu-title">Active Rentals</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.orderhistory') }}">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #fff;"></i>
                    <span class="menu-title">Past Orders</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.statistics') }}">
                    <i class="fa-solid fa-chart-line" style="color: #fff;"></i>
                    <span class="menu-title">Statistics</span>
                </a>
            </li>
        </ul>

        <div class="navigation-background"></div>

        <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out">
            <i class="material-icons">menu</i>
        </a>
    </aside>

    <div id="main">
        <div class="row">
            <div class="col s12">
                <div class="container">
                    <div class="section">

                        @yield('content')

                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="page-footer footer footer-static footer-light navbar-border navbar-shadow">
        <div class="footer-copyright">
            <div class="container">
                <span>&copy; 2020 <a href="http://themeforest.net/user/pixinvent/portfolio?ref=pixinvent" target="_blank">PIXINVENT</a> All rights reserved.</span>
                <span class="right hide-on-small-only">Design and Developed by <a href="https://pixinvent.com/">PIXINVENT</a></span>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>

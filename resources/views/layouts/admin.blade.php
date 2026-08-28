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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>

    @viteReactRefresh
    @vite(['resources/css/admin.scss', 'resources/js/admin.js', 'resources/js/react/App.jsx'])

</head>

<body class="vertical-layout page-header-light vertical-menu-collapsible vertical-dark-menu preload-transitions 2-columns" data-open="click" data-menu="vertical-dark-menu" data-col="2-columns">

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
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.promo-banner.index') }}">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="menu-title">Promo Banner</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.orders') }}">
                    <i class="fa-solid fa-store" style="color: #fff;"></i>
                    <span class="menu-title">Order Management</span>
                </a>
            </li>

            <li class="bold">
                <a class="waves-effect waves-cyan" href="{{ route('dashboard.management.reviews') }}">
                    <i class="fa-solid fa-star-half-stroke" style="color: #fff;"></i>
                    <span class="menu-title">Reviews Management</span>
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

    @stack('scripts')

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidenav = document.getElementById('slide-out');
            const triggers = document.querySelectorAll('.sidenav-trigger');

            if (!sidenav || !triggers.length) return;

            function openMenu() {
                sidenav.classList.add('mobile-open');
            }

            function closeMenu() {
                sidenav.classList.remove('mobile-open');
            }

            triggers.forEach(trigger => {
                trigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (sidenav.classList.contains('mobile-open')) {
                        closeMenu();
                    } else {
                        openMenu();
                    }
                });
            });

            // κλείσιμο πατώντας έξω από το μενού
            document.addEventListener('click', function (e) {
                if (
                    sidenav.classList.contains('mobile-open') &&
                    !sidenav.contains(e.target) &&
                    !e.target.closest('.sidenav-trigger')
                ) {
                    closeMenu();
                }
            });
        });
    </script>

</body>
</html>

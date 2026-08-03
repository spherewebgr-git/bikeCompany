<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('bikeco-favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @viteReactRefresh
    @vite(['resources/css/app.scss', 'resources/js/app.js', 'resources/js/react/App.jsx'])
</head>

<body class="app-body">

<div class="app-wrapper">


    @include('layouts.navigation')


    @isset($header)

        <header class="page-header">

            <div class="page-header-container">

                {{ $header }}

            </div>

        </header>

    @endisset



    <main class="page-content">

        {{ $slot }}

    </main>

    {{-- ============ FOOTER ============ --}}
    <div class="footer-bottom">
        <div class="nav-container">
            <div class="row">

                <div class="col-md-4 footer-col">
                    <a href="{{ route('home') }}">
                        <img src="{{Vite::asset('resources/images/bikeco-light-logo.png')}}" alt="{{ config('app.name') }}" style="height: 80px; width: auto;">
                    </a>
                    <p>{{ __('Your trail, your bike. Quality bikes for sale and rent, serviced and ready to ride.') }}</p>

                    <div class="social-icon">
                        <ul>
                            <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-4 footer-col">
                    <h5>{{ __('Quick Links') }}</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                        <li><a href="{{ route('bikes.sale') }}">{{ __('Buy Bikes') }}</a></li>
                        <li><a href="{{ route('bikes.rental') }}">{{ __('Rental Bikes') }}</a></li>
                        <li><a href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-4 footer-col" id="contact">
                    <h5>{{ __('Get in Touch') }}</h5>
                    <ul class="footer-contact">
                        <li><i class="fa fa-map-marker"></i> {{ __('123 Trail Street, Bike City') }}</li>
                        <li><i class="fa fa-phone"></i> +30 210 1234567</li>
                        <li><i class="fa fa-envelope"></i> info@trailbike.com</li>
                    </ul>

                    <div class="subscribe">
                        <form>
                            <input type="email" placeholder="{{ __('Your email address') }}">
                            <input type="submit" value="{{ __('Subscribe') }}">
                        </form>
                    </div>
                </div>

            </div>

            <div class="footer-divider"></div>

            <p class="copyright text-center">&copy; {{ date('Y') }} {{ config('app.name') }} — {{ __('All rights reserved') }}</p>
        </div>
    </div>



</div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @stack('scripts')
</body>
</html>

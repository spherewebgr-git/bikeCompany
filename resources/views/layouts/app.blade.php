<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.scss', 'resources/js/app.js'])
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
        <div class="row no-gutters">
            <div class="col-md-6 footer-left">
                <h2>{{ config('app.name') }}</h2>
                <p>{{ __('Your trail, your bike.') }}</p>
            </div>
            <div class="col-md-6 footer-right" id="contact">
                <p>{{ __('Get in touch for custom orders and rentals.') }}</p>
                <p class="copyright">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </div>
    </div>



</div>


</body>
</html>

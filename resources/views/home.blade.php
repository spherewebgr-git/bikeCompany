<x-app-layout>

    {{-- ============ HERO / SLIDER ============ --}}
    <section class="slider" style="background-image: {{Vite::asset('resources/images/hero-image.jpg')}}">
        <img src="{{Vite::asset('resources/images/hero-image.jpg')}}" alt="">
        <div class="slider-content">
            <div>
                <h1 class="title-text">{{ __('Ride the trail') }}</h1>
                <h5>{{ __('Rent or buy the bike that fits your next adventure') }}</h5>
                <div>
                    <a href="{{ route('bikes.rental') }}" class="btn btn-fill btn-md">{{ __('Rent a bike') }}</a>
                    <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('Buy a bike') }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ ABOUT ============ --}}
    <section class="about-us" id="about">
        <div class="nav-container">
            <div class="section-heading text-center">
                <h6>{{ __('Who we are') }}</h6>
                <h2 class="title-text">{{ __('About the club') }}</h2>
            </div>

            <div class="row mb-30 about-intro">
                <div class="col-md-6">
                    <p>
                        {{ __('Trail Bike started as a small group of friends who shared one thing in common: a love for two wheels and the open trail. Today we\'ve grown into a full workshop and store, offering both rental and sale bikes for every kind of rider — from weekend explorers to competitive racers.') }}
                    </p>

                    <p>
                        {{ __('Every bike that leaves our shop is inspected, tuned, and ready to ride. Whether you\'re looking to rent for a weekend adventure or invest in your next bike, our team is here to help you find the right fit.') }}
                    </p>

                    <p>
                        {{ __('We work with trusted brands and keep every bike in our fleet serviced year-round, so you can focus on the ride instead of the maintenance. From city cruisers to full-suspension trail bikes, our catalog keeps growing to match what our riders are asking for.') }}
                    </p>

                    <p>
                        {{ __('At Trail Bike, we believe cycling is more than just transportation—it\'s freedom, adventure, and a way to connect with nature. Our experienced team is passionate about helping every rider find the perfect bike, whether it\'s for a daily commute, a mountain trail, or a weekend getaway.') }}
                    </p>

                    <p>
                        {{ __('Our workshop is fully equipped to provide professional servicing, regular maintenance, and safety inspections. Every bicycle is carefully prepared before it reaches our customers, ensuring a smooth and reliable riding experience from the very first mile.') }}
                    </p>

                    <p>
                        {{ __('We\'re proud to support the cycling community by offering quality equipment, trusted advice, and a growing selection of bikes and accessories from leading brands. No matter your experience level, we\'re here to help you enjoy every ride with confidence.') }}
                    </p>
                </div>

                <div class="col-md-6">
                    <div class="about-image">
                        <img src="{{ Vite::asset('resources/images/about-image.jpg') }}" alt="{{ __('Trail Bike workshop') }}">
                    </div>
                </div>
            </div>

            <div class="row mb-30 about-values">
                <div class="col-sm-3 text-center">
                    <div class="value-card">
                        <i class="fa fa-check-circle"></i>
                        <h5>{{ __('Quality checked') }}</h5>
                        <p>{{ __('Every bike inspected before it reaches you.') }}</p>
                    </div>
                </div>

                <div class="col-sm-3 text-center">
                    <div class="value-card">
                        <i class="fa fa-clock"></i>
                        <h5>{{ __('Flexible rentals') }}</h5>
                        <p>{{ __('Rent by the hour, day, or week.') }}</p>
                    </div>
                </div>

                <div class="col-sm-3 text-center">
                    <div class="value-card">
                        <i class="fa fa-life-ring"></i>
                        <h5>{{ __('Local support') }}</h5>
                        <p>{{ __('Our team is here if anything comes up.') }}</p>
                    </div>
                </div>

                <div class="col-sm-3 text-center">
                    <div class="value-card">
                        <i class="fa fa-refresh"></i>
                        <h5>{{ __('Always fresh stock') }}</h5>
                        <p>{{ __('New arrivals added to the catalog regularly.') }}</p>
                    </div>
                </div>
            </div>

            <div class="row about-stats">
                <div class="col-sm-4 text-center">
                    <div class="stat-card">
                        <i class="fa fa-bicycle"></i>
                        <h4 class="title-text">150+</h4>
                        <p>{{ __('Bikes available') }}</p>
                    </div>
                </div>

                <div class="col-sm-4 text-center">
                    <div class="stat-card">
                        <i class="fa fa-users"></i>
                        <h4 class="title-text">2,000+</h4>
                        <p>{{ __('Happy riders') }}</p>
                    </div>
                </div>

                <div class="col-sm-4 text-center">
                    <div class="stat-card">
                        <i class="fa fa-wrench"></i>
                        <h4 class="title-text">10</h4>
                        <p>{{ __('Years of experience') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ FEATURED BIKES ============ --}}
    <section class="bike-trail">
        <div class="nav-container">
            <div class="section-heading text-center">
                <h6>{{ __('Fresh from the shop') }}</h6>
                <h2 class="title-text">{{ __('Featured bikes') }}</h2>
            </div>

            <div class="row">
                @foreach ($featuredBikes ?? [] as $bike)
                    <div class="col-md-4">
                        <div class="bike-card" style="background-image: url('{{ $bike->image_path }}')">
                            <div class="bike-info">
                                <h3>{{ $bike->brand->name }}</h3>
                                <p><i class="fa fa-bicycle"></i> {{ $bike->type->name }}</p>
                                <p><i class="fa fa-cog"></i> {{ $bike->speed->gears }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="more-info text-center">
                <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('See all bikes') }}</a>
            </div>
        </div>
    </section>

</x-app-layout>

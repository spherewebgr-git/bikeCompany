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

            <div class="row mb-30">
                <div class="col-md-8 no-float">
                    <p>
                        {{ __('Trail Bike started as a small group of friends who shared one thing in common: a love for two wheels and the open trail. Today we\'ve grown into a full workshop and store, offering both rental and sale bikes for every kind of rider — from weekend explorers to competitive racers.') }}
                    </p>
                    <p>
                        {{ __('Every bike that leaves our shop is inspected, tuned, and ready to ride. Whether you\'re looking to rent for a weekend adventure or invest in your next bike, our team is here to help you find the right fit.') }}
                    </p>
                </div>
            </div>

            <div class="row mb-30">
                <div class="col-sm-4 text-center">
                    <i class="fa fa-bicycle" style="font-size: 32px; color: #fe4819;"></i>
                    <h4 class="title-text">150+</h4>
                    <p>{{ __('Bikes available') }}</p>
                </div>

                <div class="col-sm-4 text-center">
                    <i class="fa fa-users" style="font-size: 32px; color: #fe4819;"></i>
                    <h4 class="title-text">2,000+</h4>
                    <p>{{ __('Happy riders') }}</p>
                </div>

                <div class="col-sm-4 text-center">
                    <i class="fa fa-wrench" style="font-size: 32px; color: #fe4819;"></i>
                    <h4 class="title-text">10</h4>
                    <p>{{ __('Years of experience') }}</p>
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

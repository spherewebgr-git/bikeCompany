<x-app-layout>

    {{-- ============ HERO / SLIDER ============ --}}
    <section class="slider">
        <video class="slider-video" autoplay muted loop playsinline
               poster="{{ Vite::asset('resources/images/hero-image.jpg') }}">
            <source src="{{ Vite::asset('resources/videos/hero-video.mp4') }}" type="video/mp4">
        </video>

        <div class="slider-content">
            <div class="hero-content">
                <h1 class="title-text">{{ __('Ride the trail') }}</h1>
                <h5>{{ __('Rent or buy the bike that fits your next adventure') }}</h5>
                <div class="hero-actions">
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
                    <p>{{ __('Trail Bike started as a small group of friends who shared one thing in common: a love for two wheels and the open trail. Today we\'ve grown into a full workshop and store, offering both rental and sale bikes for every kind of rider — from weekend explorers to competitive racers.') }}</p>
                    <p>{{ __('Every bike that leaves our shop is inspected, tuned, and serviced year-round before it reaches you — so you can focus on the ride, not the maintenance. Our catalog keeps growing, from city cruisers to full-suspension trail bikes.') }}</p>
                    <p>{{ __('We\'re proud to support the cycling community with quality equipment, trusted advice, and bikes from leading brands. Whatever your experience level, we\'re here to help you ride with confidence — for a daily commute, a mountain trail, or a weekend getaway.') }}</p>
                </div>

                <div class="col-md-6">
                    <div class="about-image">
                        <img src="{{ Vite::asset('resources/images/about-image.jpg') }}" alt="{{ __('Trail Bike workshop') }}">
                    </div>
                </div>
            </div>

            <div class="row mb-30 about-values">
                <div class="col-sm-3 text-center">
                    <div class="value-card reveal-up">
                        <i class="fa fa-check-circle"></i>
                        <h5>{{ __('Quality checked') }}</h5>
                        <p>{{ __('Every bike inspected before it reaches you.') }}</p>
                    </div>
                </div>
                <div class="col-sm-3 text-center">
                    <div class="value-card reveal-up">
                        <i class="fa fa-clock"></i>
                        <h5>{{ __('Flexible rentals') }}</h5>
                        <p>{{ __('Rent by the hour, day, or week.') }}</p>
                    </div>
                </div>
                <div class="col-sm-3 text-center">
                    <div class="value-card reveal-up">
                        <i class="fa fa-life-ring"></i>
                        <h5>{{ __('Local support') }}</h5>
                        <p>{{ __('Our team is here if anything comes up.') }}</p>
                    </div>
                </div>
                <div class="col-sm-3 text-center">
                    <div class="value-card reveal-up">
                        <i class="fa fa-refresh"></i>
                        <h5>{{ __('Always fresh stock') }}</h5>
                        <p>{{ __('New arrivals added to the catalog regularly.') }}</p>
                    </div>
                </div>
            </div>

            <div class="row about-stats">
                <div class="col-sm-4 text-center">
                    <div class="stat-card reveal-up">
                        <i class="fa fa-bicycle"></i>
                        <h4 class="title-text">150+</h4>
                        <p>{{ __('Bikes available') }}</p>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="stat-card reveal-up">
                        <i class="fa fa-users"></i>
                        <h4 class="title-text">2,000+</h4>
                        <p>{{ __('Happy riders') }}</p>
                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <div class="stat-card reveal-up">
                        <i class="fa fa-wrench"></i>
                        <h4 class="title-text">10</h4>
                        <p>{{ __('Years of experience') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ HOW IT WORKS ============ --}}
    <section class="how-it-works" id="how-it-works">
        <div class="leaf leaf--1"><i class="fa-solid fa-leaf"></i></div>
        <div class="leaf leaf--2"><i class="fa-solid fa-leaf"></i></div>
        <div class="leaf leaf--3"><i class="fa-solid fa-leaf"></i></div>

        <div class="nav-container">
            <div class="section-heading text-center">
                <h6>{{ __('Simple as pedaling') }}</h6>
                <h2 class="title-text">{{ __('How it works') }}</h2>
            </div>

            <div class="trail-track" id="trailTrack">
                <svg class="trail-line" viewBox="0 0 1000 40" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M0 20 Q 250 0, 500 20 T 1000 20" fill="none" stroke="#e2c9bf" stroke-width="3" stroke-dasharray="2 14" stroke-linecap="round"/>
                </svg>

                <div class="trail-rider" id="trailRider" aria-hidden="true">
                    <svg viewBox="0 0 64 40" width="52" height="32">
                        <path d="M14 30 L26 14 L40 14 L50 30 M26 14 L20 30 M32 30 L40 14"
                              fill="none" stroke="#fe4819" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <g>
                            <circle cx="14" cy="30" r="9" fill="none" stroke="#3b3738" stroke-width="3"/>
                            <g stroke="#3b3738" stroke-width="1.4">
                                <line x1="14" y1="21" x2="14" y2="39"/>
                                <line x1="5" y1="30" x2="23" y2="30"/>
                                <line x1="7.4" y1="23.4" x2="20.6" y2="36.6"/>
                                <line x1="7.4" y1="36.6" x2="20.6" y2="23.4"/>
                            </g>
                            <animateTransform attributeName="transform" type="rotate" from="0 14 30" to="360 14 30" dur="1s" repeatCount="indefinite"/>
                        </g>
                        <g>
                            <circle cx="50" cy="30" r="9" fill="none" stroke="#3b3738" stroke-width="3"/>
                            <g stroke="#3b3738" stroke-width="1.4">
                                <line x1="50" y1="21" x2="50" y2="39"/>
                                <line x1="41" y1="30" x2="59" y2="30"/>
                                <line x1="43.4" y1="23.4" x2="56.6" y2="36.6"/>
                                <line x1="43.4" y1="36.6" x2="56.6" y2="23.4"/>
                            </g>
                            <animateTransform attributeName="transform" type="rotate" from="0 50 30" to="360 50 30" dur="1s" repeatCount="indefinite"/>
                        </g>
                    </svg>
                </div>

                <div class="trail-steps">
                    <div class="trail-step">
                        <span class="trail-step__num">01</span>
                        <div class="trail-step__icon"><i class="fa-solid fa-bicycle"></i></div>
                        <h5>{{ __('Pick your bike') }}</h5>
                        <p>{{ __('Browse the catalog and find the ride that fits your trail.') }}</p>
                    </div>
                    <div class="trail-step">
                        <span class="trail-step__num">02</span>
                        <div class="trail-step__icon"><i class="fa-solid fa-calendar-check"></i></div>
                        <h5>{{ __('Choose your dates') }}</h5>
                        <p>{{ __('Rent by the hour, day, or week — whatever the adventure needs.') }}</p>
                    </div>
                    <div class="trail-step">
                        <span class="trail-step__num">03</span>
                        <div class="trail-step__icon"><i class="fa-solid fa-flag-checkered"></i></div>
                        <h5>{{ __('Grab it & ride') }}</h5>
                        <p>{{ __('Pick up at your chosen location and hit the road.') }}</p>
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
                @foreach ($featuredBikes as $bike)
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


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                // Reveal steps στο scroll
                // Αντί για querySelectorAll('.trail-step') μόνο, γενίκευσε σε ένα κοινό selector
                const revealEls = document.querySelectorAll('.trail-step, .reveal-up');
                if (revealEls.length) {
                    const revealObserver = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                revealObserver.unobserve(entry.target);
                            }
                        });
                    }, { threshold: .2 });

                    revealEls.forEach(el => revealObserver.observe(el));
                }

                // Rider ακολουθεί το scroll progress πάνω στο trail
                const track = document.getElementById('trailTrack');
                const rider = document.getElementById('trailRider');
                if (!track || !rider || reduceMotion) return;

                function updateRider() {
                    const rect = track.getBoundingClientRect();
                    const viewportH = window.innerHeight;

                    const total = rect.height + viewportH;
                    const passed = viewportH - rect.top;
                    let progress = passed / total;
                    progress = Math.min(1, Math.max(0, progress));

                    const maxLeft = track.clientWidth - 52; // πλάτος του rider svg
                    rider.style.transform = `translateX(${progress * maxLeft}px)`;
                }

                window.addEventListener('scroll', () => requestAnimationFrame(updateRider), { passive: true });
                window.addEventListener('resize', updateRider);
                updateRider();
            });
        </script>
    @endpush

</x-app-layout>

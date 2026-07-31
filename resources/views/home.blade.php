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

            <div class="about-grid">

                <div class="about-media">
                    <img src="{{ Vite::asset('resources/images/about-image.jpg') }}" alt="{{ __('Trail Bike workshop') }}">
                    <div class="about-tag">
                        <span class="about-tag__line">{{ __('Tuning since') }}</span>
                        <span class="about-tag__year">10+ {{ __('yrs') }}</span>
                    </div>
                </div>

                <div class="about-copy">
                    <span class="eyebrow">{{ __('Trail Bike Workshop') }}</span>
                    <h2 class="about-heading">
                        {{ __('Built on trails.') }}<br>
                        <span>{{ __('Tuned by hand.') }}</span>
                    </h2>

                    <p>{{ __('We started as a handful of friends who couldn\'t stay off two wheels. Now we\'re a full workshop and store — inspecting, tuning, and servicing every bike before it leaves our hands, so you can focus on the ride.') }}</p>
                    <p>{{ __('From city cruisers to full-suspension trail bikes, our catalog keeps growing. Whatever you ride, we\'re here with the right bike and honest advice.') }}</p>

                    <ul class="about-values">
                        <li><i class="fa fa-check-circle"></i> {{ __('Quality checked') }}</li>
                        <li><i class="fa fa-clock"></i> {{ __('Flexible rentals') }}</li>
                        <li><i class="fa fa-life-ring"></i> {{ __('Local support') }}</li>
                        <li><i class="fa fa-refresh"></i> {{ __('Fresh stock') }}</li>
                    </ul>
                </div>

            </div>

            <div class="about-specs">
                <div class="about-specs__item reveal-up">
                    <span class="about-specs__label">{{ __('Bikes available') }}</span>
                    <span class="about-specs__value">150+</span>
                </div>
                <div class="about-specs__item reveal-up">
                    <span class="about-specs__label">{{ __('Happy riders') }}</span>
                    <span class="about-specs__value">2,000+</span>
                </div>
                <div class="about-specs__item reveal-up">
                    <span class="about-specs__label">{{ __('Years of experience') }}</span>
                    <span class="about-specs__value">10</span>
                </div>
            </div>

        </div>
    </section>

    {{-- ============ SPLIT SCREEN ============ --}}
    <section class="rb-split" aria-label="Choose between renting or buying a bike">

        <a href="{{ route('bikes.rental') ?? '#' }}" class="rb-panel rb-panel--rent" data-panel="rent">
            <span class="rb-panel__bg" aria-hidden="true" style="--rb-photo: url('{{ asset('images/rent-bike-home.avif') }}')"></span>
            <span class="rb-panel__stripes" aria-hidden="true"></span>

            <span class="rb-panel__icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="16" cy="46" r="10" stroke="currentColor" stroke-width="3"/>
                <circle cx="48" cy="46" r="10" stroke="currentColor" stroke-width="3"/>
                <path d="M16 46L28 22H40M28 22L40 46M40 46L48 46M40 22L48 46" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M22 22H28" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path d="M14 34L28 22" stroke="currentColor" stroke-width="3" stroke-linecap="round" opacity="0.4"/>
            </svg>
        </span>

            <span class="rb-panel__content">
            <span class="rb-panel__eyebrow">No Commitment</span>
            <span class="rb-panel__title">Rent<br>a Bike</span>
            <span class="rb-panel__desc">Rent a bike for a day, a weekend, or as long as you need. No maintenance, no storage, no hassle.</span>
            <span class="rb-panel__cta">Browse Rentals <span class="rb-panel__arrow">→</span></span>
        </span>
        </a>

        <div class="rb-divider" aria-hidden="true">
            <div class="rb-divider__chain"></div>
        </div>

        <a href="{{ route('bikes.sale') ?? '#' }}" class="rb-panel rb-panel--buy" data-panel="buy">
            <span class="rb-panel__bg" aria-hidden="true" style="--rb-photo: url('{{ asset('images/buy-bike-home.jpg') }}')"></span>
            <span class="rb-panel__spokes" aria-hidden="true"></span>

            <span class="rb-panel__icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="16" cy="46" r="12" stroke="currentColor" stroke-width="3"/>
                <circle cx="48" cy="46" r="12" stroke="currentColor" stroke-width="3"/>
                <circle cx="16" cy="46" r="1.5" fill="currentColor"/>
                <circle cx="48" cy="46" r="1.5" fill="currentColor"/>
                <path d="M16 46L8 46" stroke="currentColor" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
                <path d="M48 46L56 46" stroke="currentColor" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
            </svg>
        </span>

            <span class="rb-panel__content">
            <span class="rb-panel__eyebrow">Yours to Keep</span>
            <span class="rb-panel__title">Buy<br>a Bike</span>
            <span class="rb-panel__desc">Find the perfect bike to ride for years to come. New and pre-owned bicycles, carefully inspected.</span>
            <span class="rb-panel__cta">Shop Bikes <span class="rb-panel__arrow">→</span></span>
        </span>
        </a>

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

    @if(!$featuredBikes->isEmpty())
        {{-- ============ FEATURED BIKES ============ --}}
        <section class="bike-trail">
            <div class="nav-container">
                <div class="section-heading text-center">
                    <h6>{{ __('Fresh from the shop') }}</h6>
                    <h2 class="title-text">{{ __('Featured bikes') }}</h2>
                </div>

                <div class="row">
                    @foreach ($featuredBikes as $bike)
                        @if($bike->provision->name == 'buy')
                            <a href="{{ route('bikes.sale.show', $bike) }}">
                                <div class="col-md-4">
                                    <div class="bike-card" style="background-image: url('{{ $bike->image_path }}')">
                                        <div class="bike-info">
                                            <h3>{{ $bike?->brand?->name ?? 'N/A' }}</h3>
                                            <p><i class="fa fa-bicycle"></i> {{ $bike->type->name }}</p>
                                            <p><i class="fa fa-cog"></i> {{ $bike->speed->gears }}</p>
                                        </div>
                                    </div>
                                </div>

                            </a>
                        @else
                            <a href="{{ route('bikes.rental.show', $bike) }}">
                                <div class="col-md-4">
                                    <div class="bike-card" style="background-image: url('{{ $bike->image_path }}')">
                                        <div class="bike-info">
                                            <h3>{{ $bike?->brand?->name ?? 'N/A' }}</h3>
                                            <p><i class="fa fa-bicycle"></i> {{ $bike->type->name }}</p>
                                            <p><i class="fa fa-cog"></i> {{ $bike->speed->gears }}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif


                    @endforeach
                </div>

                <div class="more-info text-center">
                    <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('See all bikes') }}</a>
                </div>
            </div>
        </section>
    @endif


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

                    const maxLeft = track.clientWidth - 52;
                    rider.style.transform = `translateX(${progress * maxLeft}px)`;
                }

                window.addEventListener('scroll', () => requestAnimationFrame(updateRider), { passive: true });
                window.addEventListener('resize', updateRider);
                updateRider();
            });
        </script>
    @endpush

</x-app-layout>

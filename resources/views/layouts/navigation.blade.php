<nav x-data="{ open: false }" class="main-nav">
    <div class="nav-container">
        <div class="nav-wrapper">

            <div class="nav-logo">
                <a href="{{ route('home') }}">
                    <img src="{{Vite::asset('resources/images/logo.png')}}" alt="{{ config('app.name', 'Trail Bike') }}">
                </a>
            </div>

            <div class="nav-links">
                <x-nav-link :href="route('bikes.rental')" :active="request()->routeIs('bikes.rental')">
                    Rental Bikes
                </x-nav-link>

                <x-nav-link :href="route('bikes.sale')" :active="request()->routeIs('bikes.sale')">
                    Buy Bikes
                </x-nav-link>
            </div>

            <div class="nav-user">
                @auth
{{--                    <a href="{{ route('dashboard') }}" class="btn btn-trans btn-md">Dashboard</a>--}}
                    <a href="{{ route('profile.edit') }}" class="btn btn-trans btn-md">Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-fill btn-md">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-trans btn-md">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-fill btn-md">Register</a>
                    @endif
                @endauth
            </div>

            <div class="nav-toggle">
                <button @click="open = !open" aria-label="Toggle menu">
                    <svg class="hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="mobile-menu hidden">
        <div class="mobile-links">
            <a href="{{ route('bikes.rental') }}" class="btn btn-trans btn-md no-float">Rental Bikes</a>
            <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md no-float">Buy Bikes</a>
        </div>

        <div class="mobile-user-links">
            @auth
                <div class="mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="mobile-user-email">{{ Auth::user()->email }}</div>

                <a href="{{ route('dashboard') }}" class="btn btn-trans btn-md no-float">Dashboard</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-trans btn-md no-float">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-fill btn-md no-float">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-trans btn-md no-float">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-fill btn-md no-float">Register</a>
                @endif
            @endauth
        </div>
    </div>
</nav>

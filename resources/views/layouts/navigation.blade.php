<nav x-data="{ open:false }" class="main-nav">

    <div class="nav-container">

        <div class="nav-wrapper">

            <div class="nav-logo">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo />
                </a>
            </div>


            <div class="nav-links">

                <x-nav-link
                    :href="route('bikes.rental')"
                    :active="request()->routeIs('bikes.rental')">
                    Rental Bikes
                </x-nav-link>


                <x-nav-link
                    :href="route('bikes.sale')"
                    :active="request()->routeIs('bikes.sale')">
                    Buy Bikes
                </x-nav-link>

            </div>


            <div class="nav-user">

                <x-dropdown align="right" width="48">

                    <x-slot name="trigger">

                        <button class="user-button">

                            <span>
                                {{ Auth::user()->name }}
                            </span>

                            <svg class="user-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>

                        </button>

                    </x-slot>


                    <x-slot name="content">

                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>


                        <form method="POST" action="{{ route('logout') }}">

                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>


            <div class="nav-toggle">

                <button @click="open=!open">

                    <svg class="hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">

                        <path :class="{'hidden':open,'inline-flex':!open}"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>

                        <path :class="{'hidden':!open,'inline-flex':open}"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

        </div>

    </div>


    <div :class="{'block':open,'hidden':!open}" class="mobile-menu">


        <div class="mobile-links">

            <x-responsive-nav-link :href="route('bikes.rental')">
                Rental Bikes
            </x-responsive-nav-link>


            <x-responsive-nav-link :href="route('bikes.sale')">
                Buy Bikes
            </x-responsive-nav-link>

        </div>


        <div class="mobile-user">

            <div class="mobile-user-name">
                {{ Auth::user()->name }}
            </div>

            <div class="mobile-user-email">
                {{ Auth::user()->email }}
            </div>


            <div class="mobile-user-links">

                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>


                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="event.preventDefault();this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>


</nav>

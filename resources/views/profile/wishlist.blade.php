<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">

            <nav class="breadcrumb">
                <a href="{{ route('home') }}">
                    {{ __('Home') }}
                </a>

                /

                <a href="{{ route('profile.edit') }}">
                    {{ __('Profile') }}
                </a>

                /

                <span class="active">
                    {{ __('My Wishlist') }}
                </span>
            </nav>

        </div>
    </div>

    <section class="profile-wishlist-page">

        <div class="container">

            <div
                id="wishlist-page-root"
                data-items-url="{{ route('profile.wishlist.items') }}"
            ></div>

        </div>

    </section>

    @vite('resources/js/wishlist-page.jsx')

</x-app-layout>

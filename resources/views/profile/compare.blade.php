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
                    {{ __('Compare Bikes') }}
                </span>
            </nav>

        </div>
    </div>

    <section class="profile-compare-page">

        <div class="container">

            <div
                id="compare-page-root"
                data-items-url="{{ route('profile.compare.items') }}"
            ></div>

        </div>

    </section>

    @vite('resources/js/compare.jsx')

</x-app-layout>

<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <h2>{{ __('Profile') }}</h2>
        </div>
    </div>

    <div class="page-content">
        <div class="nav-container">
            <div class="profile-sections">

                <div class="profile-card">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="profile-card">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="profile-card">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </div>

</x-app-layout>

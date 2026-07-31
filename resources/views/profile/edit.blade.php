<x-app-layout>

    <div id="ProfileSection">
        <div class="page-header-profile">
            <div class="page-header-container">
                <h2>{{ __('Profile') }}</h2>
            </div>
        </div>

        <div class="page-content">
            <div class="nav-container">

                <div class="profile-sidebar col l4 s12">
                    <div class="card-panel">
                        <ul class="tabs">

                            <li class="tab">
                                <a href="#general" class="active" data-target="general">
                                    <i class="fa-solid fa-gear"></i>
                                    <span>General</span>
                                </a>
                            </li>

                            <li class="tab">
                                <a href="#change-password" data-target="change-password">
                                    <i class="fa-solid fa-key"></i>
                                    <span>Change Password</span>
                                </a>
                            </li>

                            <li class="tab">
                                <a href="#saved-cards" data-target="saved-cards">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>Saved Cards</span>
                                </a>
                            </li>

                            <li class="tab">
                                <a href="#delete-account" data-target="delete-account">
                                    <i class="fa-solid fa-user-minus"></i>
                                    <span>Delete Account</span>
                                </a>
                            </li>

                            <li
                                class="indicator"
                                style="left: 0; right: 0;"
                            ></li>
                        </ul>
                    </div>
                </div>

                <div class="profile-sections col l8 s12">

                    <div class="profile-card active" id="general">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="profile-card" id="change-password">
                        @include('profile.partials.update-password-form')
                    </div>

                    <div class="profile-card" id="saved-cards">
                        @include('profile.partials.edit-cards-form')
                    </div>

                    <div class="profile-card" id="delete-account">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('.tabs .tab a');
                const profileCards = document.querySelectorAll('.profile-card');

                function openTab(targetId) {
                    tabs.forEach(function (tab) {
                        tab.classList.remove('active');
                    });

                    profileCards.forEach(function (card) {
                        card.classList.remove('active');
                    });

                    const selectedTab = document.querySelector(
                        '.tabs .tab a[data-target="' + targetId + '"]'
                    );

                    const selectedCard = document.getElementById(targetId);

                    if (selectedTab) {
                        selectedTab.classList.add('active');
                    }

                    if (selectedCard) {
                        selectedCard.classList.add('active');
                    }
                }

                const requestedTab = @json(request('tab', 'general'));

                openTab(requestedTab);

                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function (event) {
                        event.preventDefault();

                        openTab(this.dataset.target);
                    });
                });
            });
        </script>
    </div>


</x-app-layout>

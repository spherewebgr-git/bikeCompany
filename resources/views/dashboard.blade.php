<x-app-layout>
    <x-slot name="header">
        <div class="dashboard-header">
            <h2>
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="dashboard-page">
        <div class="dashboard-container">
            <div class="dashboard-card">
                <div class="dashboard-content">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

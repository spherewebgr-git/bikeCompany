<section class="profile-information-section">
    <header class="profile-section-header">
        <h4 class="title-text">
            {{ __('Profile Information') }}
        </h4>

        <p>
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="profile-form mb-30"
    >
        @csrf
        @method('patch')

        <div class="form-group input-group active">
            <x-input-label
                for="first_name"
                :value="__('First Name')"
            />

            <x-text-input
                id="first_name"
                name="first_name"
                type="text"
                class="form-control"
                :value="old('first_name', $user->first_name)"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error :messages="$errors->get('first_name')" />
        </div>

        <div class="form-group input-group active">
            <x-input-label
                for="last_name"
                :value="__('Last Name')"
            />

            <x-text-input
                id="last_name"
                name="last_name"
                type="text"
                class="form-control"
                :value="old('last_name', $user->last_name)"
                required
                autocomplete="name"
            />

            <x-input-error :messages="$errors->get('last_name')" />
        </div>

        <div class="form-group input-group active">
            <x-input-label
                for="phone"
                :value="__('Phone')"
            />

            <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="form-control"
                :value="old('phone', $user->phone)"
                required
                autocomplete="phone"
            />

            <x-input-error :messages="$errors->get('phone')" />
        </div>

        <div class="form-group input-group active">
            <x-input-label
                for="email"
                :value="__('Email')"
            />

            <x-text-input
                id="email"
                name="email"
                type="email"
                class="form-control"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="verification-notice">
                    <p class="lead">
                        {{ __('Your email address is unverified.') }}

                        <button
                            form="send-verification"
                            class="btn btn-trans btn-md"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="status-message">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <x-primary-button type="submit" class="btn btn-fill btn-md">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="saved-message"
                >
                    {{ __('Saved.') }}
                </p>
            @endif

            <button type="reset" class="btn btn-light-pink waves-effect waves-light">Cancel</button>
        </div>
    </form>

    <script>
        document.querySelectorAll('.profile-form').forEach(form => {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "Your profile information will be updated.",
                    icon: "warning",
                    buttons: {
                        cancel: "Cancel",
                        confirm: {
                            text: "Yes, Save",
                            value: true
                        }
                    }
                }).then((willSave) => {

                    if (willSave) {
                        form.submit();
                    }

                });

            });

        });
    </script>
</section>

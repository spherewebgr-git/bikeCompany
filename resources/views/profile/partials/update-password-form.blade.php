<section>
    <header>
        <h4 class="title-text">
            {{ __('Update Password') }}
        </h4>

        <p>
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="password-form mb-30">
        @csrf
        @method('put')

        <div class="form-group input-group active">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="password-wrapper">
                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="form-control"
                    autocomplete="current-password"
                />

                <i class="fa-solid fa-eye toggle-password"
                    data-target="update_password_current_password">
                </i>

            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="form-group input-group active">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="password-wrapper">
                    <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />

                <i class="fa-solid fa-eye toggle-password"
                   data-target="update_password_password">
                </i>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="form-group input-group active">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="password-wrapper">
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />

                <i class="fa-solid fa-eye toggle-password"
                   data-target="update_password_password_confirmation">
                </i>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="form-actions">
            <x-primary-button class="btn btn-fill btn-md">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="saved-message"
                >{{ __('Saved.') }}</p>
            @endif

            <button type="reset" class="btn btn-light-pink waves-effect waves-light">Cancel</button>

        </div>
    </form>

    <script>
        document.querySelectorAll('.password-form').forEach(form => {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "Your Password will be updated.",
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

    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.target);

                if (input.type === 'password') {
                    input.type = 'text';

                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';

                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });
    </script>
</section>

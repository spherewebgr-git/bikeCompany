<x-guest-layout>
    <div class="login-page">
        <form class="login-form" method="POST" action="{{ route('register') }}">
            @csrf



            <div class="login-title">
                <div class="auth-logo">
                    <a href="/">
                        <img src="{{Vite::asset('resources/images/logo.png')}}" alt="Logo">
                    </a>
                </div>
                <h2>Register</h2>
                <p>Create your account</p>
            </div>

            <!-- Name -->
            <div class="form-field">
                <label for="name">
                    Name
                </label>

                <input
                    id="name"
                    class="form-input"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                >

                @error('name')
                <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-field">
                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    class="form-input"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                >

                @error('email')
                <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-field">
                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    class="form-input"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                >

                @error('password')
                <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-field">
                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    id="password_confirmation"
                    class="form-input"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                >

                @error('password_confirmation')
                <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('login') }}">
                    Already registered?
                </a>

                <button type="submit">
                    Register
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

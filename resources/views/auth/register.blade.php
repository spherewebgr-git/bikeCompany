<x-guest-layout>
    <div class="login-page">
        <form class="login-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="login-title">
                <div class="auth-logo">
                    <a href="/">
                        <img
                            src="{{ Vite::asset('resources/images/bikeco-dark-logo.png') }}"
                            alt="Logo"
                            style="height: 70px; width: auto;"
                        >
                    </a>
                </div>

                <h2>Register</h2>
                <p>Create your account</p>
            </div>

            <!-- First Name -->
            <div class="form-field">
                <label for="first_name">
                    First Name
                </label>

                <input
                    id="first_name"
                    class="form-input"
                    type="text"
                    name="first_name"
                    value="{{ old('first_name') }}"
                    required
                    autofocus
                    autocomplete="given-name"
                >

                @error('first_name')
                <span class="form-error">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="form-field">
                <label for="last_name">
                    Last Name
                </label>

                <input
                    id="last_name"
                    class="form-input"
                    type="text"
                    name="last_name"
                    value="{{ old('last_name') }}"
                    required
                    autocomplete="family-name"
                >

                @error('last_name')
                <span class="form-error">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-field">
                <label for="phone">
                    Phone
                </label>

                <input
                    id="phone"
                    class="form-input"
                    type="tel"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                    autocomplete="tel"
                >

                @error('phone')
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

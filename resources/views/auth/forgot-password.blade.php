<x-guest-layout>
    <div class="login-page">
        <form class="login-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="login-title">
                <div class="auth-logo">
                    <a href="/">
                        <img src="{{Vite::asset('resources/images/logo.png')}}" alt="Logo">
                    </a>
                </div>
                <h2>Forgot Password</h2>
                <p>Enter your email and we will send you a reset link.</p>
            </div>

            @if(session('status'))
                <div class="form-success">
                    {{ session('status') }}
                </div>
            @endif

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
                    autofocus
                >

                @error('email')
                <span class="form-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit">
                    Send Reset Link
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

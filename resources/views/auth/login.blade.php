<x-guest-layout>

    <div class="login-page">


        <form class="login-form" method="POST" action="{{ route('login') }}">

            @csrf


            <div class="login-title">

                <div class="auth-logo">
                    <a href="/">
                        <img src="{{Vite::asset('resources/images/logo.png')}}" alt="Logo">
                    </a>
                </div>

                <h2>Login</h2>

                <p>Welcome back</p>

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
                    autofocus
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
                    autocomplete="current-password"
                >


                @error('password')

                <span class="form-error">
                        {{ $message }}
                    </span>

                @enderror



            </div>





            <!-- Remember -->

            <div class="remember-field">


                <label>

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    <span>
                        Remember me
                    </span>


                </label>


            </div>






            <div class="form-actions">


                @if(Route::has('password.request'))

                    <a href="{{ route('password.request') }}">
                        Forgot password?
                    </a>

                @endif




                <button type="submit">

                    Login

                </button>



            </div>




        </form>


    </div>


</x-guest-layout>


<x-app-layout>

    <div id="ContactUs">

        <div class="contact-hero">
            <video autoplay muted loop playsinline class="contact-hero__video">
                <source src="{{ Vite::asset('resources/videos/contact-us-video.mp4') }}" type="video/mp4">
            </video>
            <div class="contact-hero__overlay"></div>

            <div class="contact-hero__content">
                <h6>{{ __('Get in touch') }}</h6>
                <h2>{{ __('Contact Us') }}</h2>
            </div>
        </div>

        <div class="page-content">
            <div class="container">

                <div class="contact-us-container">

                    <div class="contact-us-intro">
                        <h3>{{ __('We are here to help') }}</h3>
                        <p>
                            If you have any questions about our bikes, rentals,
                            purchases or an existing order, you can contact us
                            through the form below. Write your message and a member
                            of our staff will get back to you as soon as possible.
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->has('contact'))
                        <div class="alert alert-danger">{{ $errors->first('contact') }}</div>
                    @endif

                    <div class="contact-us-card">
                        <form action="{{ route('contact-us.send') }}" method="POST" class="contact-us-form">
                            @csrf
                        @csrf

                        <div class="form-group input-group active">
                            <label for="name">
                                {{ __('Name') }}
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                class="form-control"
                                value="{{ old('name', auth()->user()?->first_name
                                ? trim(auth()->user()->first_name . ' ' . auth()->user()->last_name)
                                : '') }}"
                                placeholder="Enter your name"
                                required
                            >

                            @error('name')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="form-group input-group active">
                            <label for="email">
                                {{ __('Email') }}
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="form-control"
                                value="{{ old('email', auth()->user()?->email ?? '') }}"
                                placeholder="Enter your email address"
                                required
                            >

                            @error('email')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="form-group input-group active">
                            <label for="subject">
                                {{ __('Subject') }}
                            </label>

                            <input
                                id="subject"
                                name="subject"
                                type="text"
                                class="form-control"
                                value="{{ old('subject') }}"
                                placeholder="What is your message about?"
                                required
                            >

                            @error('subject')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="form-group input-group active">
                            <label for="message">
                                {{ __('Message') }}
                            </label>

                            <textarea
                                id="message"
                                name="message"
                                class="form-control"
                                rows="7"
                                placeholder="Write your message here"
                                required
                            >{{ old('message') }}</textarea>

                            @error('message')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button
                                type="submit"
                                class="btn btn-fill btn-md"
                            >
                                {{ __('Send Message') }}
                            </button>
                        </div>

                        </form>
                    </div>

                </div>

            </div>
        </div>
        <div id="contact-us-boxes"></div>
        @vite('resources/js/react/pages/ContactUs.jsx')
    </div>

</x-app-layout>

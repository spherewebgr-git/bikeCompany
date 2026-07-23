<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">{{ __('Home') }}</a> /
                <a href="{{ route('bikes.sale') }}">{{ __('Bikes for sale') }}</a> /
                <span class="active">{{ $bike->brand->name }}</span>
            </nav>
        </div>
    </div>

    <section class="bike-single">
        <div class="nav-container container">

            <div class="bike-single-grid row">

                <div class="bike-single-image col-sm-6">
                    <img src="{{ $bike->image_path }}" alt="{{ $bike->brand->name }}">
                </div>

                <div class="bike-single-right-section col-sm-6">
                    @include('bikes.partials.bike-info')

                    <div class="bike-single-action">
                        <p class="price">
                            {{ __('Price') }}:
                            @foreach($bike->prices as $price)
                                <strong class="price-value">{{ $price->price }} €</strong>
                            @endforeach
                        </p>
                        <div class="buttons-section">

                            @if ($bike->quantity > 0)

                                <div class="stock-status in-stock">
                                    In Stock: {{ $bike->quantity }}
                                </div>
                                @auth
                                    <a href="{{ route('checkout.create-sale', $bike) }}" class="btn btn-fill btn-md">{{ __('Buy now') }}</a>
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('bikes.sale.show', $bike)) }}"
                                    class="btn btn-fill btn-md">
                                        {{ __('Buy now') }}
                                    </a>
                                @endauth
                            @else
                                <div class="stock-status out-of-stock">
                                    Out of Stock
                                </div>
                                @auth
                                    <button type="button"
                                            class="btn btn-fill btn-md disabled"
                                            disabled>
                                        Buy now
                                    </button>
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('bikes.sale.show', $bike)) }}"
                                       class="btn btn-fill btn-md">
                                        {{ __('Buy now') }}
                                    </a>
                                @endauth
                            @endif

                                <a href="{{ route('bikes.sale') }}" class="btn btn-trans btn-md">{{ __('Back to all bikes') }}</a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    @guest
        <div class="modal fade" id="authRequiredModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fa-solid fa-lock"></i> {{ __('Login required') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('You need to be logged in to continue with your purchase.') }}</p>
                        <p>{{ __("Don't have an account? You can register for free.") }}</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('login') }}" class="btn btn-fill">
                            <i class="fa-solid fa-right-to-bracket"></i> {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline">
                            <i class="fa-solid fa-user-plus"></i> {{ __('Register') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endguest

</x-app-layout>

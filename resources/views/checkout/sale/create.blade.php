<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <nav class="breadcrumb">
                <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a> /
                <span>Checkout</span>
            </nav>
        </div>
    </div>

    <div class="page-body checkout-page">
        <div class="container">

            <div class="row">

                <div class="col-md-8">

                    <div class="blog-details checkout-card">

                        <div class="section-heading">
                            <h2>Checkout</h2>
                        </div>

                        <form action="{{ route('checkout.store-sale', $bike) }}"
                              method="POST"
                              class="contact-form checkout-form">

                            @csrf

                            <div class="checkout-section">

                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-user"></i> Customer Information
                                </h4>

                                <div class="row">

                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-id-card"></i> First Name</label>

                                        <input type="text"
                                               class="form-control"
                                               value="{{ $user->first_name }}"
                                               disabled>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-id-card"></i> Last Name</label>

                                        <input type="text"
                                               class="form-control"
                                               value="{{ $user->last_name }}"
                                               disabled>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-regular fa-envelope"></i> Email</label>

                                        <input type="email"
                                               class="form-control"
                                               value="{{ $user->email }}"
                                               disabled>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <label><i class="fa-solid fa-phone"></i> Phone</label>

                                        <input type="text"
                                               class="form-control"
                                               value="{{ $user->phone }}"
                                               disabled>
                                    </div>

                                </div>

                            </div>

                            <div class="checkout-section">

                                <h4 class="checkout-title">
                                    <i class="fa-solid fa-location-dot"></i> Delivery Address
                                </h4>

                                <div class="form-group">

                                    <label>Drop-off Address</label>

                                    <input type="text"
                                           name="dropoff_address"
                                           class="form-control"
                                           value=""
                                           placeholder="Leoforos Kifisias 125, Marousi"
                                           required>

                                    @error('dropoff_address')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>

                            <div class="checkout-actions">

                                <button type="submit" class="btn btn-fill btn-md">
                                    {{ __('Place Order') }}
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="sidebar-widget checkout-summary">

                        <div class="checkout-summary__bike">
                            <img src="{{ asset($bike->images->first()->image) }}"
                                alt="{{ $bike->brand->name }}"
                                class="checkout-summary__bike-img">
                            <span class="checkout-summary__bike-badge">{{ $bike->brand->name }}</span>
                        </div>

                        <h4 class="widget-title">
                            <i class="fa-solid fa-receipt"></i> Order Summary
                        </h4>

                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-motorcycle"></i> Bike</strong>
                            <span>{{ $bike?->brand?->name ?? 'N/A' }}</span>
                        </div>

                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-table-list"></i> Type</strong>
                            <span>{{ $bike->type->name }}</span>
                        </div>

                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-palette"></i> Colour</strong>
                            <span>{{ $bike->colour }}</span>
                        </div>

                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-gear"></i> Gears</strong>
                            <span>{{ $bike->speed->gears }}</span>
                        </div>

                        <div class="checkout-summary__item">
                            <strong><i class="fa-solid fa-tag"></i> Price</strong>
                            <span>{{ $bike->prices->first()?->price ?? '-' }} €</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>

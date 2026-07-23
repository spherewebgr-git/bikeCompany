<x-app-layout>

    <div class="page-header">
        <div class="page-header-container">
            <h2>{{ __('Payment') }}</h2>
        </div>
    </div>

    <div class="page-content">
        <div class="payment-container">

            <div class="payment-card">
                <header class="payment-header">
                    <h4 class="title-text">
                        {{ __('Choose Payment Method') }}
                    </h4>

                    <p>
                        {{ __('Select how you would like to pay for your order.') }}
                    </p>
                </header>

                <form
                    method="POST"
                    action="{{ route('payment.complete', $order) }}"
                    class="payment-form complete-order-form"
                >
                    @csrf

                    {{-- Αντικαταβολή --}}
                    <label class="payment-option">
                        <input
                            type="radio"
                            name="payment_method"
                            value="cash_on_delivery"
                            checked
                        >

                        <span class="payment-option-icon">
            <i class="fa-solid fa-money-bill-wave"></i>
        </span>

                        <span class="payment-option-content">
            <strong>{{ __('Cash on Delivery') }}</strong>

            <small>
                {{ __('Pay when your order is delivered.') }}
            </small>
        </span>
                    </label>

                    {{-- Πληρωμή με κάρτα --}}
                    <label class="payment-option">
                        <input
                            type="radio"
                            name="payment_method"
                            value="card"
                        >

                        <span class="payment-option-icon">
            <i class="fa-solid fa-credit-card"></i>
        </span>

                        <span class="payment-option-content">
            <strong>{{ __('Payment by Card') }}</strong>

            <small>
                {{ __('Use a saved card or enter a new card.') }}
            </small>
        </span>
                    </label>

                    {{-- Επιλογές κάρτας --}}
                    <div id="card-payment-section" class="card-payment-section">

                        <h5 class="card-section-title">
                            {{ __('Choose a Card') }}
                        </h5>

                        @forelse ($order->user->cards as $card)

                            <label class="saved-payment-card">
                                <input
                                    type="radio"
                                    name="card_choice"
                                    value="saved:{{ $card->id }}"
                                    {{old('card_choise') === 'saved:' . $card->id ? 'checked' : ''}}
                                >

                                <span class="saved-payment-card__icon">
            <i class="fa-solid fa-credit-card"></i>
        </span>

                                <span class="saved-payment-card__information">
            <strong>
                **** **** **** {{ substr((string) $card->number, -4) }}
            </strong>

            <small>
                {{ __('Expires') }}
                {{ str_pad((string) $card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->exp_year }}
            </small>
        </span>
                            </label>

                        @empty
                            <div class="no-payment-cards">
                                <p>{{ __('You do not have any saved cards.') }}</p>
                            </div>
                        @endforelse

                        {{-- Επιλογή νέας κάρτας --}}
                        <label class="saved-payment-card new-card-option">
                            <input
                                type="radio"
                                name="card_choice"
                                value="new"
                                id="new-card-option"
                                {{ old('card_choice') === 'new' ? 'checked' : '' }}
                            >

                            @error('card_choice')
                            <p class="text-danger">
                                {{ $message }}
                            </p>
                            @enderror

                            <span class="saved-payment-card__icon">
                <i class="fa-solid fa-plus"></i>
            </span>

                            <span class="saved-payment-card__information">
                <strong>{{ __('Use a New Card') }}</strong>

                <small>
                    {{ __('Enter card details for this payment.') }}
                </small>
            </span>
                        </label>

                        {{-- Στοιχεία νέας κάρτας --}}
                        <div id="new-card-fields" class="new-card-fields">

                            <div class="form-group input-group active">
                                <label for="card_number">
                                    {{ __('Card Number') }}
                                </label>

                                <input
                                    id="card_number"
                                    name="card_number"
                                    type="text"
                                    class="form-control new-card-input"
                                    inputmode="numeric"
                                    maxlength="19"
                                    placeholder="1234567890123456"
                                >
                            </div>

                            <div class="new-card-row">

                                <div class="form-group input-group active">
                                    <label for="card_exp_month">
                                        {{ __('Expiration Month') }}
                                    </label>

                                    <input
                                        id="card_exp_month"
                                        name="card_exp_month"
                                        type="number"
                                        class="form-control new-card-input"
                                        min="1"
                                        max="12"
                                        placeholder="MM"
                                    >
                                </div>

                                <div class="form-group input-group active">
                                    <label for="card_exp_year">
                                        {{ __('Expiration Year') }}
                                    </label>

                                    <input
                                        id="card_exp_year"
                                        name="card_exp_year"
                                        type="number"
                                        class="form-control new-card-input"
                                        min="{{ now()->year }}"
                                        placeholder="YYYY"
                                    >
                                </div>

                                <div class="form-group input-group active">
                                    <label for="card_cvv">
                                        {{ __('CVV') }}
                                    </label>

                                    <input
                                        id="card_cvv"
                                        name="card_cvv"
                                        type="password"
                                        class="form-control new-card-input"
                                        inputmode="numeric"
                                        maxlength="4"
                                        placeholder="CVV"
                                    >
                                </div>

                            </div>

                            <label class="save-new-card-option">
                                <input
                                    type="checkbox"
                                    name="save_card"
                                    value="1"
                                >

                                <span>
                    {{ __('Save this card for future purchases') }}
                </span>
                            </label>

                        </div>

                    </div>

                    <div id="reservationTimer" class="reservation-timer">

                        <h4>⏳ Reservation Time Remaining</h4>

                        <div id="reservationTime" class="time">
                            15:00
                        </div>

                        <p>
                            This bike is reserved for you.
                            Complete your order before the timer expires.
                        </p>

                    </div>

                    <div class="payment-total-container">
                        <span>{{ __('Total Amount') }}</span>

                        <strong>
                            {{ number_format($order->price, 2, ',', '.') }} €
                        </strong>
                    </div>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn btn-fill btn-md"
                        >
                            {{ __('Complete Order') }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentMethodInputs = document.querySelectorAll(
                'input[name="payment_method"]'
            );

            const cardPaymentSection = document.getElementById(
                'card-payment-section'
            );

            const cardChoiceInputs = document.querySelectorAll(
                'input[name="card_choice"]'
            );

            const newCardFields = document.getElementById(
                'new-card-fields'
            );

            const newCardInputs = document.querySelectorAll(
                '.new-card-input'
            );

            function updatePaymentMethod() {
                const selectedPaymentMethod = document.querySelector(
                    'input[name="payment_method"]:checked'
                );

                const isCardPayment =
                    selectedPaymentMethod &&
                    selectedPaymentMethod.value === 'card';

                cardPaymentSection.classList.toggle(
                    'active',
                    isCardPayment
                );

                if (!isCardPayment) {
                    newCardFields.classList.remove('active');

                    newCardInputs.forEach(function (input) {
                        input.required = false;
                    });
                }
            }

            function updateCardChoice() {
                const selectedCardChoice = document.querySelector(
                    'input[name="card_choice"]:checked'
                );

                const isNewCard =
                    selectedCardChoice &&
                    selectedCardChoice.value === 'new';

                newCardFields.classList.toggle(
                    'active',
                    isNewCard
                );

                newCardInputs.forEach(function (input) {
                    input.required = isNewCard;
                });
            }

            paymentMethodInputs.forEach(function (input) {
                input.addEventListener(
                    'change',
                    updatePaymentMethod
                );
            });

            cardChoiceInputs.forEach(function (input) {
                input.addEventListener(
                    'change',
                    updateCardChoice
                );
            });

            updatePaymentMethod();
            updateCardChoice();
        });
    </script>

    <script>
        const reservationEndsAt = {{ $order->reserved_until->timestamp * 1000 }};
        const timer = document.getElementById('reservationTime');
        const timerBox = document.getElementById('reservationTimer');
        const completeButton = document.querySelector('.complete-order-form button[type="submit"]');

        function updateReservationTimer() {

            const now = Date.now();

            const diff = reservationEndsAt - now;

            if (diff <= 0) {
                timer.textContent = '00:00';
                clearInterval(interval);

                if (completeButton) {
                    completeButton.disabled = true;
                }

                fetch("{{ route('payment.expire', $order) }}", {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                })
                    .then(async response => {
                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(
                                data.message || 'The reservation could not be released.'
                            );
                        }

                        return data;
                    })
                    .then(data => {
                        return swal({
                            title: 'Reservation Expired',
                            text: 'The bike is available again.',
                            icon: 'warning',
                            button: 'OK',
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                        }).then(() => {
                            window.location.href = data.redirect;
                        });
                    })
                    .catch(error => {
                        swal({
                            title: 'Reservation Error',
                            text: error.message,
                            icon: 'error',
                            button: 'OK',
                        }).then(() => {
                            window.location.href = "{{ route('home') }}";
                        });
                    });

                return;
            }

            const minutes = Math.floor(diff / 60000);

            const seconds = Math.floor((diff % 60000) / 1000);

            timer.textContent =
                String(minutes).padStart(2, '0')
                + ':'
                + String(seconds).padStart(2, '0');

            if (minutes < 2) {

                timerBox.classList.remove('warning');

                timerBox.classList.add('danger');

            } else if (minutes < 5) {

                timerBox.classList.add('warning');

            }

        }

        updateReservationTimer();

        const interval = setInterval(updateReservationTimer,1000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.complete-order-form');

            if (!form) {
                return;
            }

            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        let errorMessage = data.message || 'Please check the payment information.';

                        if (data.errors) {
                            const firstError = Object.values(data.errors)[0];

                            if (firstError && firstError[0]) {
                                errorMessage = firstError[0];
                            }
                        }

                        swal({
                            title: "Payment Error",
                            text: errorMessage,
                            icon: "error",
                            button: "OK"
                        });

                        return;
                    }

                    if (data.success) {
                        swal({
                            title: "Order Completed!",
                            text: "Your order has been completed successfully.",
                            icon: "success",
                            button: "OK",
                            closeOnClickOutside: false,
                            closeOnEsc: false
                        }).then(function () {
                            window.location.href = data.redirect;
                        });
                    }
                } catch (error) {
                    swal({
                        title: "Error",
                        text: "Something went wrong while completing the order.",
                        icon: "error",
                        button: "OK"
                    });
                }
            });
        });
    </script>

</x-app-layout>

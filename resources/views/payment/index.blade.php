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
                            >

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
        document.addEventListener('DOMContentLoaded', function () {

            const form = document.querySelector('.complete-order-form');

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {

                        if(data.success){

                            swal({
                                title: "Order Completed!",
                                text: "Your order has been completed successfully.",
                                icon: "success",
                                button: "OK"
                            }).then(() => {

                                window.location.href = "{{ route('home') }}";

                            });

                        }

                    });

            });

        });
    </script>

</x-app-layout>

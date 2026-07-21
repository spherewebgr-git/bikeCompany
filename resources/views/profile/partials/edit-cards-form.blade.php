<section class="saved-cards-section">

    <header class="profile-section-header">
        <h4 class="title-text">
            {{ __('Saved Cards') }}
        </h4>

        <p>
            {{ __('View your saved cards or add a new payment card.') }}
        </p>
    </header>

    @if (session('status') === 'card-added')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="saved-message"
        >
            {{ __('Card added successfully.') }}
        </p>
    @endif

    @if (session('status') === 'card-deleted')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="saved-message"
        >
            {{ __('Card deleted successfully.') }}
        </p>
    @endif

    <div class="saved-cards-list">

        @forelse (auth()->user()->cards as $card)
            <div class="saved-card">

                <div class="saved-card-icon">
                    <i class="fa-solid fa-credit-card"></i>
                </div>

                <div class="saved-card-information">
                    <h5>
                        {{ __('Card ending in') }}
                        {{ substr($card->number, -4) }}
                    </h5>

                    <p class="card-number">
                        **** **** **** {{ substr($card->number, -4) }}
                    </p>

                    <p class="card-expiration">
                        {{ __('Expires') }}
                        {{ str_pad($card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->exp_year }}
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('profile.cards.destroy', $card) }}"
                    class="delete-card-form"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-light-pink waves-effect waves-light"
                    >
                        {{ __('Delete') }}
                    </button>
                </form>

            </div>
        @empty
            <div class="no-saved-cards">
                <i class="fa-regular fa-credit-card"></i>

                <p>
                    {{ __('You do not have any saved cards.') }}
                </p>
            </div>
        @endforelse

    </div>

    <button
        type="button"
        id="show-add-card-form"
        class="btn btn-fill btn-md"
    >
        <i class="fa-solid fa-plus"></i>
        {{ __('Add New Card') }}
    </button>

    <div
        id="add-card-container"
        class="add-card-container"
        style="display: none;"
    >
        <header class="add-card-header">
            <h4 class="title-text">
                {{ __('Add New Card') }}
            </h4>

            <p>
                {{ __('Enter the details of your new card.') }}
            </p>
        </header>

        <form
            method="POST"
            action="{{ route('profile.cards.store') }}"
            class="add-card-form"
        >
            @csrf

            <div class="form-group input-group active">
                <x-input-label
                    for="number"
                    :value="__('Card Number')"
                />

                <x-text-input
                    id="number"
                    name="number"
                    type="text"
                    class="form-control"
                    :value="old('number')"
                    inputmode="numeric"
                    maxlength="19"
                    placeholder="1234567890123456"
                    required
                />

                <x-input-error :messages="$errors->get('number')" />
            </div>

            <div class="card-date-fields">

                <div class="form-group input-group active">
                    <x-input-label
                        for="exp_month"
                        :value="__('Expiration Month')"
                    />

                    <x-text-input
                        id="exp_month"
                        name="exp_month"
                        type="number"
                        class="form-control"
                        :value="old('exp_month')"
                        min="1"
                        max="12"
                        placeholder="MM"
                        required
                    />

                    <x-input-error :messages="$errors->get('exp_month')" />
                </div>

                <div class="form-group input-group active">
                    <x-input-label
                        for="exp_year"
                        :value="__('Expiration Year')"
                    />

                    <x-text-input
                        id="exp_year"
                        name="exp_year"
                        type="number"
                        class="form-control"
                        :value="old('exp_year')"
                        :min="now()->year"
                        placeholder="YYYY"
                        required
                    />

                    <x-input-error :messages="$errors->get('exp_year')" />
                </div>

                <div class="form-group input-group active">
                    <x-input-label
                        for="cvv"
                        :value="__('CVV')"
                    />

                    <x-text-input
                        id="cvv"
                        name="cvv"
                        type="password"
                        class="form-control"
                        inputmode="numeric"
                        maxlength="4"
                        placeholder="CVV"
                        required
                    />

                    <x-input-error :messages="$errors->get('cvv')" />
                </div>

            </div>

            <div class="form-actions">
                <x-primary-button
                    type="submit"
                    class="btn btn-fill btn-md"
                >
                    {{ __('Save Card') }}
                </x-primary-button>

                <button
                    type="reset"
                    id="cancel-add-card"
                    class="btn btn-light-pink waves-effect waves-light"
                >
                    {{ __('Cancel') }}
                </button>
            </div>

        </form>
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const showAddCardButton = document.getElementById('show-add-card-form');
        const addCardContainer = document.getElementById('add-card-container');
        const cancelAddCardButton = document.getElementById('cancel-add-card');

        if (showAddCardButton && addCardContainer) {
            showAddCardButton.addEventListener('click', function () {
                addCardContainer.style.display = 'block';
                showAddCardButton.style.display = 'none';
            });
        }

        if (cancelAddCardButton && addCardContainer && showAddCardButton) {
            cancelAddCardButton.addEventListener('click', function () {
                addCardContainer.style.display = 'none';
                showAddCardButton.style.display = 'inline-flex';
            });
        }

        document.querySelectorAll('.delete-card-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                swal({
                    title: "Are you sure?",
                    text: "This saved card will be deleted.",
                    icon: "warning",
                    buttons: {
                        cancel: "Cancel",
                        confirm: {
                            text: "Yes, Delete",
                            value: true
                        }
                    },
                    dangerMode: true
                }).then(function (willDelete) {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

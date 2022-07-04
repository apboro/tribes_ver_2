<div class="content-header">
    <h5 class="mb-0">
        {{ __('payment.card_number') }}
    </h5>
    <small></small>
</div>

<form>
    <div class="row">
        <div class="mb-1 col-md-6">
            <label class="form-label" for="card_number">
                {{ __('base.number') }}
            </label>
            <input
                type="number"
                name="card_number"
                id="card_number"
                class="form-control"
                placeholder="123546"
                oninput="PaymentsCardPage.data.onCardNumberInput(event)"
            />
        </div>
    </div>
</form>

<div class="">
    <button
        class="btn btn-primary"
        onclick="PaymentsCardPage.formWizard.toPrevious()"
    >
        <i data-feather="arrow-left" class="font-medium-1"></i>
        <span class="align-middle d-sm-inline-block d-none">{{ __('base.back') }}</span>
    </button>

    <button
        class="btn btn-success ms-1"
        onclick="PaymentsCardPage.data.onSubmit()"
    >
        <i data-feather="credit-card" class="font-medium-1"></i>
        <span class="align-middle d-sm-inline-block d-none">
            {{ __('payment.add_card') }}
        </span>
    </button>
</div>

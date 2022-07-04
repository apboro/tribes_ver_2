<div class="content-header">
    <h5 class="mb-0">
        {{ __('payment.reports_mail') }}
    </h5>
    <small>
        {{ __('payment.enter_your_email') }}
    </small>
</div>

<form>
    <div class="row">
        <div class="mb-1 col-md-6">
            <label class="form-label" for="email">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                class="form-control"
                placeholder="email@example.com"
                oninput="PaymentsCardPage.data.onEmailInput(event)"
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
        <span class="align-middle d-sm-inline-block d-none">
            {{ __('base.back') }}
        </span>
    </button>

    <button
        class="btn btn-primary btn-next ms-1 hide"
        onclick="PaymentsCardPage.formWizard.toNext()"
    >
        <span class="align-middle d-sm-inline-block d-none">
            {{ __('base.next') }}
        </span>
        <i data-feather="arrow-right" class="font-medium-1"></i>
    </button>

    <button
        class="btn btn-success btn-submit ms-1"
        onclick="PaymentsCardPage.data.onSubmit()"
    >
        <i data-feather="credit-card" class="font-medium-1"></i>
        <span class="align-middle d-sm-inline-block d-none">
            {{ __('payment.add_card') }}
        </span>
    </button>
</div>

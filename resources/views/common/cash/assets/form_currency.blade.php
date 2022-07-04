<div class="content-header">
    <h5 class="mb-0">
        {{ __('payment.adding_card') }}
    </h5>
    <small class="text-muted">
        {{ __('payment.choose_payment_type') }}
    </small>
</div>

<form id="currency_form">
    <div class="row">
        <div class="mb-1 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
            <div class="card shadow-none bg-transparent border-secondary custom-1-border-color">
                <input
                    type="radio"
                    name="currency"
                    id="russia"
                    class="form-control hide"
                    value="russia"
                    onchange="PaymentsCardPage.formWizard.toNextFromCurrency(event)"
                />
                <label class="form-label p-5 w-100 text-center pointer" for="russia">
                    {{ __('payment.for_ruble_tariff') }}
                </label>
            </div>
        </div>

        <div class="mb-1 col-12 col-sm-6 col-md-5 col-lg-4 col-xl-3">
            <div class="card shadow-none bg-transparent border-secondary custom-1-border-color">
                <input
                    type="radio"
                    name="currency"
                    id="other"
                    class="form-control hide"
                    value="other"
                    onchange="PaymentsCardPage.formWizard.toNextFromCurrency(event)"
                />
                <label class="form-label p-5 w-100 text-center pointer" for="other">
                    {{ __('payment.for_foreign_currencies_tariff') }}
                </label>                                
            </div>    
        </div>
    </div>
</form>

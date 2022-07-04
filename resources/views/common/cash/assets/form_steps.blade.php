<div class="bs-stepper-header" role="tablist">
    <div
        class="step"
        data-target="#add_card"
        role="tab"
        id="add_card_trigger"
    >
        <button class="step-trigger pointer">
            <span class="bs-stepper-box">1</span>
            <span class="bs-stepper-label mt-0">
                <span class="bs-stepper-title">
                    {{ __('payment.adding_card') }}
                </span>
            </span>
        </button>
    </div>

    <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div>

    <div
        class="step"
        data-target="#email_block"
        role="tab"
        id="email_block_trigger"
    >
        <button class="step-trigger pointer">
            <span class="bs-stepper-box">2</span>
            <span class="bs-stepper-label mt-0">
                <span class="bs-stepper-title">
                    {{ __('payment.reports_mail') }}
                </span>
            </span>
        </button>
    </div>
    
    <div id="card_number_block_trigger_chevron" class="line hide">
        <i data-feather="chevron-right" class="font-medium-2"></i>
    </div>

    <div
        class="step hide"
        data-target="#card_number_block"
        role="tab"
        id="card_number_block_trigger"
    >
        <button class="step-trigger pointer">
            <span class="bs-stepper-box">3</span>
            <span class="bs-stepper-label mt-0">
                <span class="bs-stepper-title">
                    {{ __('payment.card_number') }}
                </span>
            </span>
        </button>
    </div>
</div>

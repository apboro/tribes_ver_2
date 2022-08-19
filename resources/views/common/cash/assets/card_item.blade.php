<div class="card border-secondary custom-1-border-color h-100 mb-0" data-card-id="{{ $i }}">
    <div class="card-body">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-center justify-content-md-start">
            <i data-feather='credit-card'></i>
            <h5 class="card-title mb-0 ms-1">4276 ···· ···· 8499</h5>
        </div>
        
        <div class="d-flex flex-column align-items-center align-items-md-start mt-1"> 
            <span class="d-block">
                {{ __('payment.reports_mail') }} — 
                <span
                    class="text-primary pointer"
                    onclick="PaymentsPage.paymentsCardListPage.onClickEditEmailForm(event)"
                    data-card-id="{{ $i }}"
                >
                    {{ __('base.edit_low') }}
                </span>
            </span>

            <span id="email_label" class="d-block">email@example.com</span>

            <form id="email_form" class="d-flex mt-1 hide">
                <div class="input-group">
                    <input type="text" id="email" class="form-control" value="email@example.com">
                    
                    <button class="btn btn-outline-success waves-effect px-1" type="submit">
                        <i data-feather='check' class="font-medium-1"></i>
                    </button>
                    
                    <span
                        class="btn btn-outline-danger waves-effect px-1"
                        onclick="PaymentsPage.paymentsCardListPage.onClickCloseEmailForm(event)"
                        data-card-id="{{ $i }}"
                    >
                        <i data-feather='x' class="font-medium-1 pe-none" data-card-id="{{ $i }}"></i>
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="card-footer text-center">
        <button
            type="submit"
            class="btn btn-flat-danger waves-effect"
        >
            {{ __('payment.unpin_card') }}
        </button>
    </div>
</div>

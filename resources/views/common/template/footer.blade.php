<footer class="footer footer-static footer-light mt-auto">
    <div class="footer-content"></div>
    
    <div class="row flex-column flex-xl-row justify-content-between align-items-center">
        <div class="col-12 col-sm-6 col-md-12 col-xl-3 order-2 order-sm-2 order-md-2 order-xl-1 order-xxl-1">
            <div class="text-center text-xl-start">
                {{--<p class="mb-0 text-muted">
                    Телеграм и дискорд бот &copy; 2022
                    <span class="d-none d-sm-inline-block">
                        {{ __('base.all_rights_reserved') }}
                    </span>
                </p>--}}
                <p class="mb-0">
                    {{ __('base.contact_phone') }}:
                    <a href="tel:+78007003378" class="btn-link">8-800-700-33-78</a>
                </p>
            </div>
        </div>
        
        <div class="col-12 col-sm-8 col-md-7 col-lg-12 col-xl-9 order-1 order-sm-1 order-md-1 order-xl-2 order-xxl-2">
            <div class="d-flex justify-content-center justify-content-xl-end">
                <ul class="d-flex flex-column flex-lg-row flex-wrap list-none mb-xl-0">
                    <li class=" mb-sm-0">
                        <a href="{{ route('privacy.index') }}" target="_blank" class="btn-link">
                            {{ __('personal_data.title') }}
                        </a>
                    </li>

                    <li class="mb-sm-0 ms-0 ms-lg-1">
                        <a href="{{ route('payment_processing.index') }}" target="_blank" class="btn-link">
                            {{ __('security_guarantees.title') }}
                        </a>
                    </li>

                    <li class=" mb-sm-0 ms-0 ms-lg-1">
                        <a href="{{ route('sub_terms.index') }}" target="_blank" class="btn-link">
                            {{ __('subscription_consent.title') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

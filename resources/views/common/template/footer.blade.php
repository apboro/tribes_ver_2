<footer class="footer footer-static footer-light mt-auto">
    <div class="content-wrapper container-xxl p-0">
    
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
                        <a class="footer__telegram-link" href="https://t.me/TribesSupport_bot">
                            <img class="footer__telegram-img" src="/images/icons/social/telegram.png" alt="Telegram">
                            Telegram помощник
                        </a>
                    </p>
                    <a href="tel:+78007003378" class="footer__phone">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-phone mr-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                        </svg>
                        8-800-101-42-62
                    </a>
                </div>
            </div>
            
            <div class="col-12 col-sm-8 col-md-7 col-lg-12 col-xl-9 order-1 order-sm-1 order-md-1 order-xl-2 order-xxl-2">
                <div class="d-flex justify-content-center justify-content-xl-end">
                    <ul class="d-flex flex-column flex-lg-row flex-wrap list-none mb-xl-0">
                        <li class="footer__item">
                            <a href="{{ route('terms.index') }}" target="_blank" class="btn-link">
                                {{ __('terms.title') }}
                            </a>
                        </li>

                        <li class="footer__item">
                            <a href="{{ route('payment_processing.index') }}" target="_blank" class="btn-link">
                                {{ __('security_guarantees.title') }}
                            </a>
                        </li>

                        <li class="footer__item">
                            <a href="{{ route('privacy.index') }}" target="_blank" class="btn-link">
                                {{ __('personal_data.title') }}
                            </a>
                        </li>

                        <li class="footer__item">
                            <a href="{{ route('agency_contract.index') }}" target="_blank" class="btn-link">
                                {{ __('agency_contract.title') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

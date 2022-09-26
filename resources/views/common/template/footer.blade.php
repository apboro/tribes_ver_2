<footer class="footer">
    <div class="footer__wrapper container-xxl w-100 d-flex flex-wrap">
    
        <div class="footer__left">
            <p class="footer__telegram-link-wrap">
                <a class="footer__telegram-link" href="https://t.me/TribesSupport_bot">
                    <img class="footer__telegram-img" src="/images/icons/social/telegram.png" alt="Telegram">
                    Telegram помощник
                </a>
            </p>
            <p class="footer__phone-wrap">
                <a href="tel:+78001014262" class="footer__phone">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-phone mr-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                    </svg>
                    8-800-101-42-62
                </a>
            </p>
        </div>
        <ul class="footer__right">
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
</footer>

<footer class="footer2">
    <div class="container">
        <div class="footer2__contacts">
            <a
                class="footer2__telegram-link"
                href="https://t.me/TribesSupport_bot"
            >
                <!-- <img class="footer__telegram-img" src="/images/icons/social/telegram.png" alt="Telegram"> -->
                <svg height="16" viewBox="0 0 176 176" width="16" xmlns="http://www.w3.org/2000/svg">
                    <g id="Layer_2" data-name="Layer 2">
                        <g id="_16.telegram" data-name="16.telegram">
                            <circle id="background" cx="88" cy="88" fill="#1c8adb" r="88"/>
                            <path id="icon" d="m135.94 45.5-1.82.66-98.78 35.59a3.31 3.31 0 0 0 .29 6.4l25.57 7 4.77 14 4.77 14a4.54 4.54 0 0 0 7.32 1.63l13.21-12.48 25.94 17.59c3.17 2.16 7.69.56 8.5-3l17.18-75.91c.84-3.76-3.12-6.85-6.95-5.48zm-12.61 16.85-44.63 36.48-2.1 1.72a2.27 2.27 0 0 0 -.84 1.48l-.47 3.88-1.29 10.9a.5.5 0 0 1 -1 .08l-3.63-10.89-3.75-11.15a2.26 2.26 0 0 1 1.08-2.67l46.44-26.62 8.74-5c1.27-.74 2.57.87 1.45 1.79z" fill="#fff"/>
                        </g>
                    </g>
                </svg>
                Telegram помощник
            </a>

            <a
                href="tel:+78001014262"
                class="footer2__phone"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="icon-tabler icon-tabler-phone mr-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2"></path>
                </svg>
                8-800-101-42-62
            </a>

            <a
                href="https://trbs.co/contacts"
                target="_blank"
                class="link"
            >
                Контакты
            </a>
        </div>

        <div class="footer2__bank">
            <a
                href="{{ route('payment_processing.index') }}"
                target="_blank"
                class="link"
            >
                {{ __('security_guarantees.title') }}
            </a>

            <img
                src="/images/bank-logos.png"
                alt=""
            >
        </div>

        <ul class="footer2__rights-list">
            <li class="footer__rights-item">
                <a href="{{ route('terms.index') }}" target="_blank" class="link">
                    {{ __('terms.title') }}
                </a>
            </li>

            <li class="footer__rights-item">
                <a href="{{ route('privacy.index') }}" target="_blank" class="link">
                    {{ __('personal_data.title') }}
                </a>
            </li>

            <li class="footer__rights-item">
                <a href="{{ route('agency_contract.index') }}" target="_blank" class="link">
                    {{ __('agency_contract.title') }}
                </a>
            </li>
        </ul>
    </div>
</footer>

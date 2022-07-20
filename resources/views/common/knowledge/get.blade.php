@extends('layouts.app')

@section('content')
    
    <div data-plugin="KnowledgeList">
        <section class="breadcrumbs">
            <div class="container">
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="/" class="link breadcrumbs__link">Главная</a>
                    </li>

                    <li class="breadcrumbs__item">
                        <a href="{{ $question->getPublicQuestionsLink() }}" class="link breadcrumbs__link">
                            {{ $community -> title }}
                        </a>
                    </li>

                    <li class="breadcrumbs__item">
                        <span class="breadcrumbs__link--last">
                            Вопрос-ответ
                        </span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="knowledge-list__title">
            <div class="container">
                Вопрос-ответ сообщества "{{ $community -> title }}"
            </div>
        </section>

        <section class="knowledge-list__data-container">
            <div class="container">
                <div class="knowledge-list__col-first">
                    <div class="knowledge-list__author">
                        <div class="knowledge-list__avatar">
                            <img src="/images/no-image.svg" alt="">
                        </div>

                        <div>
                            <span class="knowledge-list__author-name">Lucij Seneka</span>
                            <span class="knowledge-list__author-subscribers">1K subscribers</span>
                        </div>
                        
                        <i class="icon knowledge-list__messenger icon--size-2">
                            <svg width="100%" height="100%" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M29.5 15C29.5 23.0081 23.0081 29.5 15 29.5C6.99187 29.5 0.5 23.0081 0.5 15C0.5 6.99187 6.99187 0.5 15 0.5C23.0081 0.5 29.5 6.99187 29.5 15Z" fill="url(#paint0_linear_29_1587)" stroke="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.78979 14.8415C11.1626 12.9363 14.0785 11.6803 15.5374 11.0735C19.7031 9.34085 20.5687 9.03985 21.1328 9.02989C21.2569 9.02772 21.5344 9.05848 21.7141 9.20432C21.9807 9.42065 21.9822 9.89022 21.9526 10.201C21.7269 12.5729 20.7501 18.3287 20.2532 20.9852C20.0429 22.1093 19.6289 22.4862 19.2281 22.5231C18.357 22.6032 17.6955 21.9474 16.8519 21.3944C15.5317 20.529 14.7858 19.9903 13.5043 19.1458C12.0234 18.1699 12.9834 17.6335 13.8274 16.7569C14.0483 16.5275 17.8863 13.0365 17.9606 12.7198C17.9699 12.6802 17.9785 12.5326 17.8908 12.4546C17.8031 12.3767 17.6736 12.4033 17.5802 12.4245C17.4478 12.4546 15.3388 13.8486 11.2532 16.6065C10.6545 17.0176 10.1123 17.2178 9.62649 17.2074C9.09088 17.1958 8.06069 16.9045 7.29481 16.6556C6.35543 16.3502 5.60883 16.1888 5.67387 15.6702C5.70774 15.4 6.07969 15.1238 6.78979 14.8415Z" fill="white"/>
                                <defs>
                                    <linearGradient id="paint0_linear_29_1587" x1="15" y1="0" x2="15" y2="29.7656" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#2AABEE"/>
                                        <stop offset="1" stop-color="#229ED9"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </i>
                    </div>
    
                    <a
                        href="{{ $community->getPublicKnowledgeLink() }}"
                        class="button-text knowledge-list__list-link button-text--primary"
                    >
                        <i class="icon button-text__icon icon--size-2 button-text__icon--left">
                            <svg width="100%" height="100%" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="icon__fill" d="M10.4668 3.53317C10.6002 3.6665 10.6668 3.79984 10.6668 3.99984C10.6668 4.19984 10.6002 4.33317 10.4668 4.4665L6.9335 7.99984L10.4668 11.5332C10.7335 11.7998 10.7335 12.1998 10.4668 12.4665C10.2002 12.7332 9.80016 12.7332 9.5335 12.4665L5.5335 8.4665C5.26683 8.19984 5.26683 7.79984 5.5335 7.53317L9.5335 3.53317C9.80016 3.2665 10.2002 3.2665 10.4668 3.53317Z" fill="#4C4957"/>
                            </svg>
                        </i>
                        Все вопросы сообществу
                    </a>
                    
                </div>
        
                <ul class="knowledge-list__list">
                    <li class="knowledge-list__item active">
                        <div class="knowledge-list__question">
                            <div class="knowledge-list__item-icon-wrapper">
                                <i class="icon knowledge-list__item-icon icon--size-3">
                                    <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M38 69.6668C55.489 69.6668 69.6666 55.4892 69.6666 38.0002C69.6666 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33331 20.5111 6.33331 38.0002C6.33331 55.4892 20.511 69.6668 38 69.6668Z" fill="transparent" stroke="#7367F0"  stroke-width="2"/>
                                        <path class="icon__stroke" d="M38 46.3333C38 40 42.9907 38.9401 44.7092 37.2258C46.4333 35.506 47.5 33.1276 47.5 30.5C47.5 25.2533 43.2467 21 38 21C35.163 21 32.6164 22.2436 30.8756 24.2155C30.0707 25.1273 29.4382 26.1947 29.0288 27.3669" stroke="#7367F0" stroke-width="2" stroke-linecap="round"/>
                                        <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M38 55C39.1046 55 40 54.1046 40 53C40 51.8954 39.1046 51 38 51C36.8954 51 36 51.8954 36 53C36 54.1046 36.8954 55 38 55Z" fill="#7367F0" />
                                    </svg>
                                </i>

                                <span class="knowledge-list__item-icon-label">
                                    Вопрос
                                </span>
                            </div>
                        
                            <p class="knowledge-list__item-text">
                                {{ $question -> context }}
                            </p>
                        </div>

                        <div class="knowledge-list__answer">
                            <div>
                                @if(empty($question->answer))
                                <p class="knowledge-list__item-text">Ответ отсутствует</p>
                                @else
                                <span class="knowledge-list__item-text">{!! $question->answer->context !!}</span>
                                @endif
                            </div>

                            <div class="knowledge-list__item-icon-wrapper">
                                <i class="icon knowledge-list__item-icon icon--size-3">
                                    <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd" d="M38 69.6668C55.489 69.6668 69.6667 55.4892 69.6667 38.0002C69.6667 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33333 20.5111 6.33333 38.0002C6.33333 55.4892 20.511 69.6668 38 69.6668Z" fill="transparent" stroke="#7367F0" stroke-width="2"/>
                                        <path class="icon__stroke" d="M38 54V32" stroke="#7367F0" stroke-width="2" stroke-linecap="round"/>
                                        <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M38 22C36.8954 22 36 22.8954 36 24C36 25.1046 36.8954 26 38 26C39.1046 26 40 25.1046 40 24C40 22.8954 39.1046 22 38 22Z" fill="#7367F0"/>
                                    </svg>
                                </i>

                                <span class="knowledge-list__item-icon-label">
                                    Ответ
                                </span>

                                <button class="button-text button-text--primary button-text--only-icon">
                                    <i class="icon button-text__icon icon--size-2">
                                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd" d="M17.163 13.7754L19.6368 11.3124C21.5334 9.42417 21.4509 6.22235 19.3894 4.41619C17.4929 2.52794 14.4419 2.52794 12.5453 4.41619L11.1435 5.81186C10.8137 6.14025 10.8137 6.63284 11.1435 6.96123C11.4733 7.28962 11.9681 7.28962 12.2979 6.96123L13.6997 5.56556C15.0191 4.33409 16.9981 4.33409 18.3175 5.56556C19.6368 6.79704 19.6368 8.84949 18.3999 10.1631L15.9261 12.626C15.8437 12.7902 15.7612 12.8723 15.5963 12.9544C14.112 14.0217 12.0505 13.7754 10.9786 12.2976C10.7312 11.9692 10.154 11.8871 9.82415 12.1334C9.49431 12.3797 9.41185 12.9544 9.65923 13.2828C10.6487 14.5964 12.133 15.2531 13.6173 15.2531C14.6892 15.2531 15.6788 14.9247 16.5858 14.268C16.8332 14.1038 16.9981 13.9396 17.163 13.7754ZM10.154 18.3728L11.5558 16.9772C11.8857 16.6488 12.4629 16.7309 12.7927 17.0593C13.1226 17.3877 13.1226 17.8802 12.7927 18.2086L11.3909 19.6043C10.4014 20.5074 9.16451 21 7.92762 21C6.69073 21 5.45384 20.5074 4.46433 19.6043C2.56776 17.7161 2.4853 14.5963 4.38187 12.626L6.85565 10.163L7.3504 9.67044C8.42237 8.84946 9.74172 8.52107 10.9786 8.76736C12.2155 8.93156 13.3699 9.58835 14.1945 10.6556C14.4419 10.984 14.3594 11.5587 14.0296 11.805C13.6998 12.0513 13.1226 11.9692 12.8752 11.6408C12.3804 10.9019 11.6383 10.4914 10.7312 10.3272C9.82418 10.2451 8.99959 10.4914 8.25746 10.984L7.92762 11.3124L5.45384 13.7753C4.21695 15.0068 4.21695 17.1414 5.5363 18.3728C6.85565 19.6043 8.83467 19.6043 10.154 18.3728Z" fill="#4C4957"/>
                                        </svg>
                                    </i>
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>

    
@endsection

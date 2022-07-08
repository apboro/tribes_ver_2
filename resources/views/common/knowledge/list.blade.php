@extends('layouts.app')
@section('content')
    <div data-plugin="KnowledgeList">
        <section class="breadcrumbs">
            <div class="container">
                <ul class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="/" class="breadcrumbs__link">Главная</a>
                    </li>

                    <li class="breadcrumbs__item">
                        <a href="" class="breadcrumbs__link">
                            {{ $community -> title }}
                        </a>
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
                <div class="knowledge-list__author">
                    <div class="knowledge-list__avatar">
                        <img src="/images/no-image.svg" alt="">
                    </div>
                    <span>Lucij Seneka</span>
                    <i class="knowledge-list__messenger">
                        <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                <div class="knowledge-list__control">
                    <div class="knowledge-list__search">
                        <input type="text" placeholder="Search">
                    </div>

                    <button
                        class="knowledge-list__question-btn"
                        onclick="KnowledgeList.showModal()"
                    >
                        ask
                    </button>
                </div>

                <ul class="knowledge-list__list">
                    @forelse($questions as $question)
                    <li class="knowledge-list__item" data-id="{{ $loop->index }}">
                        <div class="knowledge-list__question">
                            <span>{{ $question -> context }}</span>

                            <i class="knowledge-list__help-icon">
                                <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path class="stroke" fill-rule="evenodd" clip-rule="evenodd" d="M17.5003 32.0832C25.5545 32.0832 32.0837 25.554 32.0837 17.4998C32.0837 9.44568 25.5545 2.9165 17.5003 2.9165C9.44617 2.9165 2.91699 9.44568 2.91699 17.4998C2.91699 25.554 9.44617 32.0832 17.5003 32.0832Z" fill="white" stroke="#E1E1E1" stroke-width="3"/>
                                    <path class="stroke" d="M17.4997 20.4167C17.4997 17.5 19.798 17.0119 20.5894 16.2224C21.3834 15.4304 21.8747 14.3351 21.8747 13.125C21.8747 10.7088 19.9159 8.75 17.4997 8.75C16.1931 8.75 15.0203 9.32273 14.2187 10.2308C13.848 10.6507 13.5567 11.1423 13.3682 11.6821" stroke="#E1E1E1" stroke-width="3" stroke-linecap="round"/>
                                    <path class="fill" fill-rule="evenodd" clip-rule="evenodd" d="M17.5003 26.2502C18.3057 26.2502 18.9587 25.5972 18.9587 24.7918C18.9587 23.9864 18.3057 23.3335 17.5003 23.3335C16.6949 23.3335 16.042 23.9864 16.042 24.7918C16.042 25.5972 16.6949 26.2502 17.5003 26.2502Z" fill="#E1E1E1"/>
                                </svg>
                            </i>

                            <button onclick="KnowledgeList.toggleAnswerVisibility('{{ $loop->index }}')">
                                arrow
                            </button>
                        </div>

                        <div class="knowledge-list__answer">
                            <div>
                                @if(empty($question->answer))
                                <span>Ответ отсутствует</span>
                                @else
                                <span>{!! $question->answer->context !!}</span>
                                @endif
                                <i class="knowledge-list__help-icon">
                                    <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.5003 32.0832C25.5545 32.0832 32.0837 25.554 32.0837 17.4998C32.0837 9.44568 25.5545 2.9165 17.5003 2.9165C9.44617 2.9165 2.91699 9.44568 2.91699 17.4998C2.91699 25.554 9.44617 32.0832 17.5003 32.0832Z" fill="white" stroke="#94C98B" stroke-width="3"/>
                                        <path d="M17.4999 24.3477V16.739" stroke="#94C98B" stroke-width="3" stroke-linecap="round"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M17.4996 9.13058C16.239 9.13058 15.217 10.1525 15.217 11.4132C15.217 12.6738 16.239 13.6958 17.4996 13.6958C18.7603 13.6958 19.7822 12.6738 19.7822 11.4132C19.7822 10.1525 18.7603 9.13058 17.4996 9.13058Z" fill="#94C98B"/>
                                    </svg>
                                </i>
                            </div>

                            <div class="knowledge-list__answer-footer">
                                <button
                                    class="knowledge-list__hide-answer-btn"
                                    onclick="KnowledgeList.hideAnswerVisibility('{{ $loop->index }}')"
                                >
                                    hide answer
                                </button>

                                <button class="knowledge-list__share-btn">share</button>
                            </div>
                        </div>
                    </li>
                    @empty
                    Такой записи не существует
                    @endforelse
                </ul>
            </div>
        </section>

        @if ($questions->total() > 0)
        <div class="card-footer">
            <div class="container">
                <!-- Pagination -->
                <div class="d-flex align-items-center justify-content-between flex-wrap mx-0">
                    <div class="dataTables_info" id="DataTables_Table_2_info" role="status" aria-live="polite">
                        {{ __('base.shown_from') }} {{ $questions->perPage() * $questions->currentPage() - $questions->perPage() + 1 }} {{ __('base.to') }} {{ $questions->lastItem() }} {{ __('base.from') }} {{ $questions->total() }} {{ __('base.entries_rus_low') }}
                    </div>

                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
                        {{ $questions->onEachSide(1)->appends(request()->input())->links(!Agent::isMobile() ? 'vendor.pagination.bootstrap-4' : 'vendor.pagination.table-links') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

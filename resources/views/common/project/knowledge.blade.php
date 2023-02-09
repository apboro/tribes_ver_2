@extends('layouts.project')

@section('content')
    @if ($activeCommunity)
        <div data-plugin="KnowledgeList">
            <section class="knowledge-list__title">
                {{--                База знаний сообщества "{{ $community -> title }}"--}}База знаний
            </section>

            <section>
                <div class="d-flex knowledge-list__columns">
                    <button
                            type="button"
                            class="btn btn-outline-primary rounded-pill p-2__button knowledge-list__category-menu"
                            onclick="KnowledgeList.showCategory()">
                        Меню категорий
                    </button>

                    <div class="p-2 p-2_left mobile-hidden">
                        <div class="ms-auto text-muted">
                            <span>Постоянные категории</span>
                        </div>
                        @foreach($activeCommunity->categories as $category)
                            @if ($category->variant === 'permanent')
                                <button
                                        class="button-text knowledge-list__item-btn button-text--primary"
                                        onclick="KnowledgeList.filterByCategory('{{ $category->id }}')"
                                >
                                    {{ $category->title }}
                                </button>
                                @endif
                                @endforeach
                                </button>

                                <div class="ms-auto text-muted">
                                    <span>Пользовательские категории</span>
                                </div>
                                <div>

                                    @foreach($activeCommunity->categories as $category)
                                        @if ($category->variant === 'users')
                                            <button
                                                    class="button-text knowledge-list__item-btn button-text--primary"
                                                    onclick="KnowledgeList.filterByCategory('{{ $category->id }}')"
                                            >
                                                {{ $category->title }}
                                            </button>
                                        @endif
                                    @endforeach

                                    <div class="p-2__buttons">
                                        <button
                                                type="submit"
                                                class="btn btn-outline-primary rounded-pill p-2__button"
                                                onclick="KnowledgeList.showModal('add', {{$activeCommunity->id}})"
                                        >
                                            Добавить категорию
                                        </button>
                                        <button
                                                type="submit"
                                                class="btn btn-outline-primary rounded-pill p-2__button"
                                                onclick="KnowledgeList.showModal('edit', {{$activeCommunity->id}})"
                                        >
                                            Переименовать категорию
                                        </button>
                                        <button
                                                type="submit"
                                                class="btn btn-outline-primary rounded-pill p-2__button"
                                                onclick="KnowledgeList.showModal('del', {{$activeCommunity->id}})"
                                        >
                                            Удалить категорию
                                        </button>
                                    </div>
                                </div>
                    </div>

                    <div class="p-2 p-2_right">
                        <div class="knowledge-list__control">
                            <div class="search-field">
                                <input type="text" id="search_field" class="search-field__field" placeholder="Поиск"
                                       oninput="KnowledgeList.inputClearer()">
                                <span class="search-field__clear" onclick="KnowledgeList.inputClear()"></span>
                                <button onclick="KnowledgeList.search()">
                                    <i class="icon search-field__icon icon--size-2">
                                        <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path class="icon__stroke" d="M14.4121 14.4121L20 20" stroke="#B5B4B8"
                                                  stroke-width="2" stroke-linecap="round"/>
                                            <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M10 16C13.3137 16 16 13.3137 16 10C16 6.68629 13.3137 4 10 4C6.68629 4 4 6.68629 4 10C4 13.3137 6.68629 16 10 16Z"
                                                  stroke="#B5B4B8" stroke-width="2"/>
                                        </svg>
                                    </i>
                                </button>
                            </div>

                            <button class="btn-sm btn-outline-primary rounded-pill"
                                    onclick="KnowledgeList.openKnowledgeForm()">Добавить
                                вопрос-ответ
                            </button>

                        </div>

                        <div class="knowledge-list__new_knowledge">
                            <div class="knowledge-list__question">
                                <div class="knowledge-list__item-icon-wrapper">
                                    <i class="icon knowledge-list__item-icon icon--size-3">
                                        <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M38 69.6668C55.489 69.6668 69.6666 55.4892 69.6666 38.0002C69.6666 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33331 20.5111 6.33331 38.0002C6.33331 55.4892 20.511 69.6668 38 69.6668Z"
                                                  fill="transparent" stroke="#7367F0" stroke-width="2"/>
                                            <path class="icon__stroke"
                                                  d="M38 46.3333C38 40 42.9907 38.9401 44.7092 37.2258C46.4333 35.506 47.5 33.1276 47.5 30.5C47.5 25.2533 43.2467 21 38 21C35.163 21 32.6164 22.2436 30.8756 24.2155C30.0707 25.1273 29.4382 26.1947 29.0288 27.3669"
                                                  stroke="#7367F0" stroke-width="2" stroke-linecap="round"/>
                                            <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M38 55C39.1046 55 40 54.1046 40 53C40 51.8954 39.1046 51 38 51C36.8954 51 36 51.8954 36 53C36 54.1046 36.8954 55 38 55Z"
                                                  fill="#7367F0"/>
                                        </svg>
                                    </i>

                                    <span class="knowledge-list__item-icon-label">
                                    Вопрос
                                </span>

                                </div>

                                <textarea id="vopros" class="knowledge-list__item-text" placeholder="Напишите вопрос"
                                          oninput="KnowledgeList.inputLengthCheck()"></textarea>
                            </div>

                            <div class="knowledge-list__answer">
                                <textarea id="otvet" class="knowledge-list__item-text" placeholder="Напишите ответ"
                                          oninput="KnowledgeList.inputLengthCheck()"></textarea>

                                <div class="knowledge-list__item-icon-wrapper">
                                    <i class="icon knowledge-list__item-icon icon--size-3">
                                        <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M38 69.6668C55.489 69.6668 69.6667 55.4892 69.6667 38.0002C69.6667 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33333 20.5111 6.33333 38.0002C6.33333 55.4892 20.511 69.6668 38 69.6668Z"
                                                  fill="transparent" stroke="#7367F0" stroke-width="2"/>
                                            <path class="icon__stroke" d="M38 54V32" stroke="#7367F0"
                                                  stroke-width="2" stroke-linecap="round"/>
                                            <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M38 22C36.8954 22 36 22.8954 36 24C36 25.1046 36.8954 26 38 26C39.1046 26 40 25.1046 40 24C40 22.8954 39.1046 22 38 22Z"
                                                  fill="#7367F0"/>
                                        </svg>
                                    </i>

                                    <span class="knowledge-list__item-icon-label">
                                    Ответ
                                </span>

                                </div>


                            </div>
                            <div class="knowledge-list__new_knowledge__buttons">
                                <button disabled
                                        class="btn-sm btn-outline-primary rounded-pill knowledge-list__new_knowledge__buttons-save"
                                        onclick="KnowledgeList.processKnowledge('add',{{$activeCommunity->id}})">
                                    Сохранить
                                </button>
                                <button class="btn-sm btn-outline-primary rounded-pill knowledge-list__new_knowledge__buttons-cancel"
                                        onclick="KnowledgeList.openKnowledgeForm()">Отмена
                                </button>
                            </div>
                        </div>

                        <ul class="knowledge-list__list">
                            @forelse($activeCommunity->questions as $question)
                                <li class="knowledge-list__item"
                                    data-id="{{ $question->id }}"
                                    data-category="{{ $question->category_id }}">

                                    <div class="knowledge-list__item-actions">
                                        <button id="edit_button" class="knowledge-list__item-actions__button_edit"
                                                onclick="KnowledgeList.editQuestionShow({{$question->id}})"></button>
                                        <button class="knowledge-list__item-actions__button_delete"
                                                onclick="KnowledgeList.processKnowledge('del', {{$activeCommunity->id}}, {{$question->id}})"></button>
                                    </div>

                                    <div class="knowledge-list__question">
                                        <div class="knowledge-list__item-icon-wrapper">
                                            <i class="icon knowledge-list__item-icon icon--size-3">
                                                <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M38 69.6668C55.489 69.6668 69.6666 55.4892 69.6666 38.0002C69.6666 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33331 20.5111 6.33331 38.0002C6.33331 55.4892 20.511 69.6668 38 69.6668Z"
                                                          fill="transparent" stroke="#7367F0" stroke-width="2"/>
                                                    <path class="icon__stroke"
                                                          d="M38 46.3333C38 40 42.9907 38.9401 44.7092 37.2258C46.4333 35.506 47.5 33.1276 47.5 30.5C47.5 25.2533 43.2467 21 38 21C35.163 21 32.6164 22.2436 30.8756 24.2155C30.0707 25.1273 29.4382 26.1947 29.0288 27.3669"
                                                          stroke="#7367F0" stroke-width="2" stroke-linecap="round"/>
                                                    <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M38 55C39.1046 55 40 54.1046 40 53C40 51.8954 39.1046 51 38 51C36.8954 51 36 51.8954 36 53C36 54.1046 36.8954 55 38 55Z"
                                                          fill="#7367F0"/>
                                                </svg>
                                            </i>

                                            <span class="knowledge-list__item-icon-label">
                                            Вопрос
                                        </span>

                                        </div>
                                        <p id="{{$activeCommunity->id}}-{{$question->id}}" class="knowledge-list__item-text">
                                            {{ $question -> context }}
                                        </p>

                                        {{--                                    <button--}}
                                        {{--                                            class="button-text knowledge-list__item-btn button-text--primary"--}}
                                        {{--                                            onclick="KnowledgeList.toggleAnswerVisibility('{{ $loop->index }}')"--}}
                                        {{--                                    >--}}
                                        {{--                                        Смотреть ответ--}}
                                        {{--                                    </button>--}}
                                    </div>

                                    <div class="knowledge-list__answer">
                                        <div>
                                            @if(empty($question->answer))
                                                <p class="knowledge-list__item-text">Ответ отсутствует</p>
                                            @else
                                                <span id="{{$activeCommunity->id}}-{{$question->answer->id}}"class="knowledge-list__item-text">{!! $question->answer->context !!}</span>
                                            @endif
                                        </div>

                                        <div class="knowledge-list__item-icon-wrapper">
                                            <i class="icon knowledge-list__item-icon icon--size-3">
                                                <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path class="icon__stroke" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M38 69.6668C55.489 69.6668 69.6667 55.4892 69.6667 38.0002C69.6667 20.5111 55.489 6.3335 38 6.3335C20.511 6.3335 6.33333 20.5111 6.33333 38.0002C6.33333 55.4892 20.511 69.6668 38 69.6668Z"
                                                          fill="transparent" stroke="#7367F0" stroke-width="2"/>
                                                    <path class="icon__stroke" d="M38 54V32" stroke="#7367F0"
                                                          stroke-width="2" stroke-linecap="round"/>
                                                    <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M38 22C36.8954 22 36 22.8954 36 24C36 25.1046 36.8954 26 38 26C39.1046 26 40 25.1046 40 24C40 22.8954 39.1046 22 38 22Z"
                                                          fill="#7367F0"/>
                                                </svg>
                                            </i>

                                            <span class="knowledge-list__item-icon-label">
                                            Ответ
                                        </span>

                                            {{--                                        <button class="button-text button-text--primary button-text--only-icon">--}}
                                            {{--                                            <i class="icon button-text__icon icon--size-2">--}}
                                            {{--                                                <svg width="100%" height="100%" viewBox="0 0 24 24" fill="none"--}}
                                            {{--                                                     xmlns="http://www.w3.org/2000/svg">--}}
                                            {{--                                                    <path class="icon__fill" fill-rule="evenodd" clip-rule="evenodd"--}}
                                            {{--                                                          d="M17.163 13.7754L19.6368 11.3124C21.5334 9.42417 21.4509 6.22235 19.3894 4.41619C17.4929 2.52794 14.4419 2.52794 12.5453 4.41619L11.1435 5.81186C10.8137 6.14025 10.8137 6.63284 11.1435 6.96123C11.4733 7.28962 11.9681 7.28962 12.2979 6.96123L13.6997 5.56556C15.0191 4.33409 16.9981 4.33409 18.3175 5.56556C19.6368 6.79704 19.6368 8.84949 18.3999 10.1631L15.9261 12.626C15.8437 12.7902 15.7612 12.8723 15.5963 12.9544C14.112 14.0217 12.0505 13.7754 10.9786 12.2976C10.7312 11.9692 10.154 11.8871 9.82415 12.1334C9.49431 12.3797 9.41185 12.9544 9.65923 13.2828C10.6487 14.5964 12.133 15.2531 13.6173 15.2531C14.6892 15.2531 15.6788 14.9247 16.5858 14.268C16.8332 14.1038 16.9981 13.9396 17.163 13.7754ZM10.154 18.3728L11.5558 16.9772C11.8857 16.6488 12.4629 16.7309 12.7927 17.0593C13.1226 17.3877 13.1226 17.8802 12.7927 18.2086L11.3909 19.6043C10.4014 20.5074 9.16451 21 7.92762 21C6.69073 21 5.45384 20.5074 4.46433 19.6043C2.56776 17.7161 2.4853 14.5963 4.38187 12.626L6.85565 10.163L7.3504 9.67044C8.42237 8.84946 9.74172 8.52107 10.9786 8.76736C12.2155 8.93156 13.3699 9.58835 14.1945 10.6556C14.4419 10.984 14.3594 11.5587 14.0296 11.805C13.6998 12.0513 13.1226 11.9692 12.8752 11.6408C12.3804 10.9019 11.6383 10.4914 10.7312 10.3272C9.82418 10.2451 8.99959 10.4914 8.25746 10.984L7.92762 11.3124L5.45384 13.7753C4.21695 15.0068 4.21695 17.1414 5.5363 18.3728C6.85565 19.6043 8.83467 19.6043 10.154 18.3728Z"--}}
                                            {{--                                                          fill="#4C4957"/>--}}
                                            {{--                                                </svg>--}}
                                            {{--                                            </i>--}}
                                            {{--                                        </button>--}}
                                        </div>

                                        {{--                                    <button--}}
                                        {{--                                            class="button-text knowledge-list__item-btn button-text--primary"--}}
                                        {{--                                            onclick="KnowledgeList.hideAnswerVisibility('{{ $loop->index }}')"--}}
                                        {{--                                    >--}}
                                        {{--                                        Скрыть ответ--}}
                                        {{--                                    </button>--}}
                                        <div id="{{$question->id}}" class="knowledge-list__new_knowledge">
                                            <div class="knowledge-list__new_knowledge__buttons">
                                                <button id="save_question_button"
                                                        class="btn-sm btn-outline-primary rounded-pill knowledge-list__new_knowledge__buttons-save"
                                                        onclick="KnowledgeList.editQuestion({{$question->category->id}}, {{$activeCommunity->id}}, {{$question->id}}, {{$question->answer->id}})">
                                                    Сохранить
                                                </button>
                                                <button class="btn-sm btn-outline-primary rounded-pill knowledge-list__new_knowledge__buttons-cancel"
                                                        onclick="KnowledgeList.cancelEdit({{$question->id}})">Отмена
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </li>
                            @empty
                                Такой записи не существует
                            @endforelse
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    @else
        Выберите сообщество
    @endif
@endsection

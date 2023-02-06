@extends('layouts.auth')

@section('content')
    @if ($community)
        <div data-plugin="KnowledgeList" style="width: 100%; min-height: 100vh">
            <section class="knowledge-list__title">
                База знаний сообщества "{{ $community -> title }}"
            </section>

            <section>
                <div class="d-flex knowledge-list__columns">
                    <div class="p-2 p-2_left">

                        <div class="ms-auto text-muted">
                            <span>Категории</span>
                        </div>
                        <div>

                            @foreach($community->categories as $category)
                                @if ($category->variant === 'users' && !$category->questions->isEmpty())
                                    <button
                                            class="button-text knowledge-list__item-btn button-text--primary"
                                            onclick="KnowledgeList.filterByCategory('{{ $category->id }}')"
                                    >
                                        {{ $category->title }}
                                    </button>
                                @endif
                            @endforeach

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

                        </div>
                        <ul class="knowledge-list__list">
                        @forelse ($community->questions as $question)
                                @if ($question->category->variant ==='users')
                                    <li class="knowledge-list__item" data-id="{{ $loop->index }}"
                                        data-category="{{ $question->category_id }}">

                                        <div class="knowledge-list__question">
                                            <div class="knowledge-list__item-icon-wrapper">
                                                <i class="icon knowledge-list__item-icon icon--size-3">
                                                    <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path class="icon__stroke" fill-rule="evenodd"
                                                              clip-rule="evenodd"
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
                                                    <svg width="100%" height="100%" viewBox="0 0 76 76" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path class="icon__stroke" fill-rule="evenodd"
                                                              clip-rule="evenodd"
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
                                    </li>
                                @endif
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

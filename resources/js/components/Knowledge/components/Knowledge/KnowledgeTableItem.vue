<template>
    <div
        class="knowledge-table__item-wrapper"
        :class="{ 'open': isVisibleFullQuestion }"
    >
        <!-- Строка -->
        <div class="knowledge-table__row" :class="{ 'active': isAddedQuestion }">
            <!-- Выделить -->
            <div class="knowledge-table__item">
                <v-checkbox
                    :id="`field_${ question.id }`"
                    v-model="isAddedQuestion"
                />
            </div>

            <!-- Вопрос -->
            <div
                class="knowledge-table__item knowledge-table__item--question"
                @click="toggleQuestion"
            >
                <p class="knowledge-table__item-question">{{ question.context }}</p>
                
                <!-- <transition name="a-arrow"> -->
                    <template v-if="isVisibleFullQuestion">
                        <v-icon
                            key="0"
                            name="arrow-up"
                            size="1"
                            class="knowledge-table__item-icon"
                        />
                    </template>

                    <template v-else>
                        <v-icon
                            key="1"
                            name="arrow-down"
                            size="1"
                            class="knowledge-table__item-icon"
                        />
                    </template>
                <!-- </transition> -->
            </div>

            <!-- Дата -->
            <div class="knowledge-table__item knowledge-table__item--date">
                {{ formatDate(question.created_at) }}
            </div>

            <!-- Обращений -->
            <div class="knowledge-table__item knowledge-table__item--enquery">
                {{ question.c_enquiry }}
            </div>

            <!-- Статус -->
            <div class="knowledge-table__item">
                <template v-if="question.is_public">
                    <span
                        class="knowledge-table__status knowledge-table__status--public"
                    >
                        Опубликовано
                    </span>
                </template>

                <template v-if="!question.is_public && !question.is_draft">
                    <span
                        class="knowledge-table__status knowledge-table__status--not-public"
                    >
                        Не опубликовано
                    </span>
                </template>

                <template v-if="question.is_draft">
                    <span
                        class="knowledge-table__status knowledge-table__status--draft"
                    >
                        Черновик
                    </span>
                </template>
            </div>

            <!-- Действия -->
            <div class="knowledge-table__item knowledge-table__item--center">
                <v-dropdown>
                    <template #tooglePanel="{ toggleDropdownVisibility }">
                        <button
                            class="button-text button-text--primary button-text--only-icon"
                            @click="toggleDropdownVisibility"
                        >
                            <v-icon
                                name="vertical-dots"
                                size="1"
                                class="button-text__icon"
                            />
                        </button>
                    </template>

                    <!-- Меню действий -->
                    <template #body="{ toggleDropdownVisibility }" class="">
                        <div
                            class="knowledge-table__action-menu"
                            @click="toggleDropdownVisibility"
                        >
                            <button
                                class="knowledge-table__action-item"
                                v-if="isPublic"
                                @click="removeFromPublication"
                            >
                                Снять с публикации
                            </button>

                            <button
                                class="knowledge-table__action-item"
                                v-else
                                @click="publish"
                            >
                                Опубликовать
                            </button>
                            
                            <a
                                :href="question.public_link"
                                target="_blank"
                                class="knowledge-table__action-item"
                            >
                                Предпросмотр
                            </a>

                            <button
                                class="knowledge-table__action-item"
                                @click="openQuestionPopup"
                            >
                                Редактировать
                            </button>

                            <button
                                class="knowledge-table__action-item"
                                @click="copyLink"
                            >
                                Скопировать ссылку
                            </button>
                            
                            <button
                                class="knowledge-table__action-item"
                                @click="removeQuestion"
                            >
                                Удалить
                            </button>
                        </div>
                    </template>
                </v-dropdown>
            </div>
        </div>
        
        <!-- Скрытая строка с вопросом/ответом -->
        <transition name="a-cell">
            <div
                class="knowledge-table__row"
                v-if="isVisibleFullQuestion"
            >
                <div class="knowledge-table__item knowledge-table__full">
                    <div class="knowledge-table__full-question">
                        <p class="knowledge-table__full-title">
                            Вопрос:
                        </p>
                        <p>{{ question.context }}</p>
                    </div>

                    <div
                        class="knowledge-table__full-answer"
                        :class="{ 'hide': isLongAnswer }"
                        ref="answer"
                    >
                        <p class="knowledge-table__full-title">
                            Ответ:
                        </p>
                        
                        <template v-if="question.answer">
                            <div class="knowledge-table__answer-block" v-html="question.answer.context"></div>
                            
                            <template v-if="isVisibleFullAnswerBtn">
                                <button
                                    class="button-text knowledge-table__open-answer-btn button-text--primary"
                                    @click="toggleFullAnswerVisibility"
                                >
                                    {{ isLongAnswer ? 'Читать полностью' : 'Скрыть ответ' }}
                                </button>
                            </template>
                        </template>
                        
                        <template v-else>
                            <p></p>
                        </template>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Модальное окно редактирования -->
        <transition name="a-overlay">
            <v-overlay
                v-if="isVisibleQuestionPopup"
                @onClick="closeQuestionPopup"
            />
        </transition>

        <transition name="a-popup">
            <v-popup
                theme="primary"
                title="Редактировать вопрос-ответ"
                @close="closeQuestionPopup"
                v-if="isVisibleQuestionPopup"
            >
                <template #body>
                    <div class="form-item">
                        <label
                            class="form-label form-item__label"
                            for="question"
                        >
                            Вопрос
                        </label>

                        <input
                            type="text"
                            id="question"
                            class="form-control"
                            placeholder="Что такое Tribes?"
                            v-model="questionText"
                        >

                        <span
                            class="form-message form-message--danger form-item__message"
                            v-if="false"
                        ></span>
                    </div>

                    <div class="form-item">
                        <label
                            class="form-label form-item__label"
                        >
                            Ответ
                        </label>

                        <text-editor
                            :text="answerText"
                            @edit="setAnswer"
                        />
                    </div>

                    <div class="knowledge-modal__controls">
                        <v-checkbox
                            id="question_draft"
                            label="Черновик"
                            v-model="draft"
                        />

                        <div class="toggle-switch">
                            <label class="toggle-switch__switcher">
                                <input
                                    type="checkbox"
                                    id="is_published_question"
                                    class="toggle-switch__input"
                                    v-model="isPublic"
                                >

                                <span class="toggle-switch__slider"></span>
                            </label>

                            <label for="is_published_question" class="toggle-switch__label">
                                {{ isPublic ? 'Опубликовано' : 'Не опубликовано' }}
                            </label>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <button
                        class="button-empty button-empty--primary"
                        @click="cancelQuestion"
                    >
                        Отмена
                    </button>
                    
                    <button
                        class="button-filled button-filled--primary"
                        @click="editQuestion"
                    >
                        Сохранить
                    </button>
                </template>
            </v-popup>
        </transition>
    </div>
</template>

<script>
    import VPopup from '../VPopup.vue';
    import TextEditor from '../TextEditor.vue';
    import VOverlay from '../VOverlay.vue';
    import VDropdown from '../VDropdown.vue';
    import VIcon from '../VIcon.vue';
    import VCheckbox from '../VCheckbox.vue';
    import { mapActions, mapGetters, mapMutations } from 'vuex';
    import {bodyLock, bodyUnLock, timeFormatting} from '../../../../core/functions';
    
    export default {
        name: 'KnowledgeTableItem',

        components: {
            VPopup,
            VIcon,
            TextEditor,
            VOverlay,
            VDropdown,
            VCheckbox,
         },

        props: {
            question: {
                type: Object,
                default: {}
            },
        },

        data() {
            return {
                isVisibleFullQuestion: false,
                isVisibleQuestionPopup: false,
                
                oldQuestionText: this.question.context,
                oldAnswerText: this.question.answer ? this.question.answer.context : '',

                questionText: this.question.context,
                answerText: this.question.answer ? this.question.answer.context : '',

                draft: this.question.is_draft,
                isPublic: this.question.is_public,

                isLongAnswer: false,
                isVisibleFullAnswerBtn: false,
            }
        },

        computed: {
            ...mapGetters('knowledge', ['IS_ADDED_QUESTIONS']),

            isAddedQuestion: {
                // проверяем есть ли такая запись в массиве, и ставим чек в зависимости от ответа
                get() {
                    return this.IS_ADDED_QUESTIONS(this.question.id);
                },

                set(isAdded) {
                    console.log(132);
                    // при изменении добавляем или удалеяем элемент из массива
                    if (isAdded) {
                        this.ADD_ID_FOR_OPERATIONS(this.question.id);
                    } else {
                        this.REMOVE_ID_FOR_OPERATIONS(this.question.id);
                    }
                },
                
            },
        },

        methods: {
            ...mapActions('knowledge', ['EDIT_QUESTION', 'REMOVE_QUESTION']),
            ...mapMutations('knowledge', ['ADD_ID_FOR_OPERATIONS', 'REMOVE_ID_FOR_OPERATIONS']),
            
            toggleQuestion() {
                this.isVisibleFullQuestion = !this.isVisibleFullQuestion;
                
                this.$nextTick(() => {
                    if (this.$refs.answer) {
                        if (this.$refs.answer.getBoundingClientRect().height > 100) {
                            this.isLongAnswer = true;
                            this.isVisibleFullAnswerBtn = true;
                            //this.$refs.answer.classList.add('close');
                        }
                    }
                })
            },

            openQuestionPopup() {
                this.isVisibleQuestionPopup = true;
                bodyLock();
            },

            closeQuestionPopup() {
                this.isVisibleQuestionPopup = false;
                bodyUnLock();
            },

            setAnswer(answer) {
                this.answerText = answer;
            },

            cancelQuestion() {
                this.questionText = this.oldQuestionText;
                this.answerText = this.oldAnswerText;
                this.closeQuestionPopup();
            },

            editQuestion() {
                this.EDIT_QUESTION({
                    question: {
                        id: this.question.id,
                        context: this.questionText,
                        is_draft: this.draft,
                        is_public: this.isPublic,
                        answer: {
                            context: this.answerText,
                            is_draft: false
                        }
                    },
                });
            },

            removeFromPublication() {
                this.isPublic = false;
                this.editQuestion();
            },

            publish() {
                this.isPublic = true;
                this.editQuestion();
            },

            removeQuestion() {
                this.REMOVE_QUESTION({ id: this.question.id });
            },

            formatDate(date) {
                return timeFormatting({
                    date,
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric'
                });
            },

            toggleFullAnswerVisibility() {
                this.isLongAnswer = !this.isLongAnswer;
            },

            copyLink() {
                copyText(this.question.public_link);
            }
        },
    }
</script>

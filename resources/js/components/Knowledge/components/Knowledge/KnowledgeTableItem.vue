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
                
                <transition name="a-question-arrow" mode="out-in">
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
                </transition>
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
                <knowledge-actions-dropdown
                    :question="question"
                    :isPublic="isPublic"
                    @removeFromPublication="removeFromPublication"
                    @publish="publish"
                    @openQuestionPopup="openQuestionPopup"
                    @removeQuestion="openConfirmDeleteKnowledgeQuestionPopup"
                />
            </div>
        </div>
        
        <!-- Скрытая строка с вопросом/ответом -->
        <transition name="a-table-row">
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
                            v-model="changeDraft"
                        />

                        <toggle-switch
                            id="is_published_question"
                            :label="isPublic ? 'Опубликовано' : 'Не опубликовано'"
                            v-model="changePublic"
                        />
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

        <!-- Модальное окно подтверждения удаления -->
        <transition name="a-overlay">
            <v-overlay
                v-if="isVisibleConfirmDeleteKnowledgeQuestionPopup"
                @onClick="closeConfirmDeleteKnowledgeQuestionPopup"
            />
        </transition>

        <transition name="a-popup">
            <knowledge-confirm-delete-popup
                v-if="isVisibleConfirmDeleteKnowledgeQuestionPopup"
                @closeConfirmDeletePopup="closeConfirmDeleteKnowledgeQuestionPopup"
                @confirm="confirmDeleteKnowledgeQuestion"
            />
        </transition>
    </div>
</template>

<script>
    import { mapActions, mapGetters, mapMutations } from 'vuex';
    import { bodyLock, bodyUnLock, timeFormatting } from '../../../../core/functions';
    import VPopup from '../VPopup.vue';
    import TextEditor from '../TextEditor.vue';
    import VOverlay from '../VOverlay.vue';
    import VIcon from '../VIcon.vue';
    import VCheckbox from '../VCheckbox.vue';
    import ToggleSwitch from '../ToggleSwitch.vue';
    import KnowledgeActionsDropdown from './KnowledgeActionsDropdown.vue';
    import KnowledgeConfirmDeletePopup from './KnowledgeConfirmDeletePopup.vue';
    
    export default {
        name: 'KnowledgeTableItem',

        components: {
            VPopup,
            VIcon,
            TextEditor,
            VOverlay,
            ToggleSwitch,
            VCheckbox,
            KnowledgeActionsDropdown,
            KnowledgeConfirmDeletePopup,
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
                isVisibleConfirmDeleteKnowledgeQuestionPopup: false,
                
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
                    // при изменении добавляем или удалеяем элемент из массива
                    if (isAdded) {
                        this.ADD_ID_FOR_OPERATIONS(this.question.id);
                    } else {
                        this.REMOVE_ID_FOR_OPERATIONS(this.question.id);
                    }
                },
            },

            changeDraft: {
                get() {
                    return this.draft;
                },
                set(bool) {
                    if (bool) {
                        this.isPublic = false;
                    }
                    this.draft = bool;
                }
            },

            changePublic: {
                get() {
                    return this.isPublic;
                },
                set(bool) {
                    if (bool) {
                        this.draft = false;
                    }
                    this.isPublic = bool;
                }
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

            openConfirmDeleteKnowledgeQuestionPopup() {
                this.isVisibleConfirmDeleteKnowledgeQuestionPopup = true;
                bodyLock();
            },

            closeConfirmDeleteKnowledgeQuestionPopup() {
                this.isVisibleConfirmDeleteKnowledgeQuestionPopup = false;
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

            confirmDeleteKnowledgeQuestion() {
                this.REMOVE_QUESTION({ id: this.question.id });
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
        },
    }
</script>

<template>
    <div class="knowledge-table__item-wrapper">
        <!-- Строка -->
        <div class="knowledge-table__row">
            <!-- Выделить -->
            <div class="knowledge-table__item">
                <input
                    type="checkbox"
                    v-model="isAddedQuestion"
                >
            </div>

            <!-- Вопрос -->
            <div
                class="knowledge-table__item knowledge-table__question"
                @click="toggleQuestion"
            >
                {{ question.context }}
            </div>

            <!-- Дата -->
            <div class="knowledge-table__item">
                {{ formatDate(question.created_at) }}
            </div>

            <!-- Обращений -->
            <div class="knowledge-table__item">
                {{ question.c_enquiry }}
            </div>

            <!-- Статус -->
            <div class="knowledge-table__item">
                <span
                    class="knowledge-table__status"
                    v-for="label in getStatus()"
                    :key="label"
                >
                    {{ label }}
                </span>
            </div>

            <!-- Действия -->
            <div class="knowledge-table__item">
                <v-dropdown>
                    <template #tooglePanel="{ toggleDropdownVisibility }">
                        <button
                            class="knowledge-table__action-btn"
                            @click="toggleDropdownVisibility"
                        >
                            <span class="knowledge-table__action-icon"></span>
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
                <div class="knowledge-table__item knowledge-table__item--full">
                    <p>Вопрос</p>
                    <p>{{ question.context }}</p>
                    <p>Ответ</p>
                    <template v-if="question.answer">
                        <div v-html="question.answer.context"></div>
                    </template>
                    <template v-else>
                        <p></p>
                    </template>
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
                @close="closeQuestionPopup"
                v-if="isVisibleQuestionPopup"
            >
                <template #title>
                    <h2 class="v-popup__title">
                        Редактировать вопрос-ответ
                    </h2>
                </template>

                <template #body>
                    <label for="question">Вопрос</label>
                    <input
                        type="text"
                        id="question"
                        class="form-item"
                        v-model="questionText"
                    >

                    <label for="">Ответ</label>
                    <text-editor
                        :text="answerText"
                        @edit="setAnswer"
                    />

                    <div class="knowledge-modal__controls">
                        <div class="knowledge-filter__item">
                            <input
                                type="checkbox"
                                id="new_question_draft"
                                v-model="draft"
                            >
                            <label for="new_question_draft">Черновик</label>
                        </div>

                        <div class="toggle-switch">
                            <label class="toggle-switch__switcher">
                                <input
                                    type="checkbox"
                                    id="is_published_new_question"
                                    v-model="isPublic"
                                >
                                <span class="toggle-switch__slider"></span>
                            </label>

                            <label
                                for="is_published_new_question"
                                class="toggle-switch__label"
                            >
                                Опубликовано
                            </label>
                        </div>
                    </div>
                </template>

                <template #footer>
                    <button
                        class="v-popup__footer-btn"
                        @click="cancelQuestion"
                    >
                        Cancel
                    </button>
                    
                    <button
                        class="v-popup__footer-btn"
                        @click="editQuestion"
                    >
                        Submit
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
    import { mapActions, mapGetters, mapMutations } from 'vuex';
    import {timeFormatting} from '../../../../core/functions';
    
    export default {
        name: 'KnowledgeTableItem',

        components: {
            VPopup,
            TextEditor,
            VOverlay,
            VDropdown,
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
            }
        },

        computed: {
            ...mapGetters(['IS_ADDED_QUESTIONS']),

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
        },

        methods: {
            ...mapActions(['EDIT_QUESTION', 'REMOVE_QUESTION']),
            ...mapMutations(['ADD_ID_FOR_OPERATIONS', 'REMOVE_ID_FOR_OPERATIONS']),

            toggleQuestion() {
                this.isVisibleFullQuestion = !this.isVisibleFullQuestion;
            },

            openQuestionPopup() {
                this.isVisibleQuestionPopup = true;
            },

            closeQuestionPopup() {
                this.isVisibleQuestionPopup = false;
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

            getStatus() {
                const statuses = [];
                if (!this.question.is_public) {
                    statuses.push('Непублик');
                } else if (this.question.is_public) {
                    statuses.push('Публик');
                }
                if (this.question.is_draft) {
                    statuses.push('Черн');
                }
                
                return statuses;
            },

            copyLink() {
                copyText(this.question.public_link);
            }
        },
    }
</script>

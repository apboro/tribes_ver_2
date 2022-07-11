<template>
    <div class="table-knowledge__content">
        <tr class="table-knowledge__row">
            <td class="table-knowledge__item">
                <input type="checkbox">
            </td>

            <td class="table-knowledge__item table-knowledge__question" @click="toggleQuestion">
                {{ question.context }}
            </td>

            <td class="table-knowledge__item">{{ formatDate(question.created_at) }}</td>

            <td class="table-knowledge__item">{{ question.с_enquiry }}</td>

            <td class="table-knowledge__item">
                <span
                    class="table-knowledge__status"
                    v-for="label in getStatus()"
                    :key="label"
                >
                    {{ label }}
                </span>
            </td>

            <td class="table-knowledge__item table-knowledge__actions">
                <v-dropdown>
                    <template #tooglePanel="{ toggleDropdownVisibility }">
                        <button
                            class="table-knowledge__action-btn"
                            @click="toggleDropdownVisibility"
                        >
                            <span class="table-knowledge__action-icon"></span>
                        </button>
                    </template>

                    <template #body class="">
                        <div class="table-knowledge__action-menu">
                            <button class="table-knowledge__action-item" @click="log(1)">Снять с публикации</button>
                            <button class="table-knowledge__action-item">Предпросмотр</button>
                            <button class="table-knowledge__action-item" @click="openQuestionPopup">Редактировать</button>
                            <button class="table-knowledge__action-item">Скопировать ссылку</button>
                            <button class="table-knowledge__action-item">Удалить</button>
                        </div>
                    </template>
                </v-dropdown>
            </td>
        </tr>
        
        <transition name="a-cell">
            <tr class="table-knowledge__row" v-if="isVisibleFullQuestion">
                <td colspan="2"  class="table-knowledge__item">
                    <p>Вопрос</p>
                    <p>{{ question.context }}</p>
                    <p>Ответ</p>
                    <template v-if="question.answer">
                        <div v-html="question.answer.context">{{question.answer.context}}</div>
                    </template>
                    <template v-else>
                        <p></p>
                    </template>
                </td>
            </tr>
        </transition>

        <!-- Modal window -->
        <!-- <portal to="destination"> -->
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
                        <h2 class="v-popup__title">Редактировать вопрос-ответ</h2>
                    </template>

                    <template #body>
                        <label for="question">Вопрос</label>
                        <input
                            type="text"
                            id="question"
                            class="form-control"
                            v-model="questionText"
                        >

                        <label for="">Ответ</label>
                        <text-editor
                            :text="answerText"
                            @edit="setAnswer"
                        />

                        <div class="knowledge-modal__controls">
                            <div class="knowledge-filter__item">
                                <input type="checkbox" id="new_question_draft" v-model="draft">
                                <label for="new_question_draft">Черновик</label>
                            </div>

                            <div class="toggle-switch">
                                <label class="toggle-switch__switcher">
                                    <input type="checkbox" id="is_published_new_question" v-model="isPublic">
                                    <span class="toggle-switch__slider"></span>
                                </label>

                                <label for="is_published_new_question" class="toggle-switch__label">
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
                            @click="submitQuestion"
                        >
                            Submit
                        </button>
                    </template>
                </v-popup>
            </transition>
        <!-- </portal> -->
    </div>
</template>

<script>
    import VPopup from '../VPopup.vue';
    import TextEditor from '../TextEditor.vue';
    import VOverlay from '../VOverlay.vue';
    import VDropdown from '../VDropdown.vue';
    import { mapActions } from 'vuex';
    import {timeFormatting} from '../../../../core/functions';

    export default {
        name: 'KnowledgeTableItemRESERVE',

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

        methods: {
            ...mapActions(['submitEditedQuestion']),

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
                this.closeQuestionPopup();
                this.questionText = this.oldQuestionText;
                this.answerText = this.oldAnswerText;
                
            },

            submitQuestion() {
                this.submitEditedQuestion({
                    question: this.questionText,
                    answer: this.answerText,
                    draft: this.draft,
                    isPublic: this.isPublic
                });
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
        },

        mounted() {
            
        }        
    }
</script>

<style lang="scss" scoped>

</style>
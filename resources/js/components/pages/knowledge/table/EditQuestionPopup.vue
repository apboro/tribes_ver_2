<template>
    <!-- Модальное окно редактирования -->        
    <v-popup
        theme="primary"
        title="Редактировать вопрос-ответ"
        :isVisiblePopup="isVisibleQuestionPopup"
        @close="closeEditQuestionPopup"
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
                @click="closeEditQuestionPopup"
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
</template>

<script>
    import { mapActions } from "vuex";
    import VPopup from "../../../ui/popup/VPopup.vue";
    import TextEditor from "../../../ui/editor/TextEditor.vue";
    import VCheckbox from "../../../ui/form/VCheckbox.vue";
    import ToggleSwitch from "../../../ui/form/ToggleSwitch.vue";

    export default {
        name: 'EditQuestionPopup',

        components: {
            VPopup,
            TextEditor,
            VCheckbox,
            ToggleSwitch,
        },

        props: {
            question: {
                type: Object,
                default: () => {},
            },

            isVisibleQuestionPopup: {
                type: Boolean,
                default: false,
            }
        },

        data() {
            return {
                questionText: '',
                answerText: '',
                draft: null,
                isPublic: null,
            }
        },

        computed: {
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

        watch: {
            question() {
                this.questionText = this.question.context;
                this.answerText = this.question.answer ? this.question.answer.context : '';
                this.draft = this.question.is_draft;
                this.isPublic = this.question.is_public;
            }
        },

        methods: {
            ...mapActions('knowledge', ['EDIT_QUESTION']),

            closeEditQuestionPopup() {
                this.$emit('closeEditQuestionPopup');
            },

            setAnswer(answer) {
                this.answerText = answer;
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

                this.$emit('closeEditQuestionPopup');
            },
        },
    }
</script>

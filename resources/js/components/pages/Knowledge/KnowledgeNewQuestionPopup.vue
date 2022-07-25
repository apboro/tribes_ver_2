<template>
    <v-popup
        theme="primary"
        title="Новый вопрос-ответ"
        :isVisiblePopup="isVisibleNewQuestionPopup"
        @close="closeNewQuestionPopup"
    >
        <template #body>
            <div class="form-item">
                <label
                    class="form-label form-item__label"
                    for="new_question"
                >
                    Вопрос
                </label>

                <input
                    type="text"
                    id="new_question"
                    class="form-control"
                    placeholder="Что такое Tribes?"
                    v-model="newQuestionText"
                >
                
                <span
                    class="form-message form-message--danger form-item__message"
                    v-if="false"
                ></span>
            </div>

            <div class="form-item">
                <label class="form-label form-item__label">
                    Ответ
                </label>

                <text-editor
                    :text="newAnswerText"
                    @edit="setAnswer"
                />
            </div>

            <div class="knowledge-modal__controls">
                <v-checkbox
                    id="new_question_draft"
                    label="Черновик"
                    v-model="changeDraft"
                />

                <toggle-switch
                    id="is_published_new_question"
                    :label="isPublic ? 'Опубликовано' : 'Не опубликовано'"
                    v-model="changePublic"
                />
            </div>
        </template>

        <template #footer>
            <button
                class="button-empty button-empty--primary"
                @click="cancelNewQuestion"
            >
                Отмена
            </button>
            
            <button
                class="button-filled button-filled--primary"
                @click="sendNewQuestion"
            >
                Создать
            </button>
        </template>
    </v-popup>
</template>

<script>
    import { mapActions } from 'vuex';
    import VPopup from '../../ui/popup/VPopup.vue';
    import TextEditor from '../../ui/editor/TextEditor.vue';
    import VCheckbox from '../../ui/form/VCheckbox.vue';
    import ToggleSwitch from '../../ui/form/ToggleSwitch.vue';

    export default {
        name: 'KnowledgeNewQuestionPopup',

        components: {
            VPopup,
            TextEditor,
            VCheckbox,
            ToggleSwitch,
        },

        props: {
            isVisibleNewQuestionPopup: {
                type: Boolean,
                default: false,
            }
        },

        data() {
            return {
                newQuestionText: '',
                newAnswerText: '',
                draft: false,
                isPublic: true,
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

        methods: {
            ...mapActions('knowledge', ['ADD_QUESTION']),

            closeNewQuestionPopup() {
                this.$emit('closeNewQuestionPopup');
            },

            setAnswer(text) {
                this.newAnswerText = text;
            },

            sendNewQuestion() {
                this.ADD_QUESTION({
                    question: {
                        context: this.newQuestionText,
                        is_draft: this.draft,
                        is_public: this.isPublic,
                        answer: {
                            context: this.newAnswerText,
                            is_draft: true
                        }
                    },
                });
                this.newQuestionText = '';
                this.newAnswerText = '';
                this.closeNewQuestionPopup();
            },

            cancelNewQuestion() {
                this.newQuestionText = '';
                this.newAnswerText = '';
                this.closeNewQuestionPopup();
            },
        }
    }
</script>

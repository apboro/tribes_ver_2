<template>
    <v-popup
        @close="closeNewQuestionPopup"
    >
        <template #title>
            <h2 class="v-popup__title">Новый вопрос-ответ</h2>
        </template>

        <template #body>
            <label for="new_question">Вопрос</label>
            <input
                type="text"
                id="new_question"
                class="form-item"
                v-model="newQuestionText"
            >

            <label for="">Ответ</label>
            <text-editor
                :text="newAnswerText"
                @edit="setAnswer"
            />

            <div class="knowledge-modal__controls">
                <div class="knowledge-filter__item">
                    <input type="checkbox" id="question_draft" v-model="draft">
                    <label for="question_draft">Черновик</label>
                </div>

                <div class="toggle-switch">
                    <label class="toggle-switch__switcher">
                        <input type="checkbox" id="is_published_question" v-model="isPublic">
                        <span class="toggle-switch__slider"></span>
                    </label>

                    <label for="is_published_question" class="toggle-switch__label">
                        Опубликовано
                    </label>
                </div>
            </div>
        </template>

        <template #footer>
            <button
                class="v-popup__footer-btn"
                @click="cancelNewQuestion"
            >
                Cancel
            </button>
            
            <button
                class="v-popup__footer-btn"
                @click="sendNewQuestion"
            >
                Submit
            </button>
        </template>
    </v-popup>
</template>

<script>
    import { mapActions } from 'vuex';
    import VPopup from '../VPopup.vue';
    import TextEditor from '../TextEditor.vue';

    export default {
        name: 'KnowledgeNewQuestionPopup',

        components: { VPopup, TextEditor },

        data() {
            return {
                newQuestionText: '',
                newAnswerText: '',
                draft: false,
                isPublic: true
            }
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

<template>
    <v-popup
        @close="closeQuestionPopup"
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
                @input="setQuestionText"
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
                        @onchange="changeDraft"
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
</template>

<script>
    export default {
        name: 'KnowledgeEditQuestionPopup',

        components: {},

        props: {
            answerText: {
                type: String,
                default: ''
            }
        },

        methods: {
            closeQuestionPopup() {
                this.$emit('closeQuestionPopup');
            },

            setQuestionText(event) {
                this.$emit('setQuestionText', event.target.value);
            },

            setAnswer(answer) {
                this.$emit('setAnswer', answer);
            },

            changeDraft(event) {
                this.$emit('changeDraft', event.target.value);
            }
        }
    }
</script>

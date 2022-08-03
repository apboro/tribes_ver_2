<template>
    <div>
        <button
            v-if="isPublic"
            class="table__action-item"
            @click="removeFromPublication"
        >
            Снять с публикации
        </button>

        <button
            v-else
            class="table__action-item"
            @click="publish"
        >
            Опубликовать
        </button>

        <a
            :href="question.public_link"
            target="_blank"
            class="table__action-item"
        >
            Предпросмотр
        </a>

        <button
            class="table__action-item"
            @click="openEditQuestionPopup"
        >
            Редактировать
        </button>

        <button
            class="table__action-item"
            @click="copyLink"
        >
            Скопировать ссылку
        </button>

        <button
            class="table__action-item"
            @click="removeQuestion"
        >
            Удалить
        </button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import { copyText } from '../../../../core/functions';

    export default {
        name: 'ColActions',

        props: {
            question: {
                type: Object,
                default: () => {},
            },
        },

        data() {
            return {
                isPublic: this.question.is_public,
            }
        },

        methods: {
            ...mapActions('knowledge', ['EDIT_QUESTION']),

            editQuestion() {
                this.EDIT_QUESTION({
                    question: {
                        id: this.question.id,
                        context: this.question.context,
                        is_draft: this.question.is_draft,
                        is_public: this.isPublic,
                        answer: {
                            context: this.question.answer ? this.question.answer.context : '',
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

            openEditQuestionPopup() {
                this.$emit('openEditQuestionPopup', this.question);
            },

            copyLink() {
                copyText(this.question.public_link);
            },

            removeQuestion() {
                this.$emit('removeQuestion', this.question.id);
            }
        }
    }
</script>


<template>
    <v-dropdown>
        <template #tooglePanel="{ toggleDropdownVisibility }">
            <button
                class="dropdown button-text knowledge-table__dropdown button-text--primary button-text--only-icon"
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
        <template #body="{ toggleDropdownVisibility }">
            <div
                class="dropdown__body knowledge-table__action-menu"
                @click="toggleDropdownVisibility"
            >
                <template v-if="isPublic">
                    <button
                        class="knowledge-table__action-item"
                        @click="removeFromPublication"
                    >
                        Снять с публикации
                    </button>
                </template>

                <template v-else>
                    <button
                        class="knowledge-table__action-item"
                        @click="publish"
                    >
                        Опубликовать
                    </button>
                </template>
                
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
</template>

<script>
    import { mapActions } from 'vuex';
    import VIcon from '../../ui/icon/VIcon.vue';
    import VDropdown from '../../ui/dropdown/VDropdown.vue';
    import { copyText } from '../../../core/functions';

    export default {
        name: 'KnowledgActionsDropdown',

        components: { VIcon, VDropdown },

        props: {
            question: {
                type: Object,
                default: {},
            },

            isPublic: {
                type: Boolean,
                default: false,
            }
        },

        methods: {
            ...mapActions('knowledge', ['REMOVE_QUESTION']),

            removeFromPublication() {
                this.$emit('removeFromPublication');
            },

            publish() {
                this.$emit('publish');
            },

            openQuestionPopup() {
                this.$emit('openQuestionPopup');
            },

            copyLink() {
                copyText(this.question.public_link);
            },

            removeQuestion() {
                this.$emit('removeQuestion');
            },
        },
    }
</script>

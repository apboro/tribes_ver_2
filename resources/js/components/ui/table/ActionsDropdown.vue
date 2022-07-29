<template>
    <v-dropdown>
        <template #tooglePanel="{ toggleDropdownVisibility }">
            <button
                class="dropdown button-text table__dropdown button-text--primary button-text--only-icon"
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
                class="dropdown__body table__action-menu"
                @click="toggleDropdownVisibility"
            >
                <div
                    v-for="(action, index) in actions"
                    :key="index"
                >
                    <template v-if="action.type == 'button'">
                        <button
                            class="table__action-item"
                            @click="action.onClick(data, action)"
                        >
                            {{ action.text }}
                        </button>
                    </template>

                    <template v-if="action.type == 'link'">
                        <a
                            :href="data[action.key]"
                            target="_blank"
                            class="table__action-item"
                        >
                            {{ action.text }}
                        </a>
                    </template>
                </div>
                <!-- <template v-if="isPublic">
                    <button
                        class="table__action-item"
                        @click="removeFromPublication"
                    >
                        Снять с публикации
                    </button>
                </template>

                <template v-else>
                    <button
                        class="table__action-item"
                        @click="publish"
                    >
                        Опубликовать
                    </button>
                </template>
                
                <a
                    :href="question.public_link"
                    target="_blank"
                    class="table__action-item"
                >
                    Предпросмотр
                </a>

                <button
                    class="table__action-item"
                    @click="openQuestionPopup"
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
                </button> -->
            </div>
        </template>
    </v-dropdown>
</template>

<script>
    import VDropdown from "../dropdown/VDropdown.vue";
    import VIcon from "../icon/VIcon.vue";

    export default {
        name: 'ActionsDropdown',
        
        components: {
            VDropdown,
            VIcon,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            },

            actions: {
                type: Array,
                default: () => [],
            }
        }
    }
</script>

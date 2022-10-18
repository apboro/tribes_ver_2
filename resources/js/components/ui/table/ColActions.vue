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
                <slot
                    name="actionCol"
                    :data="colData"
                ></slot>    
            </div>
        </template>
    </v-dropdown>
</template>

<script>
    import VDropdown from "../dropdown/VDropdown.vue";
    import VIcon from "../icon/VIcon.vue";

    export default {
        name: 'ColActions',
        
        components: {
            VDropdown,
            VIcon,
        },

        props: {
            colData: {
                type: Object,
                default: () => {},
            },
        },
    }
</script>

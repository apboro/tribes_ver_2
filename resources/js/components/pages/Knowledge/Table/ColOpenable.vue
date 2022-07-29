<template>
    <div
        class="table__item table__item--openable table__item--changable"
        @click="toggleHiddenRowVisibility"
    >
        <p class="table__item-truncate-text">
            {{ data.context }}
        </p>
                        
        <transition
            name="a-question-arrow"
            mode="out-in"
        >
            <template v-if="isVisibleHiddenRow">
                <v-icon
                    class="table__item-arrow-icon"
                    key="0"
                    name="arrow-up"
                    size="1"
                />
            </template>

            <template v-else>
                <v-icon
                    class="table__item-arrow-icon"
                    key="1"
                    name="arrow-down"
                    size="1"
                />
            </template> 
        </transition>
    </div>
</template>

<script>
    import VIcon from "../../../ui/icon/VIcon.vue";

    export default {
        name: 'ColOpenable',

        components: {
            VIcon,
        },

        props: {
            data: {
                type: Object,
                default: () => {},
            },

            isVisibleHiddenRowParent: {
                type: Boolean,
                default: () => false,
            },
        },

        data() {
            return {
                isVisibleHiddenRow: this.isVisibleHiddenRowParent,
            }
        },

        methods: {
            toggleHiddenRowVisibility() {
                this.isVisibleHiddenRow = !this.isVisibleHiddenRow;
                this.$emit('toggleHiddenRowVisibility');
            }
        }
    }
</script>

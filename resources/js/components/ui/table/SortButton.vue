<template>
    <transition
        name="a-sort-icon"
        mode="out-in"
    >
        <button
            v-if="value === 'off'"
            :key="`${ sortProps.sortName }_asc`"
            class="button-text button-text--primary button-text--only-icon"
            @click="sort(sortProps.sortName, 'asc')"
        >
            <v-icon
                name="sort-asc"
                size="1"
                class="button-text__icon"
            />
        </button>

        <button
            v-else-if="value === 'asc'"
            :key="`${ sortProps.sortName }_asc_active`"
            class="button-text button-text--primary button-text--only-icon active"
            @click="sort(sortProps.sortName, 'desc')"
        >
            <v-icon
                name="sort-asc"
                size="1"
                class="button-text__icon"
            />
        </button>

        <button
            v-else-if="value === 'desc'"
            :key="`${ sortProps.sortName }_desc`"
            class="button-text button-text--primary button-text--only-icon active"
            @click="sort(sortProps.sortName, 'off')"
        >
            <v-icon
                name="sort-desc"
                size="1"
                class="button-text__icon"
            />
        </button>
    </transition>
</template>

<script>
    import VIcon from "../icon/VIcon.vue";

    export default {
        name: 'SortButton',

        components: { VIcon },

        props: {
            sortProps: {
                type: Object,
                default: {},
            },

            value: {
                type: String,
                default: 'off'
            }
        },

        methods: {
            sort(sortName, sortRule) {
                this.$emit('sort', sortName, sortRule);
            },
        },
    }
</script>

<template>
    <div
        class="search-field"
        :class="{ 'active': isActiveSearch }"
    >
        <input
            type="text"
            class="search-field__field "
            placeholder="Поиск"
            :value="value"
            @focus="isActiveSearch = true"
            @blur="isActiveSearch = false"
            @input="searchFilter"
        >
        
        <v-icon
            name="search"
            size="2"
            class="search-field__icon"
        />
    </div>
</template>

<script>
    import VIcon from "./VIcon.vue";

    export default {
        name: 'SearchField',

        components: { VIcon },

        props: {
            value: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                isActiveSearch: false,
            }
        },

        methods: {
            searchFilter(event) {
                this.$emit('input', event.target.value);
            }
        }
    }
</script>

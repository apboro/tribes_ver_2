<template>
    <div class="search-field">
        <input
            type="text"
            class="search-field__field "
            placeholder="Поиск"
            :value="value"
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

        methods: {
            searchFilter(event) {
                this.$emit('input', event.target.value);
            }
        }
    }
</script>

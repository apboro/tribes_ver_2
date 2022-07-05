<template>
    <div class="search-field">
        <input type="text" class="search-field__field" @input="inp">
        <i>></i>
    </div>
</template>

<script>
    export default {
        name: 'SearchField',

        components: {},

        props: {
            text: {
                type: String,
                default: ''
            }
        },

        /* data() {
            return {
                text: ''
            }
        }, */

        methods: {
            inp(event) {
                this.$emit('search')
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
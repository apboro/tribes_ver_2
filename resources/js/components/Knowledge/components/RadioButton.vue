<template>
    <div class="radio-button">
        <input
            type="radio"
            :id="id"
            class="radio-button__input"
            :value="value"
            :name="name"
            :checked="checked"
            @change="check"
        >
        
        <label
            :for="id"
            class="radio-button__label"
        >
            1
        </label>
    </div>
</template>

<script>
    export default {
        name: 'RadioButton',

        props: {
            id: {
                type: String,
                default: 'id'
            },

            value: {
                type: String,
                default: 'value'
            },

            checked: {
                type: Boolean,
                required: false,
                default: false
            },

            name: {
                type: String,
            }
        },

        methods: {
            check(event) {
                this.$emit('input', event.target.value);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
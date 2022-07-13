<template>
    <div class="radio-button">
        <div class="radio-button__wrapper">
            <input
                type="radio"
                :id="id"
                class="radio-button__input"
                :value="value"
                :checked="value == checkedValue"
                @input="check"
            >
            
            <label
                :for="id"
                class="radio-button__decor"
            ></label>
        </div>

        <template v-if="label">
            <label
                :for="id"
                class="radio-button__label"
            >
                {{ label }}
            </label>
        </template>
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
                type: [String, Number, Boolean],
                default: ''
            },

            checkedValue: {
                type: [String, Number, Boolean],
                default: ''
            },

            label: {
                type: [String, null],
                default: null
            }
        },

        methods: {
            check(event) {
                this.$emit('input', this.value);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
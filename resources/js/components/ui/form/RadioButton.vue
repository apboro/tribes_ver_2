<template>
    <div class="radio-button">
        <div class="radio-button__wrapper">
            <input
                type="radio"
                :id="id"
                class="radio-button__input"
                :value="value"
                :checked="isChecked"
                @change="updateInput"
            >
            
            <label
                :for="id"
                class="radio-button__decor"
            ></label>
        </div>

        <label
            v-if="label"
            :for="id"
            class="radio-button__label"
        >
            {{ label }}
        </label>
    </div>
</template>

<script>
    export default {
        name: 'RadioButton',

        model: {
            prop: 'modelValue',
            event: 'change'
        },

        props: {
            id: {
                type: String,
                default: 'id',
            },

            value: {
                type: [String, Number, Boolean],
                default: '',
            },

            modelValue: {
                default: '',
            },

            label: {
                type: [String, null],
                default: null,
            }
        },

        computed: {
            isChecked() {
                return this.modelValue == this.value;
            }
        },

        methods: {
            updateInput() {
                this.$emit('change', this.value);
            }
        }
    }
</script>

<template>
    <div class="checkbox">
        <div class="checkbox__wrapper">
            <input
                type="checkbox"
                :id="id"
                class="checkbox__input"
                :checked="isChecked"
                :value="value"
                @change="updateInput"
            >

            <label
                :for="id"
                class="checkbox__decor"
            ></label>
        </div>

        <template v-if="label">
            <label
                :for="id"
                class="checkbox__label"
            >
                {{ label }}
            </label>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'VCheckbox',

        model: {
            prop: 'modelValue',
            event: 'change'
        },

        props: {
            id: {
                type: String,
                default: ''
            },

            value: {
                type: [String, Number, Boolean],
                default: ''
            },
            
            modelValue: {
                default: ""
            },

            label: {
                type: [String, null],
                default: null
            },

            trueValue: {
                default: true
            },

            falseValue: {
                default: false
            }
        },
        
        computed: {
            isChecked() {
                if (this.modelValue instanceof Array) {
                    return this.modelValue.includes(this.value)
                }
                // Note that `true-value` and `false-value` are camelCase in the JS
                return this.modelValue === this.trueValue;
            }
        },

        methods: {
            updateInput(event) {
                let isChecked = event.target.checked
                if (this.modelValue instanceof Array) {
                    let newValue = [...this.modelValue];
                    if (isChecked) {
                        newValue.push(this.value);
                    } else {
                        newValue.splice(newValue.indexOf(this.value), 1)
                    }
                    this.$emit('change', newValue);
                } else {
                    this.$emit('change', isChecked ? this.trueValue : this.falseValue);
                }
            }
        }
    }
</script>

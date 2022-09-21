<template>
    <div class="editable-value">
        <span
            v-if="!isEditMode"
            class="editable-value__not-editable-value"
        >
            {{ value }}
        </span>

        <template v-else>
            <input
                type="number"
                class="form-control form-control-sm editable-value__input"
                size="3"
                v-model="newValue"
            >

            <div class="editable-value__buttons">
                <button
                    class="editable-value__btn editable-value__btn--success"
                    @click="edit"    
                >
                    Ок
                </button>

                <button
                    class="editable-value__btn editable-value__btn--danger"
                    @click="cancel"    
                >
                    Отмена
                </button>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        name: 'EditableValue',

        props: {
            isEditMode: {
                type: Boolean,
                default: false
            },

            value: {
                type: [String, Number],
                default: ''
            }
        },

        data() {
            return {
                newValue: this.value,
            }
        },

        methods: {
            edit() {
                this.$emit('edit', this.newValue);
            },

            cancel() {
                this.newValue = this.value;
                this.$emit('switchEditMode', false);
            }
        }
    }
</script>

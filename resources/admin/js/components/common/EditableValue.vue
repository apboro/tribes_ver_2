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
                    type="text"
                    class="form-control form-control-sm"
                    size="3"
                    :value="this.value"
                    @input="changeValue"
                >
            
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
                // isEditMode: true,
            }
        },

        methods: {
            changeValue(event) {
                console.log(event.target.value);
            }
        }
    }
</script>

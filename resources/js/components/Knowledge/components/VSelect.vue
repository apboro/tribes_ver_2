<template>
    <div class="select">
        <div class="select__head open">
            <span class="select__value">
                {{ selectedValue }}
            </span>

            <v-icon
                name="arrow-down"
                size="1"
                class="select__arrow"
            />
        </div>

        <div class="select__body">
            <div
                class="select__option"
                :class="{ 'selected': isSelectValue(option.value) }"
                v-for="option in options"
                :key="option.value"
            >
                {{ option.label }}
            </div>
        </div>
    </div>
</template>

<script>
    import VIcon from "./VIcon.vue";

    export default {
        name: 'VSelect',

        components: { VIcon },

        props: {
            options: {
                type: Array,
                default: []
            },

            defaultValue: {
                type: [String, Number],
                default: ''
            }
        },

        data() {
            return {
                selectedValue: this.defaultValue,
            }
        },

        methods: {
            isSelectValue(value) {
                return this.selectedValue == value;
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
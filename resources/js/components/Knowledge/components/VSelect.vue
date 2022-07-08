<template>
    <div
        class="select"
        :class="{
            'open': isOpen,
            'select--top': direction == 'top',
            'select--bottom': direction == 'bottom',
        }"
        ref="select"
    >
        <div
            class="select__head"
            @click="toggleOptionsVisibility"
            ref="select_head"
        >
            <span class="select__value">
                {{ selectedValue }}
            </span>

            <template v-if="isOpen">
                <v-icon
                    name="arrow-up"
                    size="1"
                    class="select__arrow"
                />
            </template>

            <template v-else>
                <v-icon
                    name="arrow-down"
                    size="1"
                    class="select__arrow"
                />
            </template>
        </div>

        <div
            class="select__body"
            ref="select_body"
        >
            <div
                class="select__option"
                :class="{ 'selected': isSelectValue(option.value) }"
                v-for="option in options"
                :key="option.value"
                @click="selectOption(option.value)"
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
            },
        },

        data() {
            return {
                selectedValue: this.defaultValue,
                isOpen: false,
                direction: 'bottom'
            }
        },

        methods: {
            isSelectValue(value) {
                return this.selectedValue == value;
            },

            toggleOptionsVisibility() {
                this.isOpen = !this.isOpen;

                if (this.isOpen) {
                    document.addEventListener('click', this.onClickOutside);
                }
            },

            onClickOutside(e) {
                if (this.$refs.select && !this.$refs.select.contains(e.target)){
                    this.isOpen = false;
                }
            },
            
            selectOption(option) {
                this.selectedValue = option;
                this.toggleOptionsVisibility();
                this.$emit('getSelectedValue', this.selectedValue)
            },

            /* setDirection: 
                _.debounce(function(text) {
                    // если элементы есть
                    if (this.$refs.select_body && this.$refs.select_head) {
                        const windowHeight = window.innerHeight;
                        const elHeight = this.$refs.select_body.getBoundingClientRect().height + this.$refs.select_head.getBoundingClientRect().height;
                        const elBottom = this.$refs.select_head.getBoundingClientRect().bottom;
                        console.log(this.$refs);
                        console.log(windowHeight - (elHeight + elBottom));
                        console.log(windowHeight, this.$refs.select_body.getBoundingClientRect());
                        if (windowHeight - (elHeight + elBottom) >= 0) {
                            this.direction = 'bottom';
                        } else {
                            this.direction = 'top';
                        }
                    }
                }, 200)
            , */

            setDirection() {
                // если есть элементы селекта
                if (this.$refs.select_body && this.$refs.select_head) {
                    const windowHeight = window.innerHeight;
                    const elHeight = this.$refs.select_body.getBoundingClientRect().height + this.$refs.select_head.getBoundingClientRect().height;
                    const elBottom = this.$refs.select_head.getBoundingClientRect().bottom;
        
                    if (windowHeight - (elHeight + elBottom) >= 0) {
                        this.direction = 'bottom';
                    } else {
                        this.direction = 'top';
                    }
                }
            },
            
        },

        mounted() {
            window.addEventListener("scroll", this.setDirection);
        },

        unmounted() {
            window.removeEventListener("scroll", this.setDirection);
            document.removeEventListener('click', this.onClickOutside);
        }
    }
</script>

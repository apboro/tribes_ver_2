<template>
    <div class="dropdown" ref="dropdown">
        <slot
            name="tooglePanel"
            :toggleDropdownVisibility="toggleDropdownVisibility"
        ></slot>

        <transition name="a-base-dropdown">
            <div
                v-if="isVisible" 
                :class="{
                    'dropdown__body--top': direction == 'top',
                    'dropdown__body--bottom': direction == 'bottom',
                }">
                <slot
                    name="body"
                    :isVisible="isVisible"
                    :toggleDropdownVisibility="toggleDropdownVisibility"
                ></slot>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'VDropdown',

        data() {
            return {
                isVisible: false,
                direction: 'bottom',
            }
        },

        methods: {
            toggleDropdownVisibility() {
                /* console.log(this.$refs.dropdown.getBoundingClientRect());
                console.log(this.$parent.$el.getBoundingClientRect()); */
                this.isVisible = !this.isVisible;
                document.addEventListener('click', this.onClickOutside);
            },

            onClickOutside(e) {
                if (this.$refs.dropdown && !this.$refs.dropdown.contains(e.target)) {
                    this.isVisible = false;
                }
            },

            setDirection() {
                // если есть элементы селекта
                if (this.$refs.dropdown) {
                    const windowHeight = window.innerHeight;
                    const elHeight = this.$refs.dropdown.getBoundingClientRect().height;
                    const elBottom = this.$refs.dropdown.getBoundingClientRect().bottom;
        
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

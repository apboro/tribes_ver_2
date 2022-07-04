<template>
    <div class="dropdown" style="position: relative;" ref="dropdown">
        <slot
            name="tooglePanel"
            :toggleDropdownVisibility="toggleDropdownVisibility"
        ></slot>

        <transition name="a-dropdown">
            <template v-if="isVisible">
                <slot
                    name="body"
                    :isVisible="isVisible"
                    :toggleDropdownVisibility="toggleDropdownVisibility"
                ></slot>
            </template>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'VDropdown',

        data() {
            return {
                isVisible: false,
            }
        },

        methods: {
            toggleDropdownVisibility() {
                console.log(this.$refs.dropdown.getBoundingClientRect());
                console.log(this.$parent.$el.getBoundingClientRect());
                this.isVisible = !this.isVisible;
                document.addEventListener('click', this.onClickOutside);
            },

            onClickOutside(e) {
                if (this.$refs.dropdown && !this.$refs.dropdown.contains(e.target)){
                    this.isVisible = false;
                }
            },
        },

        unmounted() {
            document.removeEventListener('click', this.onClickOutside);
        }
    }
</script>

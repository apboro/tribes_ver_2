<template>
    <div class="v-popup">
        <div class="v-popup__header">
            <slot name="title"></slot>
            <button
                class="v-popup__close-btn"
                @click="close"
            >X</button>
        </div>

        <div class="v-popup__body">
            <slot name="body"></slot>
        </div>

        <div class="v-popup__footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VPopup',

        methods: {
            close() {
                this.$emit('close');
            }
        },

    }
</script>

<template>
    <div
        class="popup"
        :class="{ 'popup--primary': theme == 'primary', 'popup--confirm': confirmOptions }"
    >
        <div class="popup__header">
            <h2 class="popup__title">
                {{ title }}
            </h2>
            
            <button
                class="button-text button-text--only-icon popup__close-btn"
                :class="{ 'button-text--primary': theme == 'primary' }"
                @click="close"
            >
                <v-icon
                    name="close"
                    size="2"
                    class="button-text__icon"
                />
            </button>
        </div>

        <div
            class="popup__body"
        >
            <slot name="body"></slot>
        </div>

        <div class="popup__footer">
            <slot name="footer"></slot>
        </div>

        <template v-if="confirmOptions">
            <v-icon
                name="info"
                :sizeParams="{
                    width: 231,
                    height: 231
                }"
                class="popup__decor"
            />
        </template>
    </div>
</template>

<script>
    import VIcon from "./VIcon.vue";

    export default {
        name: 'VPopup',

        components: { VIcon },

        props: {
            theme: {
                type: String,
                default: 'primary', // primary, success, warning, info, danger
            },
            
            title: {
                type: [String, null],
                default: null,
            },

            confirmOptions: {
                type: [Object, null],
                default: null,
            }
        },

        methods: {
            close() {
                this.$emit('close');
            },
        },

    }
</script>

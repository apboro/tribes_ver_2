<template>
    <div>
        <transition name="a-overlay">
            <v-overlay
                v-if="isVisiblePopup"
                @onClick="close"
            />
        </transition>

        <transition name="a-popup">
            <div
                class="popup"
                v-if="isVisiblePopup"
                :class="{
                    'popup--primary': theme == 'primary',
                    'popup--danger': theme == 'danger',
                    'popup--confirm': confirmOptions
                }"
                ref="popup"
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
                    <template v-if="confirmOptions.type == 'info'">
                        <v-icon
                            name="info"
                            :sizeParams="{
                                width: 231,
                                height: 231
                            }"
                            class="popup__decor"
                        />
                    </template>

                    <template v-else-if="confirmOptions.type == 'delete'">
                        <v-icon
                            name="delete"
                            :sizeParams="{
                                width: 231,
                                height: 231
                            }"
                            class="popup__decor"
                        />
                    </template>
                </template>
            </div>
        </transition>
    </div>
</template>

<script>
    import VIcon from "../icon/VIcon.vue";
    import VOverlay from "../overlay/VOverlay.vue";

    export default {
        name: 'VPopup',

        components: { VIcon, VOverlay },

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
            },

            isVisiblePopup: {
                type: Boolean,
                default: false,
            }
        },

        methods: {
            close() {
                this.$emit('close');
            },
        },
    }
</script>

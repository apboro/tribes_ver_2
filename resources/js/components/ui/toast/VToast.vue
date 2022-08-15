<template>
    <transition-group
        name="a-toast"
        tag="ul"
        class="toasts"
    >
        <toast-item
            v-for="message in GET_MESSAGES"
            :key="message.id"
            :data="message"
        />
    </transition-group>
</template>

<script>
    import { mapGetters } from 'vuex';
    import ToastItem from './ToastItem.vue';

    export default {
        name: 'VToast',

        components: { ToastItem },

        data() {
            return {
                timer: null,
            }
        },

        computed: {
            ...mapGetters('toast', ['GET_MESSAGES'])
        },
    }
</script>

<style lang="scss">
    .toasts {
        position: fixed;
        bottom: 16px;
        left: 16px;
        display: flex;
        flex-direction: column;
        row-gap: 10px;

        &__item {
            display: flex;
            align-items: center;
            column-gap: 8px;
            width: 300px;
            padding: 8px 16px;

            &--info {
                background-color: blue;
            }

            &--success {
                background-color: green;
            }

            &--warning {
                background-color: orange;
            }

            &--danger {
                background-color: red;
            }
        }

        &__message {
            color: white;
        }
    }

    .a-toast {
        &-enter-active {
            animation: table-row .3s;
        }
        &-leave-active {
            animation: table-row .3s reverse;
        }
        &-move {
            transition: .3s;
        }
    }

    @keyframes table-row {
        0% {
            opacity: 0;
            //transform: translateY(-3px) scaleY(-100%);
            transform: translateX(-100%) translateY(-100%);
        }

        90% {
            transform: translateY(10%);
        }

        100% {
            opacity: 1;
            //transform: translateY(0px) scaleY(0px);
            transform: translateX(0%) translateY(0%);
        }
    }
</style>
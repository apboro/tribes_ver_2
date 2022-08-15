<template>
    <ul class="toasts">
        <transition-group name="a-toast" tag="ul" class="toasts">
            <div
                class="toasts__item"
                :class="`toasts__item--${ message.type }`"
                v-for="message in GET_MESSAGES"
                :key="message.id"
                @click="REMOVE(message)"
            >
                {{ message.message }}
            </div>
        </transition-group>
    </ul>
</template>

<script>
    import { mapGetters, mapMutations } from 'vuex';

    export default {
        name: 'VToast',

        computed: {
            ...mapGetters('toast', ['GET_MESSAGES'])
        },

        methods: {
            ...mapMutations('toast', ['REMOVE'])
        },

        mounted() {
            console.log(this.GET_MESSAGES);
        }
    }
</script>

<style lang="scss" scoped>
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
        }
    }

    .a-toast {
        &-enter-active {
            animation: table-row .3s;
        }
        &-leave-active {
            animation: table-row .3s reverse;
        }
    }

    @keyframes table-row {
        0% {
            opacity: 0;
            transform: translateY(-3px) scaleY(-100%);
        }

        100% {
            opacity: 1;
            transform: translateY(0px) scaleY(0px);
        }
    }
</style>
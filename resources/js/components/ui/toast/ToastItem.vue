<template>
    <div
        class="toasts__item"
        :class="`toasts__item--${ data.type }`"
        @click="REMOVE(data)"
        @mouseenter="stopTimer"
        @mouseleave="startTimer"
    >
        <p class="toasts__message">
            {{ data.message }} {{ data.id }}
        </p>
    </div>
</template>

<script>
    import { mapMutations } from 'vuex';

    export default {
        name: 'ToastItem',

        props: {
            data: {
                type: Object,
                default: () => {}
            }
        },

        data() {
            return {
                timer: null,
            }
        },

        methods: {
            ...mapMutations('toast', ['REMOVE']),

            stopTimer() {
                clearTimeout(this.timer);
            },

            startTimer() {
                this.timer = setTimeout(() => {
                    this.REMOVE(this.data);
                }, this.data.duration);
            }
        },

        mounted() {
            this.startTimer();
        }
    }
</script>

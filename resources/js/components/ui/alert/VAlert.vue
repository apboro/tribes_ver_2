<template>
    <div
        class="alerts__item"
        :class="`alerts__item--${ data.type }`"
        @click="REMOVE(data)"
        @mouseenter="stopTimer"
        @mouseleave="startTimer"
    >
        <p class="alerts__message">
            {{ data.message }} {{ data.id }}
        </p>
    </div>
</template>

<script>
    import { mapMutations } from 'vuex';

    export default {
        name: 'VAlert',

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

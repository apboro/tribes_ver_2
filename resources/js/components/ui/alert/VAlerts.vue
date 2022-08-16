<template>
    <transition-group
        name="a-alert"
        tag="ul"
        class="alerts"
    >
        <v-alert
            v-for="message in GET_MESSAGES"
            :key="message.id"
            :data="message"
        />
    </transition-group>
</template>

<script>
    import { mapGetters } from 'vuex';
    import VAlert from './VAlert.vue';

    export default {
        name: 'VAlerts',

        components: { VAlert },

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

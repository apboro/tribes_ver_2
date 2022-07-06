<template>
    <div class="overlay" @click="onClick"></div>
</template>

<script>
    export default {
        name: 'VOverlay',

        methods: {
            onClick() {
                this.$emit('onClick');
            }
        }
    }
</script>

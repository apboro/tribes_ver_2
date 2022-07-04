<template>
    <div class="loading" v-bind:class="{'load' : this.$store.getters.loading}">
        <component :is="layout" />
    </div>
</template>

<script>
import MainLayout from './layout/MainLayout'
import AuthLayout from './layout/AuthLayout'
export default {
    name: "App",
    components:{
        MainLayout, AuthLayout
    },
    data() {
        return {
        }
    },
    computed: {
        layout() {
            return (this.$route.meta.layout || 'main') + '-layout'
        },
    },
}
</script>

<style scoped>

</style>
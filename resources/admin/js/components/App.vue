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
    methods: {

        addBodyClassForSwitchThemeOnLoad(){
            let localStoreThemeColor = localStorage.getItem('theme-color');
            if(localStoreThemeColor === 'theme-light') {
                document.body.classList.toggle('theme-light');
            } else if(localStoreThemeColor === 'theme-dark') {
                document.body.classList.toggle('theme-dark');
            }
        }
    },
    mounted(){
        if(!sessionStorage.getItem('token') || sessionStorage.getItem('token') === null){
          this.$router.push({name: 'login'}).catch((err) => {console.warn(err)})
        }

        this.addBodyClassForSwitchThemeOnLoad();

        if (this.$store.getters.isAuthen) {
            this.$store.dispatch('LOAD_USER');
        }
    }
}
</script>

<style scoped>

</style>
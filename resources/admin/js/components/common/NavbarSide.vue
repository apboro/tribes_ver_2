<template>
    <div class="navbar-nav flex-row order-md-last">

        <div class="nav-item d-none d-md-flex me-3">
            <div class="btn-list">
                <a href="https://github.com/CoderYooda/Community" class="btn" target="_blank" rel="noreferrer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path></svg>
                    Исходный код
                </a>
            </div>
        </div>

        <div class="d-none d-md-flex">
            <a @click="toggleThemeColor" class="nav-link px-0" href="javascript:void(0)" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
               <Icon :icon="schema.theme_color === 'theme-light' ? 'moon' : 'sun'" />
            </a>
        </div>

        <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="#f9d71c" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                    </svg>
                </span>
                <div class="d-none d-xl-block ps-2">
                    <div>{{GET_USER.name}}</div>
                    <div class="mt-1 small text-muted">{{GET_USER.job ?? 'Администратор'}}</div>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="#" class="dropdown-item">Set status</a>
                <a href="#" class="dropdown-item">Profile &amp; account</a>
                <a href="#" class="dropdown-item">Feedback</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">Settings</a>
                <a @click.prevent='logout' href="#" class="dropdown-item">Logout</a>
            </div>
        </div>
    </div>
</template>

<script>
import { Dropdown } from 'bootstrap';
import { mapActions, mapGetters } from 'vuex';
import Icon from "../ui/Icon";
export default {
    name: "NavbarSide",
    components:{Icon},
    data() {
        return {
            schema:{
                theme_color: 'theme-light'
            }
        }
    },
    computed: {
        ...mapGetters(["GET_USER"]),
    },
    methods: {
        ...mapActions(["LOAD_USER"]),
        
        logout(){
            sessionStorage.setItem('token', null);
            window.axios.defaults.headers.common['Authorization'] = '';
            this.$router.push({name: 'login'}).catch(() => {});
        },

        toggleThemeColor() {
            let apply_light = 
                localStorage.getItem('theme-color') === 'theme-dark';    
            let schema = apply_light ? 'theme-light' : 'theme-dark';
            this.setThemeColor(schema);
        },

        setThemeColor(schema){
            
            this.schema.theme_color = schema;
            localStorage.setItem('theme-color', schema);
            document.body.classList.remove('theme-dark');
            document.body.classList.remove('theme-light');
            document.body.classList.add(schema);
        },
    },
}
</script>

<style scoped>

</style>
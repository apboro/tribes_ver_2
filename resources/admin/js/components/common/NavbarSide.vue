<template>
    <div class="navbar-nav flex-row order-md-last">

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
                <a @click.prevent='logout' href="#" class="dropdown-item">Выход</a>
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
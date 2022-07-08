<template>
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="#" height="36" alt=""></a>
            </div>
            <form @submit.prevent="login" class="card card-md" action="." method="get" autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Вход в свой аккаунт</h2>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" v-model="email" placeholder="Введите email" autocomplete="off">
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">
                            Пароль
                        </label>
                        <div class="input-group input-group-flat">
                            <input type="password" class="form-control" name="password" v-model="password"  placeholder="Введите пароль"  autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" title="Показать пароль" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="2" />
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                    </svg>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input"/>
                            <span class="form-check-label">Запомнить меня на этом устройстве</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Войти</button>
                    </div>
                </div>
            </form>
            <p v-if="showError" id="error" style="color:red">Email или пароль неверны</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { mapGetters  } from 'vuex';

export default {
    name: "login",
    data(){
        return {
            email: '',
            password: '',
            showError: false,
        };
    },
    computed: {
        ...mapGetters(['isLogged'])
    },
    methods: {
        async login() {
            let result = await axios(
                {
                    method: 'POST',
                    data: {email : this.email, password : this.password},
                    url:'/api/login'
                }
            );
            if(result.status == 200 && result.data.token) {
                localStorage.setItem('token', result.data.token)
                this.$router.push({name: 'users'}).catch((err) => {console.warn(err)})
            }
        },
    }
}
</script>

<style scoped>

</style>
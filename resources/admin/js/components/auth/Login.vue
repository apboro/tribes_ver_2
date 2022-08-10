<template>
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark"><img src="#" height="36" alt=""></a>
            </div>
            <form @submit.prevent="login" class="card card-md" action="." method="post" autocomplete="off">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Вход в свой аккаунт</h2>
                    <div class="mb-3 form-validation">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control"
                            :class="{invalid: errors.email}"
                            name="email" v-model.trim="email"
                            placeholder="Введите email"
                            autocomplete="off"
                        >
                        <small v-if="errors.email">{{ errors.email }}</small>
                    </div>
                    <div class="mb-2">
                        <label for="password" class="form-label">
                            Пароль
                        </label>
                        <div class="form-validation">
                        <div class="input-group input-group-flat">
                            <input
                                :type="fieldType"
                                class="form-control"
                                name="password"
                                v-model.trim="password"
                                placeholder="Введите пароль"
                                autocomplete="off"
                            >
                            <span class="input-group-text">
                                <a @click.prevent="switchField" href="javascript:viol(0)" class="link-secondary" title="Показать пароль" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                    <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="3" y1="3" x2="21" y2="21"></line>
                                        <path d="M10.584 10.587a2 2 0 0 0 2.828 2.83"></path>
                                        <path d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341"></path>
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <circle cx="12" cy="12" r="2" />
                                        <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                    </svg>
                                    <i class="ti ti-eye-off"></i>
                                </a>
                            </span>
                        </div>
                        <small v-if="errors.password">{{ errors.password }}</small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input" v-model="agreeWithRememberMe"/>
                            <span class="form-check-label">Запомнить меня на этом устройстве</span>
                        </label>
                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Войти</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "login",
    data() {
        return {
            email: '',
            password: '',
            showPassword: false,
            fieldType: 'password',
            agreeWithRememberMe: false,
            errors: {
                email: null,
                password: null,
            }
        };
    },
    methods: {
        switchField() {
            this.fieldType = 
            this.fieldType === "password" ? "text" : "password";
            this.showPassword = !this.showPassword;
        },

        async login() {
            try {
                let result = await axios({
                    method: 'POST',
                    data: {email : this.email, password : this.password},
                    url:'/api/login'
                })

                if (result.status == 200 && result.data.token) {
                    sessionStorage.setItem('token', result.data.token)
                    this.$router.push({name: 'users'}).catch((err) => {console.warn(err)})
                    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + result.data.token;
                }
            } catch (err) {
                if (err.response && err.response.status === 422){

                    console.log('RESPONSE: ', err.response);
                    console.log('EMAIL: ',err.response.data.errors.email[0]);
                    // console.log('PASSWORD: ', typeof err.response.data.errors.password);

                    this.errors.email = err.response.data.errors.email[0] ?? null;
                    this.errors.password = err.response.data.errors.password ?? null;
                }
            }
        },
    }
}
</script>

<style scoped>

.form-validation small {
    color: #e53935;
}

.form-validation > .invalid {
    border-color: #e53935;
}

</style>
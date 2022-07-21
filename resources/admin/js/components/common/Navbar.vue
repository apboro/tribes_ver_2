<template>
    <div class="sticky-top">

            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-xl">
                    <button class="navbar-toggler" type="button" >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a style="text-decoration: none" href="#">
                        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3" style="align-items: end;">
                            <svg width="105" height="25" viewBox="0 0 105 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.961 23V6.04348H5V2H22V6.04348H16.039V23H10.961Z" fill="#333333"></path>
                                <path d="M23 23V8.30992H28.1468L28.315 9.67356C29.0101 9.28099 29.8736 8.92975 30.9052 8.61984C31.9367 8.28925 32.9684 8.08265 34 8V11.75C33.4169 11.812 32.7665 11.9153 32.0489 12.0599C31.3537 12.1839 30.6809 12.3388 30.0306 12.5248C29.4026 12.7107 28.8644 12.9071 28.4159 13.1136V23H23Z" fill="#333333"></path>
                                <path d="M37.7198 4.97314V1H43V4.97314H37.7198ZM38.1121 23V11.3433H36L36.4224 7.43583H42.9698V23H38.1121Z" fill="#333333"></path>
                                <path d="M57.2093 23C56.2171 23 55.3488 22.8713 54.6047 22.614C53.8605 22.3353 53.1887 21.9279 52.5891 21.3918L52.3411 22.6784H48V1H52.9922V8.14036C53.5504 7.79728 54.1809 7.53997 54.8837 7.36843C55.5866 7.19689 56.3101 7.11112 57.0543 7.11112C59.2042 7.11112 60.8992 7.73295 62.1395 8.97662C63.3798 10.1988 64 12.1823 64 14.9269C64 17.6716 63.3902 19.7086 62.1705 21.038C60.9509 22.346 59.2972 23 57.2093 23ZM55.7829 19.2368C56.8992 19.2368 57.6951 18.8938 58.1705 18.2076C58.6667 17.5 58.9147 16.4172 58.9147 14.9591C58.9147 13.4581 58.6564 12.4074 58.1395 11.807C57.6434 11.2067 56.8579 10.9064 55.7829 10.9064C54.6047 10.9064 53.6744 11.2281 52.9922 11.8713V18.0789C53.7364 18.8509 54.6667 19.2368 55.7829 19.2368Z" fill="#333333"></path>
                                <path d="M76.0392 23C73.312 23 71.1237 22.3826 69.4742 21.1478C67.8247 19.8927 67 18.0101 67 15.5C67 13.2126 67.6818 11.3907 69.0454 10.0344C70.4089 8.67814 72.4543 8 75.1814 8C77.6666 8 79.5911 8.58704 80.9546 9.76114C82.3182 10.9352 83 12.5142 83 14.498V17.0789H71.9155C72.1574 18.0304 72.7182 18.6984 73.5979 19.083C74.4996 19.4474 75.7202 19.6295 77.2598 19.6295C78.1175 19.6295 78.9862 19.5587 79.866 19.417C80.7677 19.2753 81.5155 19.0932 82.1093 18.8704V22.0587C81.3615 22.3826 80.4708 22.6154 79.4371 22.7571C78.4035 22.9191 77.2708 23 76.0392 23ZM71.9155 14.255H78.3814V13.6174C78.3814 12.9089 78.1505 12.3523 77.6887 11.9473C77.2268 11.5222 76.446 11.3097 75.3464 11.3097C74.0708 11.3097 73.18 11.5526 72.6742 12.0384C72.1684 12.5041 71.9155 13.2429 71.9155 14.255Z" fill="#333333"></path>
                                <path d="M92.1104 23C90.9258 23 89.8035 22.9191 88.7436 22.7571C87.6837 22.5952 86.8417 22.3927 86.2182 22.1498V18.415C86.9456 18.7186 87.7873 18.9514 88.7436 19.1134C89.6991 19.2753 90.5517 19.3563 91.2999 19.3563C92.193 19.3563 92.8376 19.3158 93.2327 19.2348C93.6481 19.1539 93.8562 18.9312 93.8562 18.5668C93.8562 18.162 93.5234 17.8583 92.8586 17.6559C92.193 17.4535 91.2789 17.1599 90.1153 16.7753C88.7639 16.3097 87.7351 15.7733 87.0288 15.166C86.3429 14.5385 86 13.6174 86 12.4028C86 10.9859 86.5403 9.90283 87.6213 9.15385C88.7225 8.38462 90.4683 8 92.8586 8C93.7939 8 94.7182 8.07085 95.6332 8.21255C96.5473 8.35425 97.2955 8.5162 97.8777 8.69838V12.3724C97.2955 12.1093 96.6307 11.917 95.8826 11.7955C95.1344 11.6741 94.4587 11.6133 93.8562 11.6133C93.087 11.6133 92.4323 11.664 91.8922 11.7652C91.3724 11.8462 91.1129 12.0587 91.1129 12.4028C91.1129 12.7875 91.4036 13.0607 91.9857 13.2227C92.5671 13.3644 93.4089 13.6073 94.5109 13.9514C95.6745 14.2956 96.5785 14.67 97.223 15.0749C97.8668 15.4595 98.3243 15.9251 98.5947 16.4716C98.8644 17.0182 99 17.7065 99 18.5364C99 19.9737 98.4178 21.0769 97.2542 21.8462C96.0899 22.6154 94.3753 23 92.1104 23Z" fill="#333333"></path>
                            </svg> | Администратор
                            <preloader v-if="$store.getters.getLoadingStatus"/>
                        </h1>
                    </a>
                    <NavbarSide/>
                </div>
            </header>
            <div class="navbar-expand-md">
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="navbar navbar-light">
                        <div class="container-xl">
                            <div class="navbar-nav">
                                <ul class="navbar-nav">

                                    <router-link :to="{name:'dashboard'}" v-slot="{ href, route, navigate, isActive, isExactActive }" custom exact>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Панель управления
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>
                                    <router-link :to="{name:'users'}" v-slot="{ href, route, navigate, isActive, isExactActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Пользователи
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>

                                    <router-link :to="{name:'courses'}" v-slot="{ href, route, navigate, isActive, isExactActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Медиатовары
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>

                                    <router-link :to="{name:'payments'}" v-slot="{ href, route, navigate, isActive, isExactActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Платежи
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
import NavbarSide from "./../layout/NavbarSide";
import Preloader from "../common/Preloader"
export default {
    name: "Navbar",
    components:{
        NavbarSide,
        Preloader
    },
    data() {
        return {
            include:{
                transparent : false,
            }
        }
    },
}
</script>

<style scoped>
    .navbar-brand {
        position: relative;
    }
</style>
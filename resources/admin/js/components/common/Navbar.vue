<template>
    <div class="sticky-top">

            <header class="navbar navbar-expand-md navbar-light d-print-none">
                <div class="container-xl">
                    <button class="navbar-toggler" type="button" >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a style="text-decoration: none" href="/">
                        <h1>
                          Spodial | Администратор
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

                                    <router-link :to="{name:'dashboard'}" v-slot="{ href, navigate, isActive }" custom exact>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Панель управления
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>
                                    <router-link :to="{name:'users'}" v-slot="{ href, navigate, isActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Пользователи
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>

                                    <router-link :to="{name:'courses'}" v-slot="{ href, navigate, isActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Медиатовары
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>

                                    <router-link :to="{name:'payments'}" v-slot="{ href, navigate, isActive }" custom>
                                        <li class="nav-item" :class="[isActive && 'active']" >
                                            <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Платежи
                                                </span>
                                            </a>
                                        </li>
                                    </router-link>
                                  <router-link :to="{name:'feedback'}" v-slot="{ href, navigate, isActive }" custom>
                                    <li class="nav-item" :class="[isActive && 'active']" >
                                      <a class="nav-link" :href="href" @click="navigate">
                                                <span class="nav-link-title">
                                                    Обратная связь
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
import NavbarSide from "./NavbarSide";
import Preloader from "../common/Preloader"
import { mapGetters  } from 'vuex';
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
    computed: {
        ...mapGetters(['getPreloaderStatus'])
    },
}
</script>

<style scoped>
    .navbar-brand {
        position: relative;
    }
</style>
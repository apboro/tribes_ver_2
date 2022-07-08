import Vue from 'vue'
import Router from 'vue-router'
import store from './store'

Vue.use(Router);

let router =  new Router({
    mode: 'history',
    base: '/manager/',
    // redirect: '/manager/dashboard',
    routes: [
        {
            path: '/login',
            name: 'login',
            meta: {layout: 'auth', requiresAuth: false},
            component: () => import(/* webpackChunkName: "Login" */ './components/auth/Login')
        },
        {
            path: '/',
            name: 'dashboard',
            meta: {layout: 'main', requiresAuth: true},
            component: () => import(/* webpackChunkName: "Actions" */ './components/pages/Dashboard.vue')
        },
        {
            path: '/users',
            name: 'users',
            meta: {layout: 'main', tabName: 'Пользователи'},
            component: () => import(/* webpackChunkName: "Actions" */ './components/pages/Users.vue')
        },
        {
            path: '/courses',
            name: 'courses',
            meta: {layout: 'main'},
            component: () => import(/* webpackChunkName: "Actions" */ './components/pages/Courses.vue')
        },

        {
            path: '/payments',
            name: 'payments',
            meta: {layout: 'main'},
            // meta: {layout: 'main', requiresAuth: true},
            component: () => import(/* webpackChunkName: "Actions" */ './components/pages/Payments.vue')
        },


        // {
        //     path: '/',
        //     redirect: '/pages',
        // },
        // {
        //     path: '/actions',
        //     name: 'actions',
        //     meta: {layout: 'main'},
        //     component: () => import(/* webpackChunkName: "Actions" */ './components/pages/Actions/Actions.vue')
        // },
        // {
        //     path: '/action/:id?/edit',
        //     name: 'action',
        //     meta: {layout: 'main'},
        //     component: () => import(/* webpackChunkName: "Action" */ './components/pages/Actions/Action.vue')
        // },
        // { path: '*', redirect: '/' },
    ]
});

// router.beforeEach((to, from, next) => {
//     const publicPages = ['/login'];
//     const authRequired = !publicPages.includes(to.path);
//     const loggedIn = localStorage.getItem('token');
//     console.log(loggedIn)
//     if (authRequired && !loggedIn) {
//         return next('/login');
//     } else if(to.path === '/login') {
//         return next('/');
//     }
//     next();
// });

export default router;
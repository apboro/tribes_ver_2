window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

//ADD******************************************
axios.defaults.withCredentials = true;

// axios.interceptors.response.use(undefined, function(error) {
//     if (error) {
//       const originalRequest = error.config;
//       if (error.response.status === 401 && !originalRequest._retry) {
//         originalRequest._retry = true;
//         store.dispatch("LogOut");
//         return router.push("/login");
//       }
//     }
// });
//End ADD******************************************

// window.token = document.head.querySelector('meta[name="csrf-token"]');
let api_token = document.querySelector('[name="api-token"]');

if(api_token){
    localStorage.setItem('token', api_token.content)
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = api_token.content;
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + api_token.content;
} else {
    localStorage.removeItem('token')
    console.error('TOKEN Не найден');
}

window.getParameterByName = function(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    let regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}


import Vue from 'vue';
import store from './store';
import App from './components/App';
import router from './router';

let app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');

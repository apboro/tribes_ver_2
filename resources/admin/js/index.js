window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

let api_token = sessionStorage.getItem('token');
let token = document.head.querySelector('meta[name="csrf-token"]');

if(api_token){
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + api_token.content;
} else {
    console.error('TOKEN Не найден');
}

window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status === 401 || error.response.status === 419) {
        window.location.href = '/manager/login';
    }
    return Promise.reject(error);
});






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
import axios from 'axios';

let app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');

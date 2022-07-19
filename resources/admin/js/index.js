window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

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
import axios from 'axios';
import dateFilter from './components/datefilter'

Vue.filter('date', dateFilter)

let app = new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');

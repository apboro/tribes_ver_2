import lodash from 'lodash';
window._ = lodash;

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

window.token = document.head.querySelector('meta[name="csrf-token"]');
window.api_token = document.querySelector('[name="api-token"]').content;

if (window.token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.token.content;
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.api_token;
} else {
    console.error('CSRF Не найден');
}

import BurgerMenu from './plugins/Helper/BurgerMenu';
import { Dropdown } from './plugins/Helper/Dropdown';
import { Dictionary } from './lang/Dictionary';

window['Dict'] = new Dictionary();
window['Dropdown'] = new Dropdown();
window['Burger'] = new BurgerMenu();

import Vue from 'vue';
import store from './store/store';
import Statistics from './pages/Statistics';
import PortalVue from 'portal-vue';

Vue.use(PortalVue)

let app = new Vue({
    // router,
    store,
    render: h => h(Statistics)
}).$mount('#app');




window.axios.interceptors.response.use(function (response) {
    /* document.body.classList.remove('loading');
    window.isXHRloading = false;
    // window.unsetPreloader();
    if(response.data.event){
        let event = new CustomEvent(response.data.event, {
            'detail' : {
                data: response.data
            },
        });

        let listns = document.getElementsByClassName(response.data.event + 'Listner');
        [].forEach.call(listns, function(elem){
            elem.dispatchEvent(event);
        });
        document.dispatchEvent(event);
        console.log("Событие " + response.data.event + " объявлено");
    } */
    return response;
}, function (error) {
    // window.unsetPreloader();
   /*  if (error.response.status === 422 || error.response.status === 200) {

        // console.log('123', error.response.data.message, error.response.data.type);

        if(error.response.data.message && error.response.data.type){
            window.notification.notify( error.response.data.type, error.response.data.message);
        }
    } */
    if (error.response.status === 401 || error.response.status === 419) {
        console.log(1);
        window.location.href = "/login";
    }
   /*  if (error.response.status === 403) {
        if(error.response.data.type == "gateClosed"){
            window.notification.notify( 'error', error.response.data.message);
        }
    } */
    //document.body.classList.remove('loading');
    //window.isXHRloading = false;
    return Promise.reject(error);
}, function(e){

});

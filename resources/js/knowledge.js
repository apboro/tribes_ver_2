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
import store from './knowledgeStore';
//import store from './store/modules/knowledge';
import Knowledge from './components/Knowledge/Knowledge';
import PortalVue from 'portal-vue'

Vue.use(PortalVue)

let app = new Vue({
    // router,
    store,
    render: h => h(Knowledge)
}).$mount('#app');

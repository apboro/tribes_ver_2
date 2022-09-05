window._ = require('lodash');
require('./plugins/ContainerQuery');
require('./prototype');

const feather = require('feather-icons');
const { tooltipsInit, copyText } = require('./functions');
const { Emitter } = require('./plugins/Abstract/Emitter');
const { LoadPlugin } = require('./plugins/Abstract/LoadPlugin');
const { ModalWindow } = require('./plugins/Abstract/ModalWindow');
const { Toasts } = require('./plugins/Abstract/Toasts');
const { ScrollTop } = require('./plugins/Helper/ScrollTop');
const { Dictionary } = require('./lang/Dictionary');
const { FillForm } = require('./plugins/Abstract/FillForm');
const { AlertMessage } = require('./plugins/Abstract/Alert');
const { SidePanel } = require('./plugins/Abstract/SidePanel');
const { Popup } = require('./plugins/Abstract/Popup');
const { BlockElements } = require('./plugins/Abstract/BlockElements');

window['AdminState'] = new BlockElements();

import BurgerMenu from './plugins/Helper/BurgerMenu';
import { Dropdown } from './plugins/Helper/Dropdown';



window['Dropdown'] = new Dropdown();
window['Burger'] = new BurgerMenu();


new LoadPlugin();
feather.replace();
tooltipsInit();
window['Emitter'] = new Emitter();
window['ModalWindow'] = ModalWindow;
window['Toasts'] = Toasts;
window['SidePanel'] = SidePanel;
window.Scroll = new ScrollTop();
window['Dict'] = new Dictionary();
window['AlertMessage'] = AlertMessage;
window['copyText'] = copyText;
window['Popup'] = Popup;


// new FillForm();

try {
    require('bootstrap');
} catch (e) {}

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

let token = document.head.querySelector('meta[name="csrf-token"]');
let api_token = document.head.querySelector('meta[name="api-token"]').content;

window.token = token.content;

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    window.axios.defaults.headers.common['Authorization'] = 'Bearer ' + api_token;
} else {
    console.error('CSRF Не найден');
}

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
        /* console.log(1);
        window.location.href = "/login"; */
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

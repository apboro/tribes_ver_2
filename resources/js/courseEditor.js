
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



import { Cropper } from 'vue-advanced-cropper';
import store from './courseStore';
//import store from './store/store';
import Text from "./components/pages/Course/media/Text";
import Video from "./components/pages/Course/media/Video";
import Audio from "./components/pages/Course/media/Audio";
import Image from "./components/pages/Course/media/Image";



window.text = Vue.extend(Text);
window.image = Vue.extend(Image);
window.video = Vue.extend(Video);
window.audio = Vue.extend(Audio);

import Vue from 'vue';
import AudioVisual from 'vue-audio-visual'
import VideoPlayer from 'vue-videojs7'


Vue.use(VideoPlayer)
Vue.use(AudioVisual)
Vue.component('cropper', Cropper);

import Editor from './pages/media/Editor';
import { Dictionary } from './lang/Dictionary';

import BurgerMenu from './plugins/Helper/BurgerMenu';
import { Dropdown } from './plugins/Helper/Dropdown';


window['Dict'] = new Dictionary();
window['Dropdown'] = new Dropdown();
window['Burger'] = new BurgerMenu();

let app = new Vue({
    // router,
    store,
    render: h => h(Editor)
}).$mount('#app');


window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (error.response.status === 401 || error.response.status === 419) {
        console.log(1);
        window.location.href = "/login";
    }
   
    return Promise.reject(error);
}, function(e){

});

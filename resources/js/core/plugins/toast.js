import store from '../../store/store';
import { timeFormatting } from '../functions';

export default {
    install(Vue, options) {
        Vue.prototype.$alertSuccess = function(text) {
            store.commit('alert/SET_MESSAGE', { type: 'success', message: text });
        }

        Vue.prototype.$alertInfo = function(text) {
            store.commit('alert/SET_MESSAGE', { type: 'info', message: text });
        }

        Vue.prototype.$alertDanger = function(text) {
            store.commit('alert/SET_MESSAGE', { type: 'danger', message: text });
        }

        Vue.prototype.$alertWarning = (text) => {
            store.commit('alert/SET_MESSAGE', { type: 'warning', message: text });
        }

        

        Vue.directive('short-date', {
            bind (el, binding, vnode, oldVnode) {
                console.log(el);
                console.log(new Date(binding.value).getFullYear());
                timeFormatting({
                    date: new Date(binding.value),
                    month: 'long'
                })

                el.textContent = timeFormatting({
                    date: new Date(binding.value),
                    day: '2-digit',
                    month: '2-digit',
                    year: "2-digit",
                });
            }
            
        })
    }
}

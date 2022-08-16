import store from '../../store/store';

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
    }
}

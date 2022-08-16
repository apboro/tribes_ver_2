import store from '../../store/store';

export default {
    install(Vue, options) {
        Vue.prototype.$alertSuccess = function(text) {
            store.commit('toast/SET_MESSAGE', 'success', text);
        }

        Vue.prototype.$alertInfo = function(text) {
            store.commit('toast/SET_MESSAGE', 'info', text);
        }

        Vue.prototype.$alertDanger = function(text) {
            store.commit('toast/SET_MESSAGE', 'danger', text);
        }

        Vue.prototype.$alertWarning = (text) => {
            store.commit('toast/SET_MESSAGE', 'warning', text);
        }
    }
}

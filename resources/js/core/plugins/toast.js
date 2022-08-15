import store from '../../store/store';

export default {
    install(Vue, options) {
        Vue.prototype.$messageSuccess = function(text) {
            store.commit('toast/SET_MESSAGE', 'success', text);
        }

        Vue.prototype.$messageInfo = function(text) {
            store.commit('toast/SET_MESSAGE', 'info', text);
        }

        Vue.prototype.$messageDanger = function(text) {
            store.commit('toast/SET_MESSAGE', 'danger', text);
        }

        Vue.prototype.$messageWarning = (text) => {
            store.commit('toast/SET_MESSAGE', 'warning', text);
        }
    }
}

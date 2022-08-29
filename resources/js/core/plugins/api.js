export default {
    install(Vue) {
        const api = axios.create();
        /* axios.interceptors.request.use(config => {
            console.log(config);
            return config;
        },
        error => {
            console.log(error, 1);
        }); */
        
        
        api.interceptors.response.use(config => {
            Vue.prototype.$alertSuccess('Success upload');
            
            return config;
        },
        error => {
            Vue.prototype.$alertDanger(error.message);
            //return error;
        });
        
        window.api = api;
        Vue.prototype.$api = api;
    }
}

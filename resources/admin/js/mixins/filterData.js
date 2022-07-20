export default {
    data() {
        return {
            filter_data:{
                search : null,
                entries : 10,
                page : 1,
            }
        }
    },

    watch: {
        filter_data: {
            deep: true,
            handler: _.debounce(function(v) {
                this.$store.dispatch('LOAD_PAYMENTS', v);
            },400)
        }
    },

    methods: {
        setPageByUrl(url){
            if(url){
                this.filter_data.page = getParameterByName('page', url);
            }
        },
    }
}
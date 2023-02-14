let initialState = () =>  {
    return {
        filter_data: {
            filter: {
                search : null,
                entries : 20,
                page : 1,
                date: null,
                status: null,
                sort: {
                    name: '', rule: ''
                }
            },
        },
        
        customer_data: {
            select: null,
        }
    }
};

export default {
    data() {
        return initialState()
    },

    methods: {
        setPageByUrl(url){
            if(url){
                this.filter_data.filter.page = parseInt(getParameterByName('filter.page', url));
            }
        },

        reset() {
            Object.assign(this.$data, initialState())
        }
    },
}
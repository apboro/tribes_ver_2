let initialState = () =>  {
    return {
        filter_data: {
            filter: {
                search : null,
                entries : 5,
                page : 1,
                date: null,
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
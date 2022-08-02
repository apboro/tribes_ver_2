let initialState = () =>  {
    return {
        filter_data:{
            search : null,
            entries : 10,
            page : 1,
            date: null,
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
                this.filter_data.page = getParameterByName('page', url);
            }
        },

        reset() {
            Object.assign(this.$data, initialState())
        }
    },
}
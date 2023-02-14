export default {
    methods: {
        formatDateTime(str){
            let date = new Date(str);
            return `${date.toLocaleDateString('ru')} ${date.toLocaleTimeString('ru')}`;
        },
        formatDate(str){
            let date = new Date(str);
            return `${date.toLocaleDateString('ru')}`;
        },
    }
}
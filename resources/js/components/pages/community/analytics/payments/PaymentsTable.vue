<template>
    <div>
        <v-table
            class="analytics-community-payments-table"
            :data="payments"
            :tableOptions="tableOptions"
            :sortAttrs="sort"
            :isLoading="false"
        >   
        </v-table>
    </div>
</template>

<script>
    import VTable from '../../../../ui/table/VTable.vue'
    
    export default {
        name: 'MessagesTable',
        
        components: {
            VTable
        },

        props: {
            payments: {
                type: Array,
                default: () => []
            }
        },

        data() {
            return {
                tableOptions: {
                    header: [
                        {
                            type: 'sorting',
                            text: 'Имя подписчика',
                            sortName: 'name',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Никнейм',
                            sortName: 'username',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Название транзакции',
                            sortName: 'transaction_name',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Тип транзакции',
                            sortName: 'transaction_type',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Дата',
                            sortName: 'date',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Сумма',
                            sortName: 'amount',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                    ],

                    row: [
                        { type: 'link', key: 'name' },
                        { type: 'link', key: 'username' },
                        { type: 'text', key: 'transaction_name' },
                        { type: 'text', key: 'transaction_type' },
                        { type: 'time', typeValue: 'date', key: 'date' },
                        { type: 'text', key: 'amount' },
                    ],
                },

                sort: {
                    name: 'off',
                    username: 'off',
                    transaction_name: 'off',
                    transaction_type: 'off',
                    date: 'off',
                    amount: 'off',
                },
            }
        },

        methods: {
            toSort(sortName, sortRule) {
                // выключаем все фильтры кроме того который включаем
                Object.keys(this.sort).forEach((name) => {
                    if (sortName != name) {
                        this.sort[name] = 'off';
                    }
                });
                // записываем текущее значение фильтра
                this.sort[sortName] = sortRule;
                console.log(sortName, sortRule);
            },
        }
    }
</script>

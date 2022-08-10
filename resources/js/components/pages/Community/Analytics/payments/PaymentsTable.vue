<template>
    <div>
        <v-table
            class="analytics-community-payments-table"
            :data="payments"
            :tableOptions="tableOptions"
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
                        { type: 'text', text: 'Имя подписчика' },
                        { type: 'text', text: 'Никнейм' },
                        { type: 'text', text: 'Название транзакции' },
                        { type: 'text', text: 'Тип транзакции' },
                        { type: 'text', text: 'Дата' },
                        { type: 'text', text: 'Сумма' },
                    ],

                    row: [
                        { type: 'text', key: 'name' },
                        { type: 'text', key: 'username' },
                        { type: 'text', key: 'transaction_name' },
                        { type: 'text', key: 'transaction_type' },
                        { type: 'text', key: 'date' },
                        { type: 'text', key: 'amount' },
                    ],
                },
            }
        }
    }
</script>

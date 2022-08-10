<template>
    <div>
        <v-table
            :classTable="'subscribers-table'"
            :data="subscribers"
            :tableOptions="tableOptions"
            :isLoading="false"
        >   
        </v-table>
    </div>
</template>

<script>
    import VTable from '../../../../ui/table/VTable.vue'
    
    export default {
        name: 'SubscribersTable',
        
        components: {
            VTable
        },

        props: {
            subscribers: {
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
                        { type: 'text', text: 'Дата' },
                        { type: 'text', text: 'Сообщения' },
                        { type: 'text', text: 'Реакции (оставил)' },
                        { type: 'text', text: 'Реакции (получил)' },
                        { type: 'text', text: 'Полезность' },
                    ],

                    row: [
                        { type: 'text', key: 'name' },
                        { type: 'text', key: 'username' },
                        { type: 'text', key: 'date' },
                        { type: 'text', key: 'messages' },
                        { type: 'text', key: 'reaction_out' },
                        { type: 'text', key: 'reaction_in' },
                        { type: 'text', key: 'utility' },
                    ],

                    /* name: 'name text',
                            username: 'username text',
                            date: 'date text',
                            messages: 'messages text',
                            reaction_out: 'reaction out text',
                            reaction_in: 'reaction in text',
                            utility: 'utility text', */
                },
            }
        }
    }
</script>

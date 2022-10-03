<template>
    <div>
        <v-table
            class="analytics-community-subscribers-table"
            :data="subscribers"
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
                            text: 'Дата',
                            sortName: 'date',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Сообщения',
                            sortName: 'messages',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Реакции (получил)',
                            sortName: 'reaction_out',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Реакции (оставил)',
                            sortName: 'reaction_in',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Полезность',
                            sortName: 'utility',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                    ],

                    row: [
                        { type: 'link', key: 'name' },
                        { type: 'link', key: 'username' },
                        { type: 'time', typeValue: 'date', key: 'date' },
                        { type: 'text', key: 'messages' },
                        { type: 'text', key: 'reaction_out' },
                        { type: 'text', key: 'reaction_in' },
                        { type: 'text', key: 'utility' },
                    ],
                },

                sort: {
                    name: 'off',
                    username: 'off',
                    date: 'off',
                    messages: 'off',
                    reaction_out: 'off',
                    reaction_in: 'off',
                    utility: 'off',
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

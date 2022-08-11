<template>
    <div>
        <v-table
            class="analytics-community-messages-table"
            :data="messages"
            :tableOptions="tableOptions"
            :sortAttrs="sort"
            :isLoading="false"
        >
            <!-- Слот для вставки не обычно оформленной ячейки, которое может быть добавлено как колонка в строку таблицы -->
            <template #customCol="{ data }">
                {{ data.message }}
                <ul class="analytics-community-messages-table__reaction-list">
                    <li
                        class="analytics-community-messages-table__reaction-item"
                        v-for="(reaction, index) in data.reactions"
                        :key="index"
                    >
                        {{ reaction.icon }}
                        <span class="analytics-community-messages-table__reaction-value">
                            {{ reaction.value }}
                        </span>
                    </li>
                </ul>
            </template>
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
            messages: {
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
                            text: 'Сообщение / реакция',
                            sortName: 'message',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Имя автора',
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
                            text: 'Реакции',
                            sortName: 'reaction',
                            sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                        },
                        {
                            type: 'sorting',
                            text: 'Ответы',
                            sortName: 'answer',
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
                        { type: 'custom' },
                        { type: 'link', key: 'name' },
                        { type: 'link', key: 'username' },
                        { type: 'time', typeValue: 'date', key: 'date' },
                        { type: 'text', key: 'reaction' },
                        { type: 'text', key: 'answer' },
                        { type: 'text', key: 'utility' },
                    ],
                },

                sort: {
                    message: 'off',
                    name: 'off',
                    username: 'off',
                    date: 'off',
                    reaction: 'off',
                    answer: 'off',
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
            
            toSort2(sortName, sortRule) {
                // выключаем все фильтры кроме того который включаем
                Object.keys(this.sort).forEach((name) => {
                    if (sortName != name) {
                        this.sort[name] = 'off';
                    }
                });
                // записываем текущее значение фильтра
                this.sort[sortName] = sortRule;

                // если значение не "выключен" передаем данные сортировки в состояние
                // иначе задаем дефолтное
                if (sortRule != 'off') {
                    this.SET_SORT({ name: sortName, rule: sortRule });
                } else {
                    this.SET_SORT({ name: '', rule: '' });
                }
                
                this.LOAD_QUESTIONS();

                // снять выделение
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(false);
            },
        },
    }
</script>

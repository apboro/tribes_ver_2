<template>
    <div>
        <v-table
            class="analytics-community-messages-table"
            :data="messages"
            :tableOptions="tableOptions"
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
                        { type: 'text', text: 'Сообщение / реакция' },
                        { type: 'text', text: 'Имя / никнейм автора' },
                        { type: 'text', text: 'Дата' },
                        { type: 'text', text: 'Реакции' },
                        { type: 'text', text: 'Ответы' },
                        { type: 'text', text: 'Полезность' },
                    ],

                    row: [
                        { type: 'custom', data: 'message' },
                        { type: 'text', key: 'username' },
                        { type: 'text', key: 'date' },
                        { type: 'text', key: 'reaction' },
                        { type: 'text', key: 'answer' },
                        { type: 'text', key: 'utility' },
                    ],
                },
            }
        }
    }
</script>

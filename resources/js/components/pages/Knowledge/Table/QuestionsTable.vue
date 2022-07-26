<template>
    <div>
        <v-table
            :data="questions"
            :table="table"
            :sortAttrs="sort"
            :isLoading="IS_LOADING"
            @changeMultipleState="toggleStateQuestions"
            @sort="toSort"
        >   
           
        </v-table>
    </div>
</template>

<script>
    import VTable from '../../../ui/table/VTable.vue';
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VIcon from '../../../ui/icon/VIcon.vue';
    import VCheckbox from '../../../ui/form/VCheckbox.vue';
    import KnowledgeTableItem from '../KnowledgeTableItem.vue';
    
    export default {
        name: 'QuestionsTable',
 
        components: {
            VTable,
            VIcon,
            VCheckbox,
        },
                       
        props: {
            questions: {
                type: Array,
                default: [],
            }
        },

        data() {
            return {
                sort: {
                    update_at: 'off',
                    enquiry: 'off',
                },

                table: [],
            }
        },

        computed: {
            ...mapGetters('knowledge', [
                'IS_LOADING',
                'GET_ALL_STATUS_MULTIPLE_OPERATIONS'
            ]),
        },

        methods: {
            ...mapMutations('knowledge', [
                'SET_SORT',
                'CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS'
            ]),

            ...mapActions('knowledge', ['LOAD_QUESTIONS']),

            toSort(sortName, sortRule) {
                console.log(sortName, sortRule);
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

                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(false);
            },

            toggleStateQuestions() {
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(!this.GET_ALL_STATUS_MULTIPLE_OPERATIONS);
            }
        },

        mounted() {
            this.table = [
                {
                    header: {
                        type: 'multiple',
                        id: 'all_questions',
                        value: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                        modelValue: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                    },

                    body: {}
                },

                {
                    header: {
                        type: 'text',
                        text: 'Вопрос',
                    },

                    body: {}
                },

                {
                    header: {
                        type: 'sorting',
                        text: 'Дата',
                        sortName: 'update_at',
                    },

                    body: {}
                },

                {
                    header: {
                        type: 'sorting',
                        text: 'Обращений',
                        sortName: 'enquiry',
                    },

                    body: {}
                },

                {
                    header: {
                        type: 'text',
                        text: 'Статус',
                    },

                    body: {}
                },

                {
                    header: {
                        type: 'text',
                        text: 'Действия',
                    },

                    body: {}
                },
            ];
        }
    }
</script>

<template>
    <div>
        <v-table
            :data="convert(questions)"
            :tableHeader="tableHeader"
            :tableRow="tableRow"
            :sortAttrs="sort"
            :isLoading="IS_LOADING"
        >   
           <!-- <template #openableCol="{ openableData }">
                            {{openableData}}
                        </template> -->
        </v-table>
    </div>
</template>

<script>
    import VTable from '../../../ui/table/VTable.vue';
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VIcon from '../../../ui/icon/VIcon.vue';
    import VCheckbox from '../../../ui/form/VCheckbox.vue';
    
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

                tableHeader: [],

                tableRow: [],
            }
        },

        computed: {
            ...mapGetters('knowledge', [
                'IS_LOADING',
                'GET_ALL_STATUS_MULTIPLE_OPERATIONS',
                'IS_ADDED_QUESTIONS',
            ]),
        },

        methods: {
            ...mapMutations('knowledge', [
                'SET_SORT',
                'CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS',
                'ADD_ID_FOR_OPERATIONS',
                'REMOVE_ID_FOR_OPERATIONS',
            ]),

            ...mapActions('knowledge', [
                'LOAD_QUESTIONS',
                'EDIT_QUESTION',
                'REMOVE_QUESTION',
            ]),

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

                // снять выделение
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(false);
            },

            toggleStateQuestions() {
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(!this.GET_ALL_STATUS_MULTIPLE_OPERATIONS);
            },

            getMultipleItemValue(id) {
                return this.IS_ADDED_QUESTIONS(id);
            },

            setMultipleItemValue(bool, id) {
                if (bool) {
                    this.ADD_ID_FOR_OPERATIONS(id);
                } else {
                    this.REMOVE_ID_FOR_OPERATIONS(id);
                }
            },

            

            convert(questions) {
                const data = [];    
                Object.values(questions).forEach((question, index) => {
                    const item = {};
                    item.id = question.id;
                    item.openable = {
                        mainText: question.context,
                        hiddenContent: true,
                        hiddenContentType: 'editor',
                        hiddenContent: question.answer.context,
                        titleMain: 'Вопрос:',
                        titleContent: 'Ответ:',
                    };
                    item.created_at = question.created_at;
                    data.push(item);
                });

                return data;
            },
        },

        mounted() {
            /* if (this.questions) {
                console.log(this.questions);
            } */
            this.tableHeader = [
                {
                    type: 'multiple',
                    id: 'all_questions',
                    value: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                    modelValue: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                    change: () => this.toggleStateQuestions(),
                },

                {
                    type: 'text',
                    text: 'Вопрос',
                },

                {
                    type: 'sorting',
                    text: 'Дата',
                    sortName: 'update_at',
                    sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                },

                {
                    type: 'sorting',
                    text: 'Обращений',
                    sortName: 'enquiry',
                    sort: (sortName, sortRule) => this.toSort(sortName, sortRule)
                },

                {
                    type: 'text',
                    text: 'Статус',
                },

                {
                    type: 'text',
                    text: 'Действия',
                },
            ];

            this.tableRow = [
                {
                    type: 'multiple',
                    getValue: (id) => this.getMultipleItemValue(id),
                    setValue: (event, id) => this.setMultipleItemValue(event, id),
                },

                {
                    type: 'openable',
                },

                {
                    type: 'time',
                    typeValue: 'date',
                },

                {},

                {},

                {},
            ];

            this.tableRowHidden = [
                {}
            ];
        }
    }
</script>

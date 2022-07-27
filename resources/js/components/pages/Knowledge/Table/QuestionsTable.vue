<template>
    <div>
        <v-table
            :data="questions"
            :tableHeader="tableHeader"
            :tableRow="tableRow"
            :sortAttrs="sort"
            :isLoading="IS_LOADING"
            @changeMultipleState="toggleStateQuestions"
            @sort="toSort"
            @getMultipleValue="ch"
            @setMultipleValue="sch"
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

            isAddedField: {
                // проверяем есть ли такая запись в массиве, и ставим чек в зависимости от ответа
                get() {
                    return this.IS_ADDED_QUESTIONS(this.question.id);
                },

                set(isAdded) {
                    console.log(1);
                    // при изменении добавляем или удалеяем элемент из массива
                    if (isAdded) {
                        this.ADD_ID_FOR_OPERATIONS(this.question.id);
                    } else {
                        this.REMOVE_ID_FOR_OPERATIONS(this.question.id);
                    }
                },
            },
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

                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(false);
            },

            toggleStateQuestions() {
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(!this.GET_ALL_STATUS_MULTIPLE_OPERATIONS);
            },

            ch(id) {
                return this.IS_ADDED_QUESTIONS(id);
            },

            sch(id, bool) {
                if (bool) {
                    this.ADD_ID_FOR_OPERATIONS(id);
                } else {
                    this.REMOVE_ID_FOR_OPERATIONS(id);
                }
            },

            showId(id) {
                return this.IS_ADDED_QUESTIONS(id);
            }

           
        },

        mounted() {
            this.tableHeader = [
                {
                    type: 'multiple',
                    id: 'all_questions',
                    value: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                    modelValue: () => this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                },

                {
                    type: 'text',
                    text: 'Вопрос',
                },

                {
                    type: 'sorting',
                    text: 'Дата',
                    sortName: 'update_at',
                },

                {
                    type: 'sorting',
                    text: 'Обращений',
                    sortName: 'enquiry',
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
                    show: (id) => this.showId(id)
                    /* value: (id) => this.IS_ADDED_QUESTIONS(id), */
                    /* value: false,
                    modelValue: (id) => this.IS_ADDED_QUESTIONS(id),
                    isAdd: (id) => this.IS_ADDED_QUESTIONS(id),
                    add: (id) => this.ADD_ID_FOR_OPERATIONS(id),
                    remove: (id) => this.REMOVE_ID_FOR_OPERATIONS(id),
                    isAddedField: () => this.isAddedField */
                },

                {},

                {},

                {},

                {},

                {},
            ];
        }
    }
</script>

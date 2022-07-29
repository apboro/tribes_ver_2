<template>
    <div>
        <v-table
            :data="questions"
            :tableHeader="tableHeader"
            :tableRow="tableRow"
            :sortAttrs="sort"
            :isLoading="IS_LOADING"
        >   
            <template #openableBlock="{ data, isVisibleHiddenRow, toggleHiddenRowVisibility }">
                <col-openable
                    :data="data"
                    :isVisibleHiddenRowParent="isVisibleHiddenRow"
                    @toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                />    
            </template>

            <template #hiddenRow="{ data, isVisibleHiddenRow }">
                <hidden-row
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                />
            </template>

            <template #tableAction="{ data }">
                <v-dropdown>
                    <template #tooglePanel="{ toggleDropdownVisibility }">
                        <button
                            class="dropdown button-text table__dropdown button-text--primary button-text--only-icon"
                            @click="toggleDropdownVisibility"
                        >
                            <v-icon
                                name="vertical-dots"
                                size="1"
                                class="button-text__icon"
                            />
                        </button>
                    </template>

                    <!-- Меню действий -->
                    <template #body="{ toggleDropdownVisibility }">
                        <div
                            class="dropdown__body table__action-menu"
                            @click="toggleDropdownVisibility"
                        >
                            
                                    <button
                                        class="table__action-item"
                                        @click="a(data)"
                                    >
                                        see
                                    </button>
                                
                                    <!-- <a
                                        :href="data[action.key]"
                                        target="_blank"
                                        class="table__action-item"
                                    >
                                        {{ action.text }}
                                    </a> -->
                                
                            
                            
                        </div>
                    </template>
                </v-dropdown>
            </template>
        </v-table>

        
    </div>
</template>

<script>
    import VTable from '../../../ui/table/VTable.vue';
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VIcon from '../../../ui/icon/VIcon.vue';
    import VCheckbox from '../../../ui/form/VCheckbox.vue';
    import VDropdown from '../../../ui/dropdown/VDropdown.vue';
    import ColOpenable from './ColOpenable.vue';
    import HiddenRow from './HiddenRow.vue';
    
    export default {
        name: 'QuestionsTable',
 
        components: {
            VTable,
            VIcon,
            VCheckbox,
            VDropdown,
            ColOpenable,
            HiddenRow,
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

                isVisible: false,
                dropData: {}
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

                    item.createdAt = question.created_at;
                    item.c_enquiry = question.c_enquiry;

                    if (question.is_public) {
                        item.status = 'Опубликовано';
                        item.statusTheme = 'green';
                    } else if (!question.is_public && !question.is_draft) {
                        item.status = 'Не опубликовано';
                        item.statusTheme = 'red';
                    } else if (question.is_draft) {
                        item.status = 'Черновик';
                        item.statusTheme = 'orange';
                    }

                    item.isPublic = question.is_public;
                    item.isDraft = question.is_draft;

                    item.publicLink = question.public_link;
                    item.link = question.link;

                    data.push(item);
                });

                return data;
            },

            // editQuestion() {
            //     this.EDIT_QUESTION({
            //         question: {
            //             id: this.question.id,
            //             context: this.questionText,
            //             is_draft: this.draft,
            //             is_public: this.isPublic,
            //             answer: {
            //                 context: this.answerText,
            //                 is_draft: false
            //             }
            //         },
            //     });
            // },

            editQuestion(question) {
                this.EDIT_QUESTION({
                    question: {
                        id: question.id,
                        context: question.openable.mainText,
                        is_draft: question.isDraft,
                        is_public: question.isPublic,
                        answer: {
                            context: question.openable.hiddenContent,
                            is_draft: false
                        }
                    },
                });
            },

            getPublicStatus() {
                // ne public ili public
            },

            togglePublicStatus(question, action) {
                console.log(question);
                if (question.isPublic) {
                    action.text = 'Опубликовать';
                } else {
                    action.text = 'Снять с публикации';
                }
                question.isPublic = !question.isPublic;
                    
                    
                //this.isPublic = false;
                this.editQuestion(question);
            },

        },

        mounted() {
           
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
                    type: 'text-center',
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
                    mainTextKey: 'context',
                },

                {
                    type: 'time',
                    typeValue: 'date',
                    key: 'createdAt'
                },

                {
                    type: 'text',
                },

                {
                    type: 'status'
                },

                {
                    type: 'actions',
                    actions: [
                        {
                            type: 'button',
                            text: 'Снять с публикации',
                            onClick: (data, action) => this.togglePublicStatus(data, action),

                        },

                        {
                            type: 'link',
                            text: 'Предпросмотр',
                            key: 'publicLink'
                        },
                    ]
                },
            ];
        }
    }
</script>

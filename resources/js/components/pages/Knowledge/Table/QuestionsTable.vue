<template>
    <div>
        <!-- Таблица -->
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
                <col-actions
                    :question="data"
                    @openEditQuestionPopup="openEditQuestionPopup"
                    @removeQuestion="confirmRemoved" 
                ></col-actions>
            </template>
        </v-table>

        <!-- Окно редактирования вопроса -->
        <edit-question-popup
            v-if="isVisibleQuestionPopup"
            :question="question"
            :isVisibleQuestionPopup="isVisibleQuestionPopup"
            @closeEditQuestionPopup="closeEditQuestionPopup"
        />

        <!-- Окно подтверждения удаления -->
        <!-- Модальное окно подтверждения удаления -->
        <knowledge-confirm-delete-popup
            :isVisibleConfirmDeletePopup="isVisibleConfirmDeleteKnowledgeQuestionPopup"
            @closeConfirmDeletePopup="closeConfirmDeleteKnowledgeQuestionPopup"
            @confirm="confirmDeleteKnowledgeQuestion"
        />
    </div>
</template>

<script>
    import { bodyLock, bodyUnLock } from '../../../../core/functions';
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VTable from '../../../ui/table/VTable.vue';
    import VIcon from '../../../ui/icon/VIcon.vue';
    import VCheckbox from '../../../ui/form/VCheckbox.vue';
    import VDropdown from '../../../ui/dropdown/VDropdown.vue';
    import ColOpenable from './ColOpenable.vue';
    import HiddenRow from './HiddenRow.vue';
    import ColActions from './ColActions.vue';
    import EditQuestionPopup from './EditQuestionPopup.vue';
    import KnowledgeConfirmDeletePopup from '../KnowledgeConfirmDeletePopup.vue';
    
    export default {
        name: 'QuestionsTable',
 
        components: {
            VTable,
            VIcon,
            VCheckbox,
            VDropdown,
            ColOpenable,
            HiddenRow,
            ColActions,
            EditQuestionPopup,
            KnowledgeConfirmDeletePopup           
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

                tableHeader: [
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
                ],

                tableRow: [
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
                        key: 'created_at'
                    },

                    {
                        type: 'text',
                    },

                    {
                        type: 'status',
                        getStatus: (data) => this.getStatus(data),
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
                ],

                isVisibleQuestionPopup: false,
                isVisibleConfirmDeleteKnowledgeQuestionPopup: false,

                question: {},
                removedItemId: null,
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

            getStatus(data) {
                const status = {};

                if (data.is_public) {
                    status.text = 'Опубликовано';
                    status.class = 'green';
                } else if (!data.is_public && !data.is_draft) {
                    status.text = 'Не опубликовано';
                    status.class = 'red';
                } else if (data.is_draft) {
                    status.text = 'Черновик';
                    status.class = 'orange';
                }

                return status;
            },

            openEditQuestionPopup(question) {
                this.question = question;
                this.isVisibleQuestionPopup = true;
                bodyLock();
            },

            closeEditQuestionPopup() {
                this.isVisibleQuestionPopup = false;
                bodyUnLock();
            },

            confirmRemoved(id) {
                this.removedItemId = id;
                this.openConfirmDeleteKnowledgeQuestionPopup();
            },

            openConfirmDeleteKnowledgeQuestionPopup() {
                this.isVisibleConfirmDeleteKnowledgeQuestionPopup = true;
                bodyLock();
            },

            closeConfirmDeleteKnowledgeQuestionPopup() {
                this.removedItemId = null;
                this.isVisibleConfirmDeleteKnowledgeQuestionPopup = false;
                bodyUnLock();
            },

            confirmDeleteKnowledgeQuestion() {
                this.REMOVE_QUESTION({ id: this.removedItemId });
                this.closeConfirmDeleteKnowledgeQuestionPopup();
            },
        },

        mounted() {
           
            
        }
    }
</script>

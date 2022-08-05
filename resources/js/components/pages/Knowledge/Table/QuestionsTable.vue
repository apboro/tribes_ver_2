<template>
    <div>
        <!-- Таблица -->
        <!-- В data передаем данные для отображения в строках таблицы -->
        <!-- В tableOptions описание для шапки и строк таблицы, содержащее текст, функции, названия полей данных по котрым будет обращаться и пр. -->
        <!-- sortAttrs объект содержащий актуальную сортировку -->
        <!-- Статус находится ли в состоянии загрузки данных - для отображения прелоадера -->
        <v-table
            :classTable="classTable"
            :data="questions"
            :tableOptions="tableOptions"
            :sortAttrs="sort"
            :isLoading="IS_LOADING"
        >   
            <!-- Слот для вставки элемента по которому можно будет открыть невидимую строку -->
            <template #openableCol="{ data, isVisibleHiddenRow, toggleHiddenRowVisibility }">
                <col-openable
                    :data="data"
                    :isVisibleHiddenRowParent="isVisibleHiddenRow"
                    @toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                />    
            </template>

            <!-- Слот для вставки контента, содержащегося в невидимой строке -->
            <template #hiddenRow="{ data, isVisibleHiddenRow }">
                <hidden-row
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                />
            </template>

            <!-- Слот для вставки меню действий, которое может быть добавлено как колонка в строку таблицы -->
            <template #actionCol="{ data }">
                <col-actions
                    :question="data"
                    @openEditQuestionPopup="openEditQuestionPopup"
                    @removeQuestion="confirmRemoved" 
                ></col-actions>
            </template>
        </v-table>

        <!-- Окно редактирования вопроса -->
        <edit-question-popup
            :question="question"
            :isVisibleQuestionPopup="isVisibleQuestionPopup"
            @closeEditQuestionPopup="closeEditQuestionPopup"
        />

        <!-- Модальное окно подтверждения удаления -->
        <confirm-delete-popup
            :isVisibleConfirmDeletePopup="isVisibleConfirmDeleteQuestionPopup"
            @closeConfirmDeletePopup="closeConfirmDeleteQuestionPopup"
            @confirm="confirmDeleteQuestion"
        />
    </div>
</template>

<script>
    import { bodyLock, bodyUnLock } from '../../../../core/functions';
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VTable from '../../../ui/table/VTable.vue';
    import VCheckbox from '../../../ui/form/VCheckbox.vue';
    import ColOpenable from './ColOpenable.vue';
    import HiddenRow from './HiddenRow.vue';
    import ColActions from './ColActions.vue';
    import EditQuestionPopup from './EditQuestionPopup.vue';
    import ConfirmDeletePopup from '../ConfirmDeletePopup.vue';
    
    export default {
        name: 'QuestionsTable',
 
        components: {
            VTable,
            VCheckbox,
            ColOpenable,
            HiddenRow,
            ColActions,
            EditQuestionPopup,
            ConfirmDeletePopup,           
        },

        props: {
            questions: {
                type: Array,
                default: () => [],
            },

            classTable: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                sort: {
                    update_at: 'off',
                    enquiry: 'off',
                },

                tableOptions: {
                    header: [
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

                    row: [
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
                            key: 'c_enquiry'
                        },

                        {
                            type: 'status',
                            getStatus: (data) => this.getStatus(data),
                        },

                        {
                            type: 'actions',
                        },
                    ],
                },

                isVisibleQuestionPopup: false, // окно редактирования вопроса
                isVisibleConfirmDeleteQuestionPopup: false, // окно подтверждения удаления

                question: {}, // редактируемый вопрос
                removedItemId: null, // ид удаляемого вопроса
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
                this.openconfirmDeleteQuestionPopup();
            },

            openconfirmDeleteQuestionPopup() {
                this.isVisibleConfirmDeleteQuestionPopup = true;
                bodyLock();
            },

            closeConfirmDeleteQuestionPopup() {
                this.removedItemId = null;
                this.isVisibleConfirmDeleteQuestionPopup = false;
                bodyUnLock();
            },

            confirmDeleteQuestion() {
                this.REMOVE_QUESTION({ id: this.removedItemId });
                this.closeConfirmDeleteQuestionPopup();
            },
        },
    }
</script>

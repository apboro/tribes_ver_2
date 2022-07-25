<template>
    <div class="knowledge">
        <!-- <portal-target name="destination"></portal-target> -->
        
        <div class="container">
            <!-- <template v-if="COMMUNITY_TITLE"> -->
                <!-- Breadcrumbs -->
                <v-breadcrumbs
                    class="knowledge__breadcrumbs"
                    :links="breadcrumbsLinks"
                />
            <!-- </template> -->

            <!-- Title -->
            <!-- <template v-if="COMMUNITY_TITLE"> -->
                <h1 class="knowledge__title">
                    База знаний сообщества «
                    <span
                        class="knowledge__name"
                        :title="COMMUNITY_TITLE"
                    >
                        {{ COMMUNITY_TITLE }}
                    </span>»
                </h1>
            <!-- </template> -->
            
            <!-- Auxiliary -->
            <knowledge-auxiliary
                class="knowledge__auxiliary"
                :metaData="GET_META_INFO"
            />

            <div class="knowledge__control">
                <!-- Search -->
                <search-field
                    v-model="searchText"
                    @input="searchFilter"
                />
                
                <!-- Add question -->
                <button
                    class="button-filled knowledge__add-btn button-filled--primary"
                    @click="openNewQuestionPopup"
                >
                    Добавить новый вопрос-ответ
                </button>
            </div>

            <transition name="a-knowledge-panel" mode="out-in">
                <keep-alive>
                    <template v-if="HAS_QUESTION_FOR_OPERATIONS">
                        <!-- Multiple operations -->
                        <knowledge-multiple-operations @setOperationType="setOperationType" />
                    </template>

                    <template v-else>
                        <!-- Filter -->
                        <knowledge-filter
                            class="knowledge__filter"
                            @resetFilters="resetFilters"
                        />
                    </template>
                </keep-alive>
            </transition>

            <!-- Table -->
            <knowledge-table
                class="knowledge__table"
                :questions="GET_QUESTIONS"
            />
            
            <!-- Pagination -->
            <keep-alive>
                <template v-if="GET_QUESTIONS && GET_QUESTIONS.length && !IS_LOADING">
                    <v-pagination
                        class="knowledge__pagination"
                        :paginateData="GET_PAGINATE_DATA"
                        :selectOptions="paginationSelectedOptions"
                        @onPageClick="setPage"
                        @onChangePerPage="setPerPage"
                    />
                </template>
            </keep-alive>

            <!-- Модальное окно нового вопроса --> 
            <knowledge-new-question-popup
                :isVisibleNewQuestionPopup="isVisibleNewQuestionPopup"
                @closeNewQuestionPopup="closeNewQuestionPopup"
            />

            <!-- Модальное окно подтверждения удаления -->
            <knowledge-confirm-delete-popup
                :isVisibleConfirmDeletePopup="isVisibleConfirmDeletePopup"
                @closeConfirmDeletePopup="closeConfirmDeletePopup"
                @confirm="confirmDeleteQuestions"
            />
            

            <!-- Модальное окно подтверждения черновиков -->
            <knowledge-confirm-draft-popup
                :isVisibleConfirmDraftPopup="isVisibleConfirmDraftPopup"
                :questions="needConfirmationQuestions"
                @closeConfirmDraftPopup="closeConfirmDraftPopup"
                @confirm="confirmDraftQuestions"
            />
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VBreadcrumbs from '../components/ui/breadcrumbs/VBreadcrumbs.vue';
    import VPagination from '../components/ui/pagination/VPagination.vue';
    import VPopup from '../components/ui/popup/VPopup.vue';
    import VIcon from '../components/ui/icon/VIcon.vue';
    import SearchField from '../components/ui/form/SearchField.vue';
    import KnowledgeFilter from '../components/pages/Knowledge/KnowledgeFilter.vue';
    import KnowledgeTable from '../components/pages/Knowledge/KnowledgeTable.vue';
    import KnowledgeMultipleOperations from '../components/pages/Knowledge/KnowledgeMultipleOperations.vue';
    import KnowledgeNewQuestionPopup from '../components/pages/Knowledge/KnowledgeNewQuestionPopup.vue';
    import KnowledgeConfirmDraftPopup from '../components/pages/Knowledge/KnowledgeConfirmDraftPopup.vue';
    import KnowledgeConfirmDeletePopup from '../components/pages/Knowledge/KnowledgeConfirmDeletePopup.vue';
    import KnowledgeAuxiliary from '../components//pages/Knowledge/KnowledgeAuxiliary.vue';
    import { bodyLock, bodyUnLock } from '../core/functions';

    export default {
        name: 'Knowledge',

        components: {
            VBreadcrumbs,
            VPagination,
            VPopup,
            VIcon,
            SearchField,
            KnowledgeFilter,
            KnowledgeTable,
            KnowledgeMultipleOperations,
            KnowledgeNewQuestionPopup,
            KnowledgeConfirmDraftPopup,
            KnowledgeConfirmDeletePopup,
            KnowledgeAuxiliary,
        },

        data() {
            return {
                isVisibleNewQuestionPopup: false,
                searchText: '',

                needConfirmationQuestions: [],
                isVisibleConfirmDraftPopup: false,
                isVisibleConfirmDeletePopup: false,

                paginationSelectedOptions: [
                    { label: 15, value: 15 },
                    { label: 30, value: 30 },
                    { label: 45, value: 45 }
                ],
            }
        },

        computed: {
            ...mapGetters('knowledge', [
                'COMMUNITY_TITLE',
                'GET_QUESTIONS',
                'GET_META',
                'HAS_QUESTION_FOR_OPERATIONS',
                'GET_META_INFO',
                'IS_LOADING',
                'GET_IDS_MULTIPLE_OPERATIONS',
                'GET_PAGINATE_DATA',
            ]),

            breadcrumbsLinks() {
                return [
                    {
                        text: 'Главная',
                        href: 'href-1'
                    },
                    {
                        text: `База знаний сообщества "${ this.COMMUNITY_TITLE }"`,
                        href: 'href-2'
                    },
                ]
            }
        },

        methods: {
            ...mapMutations('knowledge', [
                'SET_PAGINATION',
                'SET_IDS_MULTIPLE_OPERATIONS',
            ]),
            
            ...mapActions('knowledge', [
                'LOAD_QUESTIONS',
                'FILTER_QUESTIONS',
                'TO_MULTIPLE_OPERATIONS'
            ]),

            openNewQuestionPopup() {
                this.isVisibleNewQuestionPopup = true;
                bodyLock();
            },

            closeNewQuestionPopup() {
                this.isVisibleNewQuestionPopup = false;
                bodyUnLock();
            },

            openConfirmDraftPopup() {
                this.isVisibleConfirmDraftPopup = true;
                bodyLock();
            },

            closeConfirmDraftPopup() {
                this.isVisibleConfirmDraftPopup = false;
                bodyUnLock();
            },

            openConfirmDeletePopup() {
                this.isVisibleConfirmDeletePopup = true;
                bodyLock();
            },

            closeConfirmDeletePopup() {
                this.isVisibleConfirmDeletePopup = false;
                bodyUnLock();
            },
            
            // переключение страницы пагинации
            setPage(value) {
                this.SET_PAGINATION({ page: value });
                this.LOAD_QUESTIONS();
            },

            // изменение количества просматриваемых страниц
            setPerPage(value) {
                this.SET_PAGINATION({
                    per_page: value,
                    page: 1 
                });
                this.LOAD_QUESTIONS();
            },

            searchFilter: 
                _.debounce(function(text) {
                    // вызываем экшн фильтрации и передаем текст из поисковой строки
                    this.FILTER_QUESTIONS({ full_text: this.searchText });
                }, 300)
            ,

            resetFilters(filters) {
                //в компоненте сбрасываем остальные настройки фильтра
                // здесь сбрасываем поиск, и вызываем экшн фильтрации со всеми сброшенными параметрами
                this.searchText = '';

                this.FILTER_QUESTIONS({
                    full_text: '',
                    ...filters
                });
            },

            async setOperationType(type) {
                let message = null;
                // по типу операции вызываем экшн и получаем ответ
                switch (type) {
                    case 'delete': 
                        this.openConfirmDeletePopup();
                        break;

                    case 'hard_delete':
                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'delete',
                            params: { mark: 0 }
                        });
                        break;

                    case 'public':
                        // находим элементы которые собируаемся публиковать
                        // из них отбираем черновики
                        const draftItems = this.GET_IDS_MULTIPLE_OPERATIONS
                            .map((id) => this.GET_QUESTIONS.find((question) => question.id == id))
                            .filter((question) => question.is_draft == true);
                        
                        // если есть черновики
                        if (draftItems.length) {
                            // отображаем их для подтверждения в окне
                            this.needConfirmationQuestions = draftItems;
                            this.openConfirmDraftPopup();
                            return false;
                        }

                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'update_publish',
                            params: { mark: 1 }
                        });
                        break;
                    
                    case 'hard_public':
                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'update_publish',
                            params: { mark: 1 }
                        });
                        break;

                    case 'no_public': 
                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'update_publish',
                            params: { mark: 0 }
                        });
                        break;

                    case 'draft': 
                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'update_draft',
                            params: { mark: 1 }
                        });
                        break;

                    case 'no_draft': 
                        message = await this.TO_MULTIPLE_OPERATIONS({
                            command: 'update_draft',
                            params: { mark: 0 }
                        });
                        break;
                }

                /* if (message.type === 'error') {
                    this.needConfirmationQuestions = message.items;
                    this.openConfirmDraftPopup();
                } */
            },

            confirmDraftQuestions(notChangeableQuestions) {
                if (notChangeableQuestions.length) {
                    const idsToSend = this.GET_IDS_MULTIPLE_OPERATIONS.filter(el => !notChangeableQuestions.includes(el));
                    this.SET_IDS_MULTIPLE_OPERATIONS(idsToSend);
                }
                this.closeConfirmDraftPopup();
                this.setOperationType('hard_public');
            },

            confirmDeleteQuestions() {
                this.closeConfirmDeletePopup();
                this.setOperationType('hard_delete');
            },
        },

        mounted() {
            this.LOAD_QUESTIONS();
        }
    }
</script>

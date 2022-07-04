<template>
    <div class="knowledge">
        <portal-target name="destination"></portal-target>
        
        <div class="container">
            <!-- Breadcrumbs -->
            <v-breadcrumbs
                class="knowledge__breadcrumbs"
                :links="breadcrumbsLinks"
            />

            <!-- Title -->
            <h1 class="knowledge__title">
                База знаний сообщества «
                <span class="knowledge__name">
                    {{ COMMUNITY_TITLE }}
                </span>»
            </h1>
            
            <!-- Auxiliary -->
            <div class="knowledge__auxiliary">
                <a
                    :href="GET_META_INFO.how_it_works_link"
                    target="_blank"
                >
                    Как это работает?
                </a>

                <a
                    :href="GET_META_INFO.public_list_link"
                    target="_blank"
                >
                    Посмотреть как пользователь
                </a>

                <button @click="copyLink">
                    Копировать ссылку
                </button>
            </div>

            <div class="knowledge__control">
                <!-- Search -->
                <input
                    type="text"
                    class="form-item knowledge__search"
                    placeholder="Поиск"
                    @input="searchFilter"
                    v-model="searchText"
                >
                
                <!-- Add question -->
                <button
                    class="knowledge__add-btn button-text button-text--only-icon button-text--primary"
                    @click="openNewQuestionPopup"
                >
                    
                    <v-icon
                        name="right-arrow"
                        size="1"
                        class="icon button-text__icon "
                    />
                </button>
            </div>

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

            <!-- Table -->
            <knowledge-table
                class="knowledge__table"
                :questions="GET_QUESTIONS"
            />
            
            <!-- Pagination -->
            <v-pagination
                class="knowledge__pagination"
                :data="GET_META"
                @onPageClick="setPage"
                @onChangePerPage="setPerPage"
            />

            <!-- Модальное окно нового вопроса --> 
            <transition name="a-overlay">
                <v-overlay
                    v-if="isVisibleNewQuestionPopup"
                    @onClick="closeNewQuestionPopup"
                />
            </transition>

            <transition name="a-popup">
                <knowledge-new-question-popup
                    v-if="isVisibleNewQuestionPopup"
                    @closeNewQuestionPopup="closeNewQuestionPopup"
                />
            </transition>

            <!-- Модальное окно подтверждения -->
            
                <transition name="a-overlay">
                    <v-overlay
                        v-if="isVisibleConfirmPopup"
                        @onClick="closeConfirmPopup"
                    />
                </transition>

                <transition name="a-popup">
                    <knowledge-confirm-popup
                        v-if="isVisibleConfirmPopup"
                        :questions="needConfirmationQuestions"
                        @closeConfirmPopup="closeConfirmPopup"
                        @confirm="confirmDraftQuestions"
                    />
                </transition>
            
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import { copyText } from '../../core/functions';
    import VBreadcrumbs from './components/VBreadcrumbs.vue';
    import VPagination from './components/Knowledge/VPagination.vue';
    import VPopup from './components/VPopup.vue';
    import VOverlay from './components/VOverlay.vue';
    import VIcon from './components/VIcon.vue';
    import TextEditor from './components/TextEditor.vue';
    import KnowledgeFilter from './components/Knowledge/KnowledgeFilter.vue';
    import KnowledgeTable from './components/Knowledge/KnowledgeTable.vue';
    import KnowledgeMultipleOperations from './components/Knowledge/KnowledgeMultipleOperations.vue';
    import KnowledgeNewQuestionPopup from './components/Knowledge/KnowledgeNewQuestionPopup.vue';
    import KnowledgeConfirmPopup from './components/Knowledge/KnowledgeConfirmPopup.vue';

    export default {
        name: 'Knowledge',

        components: {
            VBreadcrumbs,
            VPagination,
            VPopup,
            VOverlay,
            VIcon,
            TextEditor,
            KnowledgeFilter,
            KnowledgeTable,
            KnowledgeMultipleOperations,
            KnowledgeNewQuestionPopup,
            KnowledgeConfirmPopup
        },

        data() {
            return {
                breadcrumbsLinks: [
                    {
                        text: 'Главная',
                        href: 'href-1'
                    },
                    {
                        text: 'База знаний сообщества "Мудрость стоиков на каждый день"',
                        href: 'href-2'
                    },
                ],
                isVisibleNewQuestionPopup: false,
                searchText: '',

                needConfirmationQuestions: [],
                isVisibleConfirmPopup: false,
            }
        },

        computed: {
            ...mapGetters([
                'COMMUNITY_TITLE',
                'GET_QUESTIONS',
                'GET_META',
                'HAS_QUESTION_FOR_OPERATIONS',
                'GET_META_INFO',
                'GET_IDS_MULTIPLE_OPERATIONS',
            ]),

        },

        methods: {
            ...mapMutations([
                'SET_PAGINATION',
                'SET_IDS_MULTIPLE_OPERATIONS',
            ]),

            ...mapActions([
                'LOAD_QUESTIONS',
                'FILTER_QUESTIONS',
                'TO_MULTIPLE_OPERATIONS'
            ]),

            openNewQuestionPopup() {
                this.isVisibleNewQuestionPopup = true;
            },

            closeNewQuestionPopup() {
                this.isVisibleNewQuestionPopup = false;
            },

            openConfirmPopup() {
                this.isVisibleConfirmPopup = true;
            },

            closeConfirmPopup() {
                this.isVisibleConfirmPopup = false;
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
                            this.openConfirmPopup();
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
                    this.openConfirmPopup();
                } */
            },

            confirmDraftQuestions(notChangeableQuestions) {
                if (notChangeableQuestions.length) {
                    const idsToSend = this.GET_IDS_MULTIPLE_OPERATIONS.filter(el => !notChangeableQuestions.includes(el));
                    this.SET_IDS_MULTIPLE_OPERATIONS(idsToSend);
                }
                this.closeConfirmPopup();
                this.setOperationType('hard_public');
            },

            copyLink() {
                copyText(this.GET_META_INFO.public_list_link);
            },

        },

        mounted() {
            this.LOAD_QUESTIONS();
        }
    }
</script>

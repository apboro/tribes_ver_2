<template>
    <div class="knowledge-table">
        <!-- Head -->
        <div class="knowledge-table__header">
            <!-- Multiple operations -->
            <div class="knowledge-table__header-item">
                <v-checkbox
                    id="all_question"
                    :value="GET_ALL_STATUS_MULTIPLE_OPERATIONS"
                    :modelValue="GET_ALL_STATUS_MULTIPLE_OPERATIONS"    
                    @change="toggleStateQuestions"
                />
            </div>

            <!-- Вопрос -->
            <div class="knowledge-table__header-item">
                Вопрос
            </div>

            <!-- Дата -->
            <div
                class="knowledge-table__header-item knowledge-table__header-item--sortable"
            >
                <span>Дата</span>
                
                <template v-if="sort.update_at === 'off'">
                    <button
                        class="button-text button-text--primary button-text--only-icon"
                        @click="toSort('update_at', 'asc')"
                    >
                        <v-icon
                            name="sort-asc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>

                <template v-if="sort.update_at === 'asc'">
                    <button
                        class="button-text button-text--primary button-text--only-icon active"
                        @click="toSort('update_at', 'desc')"
                    >
                        <v-icon
                            name="sort-asc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>

                <template v-if="sort.update_at === 'desc'">
                    <button
                        class="button-text button-text--primary button-text--only-icon active"
                        @click="toSort('update_at', 'off')"
                    >
                        <v-icon
                            name="sort-desc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>
            </div>

            <!-- Обращений -->
            <div class="knowledge-table__header-item knowledge-table__header-item--sortable">
                <span>Обращений</span>

                <template v-if="sort.c_enquiry === 'off'">
                    <button
                        class="button-text button-text--primary button-text--only-icon"
                        @click="toSort('c_enquiry', 'asc')"
                    >
                        <v-icon
                            name="sort-asc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>

                <template v-if="sort.c_enquiry === 'asc'">
                    <button
                        class="button-text button-text--primary button-text--only-icon active"
                        @click="toSort('c_enquiry', 'desc')"
                    >
                        <v-icon
                            name="sort-asc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>

                <template v-if="sort.c_enquiry === 'desc'">
                    <button
                        class="button-text button-text--primary button-text--only-icon active"
                        @click="toSort('c_enquiry', 'off')"
                    >
                        <v-icon
                            name="sort-desc"
                            size="1"
                            class="button-text__icon"
                        />
                    </button>
                </template>
            </div>

            <!-- Статус -->
            <div class="knowledge-table__header-item">
                Статус
            </div>

            <!-- Действия -->
            <div class="knowledge-table__header-item knowledge-table__header-item--center">
                Действия
            </div>            
        </div>

        <!-- Body -->
        <div class="knowledge-table__body">
            <!-- Loading -->
            <template v-if="IS_LOADING">
                <div class="knowledge-table__row knowledge-table__row--special">
                    <v-icon
                        name="spinner-primary"
                        :sizeParams="{
                            width: 36,
                            height: 36
                        }"
                        class="icon--spinner"
                    />
                </div>
            </template>

            <template v-else>
                <!-- Data -->
                <template v-if="isHasQuestions">
                    <knowledge-table-item
                        v-for="question in questions"
                        :key="question.id"
                        :question="question"
                    />
                </template>
                
                <!-- Empty -->
                <template v-else>
                    <div class="knowledge-table__row knowledge-table__row--special">
                        <p>Таблица пуста</p>
                        <p>Добавьте вопрос-ответ</p>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import VIcon from '../VIcon.vue';
    import VCheckbox from '../VCheckbox.vue';
    import KnowledgeTableItem from './KnowledgeTableItem.vue';

    export default {
        name: 'KnowledgeTable',

        components: {
            KnowledgeTableItem,
            VIcon,
            VCheckbox
        },

        props: {
            questions: {
                type: Array,
                default: []
            },
        },

        data() {
            return {
                sort: {
                    update_at: 'off',
                    c_enquiry: 'off',
                },
            }
        },

        computed: {
            ...mapGetters('knowledge', [
                'IS_LOADING',
                'GET_ALL_STATUS_MULTIPLE_OPERATIONS'
            ]),

            isHasQuestions() {
                return this.questions && this.questions.length ? true : false;
            },
        },

        methods: {
            ...mapMutations('knowledge', [
                'SET_SORT',
                'CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS'
            ]),

            ...mapActions('knowledge', ['LOAD_QUESTIONS']),

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
            },

            toggleStateQuestions() {
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(!this.GET_ALL_STATUS_MULTIPLE_OPERATIONS);
            }
        }
    }
</script>

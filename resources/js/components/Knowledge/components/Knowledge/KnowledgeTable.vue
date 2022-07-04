<template>
    <div class="knowledge-table">
        <!-- Head -->
        <div class="knowledge-table__header">
            <!-- Multiple operations -->
            <div class="knowledge-table__header-item">
                <input
                    type="checkbox"
                    v-model="isAll"
                    
                >
            </div>

            <!-- Вопрос -->
            <div class="knowledge-table__header-item">
                Вопрос {{GET_ALL_STATUS_MULTIPLE_OPERATIONS}}
            </div>

            <!-- Дата -->
            <div
                class="knowledge-table__header-item knowledge-table__header-item--sortable"
            >
                <span>Дата</span>
                
                <select
                    v-model="sort.update_at"
                    @change="selectSort('update_at', $event)"
                >
                    <option value="off">off</option>
                    <option value="asc">возр</option>
                    <option value="desc">убыв</option>
                </select>
            </div>

            <!-- Обращений -->
            <div class="knowledge-table__header-item knowledge-table__header-item--sortable">
                <span>Обращений</span>

                <select
                    v-model="sort.c_enquiry"
                    @change="selectSort('c_enquiry', $event)"
                >
                    <option value="off">off</option>
                    <option value="asc">возр</option>
                    <option value="desc">убыв</option>
                </select>
            </div>

            <!-- Статус -->
            <div class="knowledge-table__header-item">
                Статус
            </div>

            <!-- Действия -->
            <div class="knowledge-table__header-item">
                Действия
            </div>            
        </div>

        <!-- Body -->
        <div class="knowledge-table__body">
            <!-- Loading -->
            <template v-if="IS_LOADING">
                <div class="knowledge-table__row knowledge-table__row--special">
                    loading...
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
                        Empty
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapMutations, mapActions } from 'vuex';
    import KnowledgeTableItem from './KnowledgeTableItem.vue';

    export default {
        name: 'KnowledgeTable',

        components: { KnowledgeTableItem },

        props: {
            questions: {
                type: Array,
                default: []
            },
        },

        data() {
            return {
                sortName: '',

                sort: {
                    update_at: 'off',
                    c_enquiry: 'off',
                },

                isAddedAllQuestions: false,
            }
        },

        computed: {
            ...mapGetters([
                'IS_LOADING',
                'IS_ADDED_QUESTIONS',
                'GET_ALL_STATUS_MULTIPLE_OPERATIONS'
            ]),

            isHasQuestions() {
                return this.questions && this.questions.length ? true : false;
            },

            isAll: {
                get() {
                    return this.GET_ALL_STATUS_MULTIPLE_OPERATION;
                },
                set(bool) {
                    console.log(bool);
                    this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(bool);
                }
            },
        },

        methods: {
            ...mapMutations(['SET_SORT', 'ADD_ID_FOR_OPERATIONS', 'REMOVE_ID_FOR_OPERATIONS', 'CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS']),
            ...mapActions(['LOAD_QUESTIONS']),

            selectSort(sortName, event) {
                // выключаем все фильтры кроме того который включаем
                Object.keys(this.sort).forEach((name) => {
                    if (sortName != name) {
                        this.sort[name] = 'off';
                    }
                });
                // если значение "не выключен" передаем данные сортировки в состояние
                // иначе задаем дефолтное
                if (event.target.value != 'off') {
                    this.SET_SORT({ name: sortName, rule: event.target.value });
                } else {
                    this.SET_SORT({ name: 'id', rule: 'asc' });
                }

                this.LOAD_QUESTIONS();
            },

            toggleStateQuestions() {
                if (this.isAddedAllQuestions) {
                    this.questions.forEach((question) => {
                        // не добавляем элемент если такой уже был добавлен
                        if (!this.IS_ADDED_QUESTIONS(question.id)) {
                            this.ADD_ID_FOR_OPERATIONS(question.id);
                        }
                    });
                } else {
                    this.questions.forEach((question) => {
                        this.REMOVE_ID_FOR_OPERATIONS(question.id);
                    });
                }
            }
        }
    }
</script>

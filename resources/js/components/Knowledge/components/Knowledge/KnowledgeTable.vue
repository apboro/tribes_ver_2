<template>
    <div class="knowledge-table">
        <!-- Head -->
        <div class="knowledge-table__header">
            <!-- Multiple operations -->
            <div class="knowledge-table__header-item">
                <input
                    type="checkbox"
                    :checked="GET_ALL_STATUS_MULTIPLE_OPERATIONS"
                    :value="GET_ALL_STATUS_MULTIPLE_OPERATIONS"
                    @input="toggleStateQuestions"
                >
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
            }
        },

        computed: {
            ...mapGetters('knowledge', [
                'IS_LOADING',
                'IS_ADDED_QUESTIONS',
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
                this.CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(!this.GET_ALL_STATUS_MULTIPLE_OPERATIONS);
            }
        }
    }
</script>

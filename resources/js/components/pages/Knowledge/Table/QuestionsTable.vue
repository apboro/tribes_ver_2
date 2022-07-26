<template>
    <div>
        <v-table
            :data="questions"
        >   
            <!-- Header -->
            <template #header>
                <!-- Multiple operations -->
                <div class="table__header-item">
                    <v-checkbox
                        id="all_question"
                        :value="GET_ALL_STATUS_MULTIPLE_OPERATIONS"
                        :modelValue="GET_ALL_STATUS_MULTIPLE_OPERATIONS"    
                        @change="toggleStateQuestions"
                    />
                </div>

                <!-- Вопрос -->
                <div class="table__header-item">
                    Вопрос
                </div>

                <!-- Дата -->
                <div
                    class="table__header-item table__header-item--sortable"
                >
                    <span>Дата</span>
                    
                    <transition name="a-sort-icon" mode="out-in">
                        <template v-if="sort.update_at === 'off'">
                            <button
                                key="date_sort_asc"
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

                        <template v-else-if="sort.update_at === 'asc'">
                            <button
                                key="date_sort_asc_active"
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

                        <template v-else-if="sort.update_at === 'desc'">
                            <button
                                key="date_sort_desc"
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
                    </transition>
                </div>

                <!-- Обращений -->
                <div class="table__header-item table__header-item--sortable">
                    <span>Обращений</span>

                    <transition name="a-sort-icon" mode="out-in">
                        <template v-if="sort.c_enquiry === 'off'">
                            <button
                                key="enquiry_sort_asc"
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

                        <template v-else-if="sort.c_enquiry === 'asc'">
                            <button
                                key="enquiry_sort_asc_active"
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

                        <template v-else-if="sort.c_enquiry === 'desc'">
                            <button
                                key="enquiry_sort_desc"
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
                    </transition>
                </div>

                <!-- Статус -->
                <div class="table__header-item">
                    Статус
                </div>

                <!-- Действия -->
                <div class="table__header-item table__header-item--center">
                    Действия
                </div>
            </template>
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
                headerItems: [
                    {
                        type: 'multiple',
                        value=this.GET_ALL_STATUS_MULTIPLE_OPERATIONS,
                        modelValue=GET_ALL_STATUS_MULTIPLE_OPERATIONS,    
                        change() { this.toggleStateQuestions() },
                    },
                ],

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

<template>
    <div class="knowledge-filter">
        <div class="knowledge-filter__wrapper">
            <h3 class="knowledge-filter__title">
                Фильтры:
            </h3>

            <div class="knowledge-filter__group knowledge-filter__group--first">
                <!-- All -->
                <radio-button
                    id="answer_all"
                    class="knowledge-filter__item"
                    value="all"
                    label="Все"
                    v-model="filters.with_answers"
                    @change="filter"
                />

                <!-- No answer -->
                <radio-button
                    id="answer_no_answer"
                    class="knowledge-filter__item"
                    value="no_answer"
                    label="Без ответа"
                    v-model="filters.with_answers"
                    @change="filter"
                />

                <!-- Width answer -->
                <radio-button
                    id="answer_with_answer"
                    class="knowledge-filter__item"
                    value="with_answer"
                    label="С ответом"
                    v-model="filters.with_answers"
                    @change="filter"
                />
            </div>

            <div class="knowledge-filter__group">
                <!-- All -->
                <radio-button
                    id="status_all"
                    class="knowledge-filter__item"
                    value="all"
                    label="Все"
                    v-model="filters.status"
                    @change="filter"
                />

                <!-- Not public -->
                <radio-button
                    id="status_not_public"
                    class="knowledge-filter__item"
                    value="not_public"
                    label="Не опубликовано"
                    v-model="filters.status"
                    @change="filter"
                />

                <!-- Public -->
                <radio-button
                    id="status_public"
                    class="knowledge-filter__item"
                    value="public"
                    label="Опубликовано"
                    v-model="filters.status"
                    @change="filter"
                />
            
                <!-- Draft -->
                <radio-button
                    id="status_draft"
                    class="knowledge-filter__item"
                    value="draft"
                    label="Черновик"
                    v-model="filters.status"
                    @change="filter"
                />
            </div>
        </div>

        <button
            class="button-text button-text--primary"
            @click="resetFilters"
        >
            Сбросить фильтры
        </button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import RadioButton from '../../ui/form/RadioButton.vue';

    export default {
        name: 'KnowledgeFilter',
        
        components: { RadioButton },

        data() {
            return {
                filters: {
                    with_answers: 'all',
                    status: 'all',
                },
            }
        },

        methods: {
            ...mapActions('knowledge', ['FILTER_QUESTIONS']),
            
            filterStatus(value) {
                this.filters.status = value;
                this.FILTER_QUESTIONS(this.filters);
            },

            filterWithAnswers(value) {
                this.filters.with_answers = value;
                this.FILTER_QUESTIONS(this.filters);
            },
            
            resetFilters() {
                this.filters = {
                    with_answers: 'all',
                    status: 'all',
                };
                
                this.$emit('resetFilters', this.filters);
            },

            filter() {
                this.FILTER_QUESTIONS(this.filters);
            }
        }
    }
</script>

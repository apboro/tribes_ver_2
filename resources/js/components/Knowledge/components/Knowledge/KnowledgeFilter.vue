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
                    :checkedValue="filters.with_answers"
                    label="Все"
                    @input="filterWithAnswers"
                />

                <!-- No answer -->
                <radio-button
                    id="answer_no_answer"
                    class="knowledge-filter__item"
                    value="no_answer"
                    :checkedValue="filters.with_answers"
                    label="Без ответа"
                    @input="filterWithAnswers"
                />

                <!-- Width answer -->
                <radio-button
                    id="answer_with_answer"
                    class="knowledge-filter__item"
                    value="with_answer"
                    :checkedValue="filters.with_answers"
                    label="С ответом"
                    @input="filterWithAnswers"
                />
            </div>

            <div class="knowledge-filter__group">
                <!-- All -->
                <radio-button
                    id="status_all"
                    class="knowledge-filter__item"
                    value="all"
                    :checkedValue="filters.status"
                    label="Все"
                    @input="setStatus"
                />

                <!-- Not public -->
                <radio-button
                    id="status_not_public"
                    class="knowledge-filter__item"
                    value="not_public"
                    :checkedValue="filters.status"
                    label="Не опубликовано"
                    @input="setStatus"
                />

                <!-- Public -->
                <radio-button
                    id="status_public"
                    class="knowledge-filter__item"
                    value="public"
                    :checkedValue="filters.status"
                    label="Опубликовано"
                    @input="setStatus"
                />
            
                <!-- Draft -->
                <radio-button
                    id="status_draft"
                    class="knowledge-filter__item"
                    value="draft"
                    :checkedValue="filters.status"
                    label="Черновик"
                    @input="setStatus"
                />
            </div>
        </div>

        <button
            class="button-text button-text--primary knowledge-filter__btn"
            @click="resetFilters"
        >
            Сбросить фильтры
        </button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';
    import RadioButton from '../RadioButton.vue';

    export default {
        name: 'KnowledgeFilter',
        
        components: { RadioButton },

        data() {
            return {
                filters: {
                    with_answers: 'all',
                    status: 'all',
                    /* published: 'all',
                    draft: 'all', */
                },
            }
        },

        methods: {
            ...mapActions('knowledge', ['FILTER_QUESTIONS']),
            
            setStatus(value) {
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
                    /* published: 'all',
                    draft: 'all', */
                };
                
                this.$emit('resetFilters', this.filters);
            },
            
            filter() {
                this.FILTER_QUESTIONS(this.filters);
            }
        }
    }
</script>

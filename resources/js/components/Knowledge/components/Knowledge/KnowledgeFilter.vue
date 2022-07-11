<template>
    <div class="knowledge-filter">
        <div class="knowledge-filter__wrapper">
            <h3 class="knowledge-filter__title">
                Фильтры:
            </h3>

            <div class="knowledge-filter__group knowledge-filter__group--first">
                <!-- All -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="answer_all"
                            class="radio-button__input"
                            value="all"
                            v-model="filters.with_answers"
                            @change="filter"
                        >
                        
                        <label
                            for="answer_all"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="answer_all"
                        class="knowledge-filter__label"
                    >
                        Все
                    </label>
                </div>

                <!-- No answer -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="no_answer"
                            class="radio-button__input"
                            value="no_answer"
                            v-model="filters.with_answers"
                            @change="filter"
                        >
                        
                        <label
                            for="no_answer"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="no_answer"
                        class="knowledge-filter__label"
                    >
                        Без ответа
                    </label>
                </div>

                <!-- Width answer -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="with_answer"
                            class="radio-button__input"
                            value="with_answer"
                            v-model="filters.with_answers"
                            @change="filter"
                        >
                        
                        <label
                            for="with_answer"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="with_answer"
                        class="knowledge-filter__label"
                    >
                        С ответом
                    </label>
                </div>
            </div>

            <div class="knowledge-filter__group">
                <!-- All -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="status_all"
                            class="radio-button__input"
                            value="all"
                            v-model="filters.status"
                            @change="filter"
                        >
                        
                        <label
                            for="status_all"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="status_all"
                        class="knowledge-filter__label"
                    >
                        Все
                    </label>
                </div>

                <!-- Not public -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="status_not_public"
                            class="radio-button__input"
                            value="not_public"
                            v-model="filters.status"
                            @change="filter"
                        >
                        
                        <label
                            for="status_not_public"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="status_not_public"
                        class="knowledge-filter__label"
                    >
                        Не опубликовано
                    </label>
                </div>

                <!-- Public -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="status_public"
                            class="radio-button__input"
                            value="public"
                            v-model="filters.status"
                            @change="filter"
                        >
                        
                        <label
                            for="status_public"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="status_public"
                        class="knowledge-filter__label"
                    >
                        Опубликовано
                    </label>
                </div>
            
                <!-- Draft -->
                <div class="knowledge-filter__item">
                    <div class="radio-button knowledge-filter__radio-button">
                        <input
                            type="radio"
                            id="status_draft"
                            class="radio-button__input"
                            value="draft"
                            v-model="filters.status"
                            @change="filter"
                        >
                        
                        <label
                            for="status_draft"
                            class="radio-button__label"
                        ></label>
                    </div>

                    <label
                        for="status_draft"
                        class="knowledge-filter__label"
                    >
                        Черновик
                    </label>
                </div>
            </div>

            <!-- <radio-button
                id="a"
                value="1"
                :name="filters.status"
                
                @input="a"
            /> -->
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
            a(event) {
                this.filters.status = event.target.value
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

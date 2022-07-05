<template>
    <div class="knowledge-filter">
        <div class="knowledge-filter__wrapper">
            <div class="knowledge-filter__group">
                <label class="knowledge-filter__title">
                    Ответ
                </label>
                
                <select
                    class="form-item"
                    @change="filter"
                    v-model="filters.with_answers"
                >
                    <option value="all">Все</option>
                    <option value="with_answer">С ответом</option>
                    <option value="no_answer">Без ответа</option>
                </select>
            </div>

            <div class="knowledge-filter__group">
                <label class="knowledge-filter__title">
                    Статус
                </label>
                
                <select
                    class="form-item"
                    @change="filter"
                    v-model="filters.published"
                >
                    <option value="all">Все</option>
                    <option value="public">Опубликовано</option>
                    <option value="not_public">Не опубликовано</option>
                </select>
            </div>

            <div class="knowledge-filter__group">
                <label class="knowledge-filter__title">
                    Черновик
                </label>
                
                <select
                    class="form-item"
                    @change="filter"
                    v-model="filters.draft"
                >
                    <option value="all">Все</option>
                    <option value="draft">Черновик</option>
                    <option value="not_draft">Не черновик</option>
                </select>
            </div>
        </div>

        <button
            class="knowledge-filter__btn"
            @click="resetFilters"
        >
            Сбросить фильтры
        </button>
    </div>
</template>

<script>
    import { mapActions } from 'vuex';

    export default {
        name: 'KnowledgeFilter',

        data() {
            return {
                filters: {
                    with_answers: 'all',
                    published: 'all',
                    draft: 'all',
                },
            }
        },

        methods: {
            ...mapActions('knowledge', ['FILTER_QUESTIONS']),
            
            resetFilters() {
                this.filters = {
                    with_answers: 'all',
                    published: 'all',
                    draft: 'all',
                };
                
                this.$emit('resetFilters', this.filters);
            },
            
            filter() {
                this.FILTER_QUESTIONS(this.filters);
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
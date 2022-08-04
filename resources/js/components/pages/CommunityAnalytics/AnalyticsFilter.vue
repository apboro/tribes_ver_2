<template>
    <div class="filter-analytics">
        <span class="filter-analytics__title">
            Период:
        </span>

        <ul class="filter-analytics__list">
            <li
                class="filter-analytics__item"
                v-for="filter in filters"
                :key="filter.value"
            >
                <input
                    type="radio"
                    :id="filter.value"
                    class="filter-analytics__radio"
                    :value="filter.value"
                    v-model="periods"
                >

                <label
                    :for="filter.value"
                    class="filter-analytics__label"
                >
                    {{ filter.text }}
                </label>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'AnalyticsFilter',
        
        data() {
            return {
                filters: [
                    {
                        text: 'День',
                        value: 'day'
                    },

                    {
                        text: 'Неделя',
                        value: 'week'
                    },

                    {
                        text: 'Месяц',
                        value: 'month'
                    },

                    {
                        text: 'Год',
                        value: 'year'
                    },

                    {
                        text: 'За все время',
                        value: 'all_time'
                    },
                ],

                period: 'week',
            }
        },

        computed: {
            periods: {
                get() {
                    return this.period;
                },
                set(value) {
                    this.period = value;
                    this.$emit('getPeriod', value);
                }
            }
        },
    }
</script>

<template>
    <div class="filter-analytics-community">

        <!-- <ul class="filter-analytics-community__list">
            <li
                class="filter-analytics-community__item"
                v-for="filter in filters"
                :key="filter.value"
            >
                <input
                    type="radio"
                    :id="filter.value"
                    class="filter-analytics-community__radio"
                    :value="filter.value"
                    v-model="periods"
                >

                <label
                    :for="filter.value"
                    class="filter-analytics-community__label"
                >
                    {{ filter.text }}
                </label>
            </li>
        </ul> -->
        
        <select 
            class="filter-analytics-community__list"
            v-model="periods"
        >
            <option
                class="filter-analytics-community__item"
                v-for="filter in filters"
                :key="filter.value"
                :value="filter.value"
            >
                {{ filter.text }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        name: 'AnalyticsFilter',

        props: {
            filterValue: {
                type: String,
                default: '',
            }
        },
        
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

                period: this.filterValue,
            }
        },

        computed: {
            periods: {
                get() {
                    return this.period;
                },
                set(value) {
                    this.period = value;
                    this.$emit('setPeriod', value);
                }
            }
        },
    }
</script>

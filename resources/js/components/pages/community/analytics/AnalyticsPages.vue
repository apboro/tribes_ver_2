<template>
    <div class="select-rounded">
        
        <select 
            class="select-rounded__list"
            v-model="pages"
        >
            <option
                class="select-rounded__item"
                v-for="tab in tabs"
                :key="tab.value"
                :value="tab.value"
                :selected="tab.value == visibleTab"
            >
                {{ tab.text }}
            </option>
        </select>
    </div>
</template>

<script>
    export default {
        name: 'AnalyticsPages',

        props: {

            visibleTab: {
                type: String,
                default: '',
            },
        },
        
        data() {
            return {
                tabs: [
                    {
                        text: 'Подписчики',
                        value: 'subscribers'
                    },

                    {
                        text: 'Сообщения',
                        value: 'messages'
                    },

                    {
                        text: 'Финансы',
                        value: 'payments'
                    },
                ],

                page: this.visibleTab,
            }
        },

        computed: {
            pages: {
                get() {
                    return this.page;
                },
                set(value) {
                    this.page = value;
                    this.$emit('setPage', value);
                }
            }
        },
    }
</script>

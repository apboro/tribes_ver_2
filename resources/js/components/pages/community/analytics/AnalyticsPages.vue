<template>
    
        
        <select 
            class="select-rounded"
            v-model="pages"
        >
            <option
                v-for="tab in tabs"
                :key="tab.value"
                :value="tab.value"
                :selected="tab.value == visibleTab"
            >
                {{ tab.text }}
            </option>
        </select>
    
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

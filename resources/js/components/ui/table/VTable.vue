<template>
    <div class="table knowledge-table">
        <table-header
            :class="{ 'table__header--disabled' : !hasQuestions }"
            :tableHeader="tableHeader"
            :sortAttrs="sortAttrs"
        />

        <table-body
            :data="data"
            :tableRow="tableRow"
            :isLoading="isLoading"
            :hasQuestions="hasQuestions"
        >
            <template #openableBlock="{ data, isVisibleHiddenRow, toggleHiddenRowVisibility }">
                <slot
                    name="openableBlock"
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                    :toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                ></slot>    
            </template>

            <template #hiddenRow="{ data, isVisibleHiddenRow }">
                <slot
                    name="hiddenRow"
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                ></slot>
            </template>

            <template #tableAction="{ data }">
                <slot
                    name="tableAction"
                    :data="data"
                ></slot>
            </template>

        </table-body>
    </div>
</template>

<script>
    import TableHeader from './TableHeader.vue';
    import TableBody from './TableBody.vue';

    export default {
        name: 'VTable',

        components: {
            TableHeader,
            TableBody,
        },

        props: {
            data: {
                type: Array,
                default: []
            },

            tableHeader: {
                type: Array,
                default: [],
            },

            tableRow: {
                type: Array,
                default: () => [],
            },

            sortAttrs: {
                type: Object,
                default: {},
            },

            isLoading: {
                type: Boolean,
                default: false,
            },

            
        },

        computed: {
            hasQuestions() {
                return this.data && this.data.length ? true : false;
            },
        },

        methods: {
           
        }
    }
</script>

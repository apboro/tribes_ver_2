<template>
    <div class="table knowledge-table">
        <table-header
            :class="{ 'table__header--disabled' : !hasQuestions }"
            :data="data"
            :table="table"
            :sortAttrs="sortAttrs"
            @changeMultipleState="changeMultipleState"
            @sort="toSort"
        />

        <table-body
            :isLoading="isLoading"
            :hasQuestions="hasQuestions"
        />
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

            table: {
                type: Array,
                default: [],
            },

            sortAttrs: {
                type: Object,
                default: {},
            },

            isLoading: {
                type: Boolean,
                default: false,
            }
        },

        computed: {
            hasQuestions() {
                return this.data && this.data.length ? true : false;
            },
        },

        methods: {            
            changeMultipleState() {
                this.$emit('changeMultipleState');
            },

            toSort(sortName, sortRule) {
                this.$emit('sort', sortName, sortRule);
            }
        }
    }
</script>

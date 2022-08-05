<template>
    <div class="table" :class="classTable">
        <table-header
            :class="{ 'table__header--disabled' : !hasData }"
            :headerSettings="tableOptions.header"
            :sortAttrs="sortAttrs"
        />

        <table-body
            :data="data"
            :rowSettings="tableOptions.row"
            :isLoading="isLoading"
            :hasData="hasData"
        >
            <!-- Слот для вставки элемента по которому можно будет открыть невидимую строку -->
            <template #openableCol="{ data, isVisibleHiddenRow, toggleHiddenRowVisibility }">
                <slot
                    name="openableCol"
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                    :toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                ></slot>    
            </template>

            <!-- Слот для вставки контента, содержащегося в невидимой строке -->
            <template #hiddenRow="{ data, isVisibleHiddenRow }">
                <slot
                    name="hiddenRow"
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                ></slot>
            </template>

            <!-- Слот для вставки меню действий, которое может быть добавлено как колонка в строку таблицы -->
            <template #actionCol="{ data }">
                <slot
                    name="actionCol"
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

            tableOptions: {
                type: Object,
                default: () => {},
            },

            sortAttrs: {
                type: [Object, null],
                default: null,
            },

            isLoading: {
                type: Boolean,
                default: false,
            },

            classTable: {
                type: String,
                default: ''
            }
        },

        computed: {
            hasData() {
                return this.data && this.data.length ? true : false;
            },
        },
    }
</script>

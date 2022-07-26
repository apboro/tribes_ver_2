<template>
    <div class="table knowledge-table">
        <!-- Head -->
        <div
            class="table__header"
            :class="{ 'table__header--disabled' : !isHasQuestions }"    
        >
            <slot name="header"></slot>

            <div
                v-for="(headerItem, index) in headerItems"
                :key="index"
            >
                
            </div>     
        </div>
    </div>
</template>

<script>
    export default {
        name: 'VTable',

        props: {
            data: {
                type: Array,
                default: []
            },

            headerItems: {
                type: Array,
                default: []
            }
        },

        data() {
            return {

            }
        },

        methods: {
            isHasQuestions() {
                return this.data && this.data.length ? true : false;
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>
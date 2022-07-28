<template>
    <!-- Body -->
    <div class="table__body">
        <!-- Loading -->
        <template v-if="isLoading">
            <div class="table__row table__row--special">
                <v-icon
                    name="spinner-primary"
                    :sizeParams="{
                        width: 36,
                        height: 36
                    }"
                    class="icon--spinner"
                />
            </div>
        </template>

        <template v-else>
            <!-- Data -->
            <template v-if="hasQuestions">
                <table-row
                    v-for="(item, index) in data"
                    :key="index"
                    :data="item"
                    :row="tableRow"
                    
                ></table-row>
            </template>
            
            <!-- Empty -->
            <template v-else>
                <div class="table__row table__row--special">
                    <p>Таблица пуста</p>
                    <p>Добавьте вопрос-ответ</p>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    import VIcon from "../icon/VIcon.vue";
    import TableRow from "./TableRow.vue";

    export default {
        name: 'TableBody',

        components: {
            VIcon,
            TableRow,
        },

        props: {
            data: {
                type: Array,
                default: [],
            },

            tableRow: {
                type: Array,
                default: () => [],
            },

            isLoading: {
                type: Boolean,
                default: false,
            },

            hasQuestions: {
                type: Boolean,
                default: false,
            },

           /*  isAddedField: {
                type: Boolean,
                default: false,
            } */
        },

        methods: {
           
        }
    }
</script>

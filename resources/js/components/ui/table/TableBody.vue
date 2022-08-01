<template>
    <!-- Body -->
    <div class="table__body">
        <!-- Loading -->
        <div
            v-if="isLoading"
            class="table__row table__row--special"
        >
            <v-icon
                name="spinner-primary"
                :sizeParams="{
                    width: 36,
                    height: 36
                }"
                class="icon--spinner"
            />
        </div>

        <template v-else>
            <!-- Data -->
            <template v-if="hasData">
                <table-row
                    v-for="(item, index) in data"
                    :key="index"
                    :data="item"
                    :row="rowSettings"
                    :isOpenable="isOpenable"
                    
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

                </table-row>
            </template>
            
            <!-- Empty -->
            <div
                v-else
                class="table__row table__row--special"
            >
                <p>Таблица пуста</p>
                <p>Добавьте вопрос-ответ</p>
            </div>
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

            rowSettings: {
                type: Array,
                default: () => [],
            },

            isLoading: {
                type: Boolean,
                default: false,
            },

            hasData: {
                type: Boolean,
                default: false,
            }, 
        },

        computed: {
            isOpenable() {
                return this.rowSettings.find((item) => item.type == 'openable') ? true : false;
            },
        },
    }
</script>

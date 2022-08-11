<template>
    <div
        class="table__row-wrapper"
        :class="{ 'open': isVisibleHiddenRow }"
    >
        <!-- Строка -->
        <div
            class="table__row"
            :class="{ 'active': isAddedField }"
        >
            <div
                v-for="(col, index) in row"
                :key="index"
            >
                <!-- Выделить -->
                <div
                    v-if="col.type == 'multiple'"
                    class="table__item"
                >
                    <v-checkbox
                        :id="`field_${ data.id }`"
                        :value="col.getValue(data.id)"
                        :modelValue="col.getValue(data.id)"
                        @change="changeMultiple(col.setValue, $event, data.id)"
                    />
                </div>

                <!-- Раскрывающийся элемент -->
                <slot
                    v-else-if="col.type == 'openable'"
                    name="openableCol"
                    :data="data"
                    :isVisibleHiddenRow="isVisibleHiddenRow"
                    :toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                ></slot>    

                <!-- Дата, время -->
                <div
                    v-else-if="col.type == 'time'"
                    class="table__item table__item--changable"
                >
                    <time-format
                        :value="data[col.key]"
                        :typeValue="col.typeValue"
                    />
                </div>
                
                <!-- Текст -->
                <div
                    v-else-if="col.type == 'text'"
                    class="table__item table__item--changable"
                >
                    {{ data[col.key] }}
                </div>
                
                <!-- Статус -->
                <div
                    v-else-if="col.type == 'status'"
                    class="table__item"
                >
                    <span
                        class="table__status"
                        :class="{
                            'table__status--green': col.getStatus(data).class == 'green',
                            'table__status--red': col.getStatus(data).class == 'red',
                            'table__status--orange': col.getStatus(data).class == 'orange',
                        }"
                    >
                        {{ col.getStatus(data).text }}
                    </span>
                </div>

                <!-- Меню действий -->
                <div
                    v-else-if="col.type == 'actions'"
                    class="table__item table__item--center"
                >
                    <col-actions :colData="data">
                        <template #actionCol="{ data }">
                            <slot
                                name="actionCol"
                                :data="data"
                            ></slot>
                        </template>
                    </col-actions>
                </div>

                <!-- Меню действий -->
                <div
                    v-else-if="col.type == 'custom'"
                    class="table__item"
                >   
                    <slot
                        name="customCol"
                        :data="data"
                    ></slot> 
                    
                    <!-- <col-actions :colData="data">
                        <template #actionCol="{ data }">
                            <slot
                                name="actionCol"
                                :data="data"
                            ></slot>
                        </template>
                    </col-actions> -->
                </div>
            </div>
        </div>

        <!-- Невидимая строка -->
        <slot
            v-if="isOpenable"
            name="hiddenRow"
            :data="data"
            :isVisibleHiddenRow="isVisibleHiddenRow"
        ></slot>
    </div>
</template>

<script>
    import VCheckbox from"../form/VCheckbox.vue";
    import TimeFormat from '../format/TimeFormat.vue';
    import HiddenRow from "../../pages/Knowledge/Table/HiddenRow.vue";
    import ColActions from './ColActions.vue';

    export default {
        name: 'TableRow',
        
        components: {
            VCheckbox,
            HiddenRow,
            TimeFormat,
            ColActions,
        },

        props: {
            data: {
                type: Object,
                default: {},
            },

            row: {
                type: Array,
                default: () => [],
            },

            isOpenable: {
                type: Boolean,
                default: false,
            }
        },

        data() {
            return {
                isVisibleHiddenRow: false,
                isAddedField: false,
            }
        },

        methods: {
            changeMultiple(change, value, id) {
                this.isAddedField = value;
                change(value, id);
            },

            toggleHiddenRowVisibility() {
                this.isVisibleHiddenRow = !this.isVisibleHiddenRow;
            },
        },
    }
</script>

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
                <template v-if="col.type == 'multiple'">
                    <div class="table__item">
                        <v-checkbox
                            :id="`field_${ data.id }`"
                            :value="col.getValue(data.id)"
                            :modelValue="col.getValue(data.id)"
                            @change="changeMultiple(col.setValue, $event, data.id)"
                        />
                    </div>
                </template>

                <!-- Раскрывающийся элемент -->
                <template v-else-if="col.type == 'openable'">
                    <slot
                        name="openableBlock"
                        :data="data"
                        :isVisibleHiddenRow="isVisibleHiddenRow"
                        :toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                    ></slot>    
                </template>

                <template v-else-if="col.type == 'time'">
                    <div class="table__item table__item--changable">
                        <time-format
                            :value="data[col.key]"
                            :typeValue="col.typeValue"
                        />
                    </div>
                </template>

                <template v-else-if="col.type == 'text'">
                    <div class="table__item table__item--changable">
                        {{ data.c_enquiry }}
                    </div>
                </template>

                <template v-else-if="col.type == 'status'">
                    <div class="table__item">
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
                </template>

                <template v-else-if="col.type == 'actions'">
                    <div class="table__item table__item--center">
                        <col-actions :colData="data">
                            <template #tableAction="{ data }">
                                <slot
                                    name="tableAction"
                                    :data="data"
                                ></slot>
                            </template>
                        </col-actions>
                    </div>
                </template>
            </div>


        </div>

        <template v-if="isOpenable">
            <slot
                name="hiddenRow"
                :data="data"
                :isVisibleHiddenRow="isVisibleHiddenRow"
            ></slot>
        </template>
    </div>
</template>

<script>
    import VCheckbox from"../form/VCheckbox.vue";
    import TimeFormat from '../format/TimeFormat.vue';
    import VIcon from "../icon/VIcon.vue";
    import VDropdown from '../dropdown/VDropdown.vue';
    import HiddenRow from "../../pages/Knowledge/Table/HiddenRow.vue";
    import ColActions from './ColActions.vue';

    export default {
        name: 'TableRow',
        
        components: {
            VCheckbox,
            VIcon,
            VDropdown,
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
            onItemClick(type) {
                if (type == 'openable') {
                    this.toggleQuestion();
                }
            },

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

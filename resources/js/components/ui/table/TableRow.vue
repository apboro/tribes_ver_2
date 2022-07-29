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
            <!-- <div
                class="table__item"
                :class="{
                    'table__item--openable': col.type == 'openable',
                    'table__item--changable':
                        col.type == 'openable' ||
                        col.type == 'time' ||
                        col.type == 'text',
                    'table__item--center': col.type == 'actions'
                }"
                @click="onItemClick(col.type)"
            > -->
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

                <template v-else-if="col.type == 'openable'">
                    <slot
                        name="openableBlock"
                        :data="data"
                        :isVisibleHiddenRow="isVisibleHiddenRow"
                        :toggleHiddenRowVisibility="toggleHiddenRowVisibility"
                    ></slot>    
                </template>

                <!-- <template v-else-if="col.type == 'time'">
                    <time-format
                        :value="data[col.key]"
                        typeValue="date"
                    />
                </template>

                <template v-else-if="col.type == 'text'">
                    {{ data.c_enquiry }}
                </template>

                <template v-else-if="col.type == 'status'">
                    <span
                        class="table__status"
                        :class="{
                            'table__status--green': data.statusTheme == 'green',
                            'table__status--red': data.statusTheme == 'red',
                            'table__status--orange': data.statusTheme == 'orange',
                        }"
                    >
                        {{ data.status }}
                    </span>
                </template>

                <template v-else-if="col.type == 'actions'"> -->
                    <!-- <actions-dropdown
                        :data="data"
                        :actions="col.actions"
                    ></actions-dropdown> -->
                    <!-- <slot
                        name="tableAction"
                        :data="data"
                    ></slot>              
                </template> -->
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
    import HiddenRow from "../../pages/Knowledge/Table/HiddenRow.vue";
    import ActionsDropdown from './ActionsDropdown.vue';

    export default {
        name: 'TableRow',
        
        components: {
            VCheckbox,
            VIcon,
            HiddenRow,
            TimeFormat,
            ActionsDropdown,
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

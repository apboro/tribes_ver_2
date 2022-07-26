<template>
    <!-- Head -->
    <div
        class="table__header"    
    >
        <!-- Header Items -->
        <div
            v-for="(item, index) in table"
            :key="index"
        >
            <!-- Multiple -->
            <div
                v-if="item.header.type == 'multiple'"
                class="table__header-item"
            >
                <v-checkbox
                    :id="item.header.id"
                    :value="item.header.value()"
                    :modelValue="item.header.modelValue()"    
                    @change="changeMultipleState"
                />
            </div>

            <!-- Текстовый -->
            <div
                v-if="item.header.type == 'text'"
                class="table__header-item"
            >
                {{ item.header.text }}
            </div>

            <!-- Sort -->
            <div
                v-if="item.header.type == 'sorting'"
                class="table__header-item table__header-item--sortable"
            >
                <span>{{ item.header.text }}</span>
                
                <sort-button
                    :sortProps="item.header"
                    :value="sortAttrs[item.header.sortName]"
                    @sort="toSort"
                />
            </div>
        </div>     
    </div>
</template>

<script>
    import VCheckbox from "../form/VCheckbox.vue";
    import VIcon from "../icon/VIcon";
    import SortButton from './SortButton.vue';

    export default {
        name: 'TableHeader',

        components: {
            VCheckbox,
            VIcon,
            SortButton,
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
            }
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

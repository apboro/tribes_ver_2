<template>
    <!-- Head -->
    <div
        class="table__header"    
    >
        <!-- Header Items -->
        <div
            v-for="(item, index) in tableHeader"
            :key="index"
            class="table__header-item"
            :class="{
                'table__header-item--sortable': item.type == 'sorting',
                'table__header-item--center': item.type == 'text-center',    
            }"
        >
            <!-- Multiple -->
            <template v-if="item.type == 'multiple'">
                <v-checkbox
                    :id="item.id"
                    :value="item.value()"
                    :modelValue="item.modelValue()"    
                    @change="item.change"
                />
            </template>

            <!-- Текстовый -->
            <template v-if="item.type == 'text' || item.type == 'text-center'">
                {{ item.text }}
            </template>

            <!-- Sort -->
            <template v-if="item.type == 'sorting'">
                <span>{{ item.text }}</span>
            
                <sort-button
                    :sortProps="item"
                    :value="sortAttrs[item.sortName]"
                    @sort="item.sort"
                />
            </template>
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
            tableHeader: {
                type: Array,
                default: [],
            },

            sortAttrs: {
                type: Object,
                default: {},
            }
        },
    }
</script>

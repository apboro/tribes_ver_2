<template>
    <div
        class="table__row-wrapper"
        :class="{ 'open': isVisibleHideSection }"
    >
        <!-- Строка -->
        <div class="table__row" :class="{ 'active': isAddedField }">
            <!-- Выделить -->
            <div class="table__item" v-for="(col, index) in row" :key="index">
                <template v-if="col.type == 'multiple'">
                    <v-checkbox
                        :id="`field_${ data.id }`"
                        :value="col.getValue(data.id)"
                        :modelValue="col.getValue(data.id)"
                        @change="changeMultiple(col.setValue, $event, data.id)"
                    />
                </template>
            </div>


        </div>
    </div>
</template>

<script>
    import VCheckbox from"../form/VCheckbox.vue";

    export default {
        name: 'TableRow',
        
        components: {
            VCheckbox,
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

           /*  isAddedField: {
                type: Boolean,
                default: false,
            } */
        },

        data() {
            return {
                isVisibleHideSection: false,

                isAddedField: false,
            }
        },

        computed: {
            a: {
                get() {
                    
                },

                set(bool) {
                    
                }
            },

            
        },

        methods: {
            toggleQuestion() {
                this.isVisibleHideSection = !this.isVisibleHideSection;
                
                /* this.$nextTick(() => {
                    if (this.$refs.answer) {
                        if (this.$refs.answer.getBoundingClientRect().height > 100) {
                            this.isLongAnswer = true;
                            this.isVisibleFullAnswerBtn = true;
                        }
                    }
                }) */
            },

            changeMultiple(change, value, id) {
                change(value, id);
                this.isAddedField = value;
            }
        }
    }
</script>

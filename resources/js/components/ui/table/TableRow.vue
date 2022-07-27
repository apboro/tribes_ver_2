<template>
    <div
        class="table__row-wrapper"
        :class="{ 'open': isVisibleHideSection }"
    >
        <!-- Строка -->
        <div class="table__row" >
            <!-- Выделить -->
            <div class="table__item" v-for="(col, index) in row" :key="index">
                <template v-if="col.type == 'multiple'">
                    <v-checkbox
                        :id="`field_${ data.id }z`"
                        :value="col.show(data.id)"
                        :modelValue="col.show(data.id)"
                    />

                    <!-- <v-checkbox
                        :id="`field_${ data.id }x`"
                        :value="col.value(data.id)"
                        :modelValue="col.modelValue(data.id)"    
                        @change="change1($event, col.add, col.remove)"
                    /> -->
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

                
            }
        },

        computed: {
            a: {
                get() {
                    return this.$emit('getMultipleValue', this.data.id)
                    console.log(1);
                },

                set(bool) {
                    this.$emit('setMultipleValue', this.data.id, bool);
                }
            },

            isAddedField: {
                // проверяем есть ли такая запись в массиве, и ставим чек в зависимости от ответа
                get() {
                    return this.IS_ADDED_QUESTIONS(this.question.id);
                },

                set(isAdded) {
                    // при изменении добавляем или удалеяем элемент из массива
                    if (isAdded) {
                        this.ADD_ID_FOR_OPERATIONS(this.question.id);
                    } else {
                        this.REMOVE_ID_FOR_OPERATIONS(this.question.id);
                    }
                },
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

            change1(event, add, remove) {
                
                if (event.target.value) {
                    add(this.data.id);
                } else {
                    remove(this.data.id);
                }
            }
        }
    }
</script>

<template>
    <!-- Body -->
    <div class="table__body">
        <!-- Loading -->
        <template v-if="isLoading">
            <div class="knowledge-table__row knowledge-table__row--special">
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
                1
            </template>
            
            <!-- Empty -->
            <template v-else>
                <div class="knowledge-table__row knowledge-table__row--special">
                    <p>Таблица пуста</p>
                    <p>Добавьте вопрос-ответ</p>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    import VIcon from "../icon/VIcon.vue";

    export default {
        name: 'TableBody',

        components: { VIcon },

        props: {
            isLoading: {
                type: Boolean,
                default: false,
            },

            hasQuestions: {
                type: Boolean,
                default: false,
            }
        }
    }
</script>

<template>
    <table class="table-knowledge">
        <thead>
            <tr role="row">
                <th class="control" rowspan="1" colspan="1" style="width:1%">
                    <input type="checkbox" name="all" id="all">
                </th>
                <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 40%;">Вопрос</th>
                <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 10%;">Дата</th>
                <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 10%;">Обращений</th>
                <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 10%;">Статус</th>
                <th class="sorting" tabindex="0" rowspan="1" colspan="1" style="width: 10%;">Действия</th>
            </tr>
        </thead>

        <tbody ref="body">
            <template v-if="IS_LOADING">
                <tr>
                    <td colspan="6" style="text-align: center">loading...</td>
                </tr>
            </template>

            <template v-else>
                <template v-if="isHasQuestions">
                    <knowledge-table-item
                        v-for="item in questions"
                        :key="item.id"
                        :question="item"
                    />
                </template>

                <template v-else>
                    <tr>
                        <td colspan="6">Нет созданных вопросов</td>
                    </tr>
                </template>
            </template>
        </tbody>
    </table>
</template>

<script>
    import { mapGetters } from 'vuex';
    import KnowledgeTableItem from './KnowledgeTableItem.vue';
    
    export default {
        name: 'KnowledgeTableRESERVE',

        components: { KnowledgeTableItem },

        props: {
            questions: {
                type: Array,
                default: []
            }
        },

        data() {
            return {}
        },

        computed: {
            ...mapGetters(['IS_LOADING']),

            isHasQuestions() {
                return this.questions || this.questions.length ? true : false;
            }
        },

        methods: {}
    }
</script>

<style lang="scss" scoped>

</style>
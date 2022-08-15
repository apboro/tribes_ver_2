import { addQuestion, doMultipleOperations, editQuestion, filterQuestion, loadQuestions, removeQuestion } from '../../../api/knowledge';

export default ({
    async LOAD_QUESTIONS({ state, commit }) {
        try {
            commit('SET_LOADING_STATUS', true);
            const res = await loadQuestions({
                community_id: state._communityId,
                filter: {
                    ...state._filters,
                    sort: { ...state._sort },
                    ...state._pagination,
                },
            });
            
            console.log(res);
            commit('SET_META', res.data.meta);
            commit('SET_META_INFO', res.data.meta_info);
            commit('SET_QUESTIONS', res.data.items);
            commit('SET_LOADING_STATUS', false);
        } catch (error) {
            commit('SET_LOADING_STATUS', false);
            console.log(error);
        }
    },

    async ADD_QUESTION({ state, commit, dispatch }, data) {
        try {
            await addQuestion({
                community_id: state._communityId,
                ...data
            });
            await dispatch('LOAD_QUESTIONS');
        } catch (error) {
            console.log(error);
        }
    },

    async EDIT_QUESTION({ state, dispatch }, question) {
        try {
            await editQuestion({
                community_id: state._communityId,
                ...question
            });
            await dispatch('LOAD_QUESTIONS');
        } catch (error) {
            console.log(error);
        }
    },

    async FILTER_QUESTIONS({ state, commit }, filters) {
        try {
            //commit('SET_LOADING_STATUS', true);
            commit('SET_FILTERS', filters);
            const res = await filterQuestion({
                community_id: state._communityId,
                filter: {
                    ...state._filters,
                    sort: { ...state._sort },
                    ...state._pagination,
                }
            });
            
            commit('SET_QUESTIONS', res.data.items);
            commit('SET_META', res.data.meta);
            console.log(res);
            //commit('SET_LOADING_STATUS', false);
        } catch (error) {
            console.log(error);
        }
    },

    async REMOVE_QUESTION({ state, dispatch }, questionId) {
        try {
            await removeQuestion({ community_id: state._communityId, ...questionId });
            await dispatch('LOAD_QUESTIONS');
        } catch (error) {
            console.log(error);
        }
    },

    async TO_MULTIPLE_OPERATIONS({ state, commit, dispatch }, params) {
        try {
            const res = await doMultipleOperations({
                community_id: state._communityId,
                ids: [ ...state._idsMultipleOperations ],
                ...params,
            });
            commit('CLEAR_IDS_FOR_OPERATIONS');
            commit('SET_PAGINATION', { page: 1 });
            await dispatch('LOAD_QUESTIONS');
            
            if (res.data.success) {
                return { type: 'success', text: res.data.message };
            } else {
                return { type: 'error', text: res.data.message, errors: res.data.errors, items: res.data.items };
            }
        } catch (error) {
            console.log(error);
        }
    },
});

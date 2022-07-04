import Vue from 'vue'
import Vuex from 'vuex'
import { addQuestion, doMultipleOperations, editQuestion, filterQuestion, loadQuestions, removeQuestion } from './api';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        _communityId: window.location.pathname.split('/')[2],
        _communityTitle: 'Мудрость стоиков на каждый день',
        
        _questions: [],
        
        _meta: {},

        _metaInfo: {},

        _filters: {
            full_text: '',
            with_answers: 'all',
            published: 'all',
            draft: 'all',
        },

        _sort: {
            name: 'id',
            rule: 'asc'
        },

        _pagination: {
            page: '1',
            per_page: '15'
        },

        _isLoading: false,

        _isAllFields: false,

        _idsMultipleOperations: [],
    },

    getters: {
        COMMUNITY_TITLE(state) {
            return state._communityTitle;
        },

        GET_QUESTIONS(state) {
            return state._questions;
        },

        IS_LOADING(state) {
            return state._isLoading;
        },

        GET_META(state) {
            return state._meta;
        },

        GET_META_INFO(state) {
            return state._metaInfo;
        },

        IS_ADDED_QUESTIONS: (state) => (findingId) => {
            return state._idsMultipleOperations.find((id) => id == findingId);
        },

        HAS_QUESTION_FOR_OPERATIONS(state) {
            return state._idsMultipleOperations && state._idsMultipleOperations.length;
        },

        GET_IDS_MULTIPLE_OPERATIONS(state) {
            return state._idsMultipleOperations;
        },

        GET_ALL_STATUS_MULTIPLE_OPERATIONS(state) {
            console.log(state._questions.length);
            console.log(state._idsMultipleOperations.length);
            return state._questions.length === state._idsMultipleOperations.length;
        }
    },

    mutations: {
        SET_QUESTIONS(state, questions) {
            state._questions = questions;
        },
        
        SET_META(state, meta) {
            state._meta = meta;
        },

        SET_META_INFO(state, meta) {
            state._metaInfo = meta;
        },

        SET_LOADING_STATUS(state, bool) {
            state._isLoading = bool;
        },

        SET_FILTERS(state, filters) {
            Object.keys(state._filters).forEach((key) => {
                if (filters.hasOwnProperty(key)) {
                    state._filters[key] = filters[key];
                }
            });
        },

        SET_PAGINATION(state, option) {
            Object.keys(state._pagination).forEach((key) => {
                if (option.hasOwnProperty(key)) {
                    state._pagination[key] = option[key];
                }
            });
        },

        SET_SORT(state, option) {
            state._sort = option;
        },

        ADD_ID_FOR_OPERATIONS(state, addedId) {
            state._idsMultipleOperations.push(addedId);
        },

        REMOVE_ID_FOR_OPERATIONS(state, removedId) {
            state._idsMultipleOperations = state._idsMultipleOperations.filter((id) => id != removedId);
        },

        CLEAR_IDS_FOR_OPERATIONS(state) {
            state._idsMultipleOperations = [];
        },

        SET_IDS_MULTIPLE_OPERATIONS(state, ids) {
            state._idsMultipleOperations = ids;
        },

        CHANGE_ALL_QUESTIONS_ON_MULTIPLE_OPERATIONS(state, bool) {
            let arr = [];
            if (bool) {
                state._questions.forEach((question) => {
                    arr.push(question.id);
                })
            }
            state._idsMultipleOperations = arr;
        }

    },

    actions: {
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
        }
    },

    modules: {},

    mixins: {}
})
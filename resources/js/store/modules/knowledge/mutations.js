export default ({
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
    },

    SET_ERRORS(state, errors) {
        state._errors = errors;
    }
});

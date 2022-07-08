export default ({
    COMMUNITY_TITLE(state) {
        return state._metaInfo.community_title;
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

    GET__LINKS(state) {
        if (state._meta.links) {
            return state._meta.links.map((link) => {
                return {
                    active: link.active,
                    disabled: link.url ? false : true,
                    page: link.url ? new URL(link.url).search.split('=')[1] : false,
                    label: link.label,
                };
            });
        } else {
            return [];
        }
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
        return state._questions.length === state._idsMultipleOperations.length;
    }
});

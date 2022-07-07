export default ({
    _communityId: window.location.pathname.split('/')[2],
    _communityTitle: '',
    
    _questions: [],
    
    _meta: {},

    _metaInfo: {},

    _filters: {
        full_text: '',
        with_answers: 'all',
        /* published: 'all',
        draft: 'all', */
        status: 'all'
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
});

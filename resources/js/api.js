export async function loadQuestions(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/list',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function addQuestion(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/add',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function editQuestion(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/store',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function filterQuestion(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/list',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function removeQuestion(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/delete',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function doMultipleOperations(data) {
    return await axios({
        method: 'post',
        url: '/api/questions/do',
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

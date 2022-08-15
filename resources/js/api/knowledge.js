async function baseRequest(url, data) {
    return await axios({
        method: 'post',
        url: url,
        headers: {
            'Authorization': `Bearer ${ window.api_token }`,
            'Accept': 'application/json'
        },
        data
    });
}

export async function loadQuestions(data) {
    return await baseRequest('/api/questions/list', data);
}

export async function addQuestion(data) {
    return await baseRequest('/api/questions/add', data);
}

export async function editQuestion(data) {
    return await baseRequest('/api/questions/store', data);
}

export async function filterQuestion(data) {
    return await baseRequest('/api/questions/list', data);
}

export async function removeQuestion(data) {
    return await baseRequest('/api/questions/delete', data);
}

export async function doMultipleOperations(data) {
    return await baseRequest('/api/questions/do', data);
}

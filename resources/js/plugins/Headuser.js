import Page from "./Abstract/Page";

export default class Headuser extends Page {
    constructor(container) {
        super(container);
    }

    loginAsAdmin(){
        return new Promise((resolve, reject) => {
            axios({url: '/api/login-as-admin', method: 'POST' })
                .then(resp => {
                    window.location.href = '/manager/users';
                    resolve(resp);
                })
                .catch(err => {
                    console.log('Err');
                    reject(err);
                })
        })
    }
}
import Page from "./Abstract/Page";

export default class Profile extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {
        console.log('profile');

        const data = { a: 1, b: 2 }

        Emitter.emit('loadCommunityData', {
            data
        });
    }
}


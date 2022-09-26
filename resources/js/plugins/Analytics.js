import Page from "./Abstract/Page";

export default class Analytics extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        console.log('analytics');

        this.listeners()
    }
    
    listeners() {
        Emitter.subscribe('loadCommunityData', (data) => {
            console.log(data);
        });
        
    }
}

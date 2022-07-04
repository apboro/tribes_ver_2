export class DataController {
    constructor(options) {
        this.options = options;
        this.container = options.parent.container;

        this.init();
    }

    init() {
        this.initParams();
        this.watcher();        
    }

    initParams() {}

    watcher() {}
}

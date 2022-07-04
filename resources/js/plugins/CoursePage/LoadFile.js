export class LoadFile {
    constructor(options) {
        this.parent = options.parent;
        this.file = options.file;
        this.node = options.node;
        this.course_id = options.parent.course_id; 
        this.fileInput = options.fileInput;

        this.init();
    }

    init() {}
}

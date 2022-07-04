export class LoadVideo {
    constructor(options) {
        this.parent = options.parent;
        this.file = options.file;
        this.node = options.node;
        this.course_id = options.parent.course_id; 
        this.fileInput = options.fileInput;

        this.init();
    }

    init() {
        this.saveImage();
    }

    async saveImage() {
        try {
            const formData = new FormData();
            formData.append("file", this.fileInput.files[0]);
            formData.append("course_id", this.course_id);

            const res = await axios({
                method: 'post',
                url: '/api/file/upload',
                data: formData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            });

            console.log(res);

            this.addSavedImage(res.data);

            /* if (!this.isBlock) {
                console.log(res.data);
                console.log(this.parent.parent.parent.attachments);
                this.parent.parent.parent.attachments.push(res.data);
                console.log(this.parent.parent.parent.attachments);

                this.parent.data = res.data.id;
                this.parent.img.src = res.data.url;
            } */
        } catch (error) {
            console.log(error);
        }
    }

    addSavedImage(data) {
        let container = null;
        if (this.isBlock) {
            container = new CreateNode({
                parent: this.parent.filesList,
                class: 'preview',
            }).init();
        } else {
            console.log(this.parent.parent.parent.fileController);
            container = new CreateNode({
                parent: this.parent.parent.parent.fileController.filesList,
                class: 'preview',
            }).init();
        }
        
        new CreateNode({
            parent: container,
            tag: 'img',
            id: data.id,
            class: 'w-100 preview-img',
            src: data.url
        }).init();
    }
}

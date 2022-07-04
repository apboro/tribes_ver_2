import { resizeVideo } from "../../../functions";
import { CreateNode } from "../../Helper/CreateNode";
import Module from "./Module";

export default class Video extends Module{

    template() {
        if (this.data) {
            this.node = new CreateNode({
                class: 'template-video',
                dataset: {
                    containerId: this.id
                }
            }).init();
            
            this.node.append(this.getVideo()); 
        } else {
            this.node = new CreateNode({
                class: 'cont'
            }).init();

            let img = new CreateNode({
                parent: this.node,
                tag: 'img',
                class: 'w-100 template-img',
                src: '/images/no-image.svg'
            }).init();

            const input = new CreateNode({
                parent: this.node,
                tag: 'input',
                type: 'file',
                id: `${ this.course_id }_${ this.id }`,
                class: 'hide',
            }).init();

            const label = new CreateNode({
                parent: this.node,
                tag: 'label',
                for: `${ this.course_id }_${ this.id }`,
                class: '',
                text: 'LOAD'
            }).init();
        }

        this.listeners();
        return this.node;
    }

    listeners() {
        // ловим событие дроп на изображении в шаблоне
        Emitter.subscribe(`drop_${ this.course_id }_${ this.id }`, (data) => {
            console.log(data);
            this.data = Number(data.id);
        });
    }

    getVideo() {
        let src = null;
        let frame = null;
        this.parent.parent.attachments.forEach((item) => {
            if (item.id == this.data) {
                src = item.url;
                frame = item.remoteFrame;
            }
        });

        return resizeVideo(frame, src, 320, 320);
    }
}

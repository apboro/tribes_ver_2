export default class Module {

    constructor(data, moduleData, parent) {
        this.data = data;
        this.node = null;
        this.tag = null;
        this.id = moduleData.id;
        this.template_id = moduleData.template_id;
        this.parent = parent;
        this.course_id = this.parent.parent.id;
    }

    drawTo(){
        console.log(1);
    }

    setTag(tag){
        this.tag = tag;
    }
}
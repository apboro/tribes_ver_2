export class CreateNode {
    constructor(attrs) {
        this.attrs = attrs;
        
        this.tag = 'div';
        this.append = true;

        this.node = null;
    }

    init() {
        this.attrsSetup();
        this.initNode();
        this.insertNode();
        this.addAttrs();

        return this.node;
    }

    attrsSetup() {
        Object.entries(this.attrs).forEach(([attribute, value]) => {
            this[attribute] = value ?? '';
        });
    }

    initNode() {
        this.node = document.createElement(this.tag);
    }

    insertNode() {
        if (this.parent) {
            if (this.append) {
                this.parent.append(this.node);
            } else {
                this.parent.prepend(this.node);
            }
        }
    }

    addAttrs() {
        if (this.attrs) {
            Object.entries(this.attrs).forEach(([attribute, value]) => {
                if (attribute === 'class') {
                    this.node.className = value;        
                } else if (attribute === 'text') {
                    this.node.textContent = value;
                } else if (attribute === 'dataset') {
                    Object.entries(this.dataset).forEach(([key, value]) => {
                        this.node.dataset[key] = value;
                    });
                } else if (attribute === 'parent' || attribute === 'tag') {
                    
                } else {
                    this.node.setAttribute(attribute, value);
                }
            });
        }
    }
}

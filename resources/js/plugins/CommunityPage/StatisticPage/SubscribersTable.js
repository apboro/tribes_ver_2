import { CreateNode } from "../../Helper/CreateNode";

export class SubscribersTable {
    constructor(options) {
        this.container = options.parent;
        this.data = options.data;
        this.headerItems = options.headerItems;

        this.sortType = 'off'; // asc, desc
        this.sortValue = '';

        this.init();
    }

    init() {
        this.createTableHeader();
    }

    createTableHeader() {
        const header = new CreateNode({
            parent: this.container,
            class: 'table__header',
        }).init();

        this.headerItems.forEach((headerItem) => {
            const itemWrapper = new CreateNode({
                parent: header,
                class: 'table__header-item table__header-item--sortable'
            }).init();

            new CreateNode({
                parent: itemWrapper,
                tag: 'span',
                text: headerItem.text
            }).init();
            
            const sortBtn = new CreateNode({
                parent: itemWrapper,
                tag: 'button',
                class: 'table__sort-btn',
            }).init();
            sortBtn.innerHTML = `
                <i class="icon button-text__icon">
                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"></path></svg>
                </i>
            `;
            headerItem.node = sortBtn;

            sortBtn.onclick = () => this.sort(sortBtn, headerItem.sortValue);
        });
    }

    sort(node, value) {
        if (this.sortValue != value) {
            this.resetFilterState();
        }
        
        this.sortValue = value;

        switch (this.sortType) {
            case 'off':
                this.sortType = 'asc';
                node.textContent = 'asc';
                node.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line class="icon__stroke" x1="3" y1="4" x2="5" y2="4" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <line class="icon__stroke" x1="3" y1="8" x2="9" y2="8" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <line class="icon__stroke" x1="3" y1="12" x2="13" y2="12" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                `;
                break;
            case 'asc':
                this.sortType = 'desc';
                node.textContent = 'desc';
                node.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line class="icon__stroke" x1="1" y1="-1" x2="3" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 12)" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <line class="icon__stroke" x1="1" y1="-1" x2="7" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 8)" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        <line class="icon__stroke" x1="1" y1="-1" x2="11" y2="-1" transform="matrix(1 8.74228e-08 8.74228e-08 -1 2 4)" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                `;
                break;
            case 'desc':
                this.sortType = 'off';
                node.textContent = 'off';
                node.innerHTML = `
                    <i class="icon button-text__icon">
                        <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"></path></svg>
                    </i>
                `;
                break;
        }

        console.log(this.sortType, this.sortValue);
    }

    resetFilterState() {
        this.headerItems.map((headerItem) => {
            headerItem.node.innerHTML = `
                <i class="icon button-text__icon">
                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"></path></svg>
                </i>
            `;
        });
        this.sortType = 'off';
    }
}

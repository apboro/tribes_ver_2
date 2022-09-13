import { timeFormatting } from "../../core/functions";
import { CreateNode } from "./CreateNode";

export class Table {
    constructor(options) {
        this.container = options.parent;
        this.data = options.data;
        this.headerItems = options.headerItems;
        this.rowItemsFormat = options.rowItemsFormat;

        this.sortName = options.sortName;
        this.sortRule = options.sortRule;

        this.sortEvent = options.sortEvent;

        this.init();
    }

    init() {
        this.createTableHeader();
        this.createTableBody();
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
            
            sortBtn.innerHTML = this.getSortIcon(headerItem.sortName);
            headerItem.node = sortBtn;

            sortBtn.onclick = () => this.sort(sortBtn, headerItem.sortName);
        });
    }

    createTableBody() {
        const body = new CreateNode({
            parent: this.container,
            class: 'table__body',
        }).init();

        if (this.data.length || this.data) {
            this.data.forEach((dataItem) => {
                const tableRowWrapper = new CreateNode({
                    parent: body,
                    class: 'table__row-wrapper',
                }).init();
    
                const rowWrapper = new CreateNode({
                    parent: tableRowWrapper,
                    class: 'table__row',
                }).init();
    
                this.createTableRowItems(rowWrapper, dataItem);
            });
        } else {
            const wrapper = new CreateNode({
                parent: body,
                class: 'table__row table__row--special'
            }).init();

            new CreateNode({
                parent: wrapper,
                tag: 'primary',
                text: 'Таблица пуста'
            }).init();
        }

    }

    createTableRowItems(parent, data) {
        this.rowItemsFormat.forEach((itemFormat) => {
            const rowItemWrapper = new CreateNode({ parent }).init();
            
            if (itemFormat.type === 'text') {
                new CreateNode({
                    parent: rowItemWrapper,
                    class: 'table__item table__item--changable',
                    text: data[itemFormat.key]
                }).init();
            } else if (itemFormat.type === 'date') {
                new CreateNode({
                    parent: rowItemWrapper,
                    class: 'table__item table__item--changable',
                    text: timeFormatting({
                        date: data[itemFormat.key],
                        year: 'numeric',
                        month: 'numeric',
                        day: 'numeric',
                    })
                }).init();
            }
        })
    }

    sort(node, value) {
        if (this.sortName != value) {
            this.resetFilterState();
        }

        this.sortName = value;

        switch (this.sortRule) {
            case 'off':
                this.sortRule = 'asc';
                node.textContent = 'asc';
                node.innerHTML = `
                    <i class="icon button-text__icon">
                        <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff" class="icon__fill"></path>
                            <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff50" class="icon__fill"</path>
                        </svg>
                    </i>
                `;
                break;
            case 'asc':
                this.sortRule = 'desc';
                node.textContent = 'desc';
                node.innerHTML = `
                    <i class="icon button-text__icon">
                        <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff50" class="icon__fill"></path>
                            <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"</path>
                        </svg>
                    </i>
                `;
                break;
            case 'desc':
                this.sortRule = 'off';
                node.textContent = 'off';
                node.innerHTML = `
                    <i class="icon button-text__icon">
                        <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff" class="icon__fill"></path>
                            <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"</path>
                        </svg>
                    </i>
                `;
                break;
        }
        // async req
        Emitter.emit(this.sortEvent, { name: this.sortName, rule: this.sortRule });
    }

    resetFilterState() {
        this.headerItems.map((headerItem) => {
            headerItem.node.innerHTML = `
                <i class="icon button-text__icon">
                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="white" class="icon__fill"></path> <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"></path></svg>
                </i>
            `;
        });
        this.sortRule = 'off';
    }

    update(data) {
        this.data = data;
        console.log(this.data);
        this.container.innerHTML = '';
        this.createTableHeader();
        this.createTableBody();
    }

    getSortIcon(sortName) {
        let icon;
        if (sortName === this.sortName) {

            switch (this.sortRule) {
                case 'off':
                    icon = `
                        <i class="icon button-text__icon">
                            <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff" class="icon__fill"></path>
                                <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"</path>
                            </svg>
                        </i>
                    `;
                    break;
                case 'asc':
                    icon = `
                    <i class="icon button-text__icon">
                        <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff" class="icon__fill"></path>
                            <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff50" class="icon__fill"</path>
                        </svg>
                    </i>
                    `;
                    break;
                case 'desc':
                    icon = `
                        <i class="icon button-text__icon">
                            <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff50" class="icon__fill"></path>
                                <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"</path>
                            </svg>
                        </i>
                    `;
                    break;
            }
        } else {
            icon = `
                <i class="icon button-text__icon">
                    <svg width="16" height="32" viewBox="0 0 16 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.4666 21.5334C12.3333 21.4001 12.1999 21.3334 11.9999 21.3334C11.7999 21.3334 11.6666 21.4001 11.5333 21.5334L7.99992 25.0667L4.46659 21.5334C4.19992 21.2667 3.79992 21.2667 3.53325 21.5334C3.26659 21.8001 3.26659 22.2001 3.53325 22.4667L7.53325 26.4667C7.79992 26.7334 8.19992 26.7334 8.46658 26.4667L12.4666 22.4667C12.7333 22.2001 12.7333 21.8001 12.4666 21.5334Z" fill="#ffffff" class="icon__fill"></path>
                        <path d="M12.4666 10.4666C12.3333 10.5999 12.1999 10.6666 11.9999 10.6666C11.7999 10.6666 11.6666 10.5999 11.5333 10.4666L7.99992 6.93325L4.46659 10.4666C4.19992 10.7333 3.79992 10.7333 3.53325 10.4666C3.26659 10.1999 3.26659 9.79992 3.53325 9.53325L7.53325 5.53325C7.79992 5.26659 8.19992 5.26659 8.46658 5.53325L12.4666 9.53325C12.7333 9.79992 12.7333 10.1999 12.4666 10.4666Z" fill="#ffffff" class="icon__fill"</path>
                    </svg>
                </i>
            `;
        }

        return icon;
    }
}

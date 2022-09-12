import { CreateNode } from "./CreateNode";

export class Pagination {
    constructor(options) {
        this.container = options.parent;
        this.data = options.data;
        this.event = options.event;

        this.init();
    }

    init() {
        const wrapper = new CreateNode({
            parent: this.container,
            class: 'pagination__item'
        }).init();

        this.createArrowItem(wrapper, 'prev');
        this.createItems(wrapper);
        this.createArrowItem(wrapper, 'next');
    }

    createArrowItem(container, type) {
        const arrowItem = new CreateNode({
            parent: container,
            class: 'pagination__control'
        }).init();

        const arrowBtn = new CreateNode({
            parent: arrowItem,
            tag: 'button',
            class: `button-text button-text--primary button-text--only-icon ${ type == 'prev' ? this.isDisabledPrevBtn() : this.isDisabledNextBtn() }`
        }).init();

        let pageValue;

        switch (type) {
            case 'prev':
                pageValue = this.activePage - 1;
                arrowBtn.innerHTML = `
                    <i class="icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.4668 3.53317C10.6002 3.6665 10.6668 3.79984 10.6668 3.99984C10.6668 4.19984 10.6002 4.33317 10.4668 4.4665L6.9335 7.99984L10.4668 11.5332C10.7335 11.7998 10.7335 12.1998 10.4668 12.4665C10.2002 12.7332 9.80016 12.7332 9.5335 12.4665L5.5335 8.4665C5.26683 8.19984 5.26683 7.79984 5.5335 7.53317L9.5335 3.53317C9.80016 3.2665 10.2002 3.2665 10.4668 3.53317Z" fill="#4C4957" class="icon__fill"></path></svg>
                    </i>
                `;
                break;
            case 'next':
                pageValue = this.activePage + 1;
                arrowBtn.innerHTML = `
                    <i class="icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.53317 3.53317C5.39984 3.6665 5.33317 3.79984 5.33317 3.99984C5.33317 4.19984 5.39984 4.33317 5.53317 4.4665L9.0665 7.99984L5.53317 11.5332C5.2665 11.7998 5.2665 12.1998 5.53317 12.4665C5.79984 12.7332 6.19984 12.7332 6.4665 12.4665L10.4665 8.4665C10.7332 8.19984 10.7332 7.79984 10.4665 7.53317L6.4665 3.53317C6.19984 3.2665 5.79984 3.2665 5.53317 3.53317Z" fill="#4C4957" class="icon__fill"></path></svg>
                    </i>
                `;
                break;
        }

        arrowBtn.onclick = () => this.onPageClick(pageValue);
    }

    createItems(container) {
        for (let page = 1; page <= this.pageCount; page += 1) {
            const item = new CreateNode({
                parent: container,
                class: `pagination__control ${ this.isActive(page) }`
            }).init();
    
            const itemBtn = new CreateNode({
                parent: item,
                tag: 'button',
                class: 'pagination__page',
                text: page
            }).init();

            itemBtn.onclick = () => this.onPageClick(page);
        }
    }
    
    update(data) {
        this.data = data;
        this.container.innerHTML = '';
        this.init();
    }

    onPageClick(pageNumber) {
        Emitter.emit(this.event, { pageNumber });
    }

    isActive(pageNumber) {
        return pageNumber == this.data.current_page ? 'active' : '';
    }

    isDisabledPrevBtn() {
        return this.activePage <= 1 ? 'button-text--hide' : '';
    }

    isDisabledNextBtn() {
        return this.data.current_page == this.pageCount ? 'button-text--hide' : '';
    }

    get activePage() {
        return this.data.current_page;
    }

    get pageCount() {
        return Math.ceil(this.data.total / Number(this.data.per_page));
    }
}

export class TariffPage {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="tariffPage"]');

        this.tabsSwitcher = this.container.querySelector('#tariff-list-state');
        
        this.init();
    }

    init() {
        this.tabsSwitcher.addEventListener('change', (event) => {
            console.log(event.target.value);
            window.location.href = event.target.value;
        });
    }

    checkTab(link) {
        console.log(link);
    }
}

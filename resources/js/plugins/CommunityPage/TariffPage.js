export class TariffPage {
    constructor(parent) {
        this.parent = parent;
        this.container = parent.container.querySelector('[data-tab="tariffPage"]');

        this.tabsSwitcher = this.container.querySelector('#tariff-list-state');
        
        this.init();
    }

    init() {
        if (this.tabsSwitcher) {
            this.tabsSwitcher.addEventListener('change', (event) => {
                window.location.href = event.target.value;
            });
        }
    }

    checkTab(link) {
        console.log(link);
    }
}

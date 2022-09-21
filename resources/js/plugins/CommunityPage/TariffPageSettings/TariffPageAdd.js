export class TariffPageAdd {
    constructor(options) {
        this.container = options.container.querySelector('[data-tab="tariffPageAdd"]');
        
        this.init();
    }

    init() {
        
    }

    getChanges(value){
        let option = document.getElementById('tariff_pay_period');
        option.value = value;
    }

    addRandomValue(item) {
        let tariff_pay_period = document.getElementById('tariff_pay_period');
        if(item.value == 'set') {
            document.getElementById('community-settings__change-tariff').classList.add('active');
            tariff_pay_period.value = document.getElementById('quantity_of_days').value;
            document.getElementById('arbitrary_term').value = true;
        } else {
            document.getElementById('community-settings__change-tariff').classList.remove('active');
            tariff_pay_period.value = item.value;
            document.getElementById('arbitrary_term').value = false;
        }
    }

    setActive(event){
        console.log(event.target.value);
        let elem = document.getElementById('tariff_active');
        let active = document.getElementById('disabled_checkbox');
        elem.value = true;
        document.getElementById('isPersonal').value = true;
        active.classList.toggle('active');

    }
}

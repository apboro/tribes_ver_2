import Page from "./Abstract/Page";

export default class TariffYourValue extends Page {
    constructor(container) {
        super(container);
    }

    changeYourValue(obj){

        let active = document.getElementById('tariff_edit_form');
        if(obj.value == 'set') {
            active.classList.add("active")
        } else {
            active.classList.remove("active");
        }
    }

    getChanges(value){
        let option = document.getElementById('tariff_pay_period');
        // option.textContent = value;
        option.value = value;

        // let selectCustom = document.getElementById('tariff_pay_period');
        // console.log('selectCustom', selectCustom)
        // if (selectCustom.value = 'set') {
        //     console.log(value);
        // }
    }

    changeYourValueAdd(obj){

        let active = document.getElementById('tariff_add_form');
        if(obj.value == 'set') {
            active.classList.add("active")
        } else {
            active.classList.remove("active");
        }
    }

    getChangesAdd(value){
        let option = document.getElementById('yourValueAdd');
        // option.textContent = value;
        option.value = value;
    }

    test(item) {
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
}
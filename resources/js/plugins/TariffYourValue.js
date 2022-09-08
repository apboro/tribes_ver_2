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

        let option = document.getElementById('yourValue');
        option.textContent = value;
        option.value = value;

        // let selectCustom = document.getElementById('tariff_pay_period');
        // console.log('selectCustom', selectCustom)
        // if (selectCustom.value = 'set') {
        //     console.log(value);
        // }
    }
}
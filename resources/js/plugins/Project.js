import { forEach } from "lodash";
import Page from "./Abstract/Page";

export default class Project extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {}

    setCheck(event){
        let checkboxesChecked = [];
        let checkboxes = document.querySelectorAll('.profile__item-wrap')
        console.log(checkboxes);
        checkboxes.forEach((el) => {
            let child = el.querySelector('input[type="checkbox"]:checked');
            if(child) {
                checkboxesChecked.push(child.id)
            }
            if (child !== null) {
                child.parentNode.remove();
            }
        })
        return checkboxesChecked

        // let selectedCheckBoxes = document.querySelectorAll('input[type="checkbox"]:checked');
        // console.log(selectedCheckBoxes)

        // let checkedValues = Array.from(selectedCheckBoxes).map((check) => check.value);
        // checkedValues.forEach(item => {
        //     console.log(item)
        // })

        // console.log(checkedValues);
        // selectedCheckBoxes.remove();

        // return checkedValues;
        
    }
}


import { forEach } from "lodash";
import Page from "./Abstract/Page";

export default class Project extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {}

    moveSelectedCommunities(event){
        let checkboxesChecked = [];
        let checkboxes = document.querySelectorAll('.profile__item-wrap')
        checkboxes.forEach((el) => {
            let child = el.querySelector('input[type="checkbox"]:checked');
            let insertChecked = document.querySelector('.profile__list');
            console.log(insertChecked);
            if(child) {
                checkboxesChecked.push(child.id)
            }
            if (child !== null) {
                insertChecked.insertBefore(child, insertChecked)
                console.log(child);
                // child.parentNode.remove();
            }
            console.log('CHILD', child)
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


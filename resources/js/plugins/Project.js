import { forEach } from "lodash";
import Page from "./Abstract/Page";

export default class Project extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {

        
    }

    moveSelectedCommunities(){
        let checkboxes = document.querySelectorAll('.project-creation__communities-list-another .profile__list .profile__item-wrap')
        let insertChecked = document.getElementById('profile_list');
        Array.from(checkboxes).forEach(function(el) {
            let child = el.querySelector('input[type="checkbox"]:checked');
            if (child !== null) {
                child.checked = false;
                insertChecked.append(el)
                el.onclick = () => console.log(1);
            } 
        })

        let qty = document.getElementById('qty_another_projects')
        qty.textContent = `0`

        let totalQty = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length
        let qtyCommunitiesInProject = document.getElementById('qty_of_communities_in_project')
        console.log(totalQty)
        qtyCommunitiesInProject.textContent = `${totalQty}`

        let emptyBlock = document.querySelector('.project-creation__project-main--empty')
        if(totalQty > 1) {
            emptyBlock.remove()
        }
    }

    deleteSelectedCommunitiesFromProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap')
        let insertChecked = document.getElementById('profile_list_another');
        Array.from(checkboxes).forEach(function(el) {
            let child = el.querySelector('input[type="checkbox"]:checked');
            if (child !== null) {
                child.checked = false;
                insertChecked.append(el)
            }
        })

        let qty = document.getElementById('qty_checked_communities_in_project')
        qty.textContent = `0`

        let removeCheckedAll = document.getElementsByClassName('chk-all')
        removeCheckedAll[0].checked = false;

        // let qty_all = document.getElementById('qty_of_communities_in_project')
        // let checkboxesChecked = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        // qty_all.textContent = `${checkboxesChecked}`

        let totalQty = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length
        let qtyCommunitiesInProject = document.getElementById('qty_of_communities_in_project')
        console.log(totalQty)
        qtyCommunitiesInProject.textContent = `${totalQty}`


        let emptyBlock = document.querySelector('.project-creation__project-main--empty')
        if(totalQty > 0) {
            emptyBlock.remove()
        } else {
            let wrap = document.getElementsByClassName('project-creation__project-main')
            let p = document.createElement('p')
            p.className = '.project-creation__project-main--empty'
            p.innerHTML('Здесь находится список сообществ проекта, выберите сообщества из общего списка (слева) и добавьте их в свой проект.')
            // emptyBlock.insertBefore('.project-creation__list-communities')
            document.wrap.append(p);
        }
    }

    toggleAll(source){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input');
        for(let i=0, n=checkboxes.length; i<n; i++) {
            checkboxes[i].checked = source.checked;
        }
        let checkedEl = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_checked_communities_in_project');
        qty.textContent = `${checkedEl}`
    }

    qtyOfCheckedCommunities(){
        let checkboxes = document.querySelectorAll('.project-creation__communities-list-another .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_another_projects');
        if(checkboxes == 0) {
            qty.textContent = 0
        } else {
            qty.textContent = `${checkboxes}`
        }

        // checkboxes.forEach((el) => {
        //     let child = el.querySelector('input[type="checkbox"]:checked');
        //     // if(child) {
        //     //     checkboxesCheckedId.push(child.id)
        //     //     checkedElements.push(child)
        //     // }
        //     if (child !== null) {
        //         child.checked = false;
        //         insertChecked.append(el)
        //     }

        // })
    }

    qtyOfCheckedCommunitiesInProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_checked_communities_in_project');
        if(checkboxes == 0) {
            qty.textContent = 0
        } else {
            qty.textContent = `${checkboxes}`
        }
    }

    qtyAllCommunitiesInProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length;
        let qty = document.getElementById('qty_of_communities_in_project');
        console.log(qty);
            qty.textContent = `${checkboxes}`
        // let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length;
        // // let length = checkboxes.length
        // let qty = document.getElementById('qty_of_communities_in_project');
        // if(checkboxes == 0) {
        //     qty.textContent = 0
        // } else {
        //     qty.textContent = `${checkboxes}`
        // }
    }
}


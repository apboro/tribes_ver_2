import { forEach } from "lodash";
import Page from "./Abstract/Page";

export default class Project extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {

        
    }

    //перенести элемент из 'другие сообщества' в сообщества проекта; 
    moveSelectedCommunities(){
        let checkboxes = document.querySelectorAll('.project-creation__communities-list-another .profile__list .profile__item-wrap');
        let insertChecked = document.getElementById('profile_list');
        Array.from(checkboxes).forEach(function(el) {
            let child = el.querySelector('input[type="checkbox"]:checked');
            if (child !== null) {
                child.checked = false;
                insertChecked.append(el)
            } 
        })
        let qty = document.getElementById('qty_another_projects');
        qty.textContent = `0`;
        let totalQty = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length;

        //удаления дефолтного блока с текстом, если в сообществах проекта есть элементы
        // let emptyBlock = document.querySelector('.project-creation__project-main--empty');
        // if(emptyBlock !== null && totalQty > 0) {
        //     emptyBlock.remove();
        // }
        if (totalQty == 0) {
            document.querySelector('#empty_text').classList.remove('hide');
        } else {
            document.querySelector('#empty_text').classList.add('hide');
        }

        //изменение количества всех сообществ (checked && !checked)
        let qtyCommunitiesInProject = document.getElementById('qty_of_communities_in_project')
        qtyCommunitiesInProject.textContent = totalQty;
        console.log(totalQty);
    }

    deleteSelectedCommunitiesFromProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap');
        let insertChecked = document.getElementById('profile_list_another');
        Array.from(checkboxes).forEach(function(el) {
            let child = el.querySelector('input[type="checkbox"]:checked');
            if (child !== null) {
                child.checked = false;
                insertChecked.append(el);
            }
        })

        let qty = document.getElementById('qty_checked_communities_in_project');
        qty.textContent = `0`;

        let removeCheckedAll = document.getElementsByClassName('chk-all');
        removeCheckedAll[0].checked = false;

        let totalQty = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length;
        let qtyCommunitiesInProject = document.getElementById('qty_of_communities_in_project');
        qtyCommunitiesInProject.textContent = totalQty;
        if (totalQty == 0) {
            document.querySelector('#empty_text').classList.remove('hide');
        } else {
            document.querySelector('#empty_text').classList.add('hide');
        }

        // let emptyBlock = document.querySelector('.project-creation__project-main--empty');
        // if ( emptyBlock != null && totalQty == 0) {
        //     return
        // } else if(totalQty == 0){
        //     let wrap = document.querySelector('.project-creation__list-communities');
        //     wrap.insertAdjacentHTML("beforeBegin" , "<p class='project-creation__project-main--empty'>Здесь находится список сообществ проекта, выберите сообщества из общего списка (слева) и добавьте их в свой проект.</p>");
        // }
    }

    toggleAll(source){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input');
        for(let i=0, n=checkboxes.length; i<n; i++) {
            checkboxes[i].checked = source.checked;
        }
        let checkedEl = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_checked_communities_in_project');
        qty.textContent = `${checkedEl}`;
    }

    qtyOfCheckedCommunities(){
        let checkboxes = document.querySelectorAll('.project-creation__communities-list-another .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_another_projects');
        if(checkboxes == 0) {
            qty.textContent = 0;
        } else {
            qty.textContent = `${checkboxes}`;
        }
    }

    qtyOfCheckedCommunitiesInProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap input[type="checkbox"]:checked').length;
        let qty = document.getElementById('qty_checked_communities_in_project');
        if(checkboxes == 0) {
            qty.textContent = 0;
        } else {
            qty.textContent = `${checkboxes}`;
        }
    }

    qtyAllCommunitiesInProject(){
        let checkboxes = document.querySelectorAll('.project-creation__list-communities .profile__list .profile__item-wrap').length;
        let qty = document.getElementById('qty_of_communities_in_project');
        qty.textContent = `${checkboxes}`;
    }
}


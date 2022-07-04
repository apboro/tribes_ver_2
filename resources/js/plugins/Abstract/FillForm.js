export class FillForm {
    constructor() {
        this.forms = document.querySelectorAll('form[id]')
        this.forms = Object.values(this.forms).filter((form) => form.id != 'tariff_edit_form');
        
        this.formElements = document.querySelectorAll('select[name], textarea[name], input[type="text"], input[type="number"], input[type="time"]');
        this.payEditor = document.querySelector('[data-editor]');

        this.init();
    }

    init() {
        if (this.formElements.length > 0) {
            this.upload();
            this.listener();
        }
    }

    listener() {
        this.formElementsListener();
        this.payEditorListener();
        this.formListener();
    }

    // слушаем элементы форм
    formElementsListener() {
        this.formElements.forEach((formElement) => {
            // для элемента на котором производится ввод
            formElement.addEventListener('input', () => {
                this.saveFormElementsValue(formElement);
            });
        });
    }

    // слушаем редактор оплаты
    payEditorListener() {
        Emitter.subscribe('ContentEditor:text', (data) => {
            sessionStorage.setItem(`content_editor_pay_${ data.formId }`, data.html);
        });
    }

    // слушаем формы
    formListener() {
        this.forms.forEach((form) => {
            form.addEventListener('submit', () => {
                this.cleanFormDataToStorage(form);
            });
        });
    }

    // очищаем данные из сторадж касающиеся сохраняемых форм
    cleanFormDataToStorage(form) {
        Object.values(form.elements).forEach((formElement) => {
            const storageName = this.createStorageName(formElement);
            sessionStorage.removeItem(storageName);
        });
    }

    saveFormElementsValue(formElement) {
        // формируем имя для записи
        const storageName = this.createStorageName(formElement);

        // если по этому имени нельзя записать - не записываем
        if (!storageName) {
            return false;
        }

        // записываем в это имя значение элемента
        if (formElement.type === 'checkbox') {
            // для чекбоксов
            sessionStorage.setItem(storageName, formElement.checked);    
        } else {
            // для остальных
            sessionStorage.setItem(storageName, formElement.value);
        }
    }

    upload() {
        this.uploadFormElements();
        this.uploadPayEditor();        
    }

    uploadFormElements() {
        // проверяем все элементы
        this.formElements.forEach((formElement) => {
            // формируем имя для обращения к хранилищу от элемента
            const storageName = this.createStorageName(formElement);

            // если в хранилище для такого имени было записано значение
            if (sessionStorage.getItem(storageName)) {
                // задаем элементу значение из хранилища
                if (formElement.type == 'checkbox') {
                    // для чекбоксов
                    if (sessionStorage.getItem(storageName) == 'false') {
                        formElement.checked = false;

                        // для чекбоксов с изменением видимости
                        if (formElement.dataset.donateCheckId) {
                            const parentContainer = formElement.closest('[data-donate-item-id]');
                            parentContainer.classList.add('inactive-form-items');
                        }
                    } else if (sessionStorage.getItem(storageName) == 'true') {
                        formElement.checked = true;

                        // для чекбоксов с изменением видимости
                        if (formElement.dataset.donateCheckId) {
                            const parentContainer = formElement.closest('[data-donate-item-id]');
                            parentContainer.classList.remove('inactive-form-items');
                        }
                    }
                } else {
                    if (sessionStorage.getItem(storageName) !== formElement.value) {
                        formElement.parentNode.classList.add('save-form-warn');
                    }
                    // для остальных
                    formElement.value = sessionStorage.getItem(storageName);
                }
            }    
        });
    }

    uploadPayEditor() {
        if (this.payEditor && sessionStorage.getItem('content_editor_pay')) {
            let str = ``;
            for (let el of this.payEditor.children) {
                str += el.outerHTML;
            }

            if (sessionStorage.getItem('content_editor_pay') != str) {
                this.payEditor.parentNode.classList.add('save-form-warn')
            }
            
            this.payEditor.innerHTML = sessionStorage.getItem('content_editor_pay');
        }
    }

    createStorageName(element) {
        if (element.getAttribute('id') === null) {
            return false;
        }
        
        const formId = element.closest('form').getAttribute('id');
        
        if (formId === null || formId === 'register' || formId === 'login' || formId === 'password_change' || formId === 'tariff_edit_form') {
            return false;
        }

        const tagName = element.tagName;
        
        const name = element.name;
        if (name === 'send_to_community') {
            return false;
        }
        
        return `${ formId }_${ tagName }_${ name }`;
    }
}

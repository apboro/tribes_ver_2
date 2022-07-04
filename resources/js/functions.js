import { Tooltip } from "bootstrap";
import IMask from 'imask';

window.getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
window.createElementFromHTML = (htmlString) => {
    let div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}
window.removeValidationFeedbacks = () => {
    let fgs = document.querySelectorAll('.form-group');
    fgs.forEach((fg)=>{
        fg.classList.remove('is-invalid');
        let feedback = fg.querySelector('.invalid-feedback');
        if(feedback){
            feedback.remove();
        }
    });
}

window.inputDynamicWidth = (elem) => {
    let span = document.createElement('span');
    copyNodeStyle(elem, span);
    span.style.position = "absolute";
    span.style.width = "fit-content";
    span.innerText = elem.value;

    document.body.appendChild(span);

    let width = span.offsetWidth;
    span.remove();

    elem.style.width = width + "px";
}

window.copyNodeStyle = (sourceNode, targetNode) => {
    var computedStyle = window.getComputedStyle(sourceNode);
    Array.from(computedStyle).forEach(function (key) {
        return targetNode.style.setProperty(key, computedStyle.getPropertyValue(key), computedStyle.getPropertyPriority(key));
    });
}

export function createElementFromHTML(htmlString) {
    let div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}

export function lockBody() {
    document.body.classList.add('lock');
}

export function unlockBody() {
    document.body.classList.remove('lock');
}

window.parseQuery = (queryString) => {
    let query = {};
    let pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
    for (let i = 0; i < pairs.length; i++) {
        let pair = pairs[i].split('=');
        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
    }
    return query;
}

export function createServerErrorMessage(error) {
    if (error.response) {
        const templateAnswers = {
            later: Dict.write('service_message', 'please_later'),
            feedback: Dict.write('service_message', 'please_feedback'),
            auth: Dict.write('service_message', 'please_auth'),
        };

        // клиент получил ответ об ошибке (5xx, 4xx)
        switch (error.response.status) {
            case 400: return `${ Dict.write('service_message', '400') } ${ templateAnswers.feedback }`;
            case 401: return `${ Dict.write('service_message', '401') } ${ templateAnswers.auth }`;
            case 403: return `${ Dict.write('service_message', '403') }`;
            case 404: return `${ Dict.write('service_message', '404') } ${ templateAnswers.later }`;
            case 405: return `${ Dict.write('service_message', '405') } ${ templateAnswers.later }`;
            case 408: return `${ Dict.write('service_message', '408') } ${ templateAnswers.later }`;
            case 409: return `${ Dict.write('service_message', '409') } ${ templateAnswers.feedback }`;
            case 410: return `${ Dict.write('service_message', '410') } ${ templateAnswers.feedback }`;
            case 411: return `${ Dict.write('service_message', '411') } ${ templateAnswers.feedback }`;
            case 412: return `${ Dict.write('service_message', '412') } ${ templateAnswers.feedback }`;
            case 413: return `${ Dict.write('service_message', '413') } ${ templateAnswers.feedback }`;
            case 414: return `${ Dict.write('service_message', '414') } ${ templateAnswers.feedback }`;
            case 415: return `${ Dict.write('service_message', '415') } ${ templateAnswers.feedback }`;
            case 416: return `${ Dict.write('service_message', '416') } ${ templateAnswers.feedback }`;
            case 417: return `${ Dict.write('service_message', '417') } ${ templateAnswers.feedback }`;
            case 500: return `${ Dict.write('service_message', '500') } ${ templateAnswers.later }`;
            case 501: return `${ Dict.write('service_message', '501') } ${ templateAnswers.feedback }`;
            case 502: return `${ Dict.write('service_message', '502') } ${ templateAnswers.later }`;
            case 503: return `${ Dict.write('service_message', '503') } ${ templateAnswers.later }`;
            case 504: return `${ Dict.write('service_message', '504') } ${ templateAnswers.later }`;
        }
    } else if (error.request) {
        console.log(error); 
        // клиент так и не получил ответа или запрос так и не ушёл 
    } else { 
        // anything else 
    }
}

export function debounce(func, wait, immediate) {
    let timeout;
  
    // Эта функция выполняется, когда событие DOM вызвано.
    return function executedFunction() {
      // Сохраняем контекст this и любые параметры,
      // переданные в executedFunction.
      const context = this;
      const args = arguments;
  
      // Функция, вызываемая по истечению времени debounce.
      const later = function() {
        // Нулевой timeout, чтобы указать, что debounce закончилась.
        timeout = null;
  
        // Вызываем функцию, если immediate !== true,
        // то есть, мы вызываем функцию в конце, после wait времени.
        if (!immediate) func.apply(context, args);
      };
  
      // Определяем, следует ли нам вызывать функцию в начале.
      const callNow = immediate && !timeout;
  
      // clearTimeout сбрасывает ожидание при каждом выполнении функции.
      // Это шаг, который предотвращает выполнение функции.
      clearTimeout(timeout);
  
      // Перезапускаем период ожидания debounce.
      // setTimeout возвращает истинное значение / truthy value
      // (оно отличается в web и node)
      timeout = setTimeout(later, wait);
  
      // Вызываем функцию в начале, если immediate === true
      if (callNow) func.apply(context, args);
    };
}

export function tooltipsInit() {
    [...document.querySelectorAll('[data-bs-toggle="tooltip"]')]
        .map((tooltipTriggerEl) => new Tooltip(tooltipTriggerEl));
}

export function initPhoneMask(parent) {
    const phoneMask = parent.querySelector('#phone');
    if (phoneMask) {
        IMask(phoneMask, {
            mask: '(000)000-00-00',
            //lazy: false,
        });
    }
}

export function toLimitInput(event, maxValue) {
    if (event.target.value.length > maxValue) {
        event.target.value = event.target.value.slice(0, maxValue); 
    }
}

export function copyText(value) {
    const el = document.createElement('textarea');
    el.value = value;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.append(el);

    if (navigator.userAgent.match(/Mac|iPhone|iPod|iPad/i)) {
        navigator.clipboard
            .writeText(value)
            .then(() => {
                console.log('success coppy');
            })
            .catch(() => {
                console.log('error coppy');
            });
    } else {
        el.select();
    }
    document.execCommand('copy');
    document.body.removeChild(el);

    new Toasts({
        type: 'success',
        message: Dict.write('service_message', 'success_copy')
    });
}

export function dateFormatting(options) {
    const date = options.date;
    const language = document.documentElement.getAttribute('lang');
    
    let countryFormat;
    
    switch (language) {
        case 'en':
            countryFormat = 'en-US';
            break;
        default:
            countryFormat = 'ru';
    }
    
    let formatter = new Intl.DateTimeFormat(countryFormat, {
        weekday: options.weekday ?? undefined,
        year: options.year ?? undefined,
        month: options.month ?? undefined,
        day: options.day ?? undefined,
    });

    return formatter.format(date);
}

export function numberFormatting(options) {
    const value = options.value;
    const currency = options.currency ? 'currency' : 'decimal';
    
    let formatter = new Intl.NumberFormat("ru", {
        style: currency,
        currency: "RUB",
        minimumFractionDigits: 0
    });

    return formatter.format(value);
}

export function toFormatPhone(code, phone) {
    let phoneNumber = phone.match(/\d/g).join('');
    if (phoneNumber.length !== 10) {
        return 'Не валидный номер телефона';    
    }
    let formattedPhone = phoneNumber.replace(/^(\d{3})(\d{3})(\d{2})(\d{2})$/, '($1)$2-$3-$4');
    return `+${ code }${ formattedPhone }`
}

export function resizeVideo(frame, src, width, height) {
    let newSrc = new URL(`https://${ src }`);
    newSrc.searchParams.set('width', width)
    newSrc.searchParams.set('height', height)
    
    let newFrame = new DOMParser().parseFromString(frame, 'text/html').querySelector('iframe');
    newFrame.setAttribute('src', newSrc)
    newFrame.setAttribute('height', width);
    newFrame.setAttribute('width', height);

    return newFrame;
}


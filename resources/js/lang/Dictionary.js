import { LANGUAGE_EN } from "./en/language";
import { LANGUAGE_RU } from "./ru/language";

export class Dictionary {
    constructor() {
        this.language = null;
        this.usedDictionary = null;

        this.init();
    }

    init() {
        this.findUsedLanguage();
    }

    findUsedLanguage() {
        this.language = document.documentElement.getAttribute('lang');
        
        switch (this.language) {
            case 'en':
                this.english();
                break;
            default:
                this.russian();
        }
    }

    russian() {
        this.setDictionary = LANGUAGE_RU;   
    }

    english() {
        this.setDictionary = LANGUAGE_EN;   
    }

    write(category, value) {
        return this.getDictionary[category][value];
    }

    set setDictionary(language) {
        this.usedDictionary = language;
    }

    get getDictionary() {
        return this.usedDictionary;
    }
}

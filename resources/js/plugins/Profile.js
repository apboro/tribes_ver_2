import Page from "./Abstract/Page";
import { ProfileBlock } from "./CommunityPage/ProfileBlock";

export default class Profile extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {
        this.loadData = [
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/1.png', channel: 'Tech in UK', default: 'Чат'},
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/2.png', channel: 'Канал Димы Коваля', default: 'Канал'},
            {icon: '/images/icons/social/discord.png', url: '/images/avatars/3.png',channel: 'Das Herz', default: 'Чат'},
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/4.png', channel: 'Чат Коваля', default: 'Чат'},
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/5.png', channel: 'Чат Коваля', default: 'Чат'},
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/6.png', channel: 'Tech in UK', default: 'Чат'},
            {icon: '/images/icons/social/telegram.png', url: '/images/avatars/7.png', channel: 'Канал Димы Коваля', default: 'Канал'},
            {icon: '/images/icons/social/discord.png', url: '/images/avatars/8.png', channel: 'Das Herz', default: 'Чат'},
        ];
        this.list = this.container.querySelector("#profile-list");

        const data = { a: 1, b: 2 }

        Emitter.emit('loadCommunityData', {
            data
        });

        // this.loadData()

        this.hideInfoBlock = this.container.querySelector('#hide_info');

        if (this.isBlock('[data-tab="profileBlock"]')) {
            this.profileBlock = new ProfileBlock(this);
        }
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }

    

    // loadData(){
    //     //запрос на массив объектов
    // }

    
    async toggleProfileCommunityVisibility(event) {
        await this.profileBlock.toggleVisibility();
        let active = document.getElementById("btn_profile");
        let hideShow = document.getElementById("hideShow");

        if (this.profileBlock.isVisible) {
            event.target.textContent = 'Скрыть';
            active.classList.remove("active");
            hideShow.classList.remove("active");
            this.hideInfoBlock.classList.remove('active');
        } else {
            event.target.textContent = 'Раскрыть';
            active.className += " active";
            hideShow.className += " active";
            this.hideInfoBlock.classList.add('active');

        }
    }

    setCheck(){
        console.log('123')
    }



    // createProfile(){
    //     console.log(this.loadData);
    //     this.loadData.forEach((item) => {

    //         const itemWrap = new CreateNode({
    //             parent: this.list,
    //             class: 'profile__item-wrap',
    //         }).init();

    //         const itemList = new CreateNode({
    //             parent: itemWrap,
    //             tag: 'a',
    //             class: 'profile__item',
    //         }).init();

    //         const itemImage = new CreateNode({
    //             parent: itemList,
    //             class: 'profile__item-image',
    //         }).init()

    //         const img = new CreateNode({
    //             parent: itemImage,
    //             tag: 'img',
    //             class: 'profile__image',
    //             src: item.url,
    //         }).init()

    //         const itemText = new CreateNode({
    //             parent: itemList,
    //             class: 'profile__item-text',
    //         }).init()

    //         const channel = new CreateNode({
    //             parent: itemText,
    //             tag: 'p',
    //             class: 'profile__channel',
    //             text: item.channel,
    //         }).init();

    //         const messenger = new CreateNode({
    //             parent: itemText,
    //             tag: 'div',
    //             class: 'profile__messenger',
    //         }).init();

    //         const icon = new CreateNode({
    //             parent: messenger,
    //             tag: 'img',
    //             src: item.icon,
    //         }).init();

    //         const text = new CreateNode({
    //             parent: messenger,
    //             tag: 'p',
    //             class: 'profile__text',
    //             text: item.default,
    //         }).init();
    //     })
    // }
}


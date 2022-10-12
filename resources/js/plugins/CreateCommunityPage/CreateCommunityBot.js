import { createServerErrorMessage } from "../../functions";

export class CreateCommunityBot {
    constructor(parent) {
        this.container = parent.container;
        this.tabContainers = this.container.querySelectorAll('[data-tab]');

        this.backButton = document.getElementById("backButton");
        // this.backButtonSibling = backButton.nextElementSibling;
        // this.title = document.getElementById('addCommunityTitle');

        // this.backButton.addEventListener('click', function(e){
        //     let a = e.target;
        //     console.log(a)
        //     console.log(123123);
        //     // onClickTab();
        //     // stopSetInterval();

        // });

        this.loadingBlock2 = this.container.querySelectorAll('[data-community-answer-loading]');
        
        this.hash = null;
        this.interval = null;
        this.isFirstMsg = true;
        // let title = document.querySelectorAll('[data-pagename]')[0].dataset.pagename;
        // console.log(title);
        // this.title.innerText = title;

        
        // let backButtonSibling = document.querySelectorAll('[data-btnbackname]')[0].dataset.btnbackname;
        // this.backButtonSibling.innerText = backButtonSibling

    }

    onClickTab(tab) {
        this.backBtn = tab;
        this.tabContainers.forEach((tabContainer) => {
            if (tab.dataset.tabBtn === tabContainer.dataset.tab) {
                // let title = tabContainer.dataset.pagename;
                // this.title.innerText = title;
                
                // let backButtonSibling = tabContainer.dataset.btnbackname;
                // this.backButtonSibling.innerText = backButtonSibling;
                tabContainer.classList.remove('hidden');
            } else {
                tabContainer.classList.add('hidden');
            }
        });
    }

    async addCommunity(messenger, type, tab) {
        this.changeLoadMessage(type);
        await this.invokeCommunity(messenger, type);
        this.onClickTab(tab);
    }

    changeLoadMessage(type) {
        this.loadingBlock = this.container.querySelector(`[data-community-answer-loading="${ type }"]`);
        let isFirstMsg = true;
        this.changeLoadMessageInterval = setInterval(() => {
            if (isFirstMsg) {
                this.loadingBlock.querySelector('p').textContent = 'Потом сделайте его администратором...';
                isFirstMsg = false;
            } else if (!isFirstMsg) {
                this.loadingBlock.querySelector('p').textContent = 'Сначала добавьте бота...';
                isFirstMsg = true;
            }  
        }, 3000);
    }

    async invokeCommunity(messenger, type) {
        try {
            const resp = await window.axios({
                method: 'post',
                url: '/community/invoke',
                data: {
                    platform: messenger,
                    type: type
                }
            });

            this.hash = resp.data.original.hash;
            
            this.interval = this.waitForAnswer((data) => {
                if (data.original && data.original.status === "completed") {
                    this.botConnectedEvent(data.original, messenger, type);
                    this.stopSetInterval();
                    //this.backBtn.classList.add('disabled')
                }
            }, messenger);    
        } catch(error) {
            console.log('invoke', error);

            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        } finally {}
    }

    waitForAnswer(callback, messenger) {
        return setInterval(async () => {
            try {
                const resp = await window.axios({
                    method: 'post',
                    url: '/community/connection/check',
                    data: {
                        platform: messenger,
                        hash: this.hash
                    }
                });
    
                callback(resp.data);    
            } catch (error) {
                console.log('answer', error);
                new Toasts({
                    type: 'error',
                    message: createServerErrorMessage(error)
                });
            }
        }, 1000);
    }

    botConnectedEvent(data, messenger, type) {
        new Toasts({
            type: 'success',
            message: Dict.write('service_message', 'success_add_community')
        });

        this.drawToHTML(data, messenger, type);
    }

    stopSetInterval() {
        clearInterval(this.interval);
        clearInterval(this.changeLoadMessageInterval);
    }

    drawToHTML(data, messenger, type) {
        this.communityAnswerContainer = this.container.querySelector(`[data-community-answer-container="${ messenger }-${ type }"]`);
        //this.loadingBlock = this.communityAnswerContainer.querySelector('[data-community-answer-loading]');
        this.successMessageBlock = this.communityAnswerContainer.querySelector('[data-community-answer-success-message]');
        
        this.loadingBlock.classList.add('hide');
        this.successMessageBlock.innerHTML = this.createSuccessMessage(data, type);
        this.createRedirectBlock(data);
        
    }

    createRedirectBlock(data) {
        this.redirectBlock = document.createElement('div');
        this.redirectBlock.className = 'alert alert-info mt-2 p-2';
        this.redirectBlock.innerHTML =  `
            ${ Dict.write('service_message', 'redirect_message') }:
        `;

        this.timer(50, data);
        this.successMessageBlock.append(this.redirectBlock);
    }

    timer(endOfTimer, data) {
        let seconds = 0;

        const timer = setInterval(() => {
            seconds += 1;
            this.redirectBlock.innerHTML = `
            ${ Dict.write('service_message', 'redirect_message') }:
                <strong class="d-block text-center">{ ${ endOfTimer - seconds } }</strong>
            `;
            
            if (seconds === endOfTimer) {
                this.redirectToNewCommunity(data);
                clearInterval(timer);
            }
            
        }, 1000);
    }

    createSuccessMessage(data, type) {
        console.log(data)
        // return `
        //     <div class="d-flex flex-column justify-content-center align-items-center">
        //         <i class="telegram-icon telegram-icon-50"></i>
        //         <span class="mt-1">
        //             ${ data.community.title } — <span style="color: #28c76f;">${ Dict.write('base', 'connected_low') }</span>
        //         </span> 
        //         <span>${ type == 'channel' ? 'Канал' : 'Группа' }</span>
        //         <a href="/community/${ data.community.id }/statistic" class="btn btn-success mt-2">${ Dict.write('base', 'go_management') }</a>
        //     </div>

        //     <div></div>
        // `;
        return `
            
                <div class="channel-connection__add-channel-wrap">
                    <div class="channel-connection__connected-community">
                        <div class="channel-connection__image">
                            <img src="/images/avatars/1.png">
                        </div>
                        <div class="channel-connection__description">
                            <p class="channel-connection__channel">${ data.community.title }</p>
                            <div class="channel-connection__messenger">
                                <img src="/images/icons/social/telegram.png">
                                <p class="profile__text">${ type == 'channel' ? 'Канал' : 'Группа' }</p>
                            </div>
                        </div>
                    </div>
                    <span class="channel-connection__connected">Подключено</span>
                </div>
                <a href="/profile/communities" class="button-empty button-empty--primary">Перейти к списку подключённых сообществ</a>
            
        `;
    }

    redirectToNewCommunity(data) {
        window.location.href = `/profile/communities`;
        
        // if (Dict.language === 'en') {
        //     window.location.href = `/en/community/${ data.community.id }/statistic`;
        // } else {
        //     window.location.href = `/community/${ data.community.id }/statistic`;
        // }
    }
}

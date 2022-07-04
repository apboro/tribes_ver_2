import { createServerErrorMessage } from "../../../functions";

export class SidebarVisibility {
    constructor(options) {
        this.parentContainer = options.parent.container;
        // сайдбар
        this.container = options.container;

        // блок внутри сайдбара
        this.communityCard = this.container.querySelector('.community-profile-card');
        // контейнер контента, выводящегося в сообществе
        this.profileContent = this.parentContainer.querySelector('#profile-content');
        // кнопка переключения видимости
        this.toggleSidebarBtn = this.parentContainer.querySelector('#toggle_sidebar_btn');

        // видим ли сайдбар
        this.isVisibleSidebar = true;
        this.apiToken = document.querySelector('[name="api-token"]').content;

        this.init();
    }

    async init() {
        this.listeners();
        // получаем данные о видимости сайдбара
        this.isVisibleSidebar = await this.getSidebarVisibility();
    }

    listeners() {
        window.addEventListener('load', () => {
            // при загрузке присваиваем ширину для блока внутри сайдбара
            this.setCommunityCardWidth();
        });

        window.addEventListener('resize', () => {
            // при изменении размера окна присваиваем ширину для блока внутри сайдбара
            this.setCommunityCardWidth();
            // отключаем плавный переход наб действующих блоках
            this.setTransitions('none');
        })

        this.container.addEventListener('transitionend', () => {
            // по окончании анимации сайдбара - разблокируем кнопку переключения видимости
            this.toggleSidebarBtn.disabled = false;
        });
    }

    async toggleSidebarVisibility() {
        // блокируем кнопку переключения видимости
        this.toggleSidebarBtn.disabled = true;
        // переключаем значения видимости сайдбара
        this.isVisibleSidebar = !this.isVisibleSidebar;
        // отправляем значение видимости сайдбара
        await this.setSidebarVisibility();
        // включаем плавность перехода
        this.setTransitions('.5s');
        // если сайдбар не виден
        if (!this.isVisibleSidebar) {
            this.hideSidebar();
        } else {
            this.showSidebar();
        }
    }

    async getSidebarVisibility() {
        try {
            const res = await window.axios({
                method: 'get',
                url: '/api/session/get?key=is_visible_sidebar',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
            });
            
            return res.data.value == 'true' ? true : false;
        } catch (error) {
            // если не было записей на сервере о видимости сайдбара присваиваем значение, что сайбар виден
            if (error.response.data.status == 'fail') {
                return true;
            }
        
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }

    async setSidebarVisibility() {
        try {
            const res = await window.axios({
                method: 'post',
                url: '/api/session/put',
                headers: {
                    'Authorization': `Bearer ${ this.apiToken }`
                }, 
                data: {
                    key: 'is_visible_sidebar',
                    value: this.isVisibleSidebar.toString()
                }
            });
            
            return res.data;
        } catch (error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }

    setCommunityCardWidth() {
        // если в данный момент нет возможности считать ширину сайдбара - задаем ему значение близкое для всех экранов (лучше подошла минимально возможная ширина блока)
        const width = this.container.offsetWidth == 0 ? 320 : this.container.offsetWidth;
        // задаем минимальную ширину для блока внутри сайдбара, учитывая боковые паддинги
        this.communityCard.style.minWidth = `${ width - 28 }px`;
        
    }

    setTransitions(value) {
        this.container.style.transition = value;
        this.profileContent.style.transition = value;
    }

    showSidebar() {
        console.log('show');
        this.container.classList.remove('invisible');
        this.setCommunityCardWidth();
        this.profileContent.className = 'col-xl-8 col-lg-7 col-md-7 order-0 order-md-1';
        this.toggleSidebarBtn.textContent = Dict.write('base', 'hide_profile');
    }

    hideSidebar() {
        console.log('hide');
        this.container.classList.add('invisible');
        this.setCommunityCardWidth();
        this.profileContent.className = 'col-12';
        this.toggleSidebarBtn.textContent = Dict.write('base', 'show_profile');
    }
}

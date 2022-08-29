import Page from "./Abstract/Page";
import { DonatePage } from "./CommunityPage/DonatePage";
import { DonatePageSettings } from "./CommunityPage/DonatePageSettings";
import { KnowledgeBaseAddPage } from "./CommunityPage/KnowledgeBaeAddPage";
import { KnowledgeBasePage } from "./CommunityPage/KnowledgeBasePage";
import { ProfileBlock } from "./CommunityPage/ProfileBlock";
import { StatisticPage } from "./CommunityPage/StatisticPage";
import { TariffPage } from "./CommunityPage/TariffPage";
import { TariffPageSettings } from "./CommunityPage/TariffPageSettings";
import { TariffPagePublications } from "./CommunityPage/TariffPagePublications";

export default class CommunityPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        if (this.isBlock('[data-tab="profileBlock"]')) {
            this.profileBlock = new ProfileBlock(this);
        }

        if (this.isBlock('[data-tab="statisticPage"]')) {
            this.statisticPage = new StatisticPage(this);
        }

        if (this.isBlock('[data-tab="donatePage"]')) {
            this.donatePage = new DonatePage(this);
        }

        if (this.isBlock('[data-tab="donatePageSettings"]')) {
            this.donatePageSettings = new DonatePageSettings(this);
        }

        if (this.isBlock('[data-tab="tariffPage"]')) {
            this.tariffPage = new TariffPage(this);
        }

        if (this.isBlock('[data-tab="tariffPageSettings"]')) {
            this.tariffPageSettings = new TariffPageSettings(this);
        }

        if (this.isBlock('[data-tab="tariffPagePublications"]')) {
            this.tariffPagePublications = new TariffPagePublications(this);
        }
        
        if (this.isBlock('[data-tab="knowledgeBasePage"]')) {
            this.knowledgeBasePage = new KnowledgeBasePage(this);
        }

        if (this.isBlock('[data-tab="knowledgeBaseAddPage"]')) {
            this.knowledgeBaseAddPage = new KnowledgeBaseAddPage(this);
        }
        
    }

    isBlock(selector) {
        return this.container.querySelector(selector) ? true : false;
    }

    toggleProfileVisibility(event) {
        this.profileBlock.toggleVisibility();
        let active = document.getElementById("btn_profile");

        if (this.profileBlock.isVisible) {
            event.target.textContent = 'Скрыть профиль';
            active.classList.remove("active");

        } else {
            event.target.textContent = 'Открыть профиль';
            active.className += " active";
        }
    }
}

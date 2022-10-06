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
import { TariffPageAdd } from "./CommunityPage/TariffPageSettings/TariffPageAdd";
import { AnalyticsListPage } from "./CommunityPage/AnalyticsListPage";
import { AnalyticsSubscribersPage } from "./CommunityPage/AnalyticsSubscribersPage";
import { AnalyticsMessagesPage } from "./CommunityPage/AnalyticsMessagesPage";
import { AnalyticsPaymentsPage } from "./CommunityPage/AnalyticsPaymentsPage";

export default class CommunityPage extends Page {
    constructor(container) {
        super(container);
    }
    
    init() {

        this.communityId = window.community_id;
        
        if (this.isBlock('[data-tab="profileBlock"]')) {
            this.profileBlock = new ProfileBlock(this);
        }

        if (this.isBlock('[data-tab="statisticPage"]')) {
            this.statisticPage = new StatisticPage(this);
        }

        if (this.isBlock('[data-tab="analyticsListPage"]')) {
            this.analyticsListPage = new AnalyticsListPage(this);
        }

        if (this.isBlock('[data-tab="analyticsSubscribersPage"]')) {
            this.analyticsSubscribersPage = new AnalyticsSubscribersPage(this);
        }

        if (this.isBlock('[data-tab="analyticsMessagesPage"]')) {
            this.analyticsMessagesPage = new AnalyticsMessagesPage(this);
        }

        if (this.isBlock('[data-tab="analyticsPaymentsPage"]')) {
            this.analyticsPaymentsPage = new AnalyticsPaymentsPage(this);
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

        if (this.isBlock('[data-tab="tariffPageAdd"]')) {
            this.tariffPageAdd = new TariffPageAdd(this);
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

    async toggleProfileVisibility(event) {
        await this.profileBlock.toggleVisibility();
        let active = document.getElementById("btn_profile");

        if (this.profileBlock.isVisible) {
            event.target.textContent = 'Скрыть';
            active.classList.remove("active");

        } else {
            event.target.textContent = 'Раскрыть';
            active.className += " active";
        }
    }

    /*  let content;
        if (direction === 'next') {
            content = el.nextElementSibling;
        } else if (direction === 'previous') {
            content = el.previousElementSibling;
        }
        
        el.classList.toggle('active');
        el.parentNode.classList.toggle('active');
        content.classList.toggle('active');

        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            if (isChangeText) el.textContent = "Показать всё";
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            if (isChangeText) el.textContent = "Скрыть";
        } */
}

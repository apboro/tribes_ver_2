import { createServerErrorMessage, dateFormatting } from "../../../functions";
import { PaidDonationsCount } from "./PaidDonationsCount";
import { PaidTariffsCount } from "./PaidTariffsCount";
import { TariffAmounts } from "./TariffAmounts";
import { UniqueVisitors } from "./UniqueVisitiors";

export class ChartsData {
    constructor(parent) {   
        //this.communityId = parent.container.dataset.communityId;

        this._data = {
            tariffAmounts: {},
            uniqueVisitors: {},
            paidTariffsCount: {},
            paidDonationsCount: {},
        };
       
        // Событие изменения типа rank
        this.tariffAmountsRankEvent = parent.tariffAmountsRankEvent;
        // Событие изменения типа count
        this.tariffAmountsCountEvent = parent.tariffAmountsCountEvent;
        // Событие изменения суммы тарифов
        this.tariffAmountsUpdateEvent = parent.tariffAmountsUpdateEvent;
        // Событие загрузки данных
        this.loadDataEvent = parent.loadDataEvent;


        this.tariffAmountsInstance = new TariffAmounts(this);
        this.uniqueVisitorsInstance = new UniqueVisitors(this);
        this.paidTariffsCountInstance = new PaidTariffsCount(this);
        this.paidDonationsCountInstance = new PaidDonationsCount(this);

        this.init();
    }

    init() {
        window.addEventListener('load', async() => {
            await this.loadData();
            this.listeners();
        });
    }

    // загрузка данных
    async loadData() {      
       /*  await this.loadTariffAmountsData();
        await this.loadUniqueVisitorsData();
        await this.loadPaidTariffsCountData();
        await this.loadPaidDonationsCountData(); */
     
        // создаем событие загрузки данных
        Emitter.emit(this.loadDataEvent, {
            data: this.data,
        });
    }

    async loadTariffAmountsData() {
        const tariffAmountsRes = await this.createRequest(this.tariffAmountsInstance.getUrl());
        this.tariffAmounts = this.tariffAmountsInstance.getData(tariffAmountsRes);
    }

    async uploadTariffAmountsData() {
        await this.loadTariffAmountsData();

        Emitter.emit(this.tariffAmountsUpdateEvent, {
            data: this.data,
        });
    }

    async loadUniqueVisitorsData() {
        const uniqueVisitorsRes = await this.createRequest(this.uniqueVisitorsInstance.getUrl());
        this.uniqueVisitors = this.uniqueVisitorsInstance.getData(uniqueVisitorsRes);
    }

    async loadPaidTariffsCountData() {
        const paidTariffsCountRes = await this.createRequest(this.paidTariffsCountInstance.getUrl());
        this.paidTariffsCount = this.paidTariffsCountInstance.getData(paidTariffsCountRes);
    }

    async loadPaidDonationsCountData() {
        const paidDonationsCountRes = await this.createRequest(this.paidDonationsCountInstance.getUrl());
        this.paidDonationsCount = this.paidDonationsCountInstance.getData(paidDonationsCountRes);
    }

    // запрос на сервер
    async createRequest(url) {
        try {
            const res = await window.axios({
                method: 'get',
                url,
                params: {
                    count: this.tariffAmountsInstance.count,
                    rank: this.tariffAmountsInstance.rank,
                }
            });
                       
            return res.data;
        } catch(error) {
            new Toasts({
                type: 'error',
                message: createServerErrorMessage(error)
            });
        }
    }
    
    listeners() {
        // подписываемся на событие изменения rank для amount tariff
        Emitter.subscribe(this.tariffAmountsRankEvent, async ({ rank }) => {
            this.tariffAmountsInstance.updateRank(rank);
            await this.uploadTariffAmountsData();
        });

        // подписываемся на событие изменения count для amount tariff
        Emitter.subscribe(this.tariffAmountsCountEvent, async ({ count }) => {
            this.tariffAmountsInstance.updateCount(count);
            await this.uploadTariffAmountsData();
        });
    }
    
    get data() {
        return this._data;
    }

    get tariffAmounts() {
        return this._data.tariffAmounts;
    }

    set tariffAmounts(value) {
        this._data.tariffAmounts = value;
    }

    get uniqueVisitors() {
        return this._data.uniqueVisitors;
    }

    set uniqueVisitors(value) {
        return this._data.uniqueVisitors = value;
    }

    get paidTariffsCount() {
        return this._data.paidTariffsCount;
    }

    set paidTariffsCount(value) {
        return this._data.paidTariffsCount = value;
    }
    
    get paidDonationsCount() {
        return this._data.paidDonationsCount;
    }

    set paidDonationsCount(value) {
        return this._data.paidDonationsCount = value;
    }
}

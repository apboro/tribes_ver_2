import { dateFormatting } from "../../../functions";

export class PaidDonationsCount {
    constructor(parent) {
        this._urlOptions = {
            communityId: parent.communityId,
            rank: 'd',
            count: '7'
        };
        this.url =  this.createURL(); 
    }

    getData(res) {
        return this.convertDataToChartData(res);  
    }

    getUrl() {
        return this.url;
    }

    createURL() {
        return `/api/donate/${ this.communityId }/${ this.count }/${ this.rank }`;
    }

    updateRank(rank) {
        this.rank = rank;

        switch (this.rank) {
            case 'd':
                this.count = 7;
                break;
            case 'm':
                this.count = 6;
                break;
            case 'y':
                this.count = 5;
                break;
        }

        this.url = this.createURL();
    }

    updateCount(count) {
        this.count = count;
        this.url = this.createURL();
    }

    // преобразуем данные в подходящие для chart
    convertDataToChartData(res) {
        const chartData = {};
        Object.values(res).reverse().forEach(({ date, value }) => {
            let formattedDate;
            switch (this.rank) {
                case 'd':
                    formattedDate = dateFormatting({
                        date: Date.parse(date),
                        month: "short",
                        day: "numeric"
                    });
                    break;
                case 'm':
                    formattedDate = dateFormatting({
                        date: Date.parse(date),
                        month: "short",
                        year: "numeric",
                    });
                    break;
                case 'y':
                    formattedDate = dateFormatting({
                        date: Date.parse(date),
                        year: "numeric",
                    });
                    break;
            }
        
            chartData[formattedDate] = value;
        });
        
        return chartData;
    }

    get communityId() {
        return this._urlOptions.communityId;
    }

    get rank() {
        return this._urlOptions.rank;
    }

    set rank(value) {
        this._urlOptions.rank = value;
    }

    get count() {
        return this._urlOptions.count;
    }

    set count(value) {
        return this._urlOptions.count = value;
    }
}

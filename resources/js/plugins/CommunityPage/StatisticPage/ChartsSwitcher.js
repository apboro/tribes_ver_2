export class ChartsSwitcher {
    constructor(parent) {
        this.container = parent.container;
        this.data = parent.data;

        this.tariffAmountsRankEvent = parent.tariffAmountsRankEvent;
        this.tariffAmountsCountEvent = parent.tariffAmountsCountEvent;
    }

    onChangeTariffAmountsRank(event) {
        Emitter.emit(this.tariffAmountsRankEvent, {
            rank: event.target.value
        });
    }

    onChangeTariffAmountsCount(event) {
        Emitter.emit(this.tariffAmountsCountEvent, {
            count: event.target.value
        });
    }
}

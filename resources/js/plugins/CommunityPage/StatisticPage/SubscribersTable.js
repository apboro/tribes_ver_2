export class SubscribersTable {
    constructor(parent) {
        this.container = parent;

        this.sortType = 'off'; // asc, desc
        console.log(this.container);
    }

    sort(value) {
        switch (this.sortType) {
            case 'off':
                this.sortType = 'asc';
                break;
            case 'asc':
                this.sortType = 'desc';
                break;
            case 'desc':
                this.sortType = 'off';
                break;
        }
    }
}

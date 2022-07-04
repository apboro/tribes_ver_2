import Page from './Abstract/Page';
import { CreateCommunityData } from './CreateCommunityPage/CreateCommunityData';
import { CreateCommunityBot } from './CreateCommunityPage/CreateCommunityBot';

export default class CreateCommunityPage extends Page {
    constructor(container) {
        super(container);
    }

    init() {
        this.data = new CreateCommunityData(this);
        this.createCommunityBot = new CreateCommunityBot(this);
    }
}

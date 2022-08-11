<template>
    <div class="analytics-community">
        <analytics-nav
            class="analytics-community__nav"
            :links="navigationLinks"
            :visibleTab="visibleTab"
            @switchTab="switchTab"    
        />

        <analytics-filter
            class="analytics-community__filter"
            :filterValue="filterValue"
            @setPeriod="setPeriod"
        />

        <transition name="a-page-tabs" mode="out-in">
            <analytics-list
                v-if="visibleTab == 'list'"
                class="analytics-community__tab"
                :period="filterValue"
                @filter="filter"
            />
            
            <analytics-subscribers
                v-else-if="visibleTab == 'subscribers'"
                class="analytics-community__tab"
                :period="filterValue"
                @filter="filter"
            />

            <analytics-messages
                v-else-if="visibleTab == 'messages'"
                class="analytics-community__tab"
                :period="filterValue"
                @filter="filter"
            />

            <analytics-payments
                v-else-if="visibleTab == 'payments'"
                class="analytics-community__tab"
                :period="filterValue"
                @filter="filter"
            />
        </transition>

        <!-- <div>
            <line-chart
                :chartData="chartData2"
            />

            <div class="legend-box" style="display: flex;">
                <button
                    style="padding: 10px;"
                    :style="{ backgroundColor: dataset.borderColor }"
                    v-for="(dataset, index) in chartData2.datasets"
                    :key="index"
                    @click="toggleData(index)"
                >
                    {{ dataset.label }}
                </button>
            </div>
        </div> -->
    </div>
</template>

<script>
    /* import BarChart from '../../../components/ui/chart/BarChart.vue';
    import LineChart from '../../../components/ui/chart/LineChart.vue'; */
    import AnalyticsFilter from '../../../components/pages/Community/Analytics/AnalyticsFilter.vue';
    import AnalyticsSubscribers from './tabs/AnalyticsSubscribers.vue';
    import AnalyticsMessages from './tabs/AnalyticsMessages.vue';
    import AnalyticsPayments from './tabs/AnalyticsPayments.vue';
    import AnalyticsList from './tabs/AnalyticsList.vue';
    import AnalyticsNav from '../../../components/pages/Community/Analytics/AnalyticsNav.vue';

    export default {
        name: 'CommunityAnalytics',

        components: {
            /* BarChart,
            LineChart, */
            AnalyticsNav,
            AnalyticsFilter,
            AnalyticsList,
            AnalyticsSubscribers,
            AnalyticsMessages,
            AnalyticsPayments,
        },

        data() {
            return {
                navigationLinks: [
                    {
                        text: 'Все',
                        tabName: 'list'
                    },

                    {
                        text: 'Подписчики',
                        tabName: 'subscribers'
                    },

                    {
                        text: 'Сообщения',
                        tabName: 'messages'
                    },

                    {
                        text: 'Финансы',
                        tabName: 'payments'
                    },
                ],

                visibleTab: 'payments',
                filterValue: 'week',

                chartData2: {
                    labels: [ '1', '2', '3' ],
                    datasets: [
                        {
                            label: "Data 1",
                            data: [2, 20, 5],
                            backgroundColor: "rgba(171, 71, 188, 1)",
                            fontColor: 'red',
                            borderColor: "rgba(1, 116, 188, 0.50)",
                            pointBackgroundColor: "rgba(171, 71, 188, 1)",
                            pointRadius: 0,
                            hidden: false,
                            //borderWidth: 10,
                        },

                        {
                            label: "Data 2",
                            data: [20, 100, 50],
                            backgroundColor: "yellow",
                            borderColor: "rgba(0, 116, 0, 0.50)",
                            pointBackgroundColor: "yellow",
                            pointRadius: 0,
                            hidden: false,
                        }
                    ]
                },
            }
        },

        methods: {
            switchTab(tabName) {
                this.visibleTab = tabName;
            },
            
            toggleData(value) {    
                const visibilityData = this.chartData2.datasets[value].hidden;
            
                if (visibilityData) {
                    this.chartData2.datasets[value].hidden = false;
                } else {
                    this.chartData2.datasets[value].hidden = true;
                }
            },
            
            setPeriod(period) {
                this.filterValue = period;
                /* if (period == 'week') {
                    this.dataList.subscribers.data = [10, 30, 40, 20, 25, 50, 10, 5, 15, 25, 10, 45];
                    this.dataList.messages.data = [3, 7, 25, 36, 42, 23, 31];
                    this.dataList.finance.data = [100, 250, 150, 300, 250, 400, 350, 450];
                } else if (period == 'month') {
                    this.dataList.subscribers.data = [100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420, 100, 200, 300, 100, 50, 150, 420];
                    this.dataList.messages.data = [100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 100, 20, 300, 300, 100, 20, 300];
                    this.dataList.finance.data = [100, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300, 100, 200, 300, 200, 300];
                } else if (period == 'year') {
                    this.dataList.subscribers.data = [200, 100, 300, 420, 50, 100, 150];
                    this.dataList.messages.data = [10, 200, 30, 50, 20];
                    this.dataList.finance.data = [150, 20, 300, 200, 100];
                } */
            },

            filter(data) {
                // slot for vuex action
                console.log(data);

                if (data.name == 'list') {

                } else if (data.name == 'subscribers') {
                    
                } else if (data.name == 'messages') {
                    
                } else if (data.name == 'payments') {
                    
                } 
            }
        }
    }
</script>


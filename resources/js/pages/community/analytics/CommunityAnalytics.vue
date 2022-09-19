<template>
    <div class="analytics-community">
        <!-- <analytics-nav
            class="analytics-community__nav"
            :links="navigationLinks"
            :visibleTab="visibleTab"
            @switchTab="switchTab"    
        /> -->

        <div class="analytics-community__analytics-wrap">
            <div class="analytics-community__title-wrap">
                <button 
                    v-if="visibleTab == 'subscribers' || visibleTab == 'messages' || visibleTab == 'payments'"
                    type="button" 
                    class="button-back"
                    @click="back()"
                >
                    <!-- <svg width="22" height="16" viewBox="0 0 22 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 9C21.5523 9 22 8.55228 22 8C22 7.44772 21.5523 7 21 7L21 9ZM0.292892 7.2929C-0.0976315 7.68342 -0.0976314 8.31658 0.292893 8.70711L6.65686 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928933C7.68054 0.538409 7.04738 0.538409 6.65685 0.928933L0.292892 7.2929ZM21 7L1 7L1 9L21 9L21 7Z" fill="#7367F0"/>
                    </svg> -->
                    <svg width="27" height="16" viewBox="0 0 27 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 9C26.5523 9 27 8.55228 27 8C27 7.44772 26.5523 7 26 7L26 9ZM0.292893 7.2929C-0.0976311 7.68342 -0.097631 8.31658 0.292893 8.70711L6.65685 15.0711C7.04738 15.4616 7.68054 15.4616 8.07107 15.0711C8.46159 14.6805 8.46159 14.0474 8.07107 13.6569L2.41421 8L8.07107 2.34315C8.46159 1.95262 8.46159 1.31946 8.07107 0.928934C7.68054 0.538409 7.04738 0.53841 6.65685 0.928934L0.292893 7.2929ZM26 7L1 7L1 9L26 9L26 7Z" fill="#7367F0"/>
                    </svg>

                </button>
                <h2 class="analytics-community__title">
                    Аналитика
                </h2>
            </div>

            <div>
                <analytics-pages
                    v-if="visibleTab == 'subscribers' || visibleTab == 'messages' || visibleTab == 'payments'"
                    class="analytics-community__filter"
                    :page="visibleTab"
                    :visibleTab="visibleTab"
                    @setPage="setPage"
                />

                <analytics-filter
                    class="analytics-community__filter"
                    :filterValue="filterValue"
                    @setPeriod="setPeriod"
                />
            </div>
        </div>

        <transition name="a-page-tabs" mode="out-in">
            <analytics-list
                v-if="visibleTab == 'list'"
                class="analytics-community__tab"
                :period="filterValue"
                @filter="filter"
                @switchValue="switchTab"
            />
            
            <analytics-subscribers
                v-else-if="visibleTab == 'subscribers'"
                class="analytics-community__tab"
                :period="filterValue"
                :chartOptions="bigChartOptions"
                @filter="filter"
            />

            <analytics-messages
                v-else-if="visibleTab == 'messages'"
                class="analytics-community__tab"
                :period="filterValue"
                :chartOptions="bigChartOptions"
                @filter="filter"
            />

            <analytics-payments
                v-else-if="visibleTab == 'payments'"
                class="analytics-community__tab"
                :period="filterValue"
                :chartOptions="bigChartOptions"
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
    import { mapActions } from 'vuex';
    /* import BarChart from '../../../components/ui/chart/BarChart.vue';
    import LineChart from '../../../components/ui/chart/LineChart.vue'; */
    import AnalyticsFilter from '../../../components/pages/community/analytics/AnalyticsFilter.vue';
    import AnalyticsPages from '../../../components/pages/community/analytics/AnalyticsPages.vue';
    import AnalyticsSubscribers from './tabs/AnalyticsSubscribers.vue';
    import AnalyticsMessages from './tabs/AnalyticsMessages.vue';
    import AnalyticsPayments from './tabs/AnalyticsPayments.vue';
    import AnalyticsList from './tabs/AnalyticsList.vue';
    import AnalyticsNav from '../../../components/pages/community/analytics/AnalyticsNav.vue';

    export default {
        name: 'CommunityAnalytics',

        components: {
            /* BarChart,
            LineChart, */
            AnalyticsNav,
            AnalyticsFilter,
            AnalyticsPages,
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

                visibleTab: 'subscribers',
                filterValue: 'week',

                bigChartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    radius: 1,
                    hoverRadius: 0,
                    borderWidth: 2,
                    pointBorderColor: 'transparent',
                    //tension: 0.1,
                    
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutCubic'
                    },
                    
                    scales: {
                        x: {
                            ticks: {    
                                color: '#000000',

                                font: {
                                    //family: "'Montserrat', 'sans-serif'",
                                    size: 14,
                                    weight: 600,
                                    lineHeight: '20px',
                                },

                                // callback: (label, index, labels) => {
                                //     return this.labels[index];
                                // },
                            },
                            grid: {
                                borderColor: '#7367F0',
                                color: 'transparent',
                                tickColor: '#7367F0'
                            },
                        },
                        
                        y: {
                            ticks: {    
                                color: '#000000',

                                font: {
                                    size: 14,
                                    weight: 600,
                                    lineHeight: '20px',
                                },

                                // callback: (label, index, labels) => {
                                //     return this.labels[index];
                                // },
                            },
                            
                            grid: {
                                borderColor: '#7367F0',
                                color: 'transparent',
                                tickColor: '#7367F0'
                            },
                        }
                    },

                    plugins: {
                        legend: { display: false },
                        title: { display: false },
                        tooltip: { enabled: false },
                    }
                },

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
            ...mapActions('community_analytics', ['FILTER']),

            switchTab(tabName) {
                this.visibleTab = tabName;
                /* throw new Error('wewewe') */
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
            setPage(page){
                this.visibleTab = page;
            },

            back() {
                this.visibleTab = 'list'
            },

            filter(data) {
                // slot for vuex action
                
                this.FILTER(data)

                if (data.name == 'list') {

                } else if (data.name == 'subscribers') {
                    
                } else if (data.name == 'messages') {
                    
                } else if (data.name == 'payments') {
                    
                } 
            }
        }
    }
</script>


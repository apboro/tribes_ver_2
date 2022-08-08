<template>
    <div class="analytics-community">
        <nav class="analytics-community__nav">
            <ul class="analytics-community__nav-list">
                <li class="analytics-community__nav-item">
                    <button
                        class="button-empty button-empty--primary"
                        @click="visibleTab = 'main'"
                    >
                        Main
                    </button>
                </li>

                <li class="analytics-community__nav-item">
                    <button
                        class="button-empty button-empty--primary"
                        @click="visibleTab = 'subscribers'"
                    >
                        Subscribers
                    </button>
                </li>
            </ul>
        </nav>

        <transition name="a-page-tabs" mode="out-in">
            <analytics-list
                v-if="visibleTab == 'main'"
                :data="dataList"
            />
            
            <analytics-subscribers
                v-else-if="visibleTab == 'subscribers'"
                :data="subscribers"
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
    
    import BarChart from '../../../components/ui/chart/BarChart.vue';
    import LineChart from '../../../components/ui/chart/LineChart.vue';
    import AnalyticsSubscribers from './AnalyticsSubscribers.vue';
    import AnalyticsList from './AnalyticsList.vue';

    export default {
        name: 'CommunityAnalytics',

        components: {
            BarChart,
            LineChart,
            AnalyticsList,
            AnalyticsSubscribers,
        },

        data() {
            return {
                visibleTab: 'subscribers',

                dataList: {
                    subscribers: {
                        title: 'Подписчики',
                        data: [10, 30, 40, 20, 25, 50, 10, 5, 15, 25, 10, 45],
                        infoLeft: {
                            text: 'Прирост',
                            value: '+96'
                        },
                        infoRight: {
                            text: 'Полезных',
                            value: '+13'    
                        }
                    },

                    messages: {
                        title: 'Сообщества',
                        data: [3, 7, 25, 36, 42, 23, 31],
                        infoLeft: {
                            text: 'Отправлено',
                            value: '+563'
                        },
                        infoRight: {
                            text: 'Полезных',
                            value: '+233'    
                        }
                    },

                    finance: {
                        title: 'Финансы',
                        data: [100, 250, 150, 300, 250, 400, 350, 450],
                        infoLeft: {
                            text: 'Приход',
                            value: '₽24.3k'
                        },
                        infoRight: {
                            text: 'Можно вывести',
                            value: '₽20,3k'    
                        }
                    }
                },

                subscribers: {
                    common: {
                        text: 'Всего подписчиков в сообществе',
                        value: '2356'
                    },

                    joined: {
                        data: [500, 200, 100, 60, 155, 80, 220, 300, 100, 400],
                        legend: {
                            text: 'Вступили в сообщество',
                            value: '+96',
                        }
                    },

                    left: {
                        data: [100, 50, 220, 180, 70, 160, 90, 130, 400, 300],
                        legend: {
                            text: 'Покинули сообщество',
                            value: '-19',
                        }
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
            toggleData(value) {    
                const visibilityData = this.chartData2.datasets[value].hidden;
            
                if (visibilityData) {
                    this.chartData2.datasets[value].hidden = false;
                } else {
                    this.chartData2.datasets[value].hidden = true;
                }
            },
            
            
        }
    }
</script>


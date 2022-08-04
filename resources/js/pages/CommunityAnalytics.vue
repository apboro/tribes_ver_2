<template>
    <div class="analytics-community">
        <analytics-filter
            class="analytics-community__filter"
            @getPeriod="filter"
        />

        <ul class="analytics-community__card-list">
            <chart-card
                class="analytics-community__card-item"
                v-for="(data, index) in dataList"
                :key="index"
                :data="data"
            />
        </ul>

        <div>
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
        </div>

        <ul class="analytics__list" style="display: flex; column-gap: 20px;">
            <li class="analytics__item" style="width: 500px; ">
                <bar-chart
                    :chartData="chartData2"
                    
                />
            </li>

            <li class="analytics__item" style="width: 500px; ">
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
            </li>
        </ul>

        <button @click="change">change</button>
    </div>
</template>

<script>
    import AnalyticsFilter from '../components/pages/CommunityAnalytics/AnalyticsFilter.vue';
    import ChartCard from '../components/pages/CommunityAnalytics/ChartCard.vue';
    import BarChart from '../components/ui/chart/BarChart.vue';
    import LineChart from '../components/ui/chart/LineChart.vue';

    export default {
        name: 'CommunityAnalytics',

        components: {
            BarChart,
            LineChart,
            AnalyticsFilter,
            ChartCard,
        },

        data() {
            return {  
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

                

                chartData: {
                    labels: [ 'January', 'February', 'March', '1', '2', '3' ],
                    datasets: [ { data: [40, 20, 12, 1, 2, 3] } ]
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
            
            change() {
                this.chartData2.datasets[0].data = [100, 200, 300];
            },
            
            filter(period) {
                console.log(period);
            }
        }
    }
</script>


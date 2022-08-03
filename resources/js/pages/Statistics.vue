<template>
    <div class="statistics">
        <div class="container">
            <!-- HEAD -->
            <header class="community__header" style="display: flex; justify-content: space-between;">
                <h2 class="community__title">Title</h2>

                <nav class="community__nav">
                    <ul style="display: flex; column-gap: 20px;">
                        <li>
                            <a href="">Аналитика</a>
                        </li>

                        <li>
                            <a href="">Донаты</a>
                        </li>

                        <li>
                            <a href="">Тарифы</a>
                        </li>

                        <li>
                            <a href="">База знаний</a>
                        </li>
                    </ul>
                </nav>
            </header>

            <!-- Profile -->
            <div class="community__profile">
                Community profile
            </div>

            <!-- Analytics -->
            <div class="analytics">

                <div>
                    <line-chart
                        :chartData="chartData2"
                    />

                    <div class="legend-box" style="display: flex;">
                        <button
                            style="padding: 10px;"
                            :style="{ backgroundColor: dataset.backgroundColor }"
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
                            :chartData="chartData"
                            
                        />
                    </li>

                    <li class="analytics__item" style="width: 500px; ">
                        <line-chart
                            :chartData="chartData2"
                        />

                        <div class="legend-box" style="display: flex;">
                            <button
                                style="padding: 10px;"
                                :style="{ backgroundColor: dataset.backgroundColor }"
                                v-for="(dataset, index) in chartData2.datasets"
                                :key="index"
                                @click="toggleData(index)"
                            >
                                {{ dataset.label }}
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import BarChart from '../components/ui/chart/BarChart.vue';
    import LineChart from '../components/ui/chart/LineChart.vue';

    export default {
        name: 'Statistics',
        
        components: { BarChart, LineChart },

        data() {
            return {
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
            }
        },

        mounted() {
            
        },
    }
</script>

<style lang="scss" scoped>

</style>

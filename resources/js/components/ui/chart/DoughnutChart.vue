<template>
    <Doughnut
        :chart-options="chartOptions"
        :chart-data="chartData"
        :width="width"
        :height="height"
    />
</template>

<script>
import { Doughnut } from 'vue-chartjs/legacy'

import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

export default {
    name: 'DoughnutChart',
    components: {
        Doughnut
    },
    props: {
        chartOptions: {
            type: [Object, null],
            default: null
        },

        chartData: {
            type: Object,
            default: () => [],
        },

        width: {
            type: Number,
            default: 400
        },

        height: {
            type: Number,
            default: 400
        },
    },

    data() {
       return {  
            chartOptions: {
                responsive: true
            }
        }
    }
}
</script>

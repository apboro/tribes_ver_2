<template>
    <Pie
        :chart-options="chartOptions"
        :chart-data="chartData"
        
        :width="width"
        :height="height"
    />
</template>

<script>
import { Pie } from 'vue-chartjs/legacy'

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
    name: 'PieChart',
    components: {
        Pie
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
        
    }
}
</script>

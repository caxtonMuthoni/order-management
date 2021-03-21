import { Pie } from 'vue-chartjs'

export default {
    extends: Pie,

    props: {
        chartdata: {
            type: Object,
            default: null
        },

        title: {
            type: String,
            default: null
        }

    },

    data() {
        return {
            options: null
        }
    },

    methods: {
        setOptions() {
            this.options = {
                title: {
                    display: true,
                    text: this.title
                },
                legend: {
                  display: true
                },
                responsive: true,
                maintainAspectRatio: true
              }
        }
    },

    mounted() {
        this.setOptions()
        this.renderChart(this.chartdata, this.options)
    },
}

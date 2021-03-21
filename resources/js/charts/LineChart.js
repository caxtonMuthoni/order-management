import { Line } from 'vue-chartjs'

export default {
  extends: Line,

  data() {
    return {
      options: {
        title: {
          display: true,
          text: ''
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            },
            gridLines: {
              display: true
            },
            scaleLabel: {
              display: true,
              labelString: 'Total file uploads'
            }
          }],
          xAxes: [
            {
              ticks: {
                autoSkip: false,
                maxRotation: 90,
                minRotation: 45
              },
            
              gridLines: {
                display: false
              },
              scaleLabel: {
                display: true,
                labelString: 'Date time'
              }
            }]
        },
        legend: {
          display: true
        },
        responsive: true,
        maintainAspectRatio: false
      }
    }
  },

  props: {
    chartdata: {
      type: Object,
      default: null
    },
  },

  mounted() {
    this.renderChart(this.chartdata, this.options)
  },
}
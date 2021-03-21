import { Bar } from 'vue-chartjs'

export default {
  extends: Bar,
  props: {
    chartdata: {
      type: Object,
      default: null
    },
    title: {
      type: String,
      default: null
    },
    yLabelString : {
      type: String,
      default: null
    },
    xLabelString : {
      type: String,
      default: null
    }
  },
  
  data() {
    return {
      options: null,
    }
  },

  methods: {
    setOptions () {
      this.options = {
        title: {
          display: true,
          text: this.title
        },
        scales: {
          yAxes: [{
            scaleLabel: {
              display: true,
              labelString: this.yLabelString
            },
            ticks: {
              beginAtZero: true,
            },
            gridLines: {
              display: true
            },
          }],
          xAxes: [{
            scaleLabel: {
              display: true,
              labelString: this.xLabelString
            },
            gridLines: {
              display: false
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
  mounted () {
    this.setOptions()
    this.renderChart(this.chartdata, this.options)
  }
}
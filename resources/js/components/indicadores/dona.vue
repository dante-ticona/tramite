<template>
    <div style="position: relative; height: 400px; width: 100%;">
      <doughnut-chart :chart-data="chartData" :options="options" />
    </div>
</template>

  <script>
  import { Doughnut } from 'vue-chartjs'

  export default {
    name: 'DoughnutChartComponent',
    components: {
      DoughnutChart: {
        extends: Doughnut,
        props: {
          chartData: {
            type: Object,
            required: true
          },
          options: {
            type: Object,
            default: () => ({})
          }
        },
        mounted() {
          this.renderChart(this.chartData, this.options)
        }
      }
    },
    props: {
      chartData: {
        type: Object,
        required: true
      },
      options: {
        type: Object,
        default: () => ({})
      }
    }
  }
  </script>

  <style scoped>

  </style>

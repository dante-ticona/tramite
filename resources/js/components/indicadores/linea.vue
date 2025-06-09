<template>
    <div style="position: relative; height: 400px; width: 100%;">
      <line-chart :chart-data="chartData" :options="options" />
    </div>
</template>

  <script>
  import { Line } from 'vue-chartjs'

  export default {
    name: 'LineChartComponent',
    components: {
      LineChart: {
        extends: Line,
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
  /* Add any necessary styles here */
  </style>

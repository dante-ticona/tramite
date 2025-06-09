<template>
    <div style="position: relative; height: 400px; width: 100%;">
      <radar-chart :chart-data="chartData" :options="options" />
    </div>
</template>

  <script>
  import { Radar } from 'vue-chartjs'

  export default {
    name: 'RadarChartComponent',
    components: {
      RadarChart: {
        extends: Radar,
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

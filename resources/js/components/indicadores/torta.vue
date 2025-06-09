<template>
    <div style="position: relative; height: 400px; width: 100%;">
      <pie-chart :chart-data="chartData" :options="options" />
    </div>
</template>

  <script>
  import { Pie } from 'vue-chartjs'

  export default {
    name: 'Torta',
    components: {
      PieChart: {
        extends: Pie,
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
    data() {
      return {
        chartData: {
          labels: ['Red', 'Blue', 'Yellow'],
          datasets: [
            {
              label: 'Dataset 1',
              backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
              data: [300, 50, 100]
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      }
    }
  }
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>

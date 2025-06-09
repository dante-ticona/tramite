<!-- EChartsComponent.vue -->
<template>
    <v-chart :option="chartOptions" style="height: 500px; width: 100%;"></v-chart>
</template>

  <script>
  import ECharts from 'vue-echarts';
  import 'echarts/map/js/world'; // Importa el mapa del mundo

  export default {
    components: {
      'v-chart': ECharts
    },
    data() {
      return {
        chartOptions: {
          series: [{
            type: 'map',
            map: 'world'
          }]
        }
      };
    }
  };
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>

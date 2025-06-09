<!-- BarChartComponent.vue -->
<template>
    <v-chart :option="chartOptions" style="height: 500px; width: 100%;"></v-chart>
</template>

  <script>
  import * as echarts from 'echarts';
  import { use } from 'echarts/core';
  import { CanvasRenderer } from 'echarts/renderers';
  import { BarChart } from 'echarts/charts';
  import { TitleComponent, TooltipComponent, GridComponent } from 'echarts/components';
  import ECharts from 'vue-echarts';

  // Registrar los componentes necesarios
  use([
    CanvasRenderer,
    BarChart,
    TitleComponent,
    TooltipComponent,
    GridComponent
  ]);

  export default {
    components: {
      'v-chart': ECharts
    },
    data() {
      return {
        chartOptions: {
          title: {
            text: 'Bar Chart Example'
          },
          tooltip: {},
          xAxis: {
            data: ['Category A', 'Category B', 'Category C', 'Category D', 'Category E']
          },
          yAxis: {},
          series: [{
            name: 'Sales',
            type: 'bar',
            data: [5, 20, 36, 10, 10]
          }]
        }
      };
    }
  };
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>

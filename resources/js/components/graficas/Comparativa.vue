<template>
    <div>
      <v-chart :option="chartOption" autoresize style="width: 100%; height: 400px;" />
    </div>
</template>

<script>
  import { defineComponent } from "vue";
  import { use } from "echarts/core";
  import { CanvasRenderer } from "echarts/renderers";
  import { BarChart } from "echarts/charts";
  import {
    GridComponent,
    TooltipComponent,
    LegendComponent,
  } from "echarts/components";
  import VChart from "vue-echarts";

  use([
    CanvasRenderer,
    BarChart,
    GridComponent,
    TooltipComponent,
    LegendComponent,
  ]);

  export default defineComponent({
    components: {
      VChart,
    },
    data() {
      return {
        chartOption: {
          tooltip: {
            trigger: "axis",
            axisPointer: {
              type: "shadow",
            },
          },
          legend: {
            data: ["Base", "Incremento"],
          },
          xAxis: {
            type: "category",
            data: ["A", "B", "C", "D", "E"],
          },
          yAxis: {
            type: "value",
          },
          series: [
            {
              name: "Base",
              type: "bar",
              stack: "total",
              itemStyle: {
                color: "#4366c7",
              },
              data: [10, 20, 30, 40, 50],
            },
            {
              name: "Incremento",
              type: "bar",
              stack: "total",
              itemStyle: {
                color: "#97c47c",
              },
              data: [5, 5, 3, 5, 8],
            },
          ],
        },
      };
    },
  });
</script>

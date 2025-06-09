<template>
    <div>
      <div class="controls">
        <button @click="toggleSlider">{{ isPlaying ? 'Pausar' : 'Reproducir' }}</button>
        <input type="range" v-model="sliderSpeed" min="500" max="5000" step="500" />
        <span>Velocidad: {{ sliderSpeed }} ms</span>
      </div>
  
      <div v-if="SinRegistros" class="sin-registros">Sin Registros Existentes</div>
      <v-chart
        v-else
        :option="chartOptions"
        style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;">
      </v-chart>
    </div>
</template>
  
  <script>
  import { use } from 'echarts/core';
  import { CanvasRenderer } from 'echarts/renderers';
  import { BarChart, LineChart } from 'echarts/charts';
  import { GridComponent, TooltipComponent, DataZoomComponent, ToolboxComponent, LegendComponent } from 'echarts/components';
  import ECharts from 'vue-echarts';
  
  use([CanvasRenderer, BarChart, LineChart, GridComponent, TooltipComponent, DataZoomComponent, ToolboxComponent, LegendComponent]);
  
  export default {
    components: {
      'v-chart': ECharts
    },
    props: {
      chartData: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        sliderPosition: 0,
        sliderInterval: null,
        sliderSpeed: 2000, // Velocidad del slider en ms
        isPlaying: true, // Estado de reproducción
      };
    },
    mounted() {
      this.startSlider();
    },
    beforeUnmount() {
      clearInterval(this.sliderInterval);
    },
    methods: {
      startSlider() {
        this.sliderInterval = setInterval(() => {
          this.sliderPosition += 5;
          if (this.sliderPosition > 100) {
            this.sliderPosition = 0;
          }
        }, this.sliderSpeed);
      },
      stopSlider() {
        clearInterval(this.sliderInterval);
      },
      toggleSlider() {
        if (this.isPlaying) {
          this.stopSlider();
        } else {
          this.startSlider();
        }
        this.isPlaying = !this.isPlaying;
      }
    },
    watch: {
      sliderSpeed(newSpeed) {
        if (this.isPlaying) {
          this.stopSlider();
          this.startSlider();
        }
      }
    },
    computed: {
      SinRegistros() {
        return this.chartData.datasets[0].data.every(value => value === 0);
      },
      chartOptions() {
        return {
          title: {
            text: 'CASOS PENDIENTES POR BANDEJA',
            left: 'center'
          },
          tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' }
          },
          toolbox: {
            feature: {
              saveAsImage: { show: true },
              dataView: { show: true, readOnly: false },
              dataZoom: { show: true },
              restore: { show: true },
              magicType: { show: true, type: ['line', 'bar'] }
            }
          },
          grid: { left: '3%', right: '4%', bottom: '10%', containLabel: true },
          xAxis: {
            type: 'category',
            data: this.chartData.labels,
            axisLabel: { interval: 0 }
          },
          yAxis: { type: 'value' },
          dataZoom: [
            {
              type: 'slider',
              start: this.sliderPosition,
              end: this.sliderPosition + 5,
              xAxisIndex: [0]
            }
          ],
          series: [{
            name: 'Cantidad',
            type: 'bar',
            data: this.chartData.datasets[0].data,
            itemStyle: {
              color: (params) => this.chartData.datasets[0].backgroundColor[params.dataIndex]
            }
          }]
        };
      }
    }
  };
  </script>
  
  <style scoped>
  .sin-registros {
    text-align: center;
    font-size: 18px;
    color: #888;
    padding: 20px;
  }
  
  .controls {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
  }
  
  button {
    padding: 8px 12px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #0056b3;
  }
</style>
  
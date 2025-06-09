<template>
    <div>
        <div class="controls">
            <button @click="toggleSlider">
            <i :class="isPlaying ? 'fa fa-pause' : 'fa fa-play'" aria-hidden="true"></i>
            {{ isPlaying ? 'Pausar' : 'Reproducir' }}
            </button>
            <input type="range" v-model="sliderSpeed" min="500" max="5000" step="500" />
            <span>Velocidad: {{ sliderSpeed }} ms</span>
        </div>

        <div v-if="SinRegistros" class="sin-registros">Sin Registros Existentes</div>
        <v-chart v-else :option="chartOptions" style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></v-chart>
    </div>
</template>

<script>
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart, LineChart } from 'echarts/charts';
    import { GridComponent, TooltipComponent, DataZoomComponent, ToolboxComponent, LegendComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        LineChart,
        GridComponent,
        TooltipComponent,
        DataZoomComponent,
        ToolboxComponent,
        LegendComponent
    ]);

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
                sliderSpeed: 2000,
                isPlaying: true,
            };
        },
        mounted() {
            this.startSlider();
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
                        text: 'TOTAL DE REGISTROS POR TIPO DE TRÁMITE',
                        left: 'center',
                        backgroundColor: '#f5f5f5',
                        padding: [10, 20],
                        textStyle: {
                            fontSize: 16,
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    toolbox: {
                        feature: {
                            saveAsImage: {
                                show: true,
                                title: 'Guardar'
                            },
                            dataView: {
                                show: true,
                                readOnly: false,
                                title: 'Ver datos',
                                lang: ['Ver datos', 'Cerrar', 'Actualizar']
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar'],
                                title: {
                                    line: 'Cambiar a línea',
                                    bar: 'Cambiar a barra'
                                }
                            },
                            restore: {
                                show: true,
                                title: 'Restaurar'
                            },
                            dataZoom: {
                                show: true,
                                title: 'Zoom'
                            }
                        }
                    },
                    legend: {
                        data: ['Cantidad'],
                        top: 'bottom'
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '10%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: this.chartData.labels,
                        axisLabel: {
                            fontSize: 9,
                            //rotate: 45,
                            interval: 0,
                            formatter: function(value) {
                            const maxCharsPerLine = 15;
                            let formattedValue = '';
                            for (let i = 0; i < value.length; i += maxCharsPerLine) {
                                formattedValue += value.substring(i, i + maxCharsPerLine) + '\n';
                            }
                            return formattedValue.trim();
                            }
                        },
                        axisTick: {
                            alignWithLabel: true
                        }
                    },
                    yAxis: {
                        type: 'value'
                    },
                    dataZoom: [
                        {
                            type: 'slider',
                            start: this.sliderPosition,
                            end: this.sliderPosition + 5,
                            xAxisIndex: [0],
                            filterMode: 'filter',
                            zoomLock: false,
                            throttle: 100,
                            minValueSpan: 5,
                            maxValueSpan: 5
                        }
                    ],
                    series: [
                        {
                            name: 'Cantidad',
                            type: 'bar',
                            barWidth: '60%',
                            data: this.chartData.datasets[0].data,
                            itemStyle: {
                                color: (params) => this.chartData.datasets[0].backgroundColor[params.dataIndex]
                            },
                            label: {
                                show: true,
                                position: 'inside',
                                formatter: '{c}',
                                fontWeight: 'bold'
                            },
                            showBackground: true,
                            backgroundStyle: {
                                color: 'rgba(180, 180, 180, 0.2)'
                            }
                        }
                    ],
                    aria: {
                        enabled: true,
                        decal: {
                            show: true
                        }
                    }
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

    .v-chart {
        transition: transform 0.5s ease-in-out;
    }

    .v-chart:hover {
        transform: scale(1.05);
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
<template>
    <div>
        <v-chart :option="chartOptions" style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></v-chart>
    </div>
</template>

<script>
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart, LineChart } from 'echarts/charts';
    import { GridComponent, TooltipComponent, LegendComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        LineChart,
        GridComponent,
        TooltipComponent,
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
        computed: {
            chartOptions() {
                return {
                    title: {
                        text: 'LISTADO DE TOTAL DE TRÁMITES REGISTRADOS POR MES',
                        left: 'center',
                        top: '5%',
                        backgroundColor: '#f5f5f5',
                        padding: [10, 20],
                        textStyle: {
                            fontSize: 10,
                            fontWeight: 'bold'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: { type: 'cross' }
                    },
                    legend: {
                        top: '10%'
                    },
                    toolbox: {
                        show: true,
                        orient: 'vertical',
                        left: 'right',
                        top: 'center',
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
                            dataZoom: {
                                show: true,
                                title: {
                                    zoom: 'Zoom',
                                    back: 'Restaurar zoom'
                                }
                            },
                            restore: {
                                show: true,
                                title: 'Restaurar'
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar'],
                                title: {
                                    line: 'Cambiar a línea',
                                    bar: 'Cambiar a barra'
                                }
                            }
                        }
                    },
                    xAxis: [
                        {
                            type: 'category',
                            axisTick: {
                                alignWithLabel: true
                            },
                            axisLabel: {
                                rotate: 30
                            },
                            data: this.chartData.labels.map((label, index) => this.chartData.datasets[0].data[index].mes)
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value',
                            name: '',
                            min: 0,
                            axisLabel: {
                                formatter: '{value}'
                            }
                        }
                    ],
                    series: [
                        {
                            name: this.chartData.datasets[0].label,
                            type: 'bar',
                            data: this.chartData.datasets[0].data.map(item => item.total),
                            itemStyle: {
                                color: (params) => this.chartData.datasets[0].backgroundColor[params.dataIndex]
                            },
                            showBackground: true,
                            backgroundStyle: {
                                color: 'rgba(180, 180, 180, 0.2)'
                            },
                            label: {
                                show: true,
                                position: 'inside',
                                formatter: '{c}'
                            }
                        },
                        {
                            name: 'Dibujar Lineas',
                            type: 'line',
                            data: this.chartData.datasets[0].data.map(item => item.total),
                            smooth: true,
                            lineStyle: {
                                color: '#91CC75'
                            }
                        }
                    ],
                    aria: {
                        enabled: false,
                        decal: {
                            show: false
                        }
                    }
                };
            }
        }
    };
</script>
<style scoped>
</style>

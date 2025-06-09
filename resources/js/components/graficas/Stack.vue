<template>
    <v-chart :option="chartOptions" style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></v-chart>
</template>

<script>
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart } from 'echarts/charts';
    import { TitleComponent, TooltipComponent, GridComponent, AxisPointerComponent, LegendComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        TitleComponent,
        TooltipComponent,
        GridComponent,
        AxisPointerComponent,
        LegendComponent
    ]);

    export default {
        components: {
            'v-chart': ECharts
        },
        props: {
            keysOccidente: {
                type: Array,
                required: true
            },
            valuesOccidente: {
                type: Array,
                required: true
            },
            keysOriente: {
                type: Array,
                required: true
            },
            valuesOriente: {
                type: Array,
                required: true
            },
            keysValles: {
                type: Array,
                required: true
            },
            valuesValles: {
                type: Array,
                required: true
            }
        },
        computed: {
            chartOptions() {
                const series = this.keysOccidente.map((key, index) => ({
                    name: key,
                    type: 'bar',
                    stack: 'total',
                    label: { show: true },
                    emphasis: { focus: 'series' },
                    data: [
                        this.valuesOccidente[index],
                        this.valuesOriente[index],
                        this.valuesValles[index]
                    ]
                }));

                return {
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    toolbox: {
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
                    legend: {
                        data: this.keysOccidente
                    },
                    grid: {
                        left: '3%',
                        right: '10%',
                        bottom: '3%',
                        containLabel: true
                    },
                    showBackground: true,
                    backgroundStyle: {
                        color: 'rgba(180, 180, 180, 0.2)'
                    },
                    xAxis: {
                        type: 'value'
                    },
                    yAxis: {
                        type: 'category',
                        data: ['Occidente', 'Oriente', 'Valles']
                    },
                    series: series
                };
            }
        }
    };
</script>

<style scoped>
</style>

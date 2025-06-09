<template>
    <v-chart :option="chartOptions" style="height: 550px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></v-chart>
</template>

<script>
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { PieChart } from 'echarts/charts';
    import { TitleComponent, TooltipComponent, LegendComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        PieChart,
        TitleComponent,
        TooltipComponent,
        LegendComponent
    ]);

    export default {
    components: {
        'v-chart': ECharts
    },
    props: {
        data: {
            type: Array,
            required: true
        },
        options: {
            type: Object,
            required: false,
            default: () => ({})
        }
    },
    computed: {
        chartOptions() {
        return {
            title: {
                text: 'TOTAL DE TRAMITES POR DEPARTAMENTOS',
                left: 'center',
                backgroundColor: '#f5f5f5',
                padding: [10, 20],
                textStyle: {
                    fontSize: 16,
                    fontWeight: 'bold'
                },
                ...this.options.title
            },
            tooltip: {
                trigger: 'item',
                ...this.options.tooltip
            },
            legend: {
                orient: 'horizontal',
                top: 'bottom',
                ...this.options.legend
            },
            toolbox: {
            orient: 'vertical',
            right: 10,
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
            series: [
            {
                name: this.options.seriesName || 'Data',
                type: 'pie',
                radius: ['30%', '60%'], 
                data: this.data,
                itemStyle: {
                color: (params) => {
                    const colors = ['#FFC2F5', '#57DADF', '#A488BD', '#FF9404', '#A06C0C', '#F6ABBD', '#98D85B', '#FF685C', '#EA7CCC'];//colores por departamentos
                    return colors[params.dataIndex % colors.length];
                }
                },
                label: {
                    show: true,
                    position: 'outside',
                    formatter: '{b}: {c} ({d}%)',
                    fontSize: 12,
                    fontWeight: 'bold',
                    color: '#000000'
                },
                labelLine: {
                    show: true
                },
                emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
                },
                ...this.options.series
            }
            ]
        };
        }
    }
    };
</script>

<style scoped>
</style>

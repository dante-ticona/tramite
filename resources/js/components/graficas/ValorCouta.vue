<template>
    <v-chart :option="chartOptions" style="height: 500px; width: 100%; border: 1px solid #ddd; border-radius: 10px; padding: 10px;"></v-chart>
</template>

<script>
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart } from 'echarts/charts';
    import { GridComponent, TooltipComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        GridComponent,
        TooltipComponent
    ]);

    export default {
    components: {
        'v-chart': ECharts
    },
    props: {
        totalTramitesOccidentePendientes: {
            type: Number,
            required: true
        },
        totalTramitesOrientePendientes: {
            type: Number,
            required: true
        },
        totalTramitesVallesPendientes: {
            type: Number,
            required: true
        },
        diferenciaOccidenteAyer: {
            type: Number,
            required: true
        },
        diferenciaOrienteAyer: {
            type: Number,
            required: true
        },
        diferenciaVallesAyer: {
            type: Number,
            required: true
        }
    },
    computed: {
        chartOptions() {
        return {
            title: {
            text: 'PENDIENTES DE VALIDACIÓN\nPOR PRESTACIÓN DE VALOR DE COUTA',
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
                orient: 'vertical',
                right: '10px',
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
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
            {
                type: 'category',
                data: ['Occidente','Dif. dia anterior Occidente', 'Oriente','Dif. dia anterior Oriente', 'Valles','Dif. dia anterior Valles'],
                axisTick: {
                    alignWithLabel: true
                },
                axisLabel: {
                    rotate: 45
                }
            }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
            {
                name: 'Cantidad',
                type: 'bar',
                barWidth: '60%',
                data: [
                    this.totalTramitesOccidentePendientes,
                    this.diferenciaOccidenteAyer,
                    this.totalTramitesOrientePendientes,
                    this.diferenciaOrienteAyer,
                    this.totalTramitesVallesPendientes,
                    this.diferenciaVallesAyer
                ],
                itemStyle: {
                    color: (params) => {
                        const colors = ['#5470C6', '#91CC75', '#FAC858', '#EE6666', '#73C0DE', '#3BA272'];
                        return colors[params.dataIndex % colors.length];
                    }
                },
                showBackground: true,
                backgroundStyle: {
                    color: 'rgba(180, 180, 180, 0.2)'
                },
                label: {
                    show: true,
                    position: 'inside',
                    formatter: '{c}',
                    fontWeight: 'bold',
                    color: '#FFFFFF'
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
</style>

<!-- BarChartComponent.vue -->
<template>
    <v-chart :option="chartOptions" style="height: 500px; width: 100%;"></v-chart>
</template>

<script>
    import * as echarts from 'echarts';
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart } from 'echarts/charts';
    import { TitleComponent, TooltipComponent, GridComponent, AxisPointerComponent, LegendComponent, ToolboxComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        TitleComponent,
        TooltipComponent,
        GridComponent,
        AxisPointerComponent,
        LegendComponent,
        ToolboxComponent
    ]);

    export default {
    components: {
        'v-chart': ECharts
    },
    data() {
        return {
        chartOptions: {
            title: {
             left: 'center'
            },
            tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
            },
            legend: {
                data: ['Forest', 'Steppe', 'Desert', 'Wetland']
            },
            toolbox: {
                show: true,
                orient: 'vertical',
                left: 'right',
                top: 'center',
                feature: {
                    mark: { show: true },
                    dataView: { show: true, readOnly: false },
                    magicType: { show: true, type: ['line', 'bar', 'stack'] },
                    restore: { show: true },
                    saveAsImage: { show: true }
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'category',
                data: ['2012', '2013', '2014', '2015', '2016', '2017', '2018','2019','2020','2021'],
                axisLabel: {
                    rotate: 45,
                    interval: 0
                }
            },
            yAxis: {
                type: 'value'
            },
            series: [
            {
                name: 'Forest',
                type: 'bar',
                barGap: 0,
                label: {
                    show: true,
                    position: 'insideBottom',
                    distance: 15,
                    align: 'left',
                    verticalAlign: 'middle',
                    rotate: 90,
                    formatter: '{c}  {name|{a}}',
                    fontSize: 16,
                    rich: {
                        name: {}
                    }
                },
                emphasis: {
                focus: 'series'
                },
                data: [320, 332, 301, 334, 390]
            },
            {
                name: 'Steppe',
                type: 'bar',
                label: {
                    show: true,
                    position: 'insideBottom',
                    distance: 15,
                    align: 'left',
                    verticalAlign: 'middle',
                    rotate: 90,
                    formatter: '{c}  {name|{a}}',
                    fontSize: 16,
                    rich: {
                        name: {}
                    }
                },
                emphasis: {
                    focus: 'series'
                },
                data: [220, 182, 191, 234, 290, 220, 182, 191, 234, 290]
            },
            {
                name: 'Desert',
                type: 'bar',
                label: {
                show: true,
                position: 'insideBottom',
                distance: 15,
                align: 'left',
                verticalAlign: 'middle',
                rotate: 90,
                formatter: '{c}  {name|{a}}',
                fontSize: 16,
                rich: {
                    name: {}
                }
                },
                emphasis: {
                    focus: 'series'
                },
                data: [150, 232, 201, 154, 190, 220, 182, 191, 234, 290]
            },
            {
                name: 'Wetland',
                type: 'bar',
                label: {
                    show: true,
                    position: 'insideBottom',
                    distance: 15,
                    align: 'left',
                    verticalAlign: 'middle',
                    rotate: 90,
                    formatter: '{c}  {name|{a}}',
                    fontSize: 16,
                    rich: {
                        name: {}
                    }
                },
                emphasis: {
                    focus: 'series'
                },
                data: [98, 77, 101, 99, 40, 220, 182, 191, 234, 290]
            }
            ]
        }
        };
    }
    };
</script>

<style scoped>
</style>

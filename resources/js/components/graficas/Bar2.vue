<template>
    <v-chart :option="chartOptions" style="height: 500px; width: 100%;"></v-chart>
</template>

<script>
    import * as echarts from 'echarts';
    import { use } from 'echarts/core';
    import { CanvasRenderer } from 'echarts/renderers';
    import { BarChart } from 'echarts/charts';
    import { GridComponent, VisualMapComponent, DatasetComponent, TooltipComponent } from 'echarts/components';
    import ECharts from 'vue-echarts';

    use([
        CanvasRenderer,
        BarChart,
        GridComponent,
        VisualMapComponent,
        DatasetComponent,
        TooltipComponent
    ]);

    export default {
    components: {
        'v-chart': ECharts
    },
    data() {
        return {
        chartOptions: {
            dataset: {
            source: [
                ['score', 'amount', 'product'],
                [89.3, 58212, 'Matcha Latte'],
                [57.1, 78254, 'Milk Tea'],
                [74.4, 41032, 'Cheese Cocoa'],
                [50.1, 12755, 'Cheese Brownie'],
                [89.7, 20145, 'Matcha Cocoa'],
                [68.1, 79146, 'Tea'],
                [19.6, 91852, 'Orange Juice'],
                [10.6, 101852, 'Lemon Juice'],
                [32.7, 20112, 'Walnut Brownie']
            ]
            },
            grid: { containLabel: true },
            xAxis: { name: 'amount' },
            yAxis: { type: 'category' },
            visualMap: {
                orient: 'horizontal',
                left: 'center',
                min: 10,
                max: 100,
                text: ['High Score', 'Low Score'],
                dimension: 0,
                inRange: {
                    color: ['#65B581', '#FFCE34', '#FD665F']
                }
            },
            series: [
            {
                type: 'bar',
                encode: {
                    x: 'amount',
                    y: 'product'
                }
            }
            ]
        }
        };
    }
    };
</script>

<style scoped>
</style>

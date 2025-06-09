<template>
    <div ref="chart" style="width: 600px; height: 400px;"></div>
</template>

<script>
    import * as echarts from 'echarts';

    export default {
        name: 'Torta',
        data() {
            return {
                option: {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b} : {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        data: [
                            'Direct Access',
                            'Email Marketing',
                            'Affiliate Ads',
                            'Video Ads',
                            'Search Engines'
                        ]
                    },
                    series: [
                        {
                            name: 'Access Source',
                            type: 'pie',
                            radius: '45%',
                            center: ['50%', '60%'],
                            data: [
                                { value: 335, name: 'Direct Access' },
                                { value: 310, name: 'Email Marketing' },
                                { value: 234, name: 'Affiliate Ads' },
                                { value: 135, name: 'Video Ads' },
                                { value: 1548, name: 'Search Engines' }
                            ],
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                },
                currentIndex: -1,
                myChart: null
            };
        },
        mounted() {
            this.myChart = echarts.init(this.$refs.chart);
            this.myChart.setOption(this.option);

            setInterval(() => {
                const dataLen = this.option.series[0].data.length;
                this.myChart.dispatchAction({
                    type: 'downplay',
                    seriesIndex: 0,
                    dataIndex: this.currentIndex
                });
                this.currentIndex = (this.currentIndex + 1) % dataLen;
                this.myChart.dispatchAction({
                    type: 'highlight',
                    seriesIndex: 0,
                    dataIndex: this.currentIndex
                });
                this.myChart.dispatchAction({
                    type: 'showTip',
                    seriesIndex: 0,
                    dataIndex: this.currentIndex
                });
            }, 1000);
        }
    };
</script>

<style scoped>
</style>
<template>
    <div id="app">
        <!--h3>Resultados</h3>
        <vue-pivottable :data="pivotData" aggregatorName='Sum' rendererName='Table Heatmap' :rows="['Genero']"
            :cols="['Mercado']" :vals="['Aporte']">
        </vue-pivottable-->
        <h3>KPI</h3>
        <vue-pivottable-ui :data="pivotData" 
        :locales="locales"
        :locale="locale"
        :renderers="renderers"
            :localeStrings="localeStrings['es']" :rows="['Proceso', 'Estado']" :cols="[]" :vals="['Aporte']">
        </vue-pivottable-ui>
    </div>
</template>

<script>
    import { VuePivottable, VuePivottableUi } from 'vue-pivottable'
    import 'vue-pivottable/dist/vue-pivottable.css'

    export default {
        components: {
            //VuePivottable,
            VuePivottableUi
        },

        data() {
            return {
                pivotData: [],
                locale: "en",
                localeStrings: {
                    es: {
                        renderError: 'An error occurred rendering the PivotTable results.',
                        computeError: 'An error occurred computing the PivotTable results.',
                        uiRenderError: 'An error occurred rendering the PivotTable UI.',
                        selectAll: 'Select All',
                        selectNone: 'Select None',
                        tooMany: 'too many values to show',
                        filterResults: 'Filter values',
                        totals: 'Totals',

                        only: 'only',
                        rendererNames: {
                            'Table': 'Tabla',
                            'Table Heatmap': 'Tabla de Calor',
                            'Table Col Heatmap': 'Tabla Columna Calor',
                            'Table Row Heatmap': 'Tabla Fila Calor',
                            'Export Table TSV': 'Exportar a Tabla TSV',
                            'Grouped Column Chart': 'Grouped Column Chart',
                            'Stacked Column Chart': 'Stacked Column Chart',
                            'Grouped Bar Chart': 'Grouped Bar Chart',
                            'Stacked Bar Chart': 'Stacked Bar Chart',
                            'Line Chart': 'Line Chart',
                            'Dot Chart': 'Dot Chart',
                            'Area Chart': 'Area Chart',
                            'Scatter Chart': 'Scatter Chart',
                            'Multiple Pie Chart': 'Multiple Pie Chart'
                        },
                        aggregatorMap: {
                            Count: 'Contar',
                            'Count Unique Values': 'Count Unique Values',
                            'List Unique Values': 'List Unique Values',
                            Sum: 'Sumar',
                            'Integer Sum': 'Suma Entera',
                            Average: 'Promedio',
                            Median: 'Mediana',
                            'Sample Variance': 'Sample Variance',
                            'Sample Standard Deviation': 'Sample Standard Deviation',
                            Minimum: 'Minimum',
                            Maximum: 'Maximum',
                            First: 'First',
                            Last: 'Last',
                            'Sum over Sum': 'Sum over Sum',
                            'Sum as Fraction of Total': 'Sum as Fraction of Total',
                            'Sum as Fraction of Rows': 'Sum as Fraction of Rows',
                            'Sum as Fraction of Columns': 'Sum as Fraction of Columns',
                            'Count as Fraction of Total': 'Count as Fraction of Total',
                            'Count as Fraction of Rows': 'Count as Fraction of Rows',
                            'Count as Fraction of Columns': 'Count as Fraction of Columns'
                        }
                    },
                },
            }
        },

        mounted() {
            console.log('Componente instalado.');
            this.listarCasos();
        },

        methods: {
            listarCasos() {
                let that = this;
                let url = "api/dashboard";
                axios.get(url).then(response => {
                    this.primer_act_id = 0;
                    this.pivotData = response.data.data; //twice data
                    /*
                    this.pivotData.forEach(function (row) {
                        row.act_data = JSON.parse(row.act_data);
                    });*/
                    console.log("Casos: ", this.registros);
                });
            },
        }
    }
</script>

<style>
    .main {
        max-width: 980px;
        margin: 8vh auto 20px;
    }

    .title {
        text-align: center;
        margin-bottom: 20px;
    }

    h1 {
        margin-bottom: 0px;
    }

    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
    }

    pre {
        text-align: left;
        background-color: #f8f8f8;
        padding: 1.2em 1.4em;
        line-height: 1.5em;
        margin: 60px 0 0;
        overflow: auto;
    }

    code {
        padding: 0;
        margin: 0;
    }

    footer {
        text-align: center;
        margin-top: 40px;
        line-height: 2;
    }
</style>
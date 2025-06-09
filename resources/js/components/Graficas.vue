<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="tab-content">
                            <div>
                                <div class="col-md-4 text-right">
                                    <div class="d-flex justify-content-between">
                                        <router-link to="/indicadores">
                                            <button type="button" class="btn btn-outline-info btn-flat pulseBtn">
                                                <i class="fa fa-arrow-left"></i> <strong> VOLVER </strong>
                                            </button>
                                        </router-link>
                                        <div class="text-center ml-2">
                                            <span class="badge badge-primary" style="font-size: 1.2rem;">
                                                Mes actual: <strong>{{ mesActual.toUpperCase() }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-body">

                        <div class="tab-content">

                            <div class="content">
                                <div class="container-fluid">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="card card-success card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="card-title"> <strong> CANTIDAD DE TRAMITES REGISTRADOS POR AGENCIAS </strong></h4>
                                                    </div>

                                                    <div class="d-flex justify-content-end">

                                                        <!-- <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDos">
                                                            <i class="fas fa-filter"></i>
                                                        </button>

                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Actualizar">
                                                            <i class="fas fa-lightbulb"></i>
                                                        </button> -->

                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="registrosXAgencias(1)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>


                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i> Filtros  Cantidad de Tramites Registrados por Agencias</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="applyFilters">
                                                                        <div class="form-group">
                                                                            <label for="startDate">Fecha Inicio</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_inicio" class="form-control" id="startDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="endDate">Fecha Fin</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_fin" class="form-control" id="endDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                                    </form>
                                                                    <form @submit.prevent="submitForm">
                                                                        <hr>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-retweet" aria-hidden="true"></i>
                                                                            Generar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <Agencias :chartData="barChartAgencias" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card card-success card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="card-title"> <strong> CANTIDAD DE TRAMITES REGISTRADOS POR MES </strong> </h4>
                                                    </div>

                                                    <!-- FiltroMES -->

                                                    <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterMes">
                                                        <i class="fas fa-filter"></i>
                                                    </button>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="registrosXMes(1)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>

                                                    <div v-if="this.filtro.filtros.cas_gestion">
                                                        <p> <strong> Filtro de Gestión </strong> </p>
                                                        <span class="badge badge-success" style="font-size: 1.2em;">
                                                            <i class="fas fa-calendar"></i> {{ this.filtro.filtros.cas_gestion }}
                                                        </span>
                                                    </div>

                                                    <div v-else>
                                                        <p> <strong> Filtro de Gestión </strong> </p>
                                                        <span class="badge badge-success" style="font-size: 1.2em;">
                                                            <i class="fas fa-calendar"></i> {{ new Date().getFullYear() }}
                                                        </span>
                                                    </div>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterMes" tabindex="-1" role="dialog" aria-labelledby="filterMes" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterMes">
                                                                        <i class="fas fa-filter"></i> Filtro por Gestiones</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="filtroPorGestion">
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <Meses :chartData="barChartPorMes" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card card-success card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h3 class="card-title"> <strong> CANTIDAD DE TRÁMITES REGISTRADOS POR REGIONALES </strong> </h3>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <!-- <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDos">
                                                            <i class="fas fa-filter"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Actualizar">
                                                            <i class="fas fa-lightbulb"></i>
                                                        </button> -->

                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="registrosXRegionales(1)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i> Filtros  Cantidad de Tramites Registrados por Regionales</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="applyFilters">
                                                                        <div class="form-group">
                                                                            <label for="startDate">Fecha Inicio</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_inicio" class="form-control" id="startDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="endDate">Fecha Fin</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_fin" class="form-control" id="endDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                                    </form>
                                                                    <form @submit.prevent="submitForm">
                                                                        <hr>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-retweet" aria-hidden="true"></i>
                                                                            Generar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <Regionales :chartData="barChartRegionales" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card card-success card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h3 class="card-title"> <strong> CANTIDAD DE TRÁMITES PENDIENTES POR BANDEJA </strong> </h3>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <!-- <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDos">
                                                            <i class="fas fa-filter"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Actualizar">
                                                            <i class="fas fa-lightbulb"></i>
                                                        </button> -->
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="estadoBandejaPendientes(1)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i> Filtros  Cantidad de Trámites Registrados por Regionales</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="applyFilters">
                                                                        <div class="form-group">
                                                                            <label for="startDate">Fecha Inicio</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_inicio" class="form-control" id="startDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="endDate">Fecha Fin</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_fin" class="form-control" id="endDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                                    </form>
                                                                    <form @submit.prevent="submitForm">
                                                                        <hr>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-retweet" aria-hidden="true"></i>
                                                                            Generar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <chart-pendiente :chartData="barChartData2"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="card card-success card-outline">
                                                <div class="card-header border-0">
                                                    <div class="d-flex justify-content-between">
                                                        <h4 class="card-title"> <strong> ESTADO DE PROCESOS POR TRÁMITE </strong> </h4>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <!-- <button type="button" class="btn btn-primary mb-3 mr-2" data-toggle="modal" data-target="#filterDos">
                                                            <i class="fas fa-filter"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle pulseBtn mr-2" style="width: 40px; height: 40px;" data-toggle="modal" data-target="#alertaDepartamentosModal" title="Actualizar">
                                                            <i class="fas fa-lightbulb"></i>
                                                        </button> -->
                                                        <button type="button" class="btn btn-success btn-sm mb-3 rounded-circle" style="width: 40px; height: 40px;" @click="estadoProcesosTramites(1)" title="Actualizar">
                                                            <i class="fas fa-retweet"></i>
                                                        </button>
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="filterDos" tabindex="-1" role="dialog" aria-labelledby="filterDosLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="filterDosLabel">
                                                                        <i class="fas fa-filter"></i> Filtros estados de proceso por Trámite</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form @submit.prevent="applyFilters">
                                                                        <div class="form-group">
                                                                            <label for="startDate">Fecha Inicio</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_inicio" class="form-control" id="startDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="endDate">Fecha Fin</label>
                                                                            <input type="date" v-model="filtro.filtros.fecha_fin" class="form-control" id="endDate">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="gestion">Gestión</label>
                                                                            <select v-model="filtro.filtros.cas_gestion" class="form-control">
                                                                                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                                                                            </select>
                                                                        </div>
                                                                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                                                    </form>
                                                                    <form @submit.prevent="submitForm">
                                                                        <hr>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fa fa-retweet" aria-hidden="true"></i>
                                                                            Generar
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex">
                                                        <estado-procesos-tram :chartData="barChartDataEstadoProcesosTram"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    import coutaChart from './graficas/ValorCouta.vue'
    import chartPendiente from './graficas/ChartPendiente.vue'
    import LoaderGraficos from './graficas/LoaderGraficos.vue'
    import Agencias from './graficas/Agencias.vue'
    import Meses from './graficas/Meses.vue'
    import torta from './graficas/Torta.vue'
    import Regionales from './graficas/Regionales.vue'
    import estadoProcesosTram from './graficas/EstadoProcesos.vue'

    import InfoDeps from './graficas/informativos/InfoDeps.vue'
    import InfoTramites from './graficas/informativos/InfoTramites.vue'

    export default {
        name: 'TramiteSIP',
        components: {
            coutaChart,
            chartPendiente,
            LoaderGraficos,
            Agencias,
            Meses,
            torta,
            Regionales,
            estadoProcesosTram,

            //modales
            InfoDeps,
            InfoTramites
        },
    data() {
        return {
        isLoading: false,
        usrUser: window.Laravel.usr_user,

        totalTramitesHoy: 0,
        totalTramitesOccidente: 0,
        totalTramitesOriente: 0,
        totalTramitesValles: 0,
        showChatBubble: false,
            lapaz : 0,
            beni : 0,
            pando : 0,
            oruro : 0,
            cochabamba : 0,
            santaCruz : 0,
            potosi : 0,
            chuquisaca : 0,
            tarija : 0,

        pieDepData: [],

        TotalUsers : 0,
        TotalRegional : 0,
        TotalAgencia : 0,

        totalTramitesOccidentePendientes:0,
        totalTramitesOrientePendientes:0,
        totalTramitesVallesPendientes:0,

        estadoAvance : [],
        selectedDate: '',

        dataEstadoAvanceOccidente: [],
        dataEstadoAvanceOriente: [],
        dataEstadoAvanceValles: [],

        keysOccidente: [],
        valuesOccidente: [],

        keysOriente: [],
        valuesOriente: [],

        keysValles: [],
        valuesValles: [],

        selectAll: false,
        years: (() => {
            const currentYear = new Date().getFullYear();
            const startYear = 2024;
            const yearsArray = [];
            for (let year = startYear; year <= currentYear; year++) {
                yearsArray.push(year);
            }
            return yearsArray;
        })(),
        FechaHora: '',
        procesos: [],
        selectedProcesos: [],
        chartOptions: {},


        filtroFechaHoy: '',
        filtroFechaInicial: '',
        filtroFechaFinal: '',

        //filtros para depts.
        filtroFechaHoyDep : '',
        filtroFechaInicialDep : '',
        filtroFechaFinalDep : '',

        listUltimosTramites : [],

        filtro: { prc_codigo: '', cas_nro_caso: '', cas_tipo: '', fecha_ini: this.fechaIni, fecha_fin: '',
        filtros: { fecha_inicio: '', fecha_fin: '', gestion: '' },
        id_departamento: '', id_agencia: '', id_regional: '', id_area: '' },
        barChartData: {
            labels: [],
            datasets: [
            {
                label: 'Graficas 1',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartData2: {
            labels: [],
            datasets: [
            {
                label: 'Graficas 2',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartAgencias: {
            labels: [],
            datasets: [
            {
                label: 'Agencias',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartPorMes: {
            labels: [],
            datasets: [
            {
                label: 'Registros Por Mes',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartRegionales: {
            labels: [],
            datasets: [
            {
                label: 'Registros Por Regionales',
                backgroundColor: [],
                data: []
            }
            ]
        },

        barChartDataEstadoProcesosTram: {
            labels: [],
            datasets: [
            {
                label: 'Registros Por Regionales',
                backgroundColor: [],
                data: []
            }
            ]
        },

        predefinedProcesos: [
            { prc_id: 4, codigo: 'RMIN', descripcion: 'RETIRO MINIMO - FINAL' , cantidad:0},
            { prc_id: 12, codigo: 'MAHER', descripcion: 'MASA HEREDITARIA', cantidad:0 },
            { prc_id: 3, codigo: 'PM', descripcion: 'PENSIÓN POR MUERTE', cantidad:0 },
            { prc_id: 1, codigo: 'INV', descripcion: 'PENSIÓN POR INVALIDEZ' , cantidad:0},
            { prc_id: 13, codigo: 'PAGCC', descripcion: 'PAGOS DE COMPENSACION DE COTIZACIONES' , cantidad:0},
            { prc_id: 9, codigo: 'JUB', descripcion: 'PENSIÓN POR JUBILACIÓN', cantidad:0 },
            { prc_id: 5, codigo: 'GFU', descripcion: 'GASTOS FUNERARIOS' , cantidad:0},
            { prc_id: 15, codigo: 'JUB1582', descripcion: 'JUBILACIÓN LEY 1582' , cantidad:0},
            ]
        };

    },

    mounted() {
        this.getEstadoAvance();
        this.registrosXAgencias();
        this.registrosXMes();
        this.registrosXRegionales();
        this.estadoBandejaPendientes();
        this.estadoProcesosTramites();
    },

    computed: {
        mesActual() {
            const now = new Date();
            return now.toLocaleString(this.locale, { month: 'long' });
        }
    },

    methods: {
        estadoProcesosTramites(sw) {
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Estado Proceso Actualizado",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            let url = 'api/v1/kpi/estadoProcesosTramite';
            axios.get(url).then(response => {
                console.log("el response data", response.data.data);
                this.procesos = response.data.data;

                const labels = Object.keys(this.procesos);
                const values = Object.values(this.procesos);
                const predefinedColors = [
                    '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1',
                    '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                ];
                const colors = labels.map((_, index) => predefinedColors[index % predefinedColors.length]);

                this.barChartDataEstadoProcesosTram = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'TramiteSIP',
                            backgroundColor: colors,
                            data: values
                        }
                    ]
                };
            });
        },

        getEtapaPreviaCalculo(){
            const cachedData = localStorage.getItem('etapaPreviaCalculo');
            if (cachedData) {
                const data = JSON.parse(cachedData);
                this.totalTramitesOccidentePendientes = data.totalTramitesOccidentePendientes;
                this.totalTramitesOrientePendientes = data.totalTramitesOrientePendientes;
                this.totalTramitesVallesPendientes = data.totalTramitesVallesPendientes;
            } else {
                axios.get('api/v1/kpi/etapaPreviaCalculo')
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        console.log("RESPONSE DE ETAPA PREVIA CALCULO: ", response.data);
                        this.totalTramitesOccidentePendientes = response.data.dataOccidente[0].total_occidente_count;
                        this.totalTramitesOrientePendientes = response.data.dataOriente[0].total_oriente_count;
                        this.totalTramitesVallesPendientes = response.data.dataValles[0].total_valles_count;
                        console.log("totalTramitesOccidentePendientes > ", this.totalTramitesOccidentePendientes);
                        console.log("totalTramitesOrientePendientes > ",this.totalTramitesOrientePendientes);
                        console.log("totalTramitesVallesPendientes > ", this.totalTramitesVallesPendientes);
                        localStorage.setItem('etapaPreviaCalculo', JSON.stringify({
                            totalTramitesOccidentePendientes: this.totalTramitesOccidentePendientes,
                            totalTramitesOrientePendientes: this.totalTramitesOrientePendientes,
                            totalTramitesVallesPendientes: this.totalTramitesVallesPendientes
                        }));
                    } else {
                        console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("error en consumir api", error);
                });
            }
        },

        getEstadoAvance() {
            const cachedData = localStorage.getItem('estadoAvance');
            if (cachedData) {
                const data = JSON.parse(cachedData);
                this.dataEstadoAvanceOccidente = data.dataEstadoAvanceOccidente;
                this.dataEstadoAvanceOriente = data.dataEstadoAvanceOriente;
                this.dataEstadoAvanceValles = data.dataEstadoAvanceValles;
                this.keysOccidente = Object.keys(data.dataEstadoAvanceOccidente[0]);
                this.valuesOccidente = Object.values(data.dataEstadoAvanceOccidente[0]);
                this.keysOriente = Object.keys(data.dataEstadoAvanceOriente[0]);
                this.valuesOriente = Object.values(data.dataEstadoAvanceOriente[0]);
                this.keysValles = Object.keys(data.dataEstadoAvanceValles[0]);
                this.valuesValles = Object.values(data.dataEstadoAvanceValles[0]);
            } else {
                axios.get('api/v1/kpi/estadoAvanceTramite')
                    .then(response => {
                        if (response.data.codigoRespuesta.code === 200) {
                            console.log("RESPONSE DE ESTADO AVANCE: ", response.data);
                            const data = response.data;
                            this.dataEstadoAvanceOccidente = data.dataEstadoAvanceOccidente;
                            this.dataEstadoAvanceOriente = data.dataEstadoAvanceOriente;
                            this.dataEstadoAvanceValles = data.dataEstadoAvanceValles;
                            this.keysOccidente = Object.keys(data.dataEstadoAvanceOccidente[0]);
                            this.valuesOccidente = Object.values(data.dataEstadoAvanceOccidente[0]);
                            this.keysOriente = Object.keys(data.dataEstadoAvanceOriente[0]);
                            this.valuesOriente = Object.values(data.dataEstadoAvanceOriente[0]);
                            this.keysValles = Object.keys(data.dataEstadoAvanceValles[0]);
                            this.valuesValles = Object.values(data.dataEstadoAvanceValles[0]);
                            localStorage.setItem('estadoAvance', JSON.stringify({
                                dataEstadoAvanceOccidente: this.dataEstadoAvanceOccidente,
                                dataEstadoAvanceOriente: this.dataEstadoAvanceOriente,
                                dataEstadoAvanceValles: this.dataEstadoAvanceValles
                            }));
                        } else {
                            console.error("Error en la respuesta api", response.data.codigoRespuesta.mensaje);
                        }
                    })
                    .catch(error => {
                        console.error("error en consumir api", error);
                    });
            }
        },

        registrosXAgencias(sw){
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Registros Agencias Actualizados",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            let url = "api/v1/kpi/registrosPorAgencia";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
                const labels = Object.keys(response.data.data);
                const values = Object.values(response.data.data);

                const colors = labels.map(() => {
                    const predefinedColors = [
                    '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                    ];
                    return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                });

                this.barChartAgencias = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'REGISTROS POR AGENCIA',
                            backgroundColor: colors,
                            data: values
                        }
                    ]
                };

            } else {
                console.error("Error en la respuesta del API", response.data);
            }
            }).catch(error => {
                console.error("Error API", error);
            });
        },

        registrosXMes(sw){
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Registros Mes Actualizados ......",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            const selectedYear = this.filtro.filtros.cas_gestion;
            console.log("selectedYear: ", selectedYear);

            axios.get('api/v1/kpi/cantidadRegistrosPorMes', { params: { gestion: selectedYear } })
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        const labels = Object.keys(response.data.data);
                        const values = Object.values(response.data.data);

                        const colors = labels.map(() => {
                            const predefinedColors = [
                                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                            ];
                            return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                        });

                        this.barChartPorMes = {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Registros por Mes',
                                    backgroundColor: colors,
                                    data: values
                                }
                            ]
                        };

                    } else {
                        console.error("Error en la respuesta del API", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("Error al consumir el API", error);
                });
        },

        registrosXRegionales(sw){

            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Registros Regionales Actualizados",
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            let url = "api/v1/kpi/cantidadRegistrosPorRegional";
            axios.get(url).then(response => {
            if (response.data.codigoRespuesta.code === 200) {
            const labels = Object.keys(response.data.data);
            const values = Object.values(response.data.data);

            const colors = labels.map(() => {
                const predefinedColors = [
                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                ];
                return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
            });

            this.barChartRegionales = {
                labels: labels,
                datasets: [
                    {
                        label: 'REGISTROS POR REGIONALES',
                        backgroundColor: colors,
                        data: values
                    }
                ]
            };

                console.log("Registros por Regionales:>>>>  ", this.barChartRegionales);
            } else {
                console.error("Error en la respuesta del API", response.data);
            }
            }).catch(error => {
                console.error("Error API", error);
            });
        },

        estadoBandejaPendientes(sw=0){
            if (sw === 1) {
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "Datos Bandeja Actualizados ...",
                    showConfirmButton: false,
                    timer: 1500
                });
            }
            let url = "api/v1/kpi/casosPendientesBandeja";
            axios.get(url).then(response => {
                console.log("api/v1/kpi/casosPendientesBandeja ", response.data.data);
                this.procesos = response.data.data;

                console.log("procesos: ", this.procesos);

                const labels = Object.keys(this.procesos);
                const values = Object.values(this.procesos);

                const colors = labels.map(() => {
                    const predefinedColors = [
                        '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1',
                        '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                    ];
                    return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                });

                this.predefinedProcesos = labels.map((label, index) => ({
                    codigo: label,
                    descripcion: label,
                    cantidad: values[index]
                }));

                this.barChartData2 = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'TramiteSIP',
                            backgroundColor: colors,
                            data: values
                        }
                    ]
                };
            });
        },

        filtroPorGestion() {
            const selectedYear = this.filtro.filtros.cas_gestion;
            console.log("selectedYear: ", selectedYear);

            axios.get('api/v1/kpi/cantidadRegistrosPorMes', { params: { gestion: selectedYear } })
                .then(response => {
                    if (response.data.codigoRespuesta.code === 200) {
                        const labels = Object.keys(response.data.data);
                        const values = Object.values(response.data.data);

                        const colors = labels.map(() => {
                            const predefinedColors = [
                                '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#007bff', '#6610f2', '#e83e8c'
                            ];
                            return predefinedColors[Math.floor(Math.random() * predefinedColors.length)];
                        });

                        this.barChartPorMes = {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Registros por Mes',
                                    backgroundColor: colors,
                                    data: values
                                }
                            ]
                        };
                    } else {
                        console.error("Error en la respuesta del API", response.data.codigoRespuesta.mensaje);
                    }
                })
                .catch(error => {
                    console.error("Error al consumir el API", error);
                });
        }
    }
    };
</script>

<style scoped>
    .step-box {
        margin: 20px;
        padding: 15px;
        border: 1px solid #ccc;
        display: inline-block;
    }

    .product-img img:hover {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    .pulseBtn {
        background: #0b63bb;
        color: #fff;
        border: 1px solid #0b63bb;
        font-size: 1rem;
        box-shadow: 0 0 0 0 rgba(88, 120, 243, 0.4);
        -moz-animation: pulse 2s infinite;
        -webkit-animation: pulse 2s infinite;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(88, 120, 243, 1);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(88, 120, 243, 0);
        }
        100% {
            box-shadow: 0 0 0 50px rgba(88, 120, 243, 0);
        }
    }

    .departments-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        padding: 1rem;
    }

    .department-card {
        background-color: #f8f9fa;
        border-radius: 8px;
        text-align: center;
        padding: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .department-card .product-img img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        margin-bottom: 0.5rem;
        border-radius: 50%;
    }

    .department-card .product-title {
        font-size: 1rem;
        font-weight: bold;
        color: #343a40;
    }

    .department-card .badge {
        display: inline-block;
        margin-top: 0.5rem;
        font-size: 0.85rem;
    }
</style>

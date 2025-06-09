<template>
    <div class="container-fluid">
        <section class="content">
            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Oriente</h3>
                <div class="card-tools">
                    <div class="btn-group" role="group">
                        <router-link to="/dashboardv2">
                            <button type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-left"></i> Volver
                            </button>
                        </router-link>

                        <button type="button" class="btn btn-success btn-sm" style="margin-left: 10px;">
                            <i class="fas fa-file-export"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                            <div class="info-box-content">
                                <img src="img/departamentos/santa cruz.png" style="width: 20%; height: auto; display: block; margin: 0 auto 5px;" alt="La PAZ">
                                <center> <span class="badge bg-primary text-center">{{ lapaz }}</span>  </center>
                                <span class="info-box-text text-center text-muted">SANTA CRUZ</span>
                            </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                            <div class="info-box-content">
                                <img src="img/departamentos/beni.png" style="width: 20%; height: auto; display: block; margin: 0 auto 5px;" alt="ORURO">
                                <center> <span class="badge bg-primary text-center">{{ oruro }}</span></center>
                                <span class="info-box-text text-center text-muted">BENI</span>
                            </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="info-box bg-light">
                            <div class="info-box-content">
                                <img src="img/departamentos/pando.png" style="width: 20%; height: auto; display: block; margin: 0 auto 5px;" alt="POTOSI">
                                <center> <span class="badge bg-primary text-center">{{ potosi }}</span></center>
                                <span class="info-box-text text-center text-muted">PANDO</span>
                            </div>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div style="display: flex; align-items: center;">
                                    <img src="img/asistente.gif" alt="TramiteSIP" style="width: 50px; height: auto; margin-right: 10px;">
                                    <h4>Listado de Tramites de Occidente</h4>
                                </div>

                                <hr>
                                <div class="navegacion">
                                    <select name="porPagina" @change="listarRegistros" v-model="RegistrosXPagina"
                                        class="selectRegistros">
                                        <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                                    </select>

                                    <div class="select-container">
                                        <button @click="anteriorPagina()" class="btnEAvance"> <i class="fa fa-arrow-left white"
                                                aria-hidden="true"></i> Anterior </button> &nbsp; &nbsp;
                                        <select name="paginacion" @change="listarRegistros()" v-model="PaginaActual"
                                            class="selectRegistros">
                                            <option v-for="pagina in paginas" :key="pagina" :value="pagina">
                                                {{ pagina }}
                                            </option>
                                        </select>
                                        &nbsp; &nbsp; <button @click="siguientePagina()" class="btnEAvance"> Siguiente <i
                                                class="fa fa-arrow-right white" aria-hidden="true"></i></button>
                                    </div>

                                    <div class="buscarEAvance">
                                        <label style="color: white;">Buscar: </label>
                                        <input type="search" v-model="buscarRegistro" @input="bRegistros()"
                                            @keyup.enter="bRegistros" class="selectRegistros" placeholder="Buscar">
                                    </div>
                                </div>

                                <table class="table table-bordered table-striped" style="border: 1px solid #ddd; border-radius: 10px; padding: 10px;">
                                    <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">NODO</th>
                                        <th scope="col">PROCESO ACTIVIDAD</th>
                                        <th scope="col">No. CASO</th>
                                        <th scope="col">CAMPOS CLAVE</th>
                                        <th scope="col">USUARIO</th>
                                        <th scope="col">ESTADO</th>
                                        <th scope="col">HISTORICO</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(r, index) in listadoTramites" :key="r.cas_id">
                                        <td width="3%" scope="row">{{ r.cas_id }}</td>
                                        <td>{{ r.nodo_codigo }}
                                        <span v-if="r.cas_estado === 'A'" class="badge badge-success"><strong>LI</strong></span>
                                        <span v-else-if="r.cas_estado === 'T'" class="badge badge-success"><strong>AS</strong></span>
                                        <span v-else-if="r.cas_estado === 'H'" class="badge badge-success"><strong>AR</strong>
                                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                            <a class="dropdown-item">
                                                <div class="media">
                                                <div class="media-body">
                                                    <p class="text-sm text-muted"><i class="fa fa-angle-right"></i> <strong> Motivo de Archivo: </strong> </p>
                                                    <p class="text-sm"> {{ r.cas_data.cas_motivo_archivo }}</p>
                                                    <hr>
                                                    <p class="text-sm text-muted"><i class="fa fa-angle-right"></i> <strong> Descripción:</strong> </p>
                                                    <p class="text-sm"> {{ r.cas_data.cas_descripcion_archivo }} </p>
                                                </div>
                                                </div>
                                            </a>
                                            </div>
                                        </span>
                                        <span v-else-if="r.cas_estado === 'E'" class="badge badge-success"><strong>E</strong></span>
                                        <span v-else-if="r.cas_estado === 'W'" class="badge badge-success"><strong>W</strong></span>
                                        <span v-else class="badge badge-warning">{{ r.cas_estado }}</span>
                                        </td>
                                        <td>
                                        <span class="badge badge-info">{{ (JSON.parse(r.cas_data)).cas_registrado }}</span> <br>
                                        <strong>{{ JSON.parse(r.cas_data).cas_cod_id }}</strong><br>
                                        <template v-if="JSON.parse(r.cas_data).AS_TIPO_EAP">
                                            <span class="badge" style="background: linear-gradient(45deg, #EDCD4E, #f2dd9b); font-size: 0.7em; padding: 1px 3px; word-break: break-word; white-space: normal; overflow-wrap: break-word;">
                                            <strong class="break-text"> {{ (JSON.parse(r.cas_data)).AS_TIPO_EAP }} </strong>
                                            </span>
                                        </template>
                                        </td>
                                        <td width="1%" scope="row">
                                        <span v-html="JSON.parse(r.cas_data).cas_nombre_caso"></span>
                                        </td>
                                        <td>{{ JSON.parse(r.cas_data).a_usuario }}</td>
                                        <td>
                                        <i class="fas fa-calendar" style="color:#274690"></i> {{ r.cas_registrado.substr(0, 10) }} <br> <i class="fas fa-clock" style="color:#274690"></i> {{ r.cas_registrado.substr(10, 5) }} <br>
                                        <hr>
                                        <i class="fas fa-calendar" style="color:#297373"></i> {{ r.cas_modificado ? r.cas_modificado.substr(0, 10) : '-' }}
                                        <br> <i class="fas fa-clock" style="color:#297373"></i> {{ r.cas_modificado ? r.cas_modificado.substr(10, 5) : '-' }}
                                        </td>
                                        <td>
                                        <button type="button" class="btn btn-primary btn-circle" title="Histórico">
                                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                                        </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                    <h3 class="text-primary"><i class="fas fa-chart-pie"></i> Grafico</h3>
                    <pieChart :data="pieChartData" :options="chartOptions"></pieChart>
                </div>
                </div>
            </div>
            </div>
        </section>
    </div>
</template>

<script>
    import axios from 'axios'
    import pieChart from '../Pie.vue'


    export default {
    name: 'Occidente',
    components: {
        pieChart
    },
    data() {
        return {
            regionales: [],
            listadoTramites: [],
            selectAll: false,
            FechaHora: '',
            usrUser: window.Laravel.usr_user,
            procesos: [],
            selectedProcesos: [],
            chartOptions: {},
            
            filtro: {
                prc_codigo: '',
                cas_nro_caso: '',
                cas_tipo: '',
                fecha_ini: this.fechaIni,
                fecha_fin: '',
                filtros: {
                fecha_inicio: '',
                fecha_fin: '',
                gestion: ''
                },
                id_departamento: '',
                id_agencia: '',
                id_regional: '',
                id_area: ''
            },

            pieChartData: [],

            lapaz : 0,
            oruro : 0,
            potosi : 0,
        };
    },
    mounted() {
        this.updateDateTime();
        this.cantidadDeOccidente();
        setInterval(this.updateDateTime, 1000);

        setTimeout(() => {
            this.loading = false;
        }, 2000);
    },
    methods: {
        updateDateTime() {
            const now = new Date();
            this.FechaHora = now.toLocaleString();
        },
        cantidadDeOccidente() {
        axios.get('api/metricasOccidente')
            .then(response => {
                console.log("response: ", response.data);
                this.regionales = response.data.cantidad;
                this.lapaz = this.regionales.find(item => item.cas_regional === 'LA PAZ')?.total || 0;
                this.oruro = this.regionales.find(item => item.cas_regional === 'ORURO')?.total || 0;
                this.potosi = this.regionales.find(item => item.cas_regional === 'POTOSI')?.total || 0;
                console.log("regionales: ", this.regionales);
                this.updateChartData();
                this.listadoTramites = response.data.data;
                console.log("listadoTramites: ", this.listadoTramites);
            })
            .catch(error => {
            console.error("error....", error);
            });
        },
        updateChartData() {
            this.pieChartData = this.regionales.map(item => ({
                value: item.total,
                name: item.cas_regional
            }));
        }
    }
    };
</script>
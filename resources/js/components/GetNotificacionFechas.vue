<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs custom-tabs" role="tablist">
                    <li class="nav-item" v-for="(tab, index) in tabs" :key="index">
                        <a class="nav-link" :class="{ active: activeTab === index }" href="#"
                            @click="activeTab = index">
                            <i v-if="activeTab === index" class="fa fa-check-square" aria-hidden="true"></i> {{ tab.title }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <!-- Tab 1: Listado -->
                <div v-show="activeTab === 0">
                    <h5>{{ tabs[0].title }}</h5>
                    <table class="table table-bordered table-hover table-striped table-responsive-lg" id="table-listado">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Nro. TRAMITE</th>
                                <th>NOMBRE</th>
                                <th>CUA</th>
                                <th>FECHA FALLECIMIENTO</th>
                                <th>FECHA NOTIFICACIÓN</th>
                                <th>FECHA PUBLICACIÓN</th>
                                <th>DIAS TRANSCURRIDOS</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(n, idx) in tabs[0].data" :key="idx">
                                <td>{{ idx + 1 }}</td>
                                <td>{{ n.cas_cod_id }}</td>
                                <td>
                                    {{ n.as_primer_nombre }} {{ n.as_segundo_nombre }}
                                    {{ n.as_primer_apellido }} {{ n.as_segundo_apellido }}
                                </td>
                                <td>{{ n.as_cua }}</td>
                                <td>{{ n.as_fecha_fallecimiento }}</td>
                                <td>{{ n.fecha_notificacion }}</td>
                                <td>{{ n.fecha_publicacion }}</td>
                                <td>{{ n.dias_transcurridos }}</td>
                                <!-- Aplicar color según el estado -->
                                <td :class="getEstadoClase(n.estado_vencimiento)">
                                    <span class="badge badge-primary"><strong>{{ n.estado_vencimiento }}</strong></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab 2: Acciones -->
                <div v-show="activeTab === 1">
                    <h5>{{ tabs[1].title }}</h5>
                    <table class="table table-bordered table-hover table-striped table-responsive-lg" id="table-listado1">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Nro. TRAMITE</th>
                                <th>NOMBRE</th>
                                <th>CUA</th>
                                <th>FECHA FALLECIMIENTO</th>
                                <th>FECHA NOTIFICACIÓN</th>
                                <th>FECHA PUBLICACIÓN</th>
                                <th>DIAS TRANSCURRIDOS</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(n, idx) in tabs[1].data" :key="idx">
                                <td>{{ idx + 1 }}</td>
                                <td>{{ n.cas_cod_id }}</td>
                                <td>
                                    {{ n.as_primer_nombre }} {{ n.as_segundo_nombre }}
                                    {{ n.as_primer_apellido }} {{ n.as_segundo_apellido }}
                                </td>
                                <td>{{ n.as_cua }}</td>
                                <td>{{ n.as_fecha_fallecimiento }}</td>
                                <td>{{ n.fecha_notificacion }}</td>
                                <td>{{ n.fecha_publicacion }}</td>
                                <td>{{ n.dias_transcurridos }}</td>
                                <!-- Aplicar color según el estado -->
                                <td :class="getEstadoClase(n.estado_vencimiento)">
                                    <span class="badge badge-primary"><strong>{{ n.estado_vencimiento }}</strong></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab 3: Formulario -->
                <div v-show="activeTab === 2">
                    <h5>{{ tabs[2].title }}</h5>
                    <table class="table table-bordered table-hover table-striped table-responsive-lg" id="table-listado2">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Nro. TRAMITE</th>
                                <th>NOMBRE</th>
                                <th>CUA</th>
                                <th>FECHA FALLECIMIENTO</th>
                                <th>FECHA NOTIFICACIÓN</th>
                                <th>FECHA PUBLICACIÓN</th>
                                <th>DIAS TRANSCURRIDOS</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(n, idx) in tabs[2].data" :key="idx">
                                <td>{{ idx + 1 }}</td>
                                <td>{{ n.cas_cod_id }}</td>
                                <td>
                                    {{ n.as_primer_nombre }} {{ n.as_segundo_nombre }}
                                    {{ n.as_primer_apellido }} {{ n.as_segundo_apellido }}
                                </td>
                                <td>{{ n.as_cua }}</td>
                                <td>{{ n.as_fecha_fallecimiento }}</td>
                                <td>{{ n.fecha_notificacion }}</td>
                                <td>{{ n.fecha_publicacion }}</td>
                                <td>{{ n.dias_transcurridos }}</td>
                                <!-- Aplicar color según el estado -->
                                <td :class="getEstadoClase(n.estado_vencimiento)">
                                    <span class="badge badge-primary"><strong>{{ n.estado_vencimiento }}</strong></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Tab 3: Formulario -->
                <div v-show="activeTab === 3">
                    <h5>{{ tabs[3].title }}</h5>
                    <table class="table table-bordered table-hover table-striped table-responsive-lg" id="table-listado3">
                        <thead class="thead-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Nro. TRAMITE</th>
                                <th>NOMBRE</th>
                                <th>CUA</th>
                                <th>FECHA FALLECIMIENTO</th>
                                <th>FECHA NOTIFICACIÓN</th>
                                <th>FECHA PUBLICACIÓN</th>
                                <th>DIAS TRANSCURRIDOS</th>
                                <th>ESTADO</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <tr v-for="(n, idx) in tabs[3].data" :key="idx">
                                <td>{{ idx + 1 }}</td>
                                <td>{{ n.cas_cod_id }}</td>
                                <td>
                                    {{ n.as_primer_nombre }} {{ n.as_segundo_nombre }}
                                    {{ n.as_primer_apellido }} {{ n.as_segundo_apellido }}
                                </td>
                                <td>{{ n.as_cua }}</td>
                                <td>{{ n.as_fecha_fallecimiento }}</td>
                                <td>{{ n.fecha_notificacion }}</td>
                                <td>{{ n.fecha_publicacion }}</td>
                                <td>{{ n.dias_transcurridos }}</td>
                                <!-- Aplicar color según el estado -->
                                <td :class="getEstadoClase(n.estado_vencimiento)">
                                    <span class="badge badge-primary"><strong>{{ n.estado_vencimiento }}</strong></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    name: 'notificacionFechas',

    data() {
        return {
            tabs: [
                { title: 'PENSIÓN POR MUERTE', data: [] },
                { title: 'PUBLICACIÓN PENSIÓN POR MUERTE', data: [] },
                { title: 'PENSIÓN POR INVALIDEZ', data: [] },
                { title: 'PUBLICACIÓN PENSIÓN POR INVALIDEZ', data: [] }
            ],
            activeTab: 0, // Controla la pestaña activa
            dataTable: null, // Instancia de DataTable
            form: { nombre: '', email: '' },
        };
    },

    mounted() {
        this.cargarListado();
    },

    watch: {
        activeTab(newTab) {
            if (newTab === 0) {
                this.$nextTick(() => this.inicializarDataTable());
            } else if (newTab === 1) {
                this.cargarListadoPM(); // Cargar los datos para el segundo tab
            } else if (newTab === 2) {
                this.cargarListadoINV(); // Cargar los datos para el segundo tab
            } else if (newTab === 3) {
                this.cargarListadoPUINV(); // Cargar los datos para el segundo tab
            }
        },
    },


    methods: {
        cargarListado() {
            axios.post('/api/GetNotificacionFechas')
                .then(response => {
                    this.tabs[0].data = response.data.data;
                    this.$nextTick(() => this.inicializarDataTable());
                })
                .catch(error => {
                    console.error('Error al cargar el listado:', error);
                });
        },
        cargarListadoPM() {
            axios.post('/api/GetFuncionPublicacionPM')
                .then(response => {
                    this.tabs[1].data = response.data.data;
                    this.$nextTick(() => this.inicializarDataTablePM());
                })
                .catch(error => {
                    console.error('Error al cargar el listado:', error);
                });
        }, cargarListadoINV() {
            axios.post('/api/GetAlertaFechaNotificacionINV')
                .then(response => {
                    this.tabs[2].data = response.data.data;
                    this.$nextTick(() => this.inicializarDataTableINV());
                })
                .catch(error => {
                    console.error('Error al cargar el listado:', error);
                });
        },
        cargarListadoPUINV() {
            axios.post('/api/GetFuncionPublicacionINV')
                .then(response => {
                    this.tabs[3].data = response.data.data;
                    this.$nextTick(() => this.inicializarDataTablePUINV());
                })
                .catch(error => {
                    console.error('Error al cargar el listado:', error);
                });
        },
        inicializarDataTable() {
            if (this.dataTable) {
                this.dataTable.destroy();
            }
            this.dataTable = $('#table-listado').DataTable({
                responsive: true,
                order: [],
                language: {
                    lengthMenu: 'Desplegar _MENU_ registros por página',
                    zeroRecords: 'Sin registros',
                    info: 'Página _PAGE_ de _PAGES_',
                    infoEmpty: 'No existen registros disponibles',
                    infoFiltered: '(filtrados de _MAX_ registros en total)',
                    search: 'Buscar',
                    paginate: {
                        first: 'Primera',
                        last: 'Última',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                },
            });
        },
        inicializarDataTablePM() {

            if (this.dataTable) {
                this.dataTable.destroy();
            }
            this.dataTable = $('#table-listado1').DataTable({
                responsive: true,
                order: [],
                language: {
                    lengthMenu: 'Desplegar _MENU_ registros por página',
                    zeroRecords: 'Sin registros',
                    info: 'Página _PAGE_ de _PAGES_',
                    infoEmpty: 'No existen registros disponibles',
                    infoFiltered: '(filtrados de _MAX_ registros en total)',
                    search: 'Buscar',
                    paginate: {
                        first: 'Primera',
                        last: 'Última',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                },
            });
        }, inicializarDataTableINV() {
            if (this.dataTable) {
                this.dataTable.destroy();
            }
            this.dataTable = $('#table-listado2').DataTable({
                responsive: true,
                order: [],
                language: {
                    lengthMenu: 'Desplegar _MENU_ registros por página',
                    zeroRecords: 'Sin registros',
                    info: 'Página _PAGE_ de _PAGES_',
                    infoEmpty: 'No existen registros disponibles',
                    infoFiltered: '(filtrados de _MAX_ registros en total)',
                    search: 'Buscar',
                    paginate: {
                        first: 'Primera',
                        last: 'Última',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                },
            });
        },
        inicializarDataTablePUINV() {

            if (this.dataTable) {
                this.dataTable.destroy();
            }
            this.dataTable = $('#table-listado3').DataTable({
                responsive: true,
                order: [],
                language: {
                    lengthMenu: 'Desplegar _MENU_ registros por página',
                    zeroRecords: 'Sin registros',
                    info: 'Página _PAGE_ de _PAGES_',
                    infoEmpty: 'No existen registros disponibles',
                    infoFiltered: '(filtrados de _MAX_ registros en total)',
                    search: 'Buscar',
                    paginate: {
                        first: 'Primera',
                        last: 'Última',
                        next: 'Siguiente',
                        previous: 'Anterior',
                    },
                },
            });
        },
        getEstadoClase(estado) {
            return estado === 'Vencido' ? 'text-danger' : 'text-success';
        },

        accionBoton1() {
            alert('Botón 1 presionado');
        },

        accionBoton2() {
            alert('Botón 2 presionado');
        },

        submitFormulario() {
            alert(`Formulario enviado con nombre: ${this.form.nombre} y email: ${this.form.email}`);
            this.form.nombre = '';
            this.form.email = '';
        },
    },

    beforeDestroy() {
        if (this.dataTable) {
            this.dataTable.destroy();
        }
    },
};

</script>

<style scoped>
.nav-tabs .nav-link {
    cursor: pointer;
    color: #495057;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-bottom-color: transparent;
    margin-right: 2px;
    padding: 10px 15px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.nav-tabs .nav-link.active {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.nav-tabs .nav-link:hover {
    background-color: #e9ecef;
}

.table-responsive {
    margin-top: 20px;
}

.table thead th {
    background-color: #343a40;
    color: #fff;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

.table tbody td {
    vertical-align: middle;
}

.text-danger {
    color: red !important;
}

.text-success {
    color: green !important;
}

/* Custom styles for DataTables pagination */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.5em 1em;
    margin-left: 0.5em;
    border: 1px solid #dee2e6;
    background-color: #f8f9fa;
    color: #495057;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #e9ecef;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #007bff;
    color: #fff !important;
    border-color: #007bff;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    cursor: not-allowed;
    background-color: #e9ecef;
    color: #6c757d !important;
}
</style>

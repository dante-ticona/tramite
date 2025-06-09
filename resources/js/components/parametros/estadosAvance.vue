<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <div class="navegacion">
                        <div style="display: flex; align-items: center;">
                            <label for="porPagina" style="margin-right: 10px; color: white;">Registros por
                                Página:
                            </label>
                            <select id="porPagina" name="porPagina" @change="listarRegistros" v-model="RegistrosXPagina"
                                class="selectRegistros">
                                <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                            </select>
                        </div>

                        <div class="select-container">
                            <button @click="anteriorPagina()" @mousedown="marcarPagina(PaginaActual)" class="btnEAvance"
                                :disabled="PaginaActual === 1"> Anterior
                            </button>

                            <button v-for="pagina in paginasVisibles" :key="pagina" @click="cambiarPagina(pagina)"
                                @mousedown="marcarPagina(pagina)" class="btn btn-success"
                                :class="{ 'btnEAvance-active': pagina === PaginaActual }">
                                {{ pagina }}
                            </button>

                            <span v-if="paginasVisibles[paginasVisibles.length - 1] !== paginas">
                                <span style="color: white;">...</span>
                            </span>

                            <button v-if="paginas > paginasVisibles[paginasVisibles.length - 1]"
                                @click="cambiarPagina(paginas)" @mousedown="marcarPagina(paginas)"
                                class="btn btn-success" :class="{ 'btnEAvance-active': paginas === PaginaActual }">
                                {{ paginas }}
                            </button>

                            <button @click="siguientePagina()" class="btnEAvance" :disabled="PaginaActual === paginas">
                                Siguiente </button>
                        </div>

                        <div class="buscarEAvance">
                            <label style="color: white;">Buscar</label>
                            <input type="search" v-model="buscarRegistro" @input="bRegistros" @keyup.enter="bRegistros"
                                class="selectRegistros" placeholder="Buscar">
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">#</th>
                                    <th>
                                        <button type="button" class="btn btn-success" @click="doLimpiar()"
                                            data-toggle="modal" data-target="#modalNuevo">
                                            <i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
                                        </button>
                                    </th>
                                    <th scope="col">CÓDIGO</th>
                                    <th scope="col">DESCRIPCIÓN</th>
                                    <th scope="col">CAT_DESCRIPCIÓN</th>
                                    <th scope="col">PRC_DESCRIPCIÓN</th>
                                    <th scope="col">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(r, index) in registros">
                                    <td width="3%" scope="row">{{ r.est_id }}</td>
                                    <td width="15%" scope="row">
                                        <button type="button" class="btn btn-primary btn-circle btn-xl"
                                            v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEditar">
                                            <i class="fa fa-pen white" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-circle btn-xl"
                                            v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEliminar">
                                            <i class="fa fa-trash white" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                    <td>{{ r.est_codigo }}</td>
                                    <td>{{ r.est_descripcion }}</td>
                                    <td>{{ r.cat_descripcion }}</td>
                                    <td>{{ r.prc_descripcion }}</td>
                                    <td>
                                        <span v-if="r.est_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                        <span v-else-if="r.est_estado !== 'A'" class="badge badge-warning">{{
                                            r.est_estado }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalVer -->
        <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.est_codigo" class="form-control" placeholder="Codigo" disabled>
                            </div>
                            <div class="col-md-12">
                                <label>Descripción</label>
                                <input v-model="registro.est_descripcion" class="form-control" placeholder="Descripcion"
                                    disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Cat_descripción</label>
                                <input v-model="registro.est_cat_id" class="form-control" placeholder="Cat_descripción"
                                    disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Prc_descripción</label>
                                <input v-model="registro.est_prc_id" class="form-control" placeholder="Prc_descripción"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalNuevo -->
        <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{
                                error }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.est_codigo" class="form-control" placeholder="Código">
                                <p v-if="!registro.est_codigo" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-12">
                                <label>Descripción</label>
                                <input v-model="registro.est_descripcion" class="form-control"
                                    placeholder="Descripcion">
                                <p v-if="!registro.est_descripcion" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-12">
                                <label>Área</label>
                                <select v-model="registro.est_cat_id" class="form-control"
                                    placeholder="Descripción de Proceso" @change="listarProcesos()">
                                    <option value="-1" disabled>-- Seleccione Área --</option>
                                    <option v-for="e in descripcionCatalogo" v-bind:value="e.cat_id">
                                        {{ e.cat_codigo }} - {{ e.cat_descripcion }}
                                    </option>
                                </select>
                                <p v-if="registro.est_cat_id == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Solicitud</label>
                                <select v-model="registro.est_prc_id" class="form-control"
                                    placeholder="Descripción de Proceso">
                                    <option value="-1" disabled>-- Seleccione Solicitud --</option>
                                    <option v-for="e in descripcionProceso" v-bind:value="e.prc_id">
                                        {{ e.prc_codigo }} - {{ e.prc_descripcion }}
                                    </option>
                                </select>
                                <p v-if="registro.est_prc_id == '-1'" class="mensaje">Seleccione</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="insertarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEditar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Editar {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
                                    }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.est_codigo" class="form-control" placeholder="Código">
                            </div>
                            <div class="col-md-12">
                                <label>Descripción</label>
                                <input v-model="registro.est_descripcion" class="form-control"
                                    placeholder="Descripción">
                            </div>

                            <div class="col-md-12">
                                <label>Área</label>
                                <select v-model="registro.est_cat_id" class="form-control"
                                    placeholder="Descripción de Catálogo" @change="listarProcesos()">
                                    <option value="-1" disabled>-- Seleccione Área --</option>
                                    <option v-for="e in descripcionCatalogo" v-bind:value="e.cat_id">
                                        {{ e.cat_codigo }} - {{ e.cat_descripcion }}
                                    </option>
                                </select>
                                <p v-if="registro.est_cat_id == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Solicitud</label>
                                <select v-model="registro.est_prc_id" class="form-control"
                                    placeholder="Descripción de Proceso">
                                    <option value="-1" disabled>-- Seleccione Solicitud --</option>
                                    <option v-for="e in descripcionProceso" v-bind:value="e.prc_id">
                                        {{ e.prc_codigo }} - {{ e.prc_descripcion }}
                                    </option>
                                </select>
                                <p v-if="registro.est_prc_id == '-1'" class="mensaje">Seleccione</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>¿ Confirma eliminar el {{ singular }} ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" @click="eliminarRegistro($event)"
                            data-dismiss="modal">Si, eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import datatables from 'datatables';

export default {
    name: 'estadosAvance',

    data() {
        return {
            PaginaActual: 1,
            RegistrosXPagina: 10,
            opcionesRegistrosPorPagina: [10, 15, 20, 25],

            totalRegistros: 0,
            paginas: 0,
            buscarRegistro: '',
            plural: 'Estados de Avance',
            singular: 'Estados de Avance',
            seleccionado: '',
            errores: [],
            registro: {},
            registros: [],
            descripcionCatalogo: [],
            descripcionProceso: [],
            dataTable: null,
        }
    },

    mounted() {
        console.log('Componente instalado.');
        this.listarRegistros();
        this.listarCatalogos();
        this.buscarRegistros;
        this.getTotalRegistros();
    },

    created() {
        this.getTotalRegistros();
    },

    watch: {
        RegistrosXPagina: function (newValue, oldValue) {
            this.cambiarRegistrosPorPagina();
        }
    },

    computed: {
        /* paginasVisibles() {
            const paginasVisibles = [];
            const paginasMostradas = 3;
            const paginaInicial = Math.max(1, this.PaginaActual - Math.floor(paginasMostradas / 2));
            const paginaFinal = Math.min(this.paginas, paginaInicial + paginasMostradas - 1);

            for (let i = paginaInicial; i <= paginaFinal; i++) {
                paginasVisibles.push(i);
            }

            return paginasVisibles; 
        } */

        paginasVisibles() {
            const paginasVisibles = [];
            const paginasMostradas = 10;
            const paginaInicial = Math.max(1, this.PaginaActual - Math.floor(paginasMostradas / 2));
            const paginaFinal = Math.min(this.paginas, paginaInicial + paginasMostradas - 1);
            for (let i = paginaInicial; i <= paginaFinal; i++) {
                if (i <= this.paginas && i >= 1) {
                    paginasVisibles.push(i);
                }
            }
            if (paginaInicial > 1) {
                paginasVisibles.unshift('...');
            }

            if (paginaFinal < this.paginas) {
                paginasVisibles.push('...');
            }

            return paginasVisibles;
        }
    },

    methods: {
        marcarPagina(pagina) {
            this.PaginaActual = pagina;
        },

        getTotalRegistros() {
            axios.get('api/totalRegistros')
                .then(response => {
                    this.totalRegistros = response.data.totalRegistros;
                    this.calcularNumeroPaginas();
                })
                .catch(error => {
                    console.error("Error al obtener el total de registros:", error);
                });
        },

        calcularNumeroPaginas() {
            this.paginas = Math.ceil(this.totalRegistros / this.RegistrosXPagina);
        },

        cambiarPagina(pagina) {
            this.PaginaActual = pagina;
            this.$emit('cambiar-pagina', pagina);
            this.listarRegistros();
        },

        cambiarRegistrosPorPagina() {
            this.calcularNumeroPaginas();
            if (this.PaginaActual > this.paginas) {
                this.PaginaActual = this.paginas;
            }
            this.listarRegistros();
        },

        anteriorPagina() {
            if (this.PaginaActual >= 1) {
                this.PaginaActual--;
                this.listarRegistros();
            }
        },

        siguientePagina() {
            if (this.PaginaActual < this.paginas) {
                this.PaginaActual++;
                this.listarRegistros();
            }
        },

        bRegistros() {
            axios.get(`api/estadosAvance`, {
                params: {
                    search: this.buscarRegistro,
                    RegistrosXPagina: this.RegistrosXPagina,
                    PaginaActual: this.PaginaActual
                }
            })
                .then(response => {
                    this.registros = response.data.data;
                })
                .catch(error => {
                    console.error("Error al Buscar los Datos: ", error)
                })
        },

        listarRegistros() {
            let url = '/api/estadosAvance/' + this.RegistrosXPagina + '/' + this.PaginaActual;

            axios.get(url)
                .then(response => {
                    this.registros = response.data.data;
                    console.log("Estados de Avance: ", this.registros);
                })
                .catch(error => {
                    console.error("Error al obtener los datos:", error);
                });
        },

        listarCatalogos() {
            let url = "/api/catalogos/";
            axios.get(url).then(response => {
                this.descripcionCatalogo = response.data.data; //twice data
            })
                .catch(error => {
                    console.error("Error al obtener los datos:", error);
                });
        },

        listarProcesos() {
            let url = "/api/procesos/" + this.registro.est_cat_id;
            axios.get(url).then(response => {
                this.descripcionProceso = response.data.data; //twice 
                this.descripcionProceso.forEach(function (row) {
                    row.prc_descripcion = JSON.parse(row.prc_data);
                    row.prc_codigo = JSON.parse(row.prc_data).prc_codigo;
                    row.prc_descripcion = JSON.parse(row.prc_data).prc_descripcion;
                });
            })
                .catch(error => {
                    console.error("Error al obtener los datos:", error);
                });
        },

        doVer(index) {
            this.registro = this.registros[index];
        },

        doLimpiar() {
            this.registro = {
                est_descripcion: ''
            };
        },

        insertarRegistro(e) {
            this.errores = [];
            if (this.errores.length === 0) {
                let gRegistro = this.registro;
                gRegistro.est_usr_id = 1;
                let that = this;
                let url = "api/estadosAvance/";
                axios.post(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            } else {
                e.preventDefault()
            }
        },

        actualizarRegistro(e) {
            this.errores = [];
            if (this.errores.length === 0) {
                let gRegistro = this.registro;
                let that = this;
                let url = "api/estadosAvance/" + gRegistro.est_id;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                    })
                    .catch(function (error) {
                        that.output = error;
                    });
            } else {
                e.preventDefault()
            }
        },

        eliminarRegistro() {
            let gRegistro = this.registro;
            gRegistro.est_usr_id = 1;
            let that = this;
            let url = "api/estadosAvance/" + gRegistro.est_id;
            axios.post(url, gRegistro)
                .then(function (response) {
                    that.output = response.data;
                    that.listarRegistros();
                })
                .catch(function (error) {
                    that.output = error;
                });
        },

    },

    beforeUpdate: function () {
        if (this.dataTable) {
            this.dataTable.destroy()
        }
    },
}
</script>

<style>
.btn-circle {
    width: 30px;
    height: 30px;
    padding: 6px 0px;
    border-radius: 15px;
    text-align: center;
    font-size: 12px;
    line-height: 1.42857;
}


.navegacion {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #1a1e23;
}

.selectRegistros {
    padding: 0.5rem 1rem;
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background-color: #fff;
    line-height: 1.5;
    color: #212529;
}

.btnEAvance {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: 400;
    text-align: center;
    line-height: 1.5;
    background-color: #007bff;
    color: #fff;
    user-select: none;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out,
        background-color 0.15s ease-in-out,
        border-color 0.15s ease-in-out,
        box-shadow 0.15s ease-in-out;
}

.select-container {
    display: flex;
    justify-content: center;
    align-items: center;
    align-content: center;
}

.btnEAvance:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #0056b3;
}

.btnEAvance.anterior {
    margin-right: 5px;
}

.btnEAvance.siguiente {
    margin-left: 5px;
}

.btnEAvance-active {
    background-color: #0056b3;
    color: #fff;
    border: 1px solid #0056b3;
}

.select-container {
    display: flex;
    justify-content: center;
    align-items: center;
    align-content: center;
}

@media only screen and (max-width: 768px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 1135px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 486px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}
</style>
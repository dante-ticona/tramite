<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-responsive" id="divTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th>
                                <button type="button" class="btn btn-success" @click="doLimpiar()" data-toggle="modal"
                                    data-target="#modalNuevo">
                                    <i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
                                </button>
                            </th>
                            <th scope="col">CI / NIT</th>
                            <th scope="col">NOMBRES</th>
                            <th scope="col">E-MAIL</th>
                            <th scope="col">TELÉFONOS</th>
                            <th scope="col">TIPO CLIENTE</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.cli_id }}</td>
                            <td width="15%" scope="row">
                                <button type="button" class="btn btn-primary btn-circle" v-on:click="doVer( index )"
                                    data-toggle="modal" data-target="#modalVer">
                                    <i class="fa fa-eye white" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-circle btn-xl"
                                    v-on:click="doVer( index )" data-toggle="modal" data-target="#modalEditar">
                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-circle btn-xl"
                                    v-on:click="doVer( index )" data-toggle="modal" data-target="#modalEliminar">
                                    <i class="fa fa-trash white" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                {{ r.cli_data.cli_nit}}
                            </td>
                            <td>
                                {{ r.cli_data.cli_paterno}} {{ r.cli_data.cli_materno}} {{ r.cli_data.cli_nombres}}
                            </td>
                            <td>
                                {{ r.cli_data.cli_correo}}
                            </td>
                            <td>
                                {{ r.cli_data.cli_celular}} <br> {{ r.cli_data.cli_telefono}}
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ r.tcli_descripcion}}</span>
                            </td>
                            <td>
                                <span v-if="r.cli_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.cli_estado !== 'A'" class="badge badge-dark">{{ r.cli_estado}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- modalVer -->
        <div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span
                                    class="badge badge-danger">{{ error }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Tipo Cliemte</label>
                                <select v-model="registro.tcli_id" class="form-control" placeholder="Tipo Cliente"
                                    disabled>
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="s in tiposCliente" v-bind:value="s.tcli_id">
                                        {{ s.tcli_descripcion}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>CI / NIT</label>
                                <input v-model="registro.cli_data.cli_nit" class="form-control" placeholder="CI"
                                    disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Nombres</label>
                                <input v-model="registro.cli_data.cli_nombres" class="form-control"
                                    placeholder="Nombres" disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Paterno</label>
                                <input v-model="registro.cli_data.cli_paterno" class="form-control"
                                    placeholder="Paterno" disabled>
                            </div>
                            <div class="col-md-4">
                                <label>Materno</label>
                                <input v-model="registro.cli_data.cli_materno" class="form-control"
                                    placeholder="Materno" disabled>
                            </div>
                            <div class="col-md-6">
                                <label> Teléfono Celular</label>
                                <input v-model="registro.cli_data.cli_celular" class="form-control"
                                    placeholder="Teléfono" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono Fijo</label>
                                <input v-model="registro.cli_data.cli_telefono" class="form-control"
                                    placeholder="Teléfono" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input v-model="registro.cli_data.cli_correo" class="form-control"
                                    placeholder="Comentarios" disabled>
                            </div>
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <textarea v-model="registro.cli_data.cli_direccion" class="form-control"
                                    placeholder="Dirección" disabled></textarea>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span
                                    class="badge badge-danger">{{ error }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Tipo Cliemte</label>
                                <select v-model="registro.cli_tcli_id" class="form-control" placeholder="Tipo Cliente">
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="tc in tiposCliente" v-bind:value="tc.tcli_id">
                                        {{ tc.tcli_descripcion}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>CI / NIT</label>
                                <input v-model="registro.cli_data.cli_nit" class="form-control" placeholder="CI">
                            </div>
                            <div class="col-md-4">
                                <label>Nombres</label>
                                <input v-model="registro.cli_data.cli_nombres" class="form-control"
                                    placeholder="Nombres">
                            </div>
                            <div class="col-md-4">
                                <label>Paterno</label>
                                <input v-model="registro.cli_data.cli_paterno" class="form-control"
                                    placeholder="Paterno">
                            </div>
                            <div class="col-md-4">
                                <label>Materno</label>
                                <input v-model="registro.cli_data.cli_materno" class="form-control"
                                    placeholder="Materno">
                            </div>
                            <div class="col-md-6">
                                <label> Teléfono Celular</label>
                                <input v-model="registro.cli_data.cli_celular" class="form-control"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono Fijo</label>
                                <input v-model="registro.cli_data.cli_telefono" class="form-control"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input v-model="registro.cli_data.cli_correo" class="form-control"
                                    placeholder="Comentarios">
                            </div>
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <textarea v-model="registro.cli_data.cli_direccion" class="form-control"
                                    placeholder="Dirección"></textarea>
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
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Editar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span
                                    class="badge badge-danger">{{ error }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Tipo Cliemte</label>
                                <select v-model="registro.cli_tcli_id" class="form-control" placeholder="Tipo Cliente">
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="tc in tiposCliente" v-bind:value="tc.tcli_id">
                                        {{ tc.tcli_descripcion}}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>CI / NIT</label>
                                <input v-model="registro.cli_data.cli_nit" class="form-control" placeholder="CI">
                            </div>
                            <div class="col-md-4">
                                <label>Nombres</label>
                                <input v-model="registro.cli_data.cli_nombres" class="form-control"
                                    placeholder="Nombres">
                            </div>
                            <div class="col-md-4">
                                <label>Paterno</label>
                                <input v-model="registro.cli_data.cli_paterno" class="form-control"
                                    placeholder="Paterno">
                            </div>
                            <div class="col-md-4">
                                <label>Materno</label>
                                <input v-model="registro.cli_data.cli_materno" class="form-control"
                                    placeholder="Materno">
                            </div>
                            <div class="col-md-6">
                                <label> Teléfono Celular</label>
                                <input v-model="registro.cli_data.cli_celular" class="form-control"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-md-6">
                                <label>Teléfono Fijo</label>
                                <input v-model="registro.cli_data.cli_telefono" class="form-control"
                                    placeholder="Teléfono">
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input v-model="registro.cli_data.cli_correo" class="form-control"
                                    placeholder="Comentarios">
                            </div>
                            <div class="col-md-6">
                                <label>Dirección</label>
                                <textarea v-model="registro.cli_data.cli_direccion" class="form-control"
                                    placeholder="Dirección"></textarea>
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
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar {{ singular}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
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
        name: 'clientes',
        data() {
            return {
                filtro: {},
                plural: 'Clientes',
                singular: 'Cliente',
                errores: [],
                registro: { cli_data: {} },
                registros: [],
                tiposCliente: [],

                dataTable: null,
            }
        },

        mounted() {
            console.log('Componente instalado.');
            this.listarRegistros();
            this.listarTiposCliente();

            this.dataTable = $('#divTable').DataTable({ responsive: true });
        },

        created() {
        },

        methods: {
            listarRegistros() {
                let url = "api/clientes/";
                axios.get(url).then(response => {
                    this.registros = response.data.data; //twice data
                    this.registros.forEach(function (row) {
                        row.cli_data = JSON.parse(row.cli_data);
                    });
                });
            },

            listarTiposCliente() {
                let url = "api/tiposCliente/";
                axios.get(url).then(response => {
                    this.tiposCliente = response.data.data; //twice data
                });
            },

            doVer(index) {
                this.registro = this.registros[index];
            },

            doLimpiar() {
                this.registro = {
                    cli_tcli_id: null,
                    cli_data: {}
                };
            },

            insertarRegistro(e) {
                this.errores = [];
                /*
                if (!this.registro.trb_data.trb_ci) {
                    this.errores.push('Falta CI');
                }
                if (!this.registro.trb_data.trb_nombres || !this.registro.trb_data.trb_materno) {
                    this.errores.push('Falta Nombres y Materno');
                }
                if (!this.registro.trb_data.trb_direccion) {
                    this.errores.push('Falta Dirección');
                }
                if (!this.registro.trb_data.trb_telefono) {
                    this.errores.push('Falta Teléfono');
                }
                */
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    gRegistro.cli_suc_id = this.filtro.suc_id;
                    gRegistro.cli_usr_id = 1;
                    gRegistro.cli_data = JSON.stringify(gRegistro.cli_data);
                    let that = this;
                    let url = "api/clientes/";
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
                /*
                if (!this.registro.trb_data.trb_ci) {
                    this.errores.push('Falta CI');
                }
                if (!this.registro.trb_data.trb_nombres || !this.registro.trb_data.trb_materno) {
                    this.errores.push('Falta Nombres y Materno');
                }
                if (!this.registro.trb_data.trb_direccion) {
                    this.errores.push('Falta Dirección');
                }
                if (!this.registro.trb_data.trb_telefono) {
                    this.errores.push('Falta Teléfono');
                }
                */
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    console.log("gRegistro: ", gRegistro);
                    gRegistro.cli_usr_id = 1;
                    gRegistro.cli_data = JSON.stringify(gRegistro.cli_data);
                    let that = this;
                    let url = "api/clientes/" + gRegistro.cli_id;
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
                gRegistro.trb_usr_id = 1;

                let that = this;
                let url = "api/clientes/" + gRegistro.cli_id;
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

        updated: function () {
            this.dataTable = $("#divTable").DataTable({ responsive: true })
        }
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

    .combo {
        font-size: 10px;
        height: 25px !important;
    }
</style>
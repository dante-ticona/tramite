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
                            <th scope="col">CORRESPONDENCIA</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.act_id }}</td>
                            <td width="15%" scope="row">
                                <button type="button" class="btn btn-warning btn-circle btn-xl"
                                    v-on:click="doVer(index)" data-toggle="modal" data-target="#modalEditar">
                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-circle btn-xl" v-on:click="doVer(index)"
                                    data-toggle="modal" data-target="#modalEliminar">
                                    <i class="fa fa-trash white" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>
                                {{ r.crr_data.crr_ciudadano_nombres }}
                            </td>
                            <td>
                                {{ r.act_data.act_descripcion }}
                            </td>
                            <td>
                                <span v-if="r.act_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.act_estado !== 'A'" class="badge badge-warning">{{ r.act_estado
                                    }}</span>
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
                        <h5 class="modal-title" id="exampleModalLabel">
                            Ver {{ singular }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Correspondencia</label>
                                <select v-model="registro.act_crr_id" class="form-control" placeholder="Tipo Cliente"
                                    disabled>
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="s in correspondencia" v-bind:value="s.crr_id">
                                        {{ s.crr_data.crr_ciudadano_nombres }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Descripcion</label>
                                <input v-model="registro.act_data.act_descripcion" class="form-control"
                                    placeholder="Descripcion" disabled />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalNuevo  -->
        <div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Nuevo {{ singular }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3">
                                <span class="badge badge-danger">{{ error }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Correspondencia</label>
                                <select v-model="registro.act_crr_id" class="form-control" placeholder="Tipo Cliente">
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="s in correspondencia" v-bind:value="s.crr_id">
                                        {{ s.crr_data.crr_ciudadano_nombres }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Descripcion</label>
                                <input v-model="registro.act_data.act_descripcion" class="form-control"
                                    placeholder="Descripcion" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" @click="insertarRegistro($event)"
                            data-dismiss="modal">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEditar  -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Editar {{ singular }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3">
                                <span class="badge badge-danger">{{ error }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Correspondencia</label>
                                <select v-model="registro.act_crr_id" class="form-control" placeholder="Tipo Cliente">
                                    <option value="null">-- Seleccione Tipo --</option>
                                    <option v-for="s in correspondencia" v-bind:value="s.crr_id">
                                        {{ s.crr_data.crr_ciudadano_nombres }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Descripcion</label>
                                <input v-model="registro.act_data.act_descripcion" class="form-control"
                                    placeholder="Descripcion" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalEliminar  -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Eliminar {{ singular }}
                        </h5>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            No
                        </button>
                        <button type="button" class="btn btn-danger" @click="eliminarRegistro($event)"
                            data-dismiss="modal">
                            Si, eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import datatables from "datatables";

    export default {
        name: "actuaciones",

        data() {
            return {
                plural: "Actuaciones",
                singular: "Actuacion",
                errores: [],
                registro: { act_data: {} },
                registros: [],
                correspondencia: [],
                dataTable: null,
            };
        },

        mounted() {
            console.log("Componente instalado.");
            this.listarRegistros();
            this.dataTable = $("#divTable").DataTable({
                responsive: true,
                order: [[1, "desc"]],
				"language": {
					"lengthMenu": "Desplegar _MENU_ registros por página",
					"zeroRecords": "Sin registros",
					"info": "Página _PAGE_ de _PAGES_",
					"infoEmpty": "No existen registros disponibles",
					"infoFiltered": "(filtrados de _MAX_ registros en total)",

				    "search": "Buscar",
					"paginate": {
    				    "first":      "Primera",
    				    "last":       "Última",
    				    "next":       "Siguiente",
    				    "previous":   "Anterior"
    				},

				}
			 })
        },

        created() { },

        methods: {
            listarRegistros() {
                let url = "api/actuaciones";
                axios.get(url).then((response) => {
                    this.registros = response.data.data; //twice data
                    this.registros.forEach(function (row) {
                        row.act_data = JSON.parse(row.act_data);
                    });
                    console.log("actuaciones: ", this.registros);
                });
            },

            listarCorrespondencia() {
                let url = "api/ceroPapel";
                axios.get(url).then((response) => {
                    this.correspondencia = response.data.data; //twice data
                    this.correspondencia.forEach(function (row) {
                        row.crr_data = JSON.parse(row.crr_data);
                    });
                });
            },

            doVer(index) {
                this.registro = this.registros[index];
            },

            doLimpiar() {
                this.registro = {
                    act_crr_id: null,
                    act_data: {},
                };
            },

            insertarRegistro(e) {
                this.errores = [];
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    gRegistro.cat_usr_id = 1;
                    gRegistro.act_data = JSON.stringify(gRegistro.act_data);
                    let that = this;
                    let url = "api/actuaciones";
                    axios
                        .post(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                }
            },

            actualizarRegistro(e) {
                this.errores = [];
                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    //gRegistro.cat_usr_id = 1;
                    gRegistro.act_data = JSON.stringify(gRegistro.act_data);
                    let that = this;
                    let url = "api/actuaciones/" + gRegistro.act_id;
                    axios
                        .put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                }
            },

            eliminarRegistro() {
                let gRegistro = this.registro;
                gRegistro.cat_usr_id = 1;

                let that = this;
                let url = "api/actuaciones/" + gRegistro.act_id;
                axios
                    .post(url, gRegistro)
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
                this.dataTable.destroy();
            }
        },

        updated: function () {
            this.dataTable = $("#divTable").DataTable({
                responsive: true,
                order: [[1, "desc"]],
            });
        },
    };
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
</style>
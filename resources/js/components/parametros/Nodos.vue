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
                            <th scope="col">CODIGO</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">PADRE</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.nodo_id }}</td>
                            <td width="15%" scope="row">
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
                                {{ r.nodo_codigo}}
                            </td>
                            <td>
                                {{ r.nodo_descripcion}}
                            </td>
                            <td>
                                {{ r.nodo_padre_id}}
                            </td>
                            <td>
                                <span v-if="r.nodo_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.nodo_estado !== 'A'" class="badge badge-warning">{{
                                    r.nodo_estado}}</span>
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
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.nodo_codigo" class="form-control" placeholder="Ingrese Codigo" disabled>
                                <p v-if="!registro.nodo_codigo" class="mensaje">Complete</p>

                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <input v-model="registro.nodo_descripcion" class="form-control" placeholder="Ingrese Descripcion" disabled>
                                <p v-if="!registro.nodo_descripcion" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Nodo Padre</label>
                                <select v-model="registro.nodo_padre_id" class="form-control" 
                                    placeholder="Padre" disabled>
                                    <option value="-1">-- Seleccione Nodos --</option>
                                    <option v-for="s in nodosPadres" v-bind:value="s.nodo_id">
                                        {{ s.nodo_descripcion}} - {{ s.nodo_codigo}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
                                    }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.nodo_codigo" class="form-control" placeholder="Ingrese Codigo">
                                <p v-if="!registro.nodo_codigo" class="mensaje">Complete</p>

                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <input v-model="registro.nodo_descripcion" class="form-control" placeholder="Ingrese Descripcion">
                                <p v-if="!registro.nodo_descripcion" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Nodo Padre</label>
                                <select v-model="registro.nodo_padre_id" class="form-control" 
                                    placeholder="Padre">
                                    <option value="-1" disabled>-- Seleccione Nodos --</option>
                                    <option v-for="s in nodosPadres" v-bind:value="s.nodo_id">
                                        {{ s.nodo_descripcion}} - {{ s.nodo_codigo}}
                                    </option>
                                </select>
        						<p v-if="registro.nodo_padre_id=='-1'" class="mensaje">Seleccione</p>
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

        <!-- modalEditar  -->
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
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
                                    }}</span>
                            </div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-6">
                                <label>Código</label>
                                <input v-model="registro.nodo_codigo" class="form-control" placeholder="Ingrese Codigo">
                                <p v-if="!registro.nodo_codigo" class="mensaje">Complete</p>

                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <input v-model="registro.nodo_descripcion" class="form-control" placeholder="Ingrese Descripcion">
                                <p v-if="!registro.nodo_descripcion" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Nodo Padre</label>
                                <select v-model="registro.nodo_padre_id" class="form-control" 
                                    placeholder="Padre">
                                    <option value="-1" disa>-- Seleccione Nodos --</option>
                                    <option v-for="s in nodosPadres" v-bind:value="s.nodo_id">
                                        {{ s.nodo_descripcion}} - {{ s.nodo_codigo}}
                                    </option>
                                </select>
        						<p v-if="registro.nodo_padre_id=='-1'" class="mensaje">Seleccione</p>
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

        <!-- modalEliminar  -->
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
        name: 'nodos',

        data() {
            return {
                plural: 'Nodos',
                singular: 'Nodo',
                seleccionado: '',
                errores: [],
                registro: {},
                registros: [],
                nodosPadres: [],

                dataTable: null,
            }
        },

        mounted() {
            this.listarRegistros();

            this.dataTable = $('#divTable').DataTable({ responsive: true });
        },

        created() {

        },

        methods: {
            listarRegistros() {
                let url = "api/nodos";
                axios.get(url).then(response => {
                    this.registros = response.data.data; //twice data
                    this.nodosPadres = this.registros;
                });
            },

            doVer(index) {
                this.registro = this.registros[index];
            },

            doLimpiar() {
                this.registro = {
                    nodo_codigo: '',
                    usn_user_id: '',
                    usn_nodo_id: '-1'
                };
            },

            insertarRegistro(e) {
                this.errores = [];
                
                if (this.registro && !this.registro.nodo_codigo) {
                    this.errores.push('Falta Codigo');
                    console.log("error Codigo");
                }
                if (this.registro && !this.registro.nodo_descripcion) {
                    this.errores.push('Falta Descripcion');
                    console.log("error Descripcion");
                }
                if (this.registro && !this.registro.nodo_padre_id) {
                    this.errores.push('Falta Nodo Padre');
                    console.log("error Nodo Padre");
                }
                /*if (this.registro.nodo_padre_id == '-1') {
                    this.errores.push('Falta Nodo Padre');
                }*/

                console.log("Errores >>> ", this.errores);

                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    gRegistro.cat_usr_id = 1;
                    let that = this;
                    let url = "api/nodos";
                    axios.post(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                } else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            actualizarRegistro(e) {
                this.errores = [];
                
                if (this.registro && !this.registro.nodo_codigo) {
                    this.errores.push('Falta Codigo');
                    console.log("error Codigo");
                }
                if (this.registro && !this.registro.nodo_descripcion) {
                    this.errores.push('Falta Descripcion');
                    console.log("error Descripcion");
                }
                if (this.registro && !this.registro.nodo_padre_id) {
                    this.errores.push('Falta Nodo Padre');
                    console.log("error Nodo Padre");
                }
                /*if (this.registro.nodo_padre_id == '-1') {
                    this.errores.push('Falta Nodo Padre');
                }*/

                console.log("Errores >>> ", this.errores);


                if (this.errores.length === 0) {
                    let gRegistro = this.registro;
                    //gRegistro.cat_usr_id = 1;
                    let that = this;
                    let url = "api/nodos/" + gRegistro.nodo_id;
                    axios.put(url, gRegistro)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                        })
                        .catch(function (error) {
                            that.output = error;
                        });
                }  else {
                    e.preventDefault();
                    this.$swal({
                        title: 'Error!',
                        text: 'Corrija errores',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },

            eliminarRegistro() {
                let gRegistro = this.registro;
                gRegistro.cat_usr_id = 1;

                let that = this;
                let url = "api/nodos/" + gRegistro.nodo_id;
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
</style>
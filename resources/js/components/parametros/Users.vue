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
                            <th scope="col">NAME</th>
                            <th scope="col">EMAIL</th>
                            <th scope="col">ROL</th>
                            <th scope="col">CREADO</th>
                            <th scope="col">DEPARTAMENTO - REGIONAL - AGENCIA</th>
                            <th scope="col">ATC</th>
                            <th scope="col">SUPERVISOR</th>
                            <th scope="col">JEFE</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row">{{ r.id }}</td>
                            <td width="3%" scope="row">
                                <button v-if="r.status !== 'X'" type="button" class="btn btn-warning btn-circle"
                                    v-on:click="doVer(index)" title="Editar" data-toggle="modal"
                                    data-target="#modalEditar">
                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                </button>
                                <button v-if="r.status !== 'X'" type="button" class="btn btn-danger btn-circle"
                                    v-on:click="doVer(index)" title="Eliminar" data-toggle="modal"
                                    data-target="#modalEliminar">
                                    <i class="fa fa-trash white" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>{{ r.name }}</td>
                            <td width="5%" scope="row">{{ r.email }}</td>
                            <td>{{ r.description }}</td>
                            <td>{{ r.created_at }}</td>
                            <td width="20%" scope="row">[{{ r.departamento_description }}] - [{{ r.regional_description
                                }}] - [{{
                                    r.agencia_description }}]</td>
                            <td width="10%" scope="row">
                                {{ r.es_atc ? 'Es Atención al Cliente' : 'No Es Atención al Cliente' }}
                            </td>
                            <td>{{ r.es_supervisor ? 'Es Supervisor' : 'No Es Supervisor' }}</td>
                            <td>{{ r.es_jefe ? 'Es Jefe' : 'No Es Jefe' }}</td>
                            <td align="center">
                                <span v-if="r.status === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.status === 'X'" class="badge badge-danger">ELIMINADO</span>
                                <span v-else class="badge badge-warning">{{ r.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                            <div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{ error
                                    }}</span></div>
                        </div>
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Nombres Usuario</label>
                                <input v-model="registro.name"
                                    @input="registro.name = $event.target.value.toUpperCase()" class="form-control"
                                    placeholder="Ingrese Nombre">
                                <p v-if="!registro.name" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                               <!--  <input type="text" v-model="emailInput" class="form-control" @input="formatEmail"
                                    placeholder="Ingrese Email"> -->
                                <input type="text" id:="emailInput" class="form-control" 
                                    placeholder="Ingrese Email" v-model="registro.email" @input="registro.email = $event.target.value.toLowerCase()">
                                <p v-if="!registro.email" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-6">
                                <label>Clave</label>
                                <input type="password" v-model="registro.password" class="form-control"
                                    placeholder="Ingrese Password">
                                <p v-if="!registro.password" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Branch</label>
                                <input v-model="registro.branch_id" class="form-control" placeholder="Ingrese Rama">
                                <p v-if="!registro.branch_id" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Rol</label>
                                <select v-model="registro.role_id" class="form-control" placeholder="Rol">
                                    <option value="-1">-- Seleccione Rol --</option>
                                    <option v-for="s in rol" v-bind:value="s.role_id">
                                        {{ s.role_description }} - {{ s.role_code }}
                                    </option>
                                </select>
                                <p v-if="registro.role_id == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Departamento</label>
                                <select v-model="registro.id_departamento" class="form-control"
                                    placeholder="Departamento" @change="listarRegional()">
                                    <option value="-1">-- Seleccione Departamento --</option>
                                    <option v-for="s in DescDepartamento" v-bind:value="s.id_sip_departamento">
                                        {{ s.descripcion_dep }}
                                    </option>
                                </select>
                                <p v-if="registro.id_departamento == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Regional</label>
                                <select v-model="registro.id_regional" class="form-control" placeholder="Regional"
                                    @change="listarAgencia()">
                                    <option value="-1">-- Seleccione Regional --</option>
                                    <option v-for="s in DescRegionalFiltro" v-bind:value="s.id_sip_regional">
                                        {{ s.descripcion_doc }}
                                    </option>
                                </select>
                                <p v-if="registro.id_regional == '-1'" class="mensaje">Seleccione</p>
                            </div>


                            <div class="col-md-12 mb-4">
                                <label>Agencia</label>
                                <select v-model="registro.id_agencia" class="form-control" placeholder="Agencia">
                                    <option value="-1">-- Seleccione Agencia --</option>
                                    <option v-for="s in DescAgenciaFiltro" v-bind:value="s.id_sip_agencia">
                                        {{ s.descripcion_agencia }}
                                    </option>
                                </select>
                                <p v-if="registro.id_agencia == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <div class="section-container text-center">
                                    <h5>Marque una o todas las opciones</h5>
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-4">
                                            <label>¿Es Atención al Cliente?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="es_atc_checkbox"
                                                    v-model="registro.es_atc">
                                                <label class="form-check-label" for="es_atc_checkbox">Soy ATC</label>
                                            </div>
                                            <p v-if="!registro.es_atc" class="mensaje">Marque (opcional)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>¿Es Supervisor?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    id="es_supervisor_checkbox" v-model="registro.es_supervisor">
                                                <label class="form-check-label" for="es_supervisor_checkbox">Soy
                                                    Supervisor</label>
                                            </div>
                                            <p v-if="!registro.es_supervisor" class="mensaje">Marque (opcional)</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>¿Es Jefe?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="es_jefe_checkbox"
                                                    v-model="registro.es_jefe">
                                                <label class="form-check-label" for="es_jefe_checkbox">Soy Jefe</label>
                                            </div>
                                            <p v-if="!registro.es_jefe" class="mensaje">Marque (opcional)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="insertarRegistro($event)"
                            data-dismiss="modal">Guardar
                        </button>
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
                            <div class="col-md-12">
                                <label>Nombres Usuario</label>
                                <input v-model="registro.name"
                                    @input="registro.name = $event.target.value.toUpperCase()" class="form-control"
                                    placeholder="Ingrese Nombre">
                                <p v-if="!registro.name" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input type="email" v-model="registro.email"
                                    @input="registro.email = $event.target.value.toLowerCase()" class="form-control"
                                    placeholder="Ingrese Email">
                                <p v-if="!registro.email" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Clave</label>
                                <input type="password" v-model="registro.password" class="form-control"
                                    placeholder="Ingrese Password">
                                <p v-if="!registro.password" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Branch</label>
                                <input v-model="registro.branch_id" class="form-control" placeholder="Ingrese Rama">
                                <p v-if="!registro.branch_id" class="mensaje">Complete</p>
                            </div>
                            <div class="col-md-6">
                                <label>Rol</label>
                                <select v-model="registro.role_id" class="form-control" placeholder="Rol">
                                    <option value="null">-- Seleccione Rol --</option>
                                    <option v-for="s in rol" v-bind:value="s.role_id">
                                        {{ s.role_description }} - {{ s.role_code }}
                                    </option>
                                </select>
                                <p v-if="registro.role_id == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Departamento</label>
                                <select v-model="registro.id_departamento" class="form-control"
                                    placeholder="Departamento" @change="listarRegionalE()">
                                    <option value="-1">-- Seleccione Departamento --</option>
                                    <option v-for="s in DescDepartamento" :value="s.id_sip_departamento">
                                        {{ s.descripcion_dep }}
                                    </option>
                                </select>
                                <p v-if="registro.id_departamento == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <label>Regional</label>
                                <select v-model="registro.id_regional" class="form-control" placeholder="Regional"
                                    @change="listarAgenciaE()">
                                    <option value="-1">-- Seleccione Regional --</option>
                                    <option v-for="s in DescRegionalEFiltro" :value="s.id_sip_regional">
                                        {{ s.descripcion_doc }}
                                    </option>
                                </select>
                                <p v-if="registro.id_regional == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label>Agencia</label>
                                <select v-model="registro.id_agencia" class="form-control" placeholder="Agencia">
                                    <option value="-1">-- Seleccione Agencia --</option>
                                    <option v-for="s in DescAgenciaEFiltro" :value="s.id_sip_agencia">
                                        {{ s.descripcion_agencia }}
                                    </option>
                                </select>
                                <p v-if="registro.id_agencia == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12">
                                <div class="section-container text-center">
                                    <h5>Marque una o todas las opciones</h5>
                                    <div class="row justify-content-md-center">
                                        <div class="col-md-4">
                                            <label>¿Es Atención al Cliente?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="es_atc_checkbox"
                                                    v-model="registro.es_atc">
                                                <label class="form-check-label" for="es_atc_checkbox">Soy ATC</label>
                                            </div>
                                            <p v-if="!registro.es_atc" class="mensaje">Marque (opcional)</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label>¿Es Supervisor?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    id="es_supervisor_checkbox" v-model="registro.es_supervisor">
                                                <label class="form-check-label" for="es_supervisor_checkbox">Soy
                                                    Supervisor</label>
                                            </div>
                                            <p v-if="!registro.es_supervisor" class="mensaje">Marque (opcional)</p>
                                        </div>

                                        <div class="col-md-4">
                                            <label>¿Es Jefe?</label>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="es_jefe_checkbox"
                                                    v-model="registro.es_jefe">
                                                <label class="form-check-label" for="es_jefe_checkbox">Soy Jefe</label>
                                            </div>
                                            <p v-if="!registro.es_jefe" class="mensaje">Marque (opcional)</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">Actualizar</button>
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
    name: 'users',

    data() {
        return {
            plural: 'Usuarios',
            singular: 'Usuario',
            seleccionado: '',
            errores: [],
            registro: {},
            registros: [],
            rol: [],
            DescDepartamento: [],
            DescRegional: [],
            DescAgencia: [],
            DescRegionalE: [],
            DescRegionalFiltro: [],
            DescRegionalEFiltro: [],
            DescAgenciaE: [],
            DescAgenciaFiltro: [],
            DescAgenciaEFiltro: [],
            dataTable: null,
        }
    },

    mounted() {
        this.listarRegistros();
        this.listarRoles();
        this.listarDepartamento();
        this.listarRegionalE();
        this.listarAgenciaE();


        this.dataTable = $('#divTable').DataTable({ responsive: true });
    },

    methods: {
       
        listarRegistros() {
            let url = "api/users/";
            axios.get(url).then(response => {
                this.registros = response.data.data;
                console.log(this.registros);
            });
        },

        listarRoles() {
            let url = "api/roles/";
            axios.get(url).then(response => {
                this.rol = response.data.data;
            });
        },

        listarDepartamento() {
            let url = "api/userDepartamento/";
            axios.get(url).then(response => {
                this.DescDepartamento = response.data.data;
            });
        },

        listarRegional() {
            let url = "api/userRegional/";
            axios.get(url).then(response => {
                this.DescRegional = response.data.data;
                this.DescRegionalFiltro = this.DescRegional.filter(region => region.id_sip_departamento === this.registro.id_departamento);
                this.registro.id_regional = -1;
            });
        },

        listarAgencia() {
            let url = "api/userAgencia/";
            axios.get(url).then(response => {
                this.DescAgencia = response.data.data;
                this.DescAgenciaFiltro = this.DescAgencia.filter(agencia => agencia.id_sip_regional === this.registro.id_regional);
                this.registro.id_agencia = -1;
            });
        },

        listarRegionalE() {
            let url = "api/userRegional/";
            axios.get(url).then(response => {
                this.DescRegionalE = response.data.data;
                this.DescRegionalEFiltro = this.DescRegionalE.filter(region => region.id_sip_departamento === this.registro.id_departamento);
                this.registro.id_regional = -1;
            });
        },

        listarAgenciaE() {
            let url = "api/userAgencia/";
            axios.get(url).then(response => {
                this.DescAgenciaE = response.data.data;
                this.DescAgenciaEFiltro = this.DescAgenciaE.filter(agencia => agencia.id_sip_regional === this.registro.id_regional);
                this.registro.id_agencia = -1;
            });
        },



        doVer(index) {
            this.registro = this.registros[index];
        },

        doLimpiar() {
            this.registro = {
                name: '',
                email: '',
                password: '',
                role_id: '-1',
                id_departamento: '-1',
                id_regional: '-1',
                id_agencia: '-1',
                es_atc: false,
                es_supervisor: false,
                emp_id: '-1'
            };
        },

        insertarRegistro(e) {
            this.errores = [];

            if (!this.registro || !this.registro.name) {
                this.errores.push('Falta Nombre de Usuario');
            }
            if (!this.registro || !this.registro.email) {
                this.errores.push('Falta Email');
            }
            if (!this.registro || !this.registro.password) {
                this.errores.push('Falta Clave');
            }
            if (!this.registro || !this.registro.branch_id) {
                this.errores.push('Falta Branch');
            }
            if (this.registro.role_id === '-1') {
                this.errores.push('Falta Rol');
            }
            if (this.registro.id_departamento === '-1') {
                this.errores.push('Falta Departamento');
            }
            if (this.registro.id_regional === '-1') {
                this.errores.push('Falta Regional');
            }
            if (this.registro.id_agencia === '-1') {
                this.errores.push('Falta Agencia');
            }

            if (this.errores.length === 0) {
                let gRegistro = this.registro;
                let that = this;
                let url = "api/users/";
                axios.post(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                        that.$swal({
                            title: 'Registro Exitoso',
                            text: 'El usuario se ha registrado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    })
                    .catch(function (error) {
                        that.output = error;
                        let errorText = error.response.data.details || 'Ha ocurrido un error al registrar el usuario';
                        that.$swal({
                            title: 'Error al Registrar',
                            text: errorText,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    });
            } else {
                e.preventDefault();
                let errorText = this.errores.join('\n');
                this.$swal({
                    title: 'Error!',
                    text: errorText,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        },

        actualizarRegistro(e) {
            this.errores = [];

            if (!this.registro || !this.registro.name) {
                this.errores.push('Falta Nombre de Usuario');
            }
            if (!this.registro || !this.registro.email) {
                this.errores.push('Falta Email');
            }
            if (!this.registro || !this.registro.password) {
                this.errores.push('Falta Clave');
            }
            if (!this.registro || !this.registro.branch_id) {
                this.errores.push('Falta Branch');
            }
            if (this.registro.role_id === '-1') {
                this.errores.push('Falta Rol');
            }
            if (this.registro.id_departamento === '-1') {
                this.errores.push('Falta Departamento');
            }
            if (this.registro.id_regional === '-1') {
                this.errores.push('Falta Regional');
            }
            if (this.registro.id_agencia === '-1') {
                this.errores.push('Falta Agencia');
            }

            if (this.errores.length === 0) {
                let gRegistro = this.registro;
                let that = this;
                let url = "api/users/" + gRegistro.id;
                axios.put(url, gRegistro)
                    .then(function (response) {
                        that.output = response.data;
                        that.listarRegistros();
                        that.$swal({
                            title: 'Actualización Exitosa',
                            text: 'Los datos se han actualizado correctamente',
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    })
                    .catch(function (error) {
                        that.output = error;
                        that.$swal({
                            title: 'Error al Actualizar',
                            text: 'Ha ocurrido un error al actualizar los datos',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    });
            } else {
                e.preventDefault();
                let errorText = this.errores.join('\n');
                this.$swal({
                    title: 'Error!',
                    text: errorText,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            }
        },

        eliminarRegistro() {
            let gRegistro = this.registro;
            let that = this;
            let url = "api/users/" + gRegistro.id;

            this.$swal({
                title: '¿Estás seguro?',
                text: '¿Quieres eliminar este registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(url)
                        .then(function (response) {
                            that.output = response.data;
                            that.listarRegistros();
                            that.$swal({
                                title: 'Eliminado',
                                text: 'El registro ha sido eliminado exitosamente',
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            });
                        })
                        .catch(function (error) {
                            that.output = error;
                            that.$swal({
                                title: 'Error',
                                text: 'Ha ocurrido un error al intentar eliminar el registro',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        });
                }
            });
        },

        doTable() {
            $(function () {
                $('#divTable').DataTable({});
            });
        }

    },

    beforeUpdate: function () {
        if (this.dataTable) {
            this.dataTable.destroy()
        }
    },

    updated: function () {
        this.dataTable = $("#divTable").DataTable({
            responsive: true,
            order: [],
            "language": {
                "lengthMenu": "Desplegar _MENU_ registros por página",
                "zeroRecords": "Sin registros",
                "info": "Página _PAGE_ de _PAGES_",
                "infoEmpty": "No existen registros disponibles",
                "infoFiltered": "(filtrados de _MAX_ registros en total)",

                "search": "Buscar",
                "paginate": {
                    "first": "Primera",
                    "last": "Última",
                    "next": "Siguiente",
                    "previous": "Anterior"
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

.combo {
    font-size: 10px;
    height: 25px !important;
}
</style>
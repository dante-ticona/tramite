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
                            <th scope="col">USER ID</th>
                            <th scope="col">OPCIONES</th>
                            <th scope="col">NAME</th>
                            <th scope="col" style="text-align: center;">EMAIL</th>
                            <th scope="col">ROL</th>
                            <th scope="col">CREADO</th>
                            <th scope="col">DEPARTAMENTO</th>
                            <th scope="col">REGIONAL</th>
                            <th scope="col">AGENCIA</th>
                            <th scope="col" style="text-align: center;">ATC</th>
                            <th scope="col">SUPERVISOR</th>
                            <th scope="col">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td width="3%" scope="row" align="center">{{ r.id }}</td>
                            <td width="2%" scope="row" style="text-align: center;">
                                <button v-if="r.status !== 'X'" type="button" class="btn btn-warning1 btn-toggle"
                                    v-on:click="doVer(index)" title="Editar" data-toggle="modal"
                                    data-target="#modalEditar">
                                    <i class="fa fa-pen white" aria-hidden="true"></i>
                                </button>
                            </td>
                            <td>{{ r.name }}</td>
                            <td width="5%" scope="row" align="center"><span class="badge badge-dark">{{ r.email }}</span></td>
                            <td>{{ r.description }}</td>
                            <td>{{ r.created_at }}</td>
                            <td width="5%">{{ r.departamento_description }}</td>
                            <td>{{ r.regional_description }}</td>
                            <td>{{ r.agencia_description }}</td>
                            <td align="center">{{ r.es_atc ? 'Es Atención al Cliente' : '' }}</td>
                            <td width="8%" align="center">{{ r.es_supervisor ? 'Es Supervisor' : 'No es Supervisor' }}</td>
                            <td align="center" width="5%">
                                <span v-if="r.status === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.status === 'X'" class="badge badge-danger">INACTIVO</span>
                                <span v-else class="badge badge-warning">{{ r.status }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <!-- modalEditar -->
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditar"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel"> {{ singular }}</h5>
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
                                    placeholder="Ingrese Nombre" disabled>
                                <p v-if="!registro.name" class="mensaje">Complete</p>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Correo Electrónico</label>
                                <input type="email" v-model="registro.email"
                                    @input="registro.email = $event.target.value.toLowerCase()" class="form-control"
                                    placeholder="Ingrese Email" disabled>
                                <p v-if="!registro.email" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-6">
                                <label>Branch</label>
                                <input v-model="registro.branch_id" class="form-control" placeholder="Ingrese Rama"
                                    disabled>
                                <p v-if="!registro.branch_id" class="mensaje">Complete</p>
                            </div>

                            <div class="col-md-6">
                                <label>Rol</label>
                                <select v-model="registro.role_id" class="form-control" placeholder="Rol" disabled>
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
                                    placeholder="Departamento" @change="listarRegional()">
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
                                    @change="listarAgencia()">
                                    <option value="-1">-- Seleccione Regional --</option>
                                    <option v-for="s in DescRegionalFiltro" :value="s.id_sip_regional">
                                        {{ s.descripcion_doc }}
                                    </option>
                                </select>
                                <p v-if="registro.id_regional == '-1'" class="mensaje">Seleccione</p>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label>Agencia</label>
                                <select v-model="registro.id_agencia" class="form-control" placeholder="Agencia">
                                    <option value="-1">-- Seleccione Agencia --</option>
                                    <option v-for="s in DescAgenciaFiltro" :value="s.id_sip_agencia">
                                        {{ s.descripcion_agencia }}
                                    </option>
                                </select>
                                <p v-if="registro.id_agencia == '-1'" class="mensaje">Seleccione</p>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" @click="actualizarRegistro($event)"
                            data-dismiss="modal">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'cambioAgenciaUsers',

    data() {
        return {
            plural: 'Cambio de Agencia Usuarios ATC',
            singular: 'Cambiar Agencia',
            seleccionado: '',
            errores: [],
            registro: {},
            registros: [],
            rol: [],
            DescDepartamento: [],
            DescRegional: [],
            DescAgencia: [],
            DescRegionalFiltro: [],
            DescAgenciaFiltro: [],
            dataTable: null,
        }
    },

    mounted() {
        this.listarRegistros();
        this.listarRoles();
        this.listarDepartamento();
        this.listarRegional();
        this.listarAgencia();

        this.dataTable = $('#divTable').DataTable({ responsive: true });
    },

    methods: {
        listarRegistros() {
            let url = "api/usersATC/";
            axios.get(url).then(response => {
                this.registros = response.data.data;
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

        doVer(index) {
            this.registro = this.registros[index];
        },

        actualizarRegistro(e) {
            this.errores = [];
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
                let url = "api/usersATC/" + gRegistro.id;
                console.log("registros enviados para cambio agencia",gRegistro);
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

.mensaje2 {
    color: #0319e4;
    font-size: x-small;
    font-weight: bold;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.btn-warning1{
    background-color: #ebd722;
    color: white;
    font-size: 13px;
}

.btn-warning1:hover{
    background-color: transparent;
    color: #ebd722;
    border: 3px solid #ebd722;
}
</style>
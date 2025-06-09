<template>
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">Perfil de Usuario</div>

            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-12">
                        <label>Name</label>
                        <input v-model="registro.name" class="form-control" placeholder="Ingrese Name" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input v-model="registro.email" class="form-control" placeholder="Ingrese Email" disabled>
                    </div>
                    <div class="col-md-6">
                        <label>Roles</label>
                        <select v-model="registro.role_id" class="form-control" placeholder="Roles" disabled>
                            <option value="null">-- Seleccione Roles --</option>
                            <option v-for="s in roles" v-bind:value="s.role_id">
                                {{ s.role_description}} - {{ s.role_code}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <hr>
                    </div>
                </div>
                <div class="row justify-content-left">
                    <div class="col-md-12">
                        <label>Nodos Asignados</label>
                        <table class="table table-hover table-responsive">
                            <thead class="thead-dark">
                                <tr>
                                    <th>CODIGO</th>
                                    <th>DESCRIPCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="n in usrNodos">
                                    <td>{{ n.nodo_codigo}}</td>
                                    <td>{{ n.nodo_descripcion}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'profile',
        data() {
            return {
                //prvId: parseInt(window.Laravel.emp_id),
                rolId: parseInt(window.Laravel.rol_id),
                usrId: parseInt(window.Laravel.usr_id),

                registro: {},
                roles: [],
                usrNodos: [],
            }
        },

        mounted() {
            console.log('Component mounted.');

            this.buscarUser();
            this.listarRoles();
            this.listarUsrNodos();
        },

        methods: {
            buscarUser() {
                var url = "api/users/" + this.usrId;
                axios.get(url).then(response => {
                    this.registros = response.data.data; //twice data
                    if (this.registros.length > 0) {
                        this.registro = this.registros[0];
                    }
                    console.log("usuario >>>", this.registros);
                });
            },

            listarRoles() {
                var url = "api/roles";
                axios.get(url).then(response => {
                    this.roles = response.data.data; //twice data
                });
            },

            listarUsrNodos() {
                var url = "api/usrnodosXId/" + this.usrId;
                axios.get(url).then(response => {
                    this.usrNodos = response.data.data; //twice data
                    console.log("nodos >>>", this.usrNodos);
                });
            },

        }
    }
</script>

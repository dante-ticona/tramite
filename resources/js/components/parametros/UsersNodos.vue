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
								<button type="button" class="btn btn-success" @click="doLimpiar()" data-toggle="modal" data-target="#modal">
									<i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
								</button>
							</th>
							<th scope="col">NOMBRE USUARIO</th>
							<th scope="col">EMAIL</th>
							<th scope="col">NODO</th>
							<th scope="col">ESTADO</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.usn_id }}</td>
							<td width="15%" scope="row">
								<button type="button" class="btn btn-warning btn-circle btn-sm" v-on:click="doVer(index, 'Editar')" data-toggle="modal" data-target="#modal">
									<i class="fa fa-pen white" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn btn-danger btn-circle btn-sm" v-on:click="doVer(index, 'Eliminar')" data-toggle="modal"
									data-target="#modal">
									<i class="fa fa-trash white" aria-hidden="true"></i>
								</button>
							</td>
							<td>{{ r.name }}</td>
							<td>{{ r.email }}</td>
							<td>{{ r.nodo_codigo }} - {{ r.nodo_descripcion }}</td>
							<td>
                                <span v-if="r.usn_estado == 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.usn_estado == 'X'" class="badge badge-danger">ELIMINADO</span>
                                <span v-else class="badge badge-warning">{{ r.usn_estado }}</span>
                            </td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- Ventana para Nuevo, Editar y Eliminar  -->
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalNuevo" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div id="mTitulo">
						<h5 class="modal-title" id="exampleModalLabel">{{ accion }} {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Nombre Usuario</label>
								<select v-model="registro.usn_user_id" class="form-control" placeholder="Nombre" :disabled="disable">
									<option value="-1">-- Seleccione Usuario --</option>
									<option v-for="u in usuarios" :value="u.id">
										{{ u.name }}
									</option>
								</select>
								<p v-if="registro.usn_user_id=='-1'" class="mensaje">Seleccione</p>
							</div>

							<!-- --------------------------------------------------------------------------------------------------------------------------- -->

							<div class="col-md-12">
								<label>Email</label>
								<select v-model="registro.usn_user_id" class="form-control" placeholder="Nombre" :disabled="disable">
									<option value="-1">-- Seleccione Usuario --</option>
									<option v-for="u in usuarios" :value="u.id">
										{{ u.email }}
									</option>
								</select>
								<p v-if="registro.usn_user_id=='-1'" class="mensaje">Seleccione</p>
							</div>	

							<!-- --------------------------------------------------------------------------------------------------------------------------- -->

							
							<div class="col-md-12">
								<label>Nodo</label>
								<select v-model="registro.usn_nodo_id" class="form-control" placeholder="Nodo" :disabled="disable">
									<option value="-1">-- Seleccione Nodo --</option>
									<option v-for="s in nodos" v-bind:value="s.nodo_id">
										{{ s.nodo_codigo }} - {{ s.nodo_descripcion }}
									</option>
								</select>
								<p v-if="registro.usn_nodo_id=='-1'" class="mensaje">Seleccione</p>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" @click="registrar()" data-dismiss="modal">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    import datatables from 'datatables';

	export default {
		name: 'users_nodos',
		data() {
			return {
				plural: 'Usuarios - Nodos',
				singular: 'Usuario - Nodo',
				registros: [],
				registro: {},
				usuarios: [],
				nodos: [],
				accion: '',
				disable: false,
			}
		},

		mounted() {
			this.listarRegistros();
			this.listarUsuarios();
			this.listarNodos();

			this.dataTable = $('#divTable').DataTable({ responsive: true });
		},

		methods: {
			listarRegistros() {
				let url = "api/usrnodos";
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
				});
			},

			listarUsuarios() {
				let url = "api/users";
				axios.get(url).then(response => {
					this.usuarios = response.data.data; //twice data
				});
			},

			listarNodos() {
				let url = "api/nodos";
				axios.get(url).then(response => {
					this.nodos = response.data.data; //twice data
				});
			},

			doLimpiar() {
				this.registro = {};
				this.accion = 'Nuevo';
				document.getElementById("mTitulo").className = "modal-header bg-primary";
				this.disable = false;
			},

			doVer(index, accion) {
				this.accion = accion;
				this.registro = this.registros[index];
				if (this.accion == 'Eliminar') {
					document.getElementById("mTitulo").className = "modal-header bg-danger";
					this.disable = true;
				} else {
					document.getElementById("mTitulo").className = "modal-header bg-warning";
					this.disable = false;
				}
			},

			registrar() {
				if (this.accion == 'Nuevo') {
					let url = 'api/usrnodos';
					axios.post(url, this.registro).then(response => {
						this.listarRegistros();
					});
				} else if (this.accion == 'Eliminar') {
					let url = 'api/usrnodos/' + this.registro.usn_id;
					axios.delete(url).then(response => {
						this.listarRegistros();
					});
				} else if (this.accion == 'Editar') {
					let url = 'api/usrnodos/' + this.registro.usn_id;
					axios.put(url, this.registro).then(response => {
						this.listarRegistros();
					});
				}
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
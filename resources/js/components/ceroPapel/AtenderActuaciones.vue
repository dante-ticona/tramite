<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-10">
						<h5>{{ plural }}</h5>
					</div>
					<div class="col-md-2">
						<button type="button" class="btn btn-primary" title="Nuevo" v-on:click="doLimpiar( )"
							data-toggle="modal" data-target="#modalNuevo">
							<i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-striped table-responsive" id="divTable">
					<thead class="thead-dark">
						<tr>
							<th scope="col">#</th>
							<th scope="col">
							</th>
							<th scope="col">ASUNTO</th>
							<th scope="col">REGISTRADO</th>
							<th scope="col">ACTUALIZADO</th>
							<th scope="col">E</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registrosActuaciones">
							<td width="3%" scope="row">{{ r.act_id }}</td>
							<td width="15%" scope="row">
								<button type="button" class="btn btn-warning btn-circle" v-on:click="doVer( index )"
									title="Editar" data-toggle="modal" data-target="#modalEditar">
									<i class="fa fa-pen white" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn btn-danger btn-circle" v-on:click="doVer( index )"
									title="Eliminar" data-toggle="modal" data-target="#modalEliminar">
									<i class="fa fa-trash white" aria-hidden="true"></i>
								</button>
							</td>
							<td width="25%">{{ r.act_data.act_descripcion }}</td>
							<td>{{ r.act_registrado.substr(0, 16) }}</td>
							<td>{{ r.act_actualizado ? r.act_actualizado.substring(0, 16) : 'por definir' }}</td>
							<td>
								<span v-if="r.act_estado === 'A'" class="badge badge-primary">Libre</span>
								<span v-else-if="r.act_estado === 'T'" class="badge badge-warning">Tomado</span>
								<span v-else class="badge badge-dark">{{ r.act_estado}}</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- modalNuevo -->
		<div class="modal fade" id="modalNuevo" tabindex="-1" role="dialog" aria-labelledby="modalNuevo"
			aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Nueva {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-6">
								<label>Tipo Correspondencia:</label>
								<select v-model="registroActuacion.crr_tcrr_id" class="form-control">
									<option value=-1>-- Seleccione Tipo --</option>
									<option v-for="s in tipos" v-bind:value="s.tcrr_id">
										{{ s.tcrr_codigo}} - {{ s.tcrr_descripcion}}
									</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>CITE:</label>
								<input v-model="registroActuacion.act_data.crr_cite" class="form-control">
							</div>
							<div class="col-md-12">
								<label>Asunto:</label>
								<input v-model="registroActuacion.act_data.crr_asunto" class="form-control">
							</div>
							<div v-if="registroActuacion.crr_tcrr_id == 2" class="col-md-2">
								<label>CI:</label>
								<input v-model="registroActuacion.act_data.crr_ciudadano_ci" class="form-control">
							</div>
							<div v-if="registroActuacion.crr_tcrr_id == 2" class="col-md-6">
								<label>Nombre Completo:</label>
								<input v-model="registroActuacion.act_data.crr_ciudadano_nombres" class="form-control">
							</div>
							<div v-if="registroActuacion.crr_tcrr_id == 2" class="col-md-2">
								<label>Celular:</label>
								<input v-model="registroActuacion.act_data.crr_ciudadano_celular" class="form-control">
							</div>
							<div v-if="registroActuacion.crr_tcrr_id == 2" class="col-md-2">
								<label>Teléfono:</label>
								<input v-model="registroActuacion.act_data.crr_ciudadano_telefono" class="form-control">
							</div>
							<div class="col-md-8">
								<label>Correo Electrónico:</label>
								<input v-model="registroActuacion.act_data.crr_correo" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Fojas:</label>
								<input type="number" v-model="registroActuacion.act_data.crr_fojas"
									class="form-control">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-warning" @click="insertarRegistro($event)"
							data-dismiss="modal">Grabar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalTomar -->
		<div class="modal fade" id="modalTomar" tabindex="-1" role="dialog" aria-labelledby="modalTomar"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-warning">
						<h5 class="modal-title" id="exampleModalLabel">Tomar la {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Asunto: {{ registroActuacion.act_data.crr_asunto }}</label>
								<label>¿ Confirmar tomar la {{ singular }} ?</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-warning" @click="tomarCorrespondencia($event)"
							data-dismiss="modal">Si, tomar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalLiberar -->
		<div class="modal fade" id="modalLiberar" tabindex="-1" role="dialog" aria-labelledby="modalLiberar"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-success">
						<h5 class="modal-title" id="exampleModalLabel">Liberar la {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Asunto: {{ registroActuacion.act_data.crr_asunto }}</label>
								<label>¿ Confirmar liberar la {{ singular }} ?</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
						<button type="button" class="btn btn-success" @click="liberarCorrespondencia($event)"
							data-dismiss="modal">Si, liberar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- modalHistorico -->
		<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Historico de {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Asunto: {{ registroActuacion.act_data.crr_asunto }}</span></label>
							</div>
						</div>
						<div class="row justify-content-left">
							<!-- aqui poner for -->
							<div class="col-md-12">
								<label>listar cada movimiento historico</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>

<script>
	import datatables from "datatables";
	import jsPDF from "jspdf";

	export default {
		name: "servicios",
		props: ["crr_id"],
		data() {
			return {
				usrId: window.Laravel.usr_id,
				plural: "Actuaciones",
				singular: "Actuación",
				seleccionado: "",
				errores: [],
				registroActuacion: { act_data: {} },
				registrosActuaciones: [],
				tipos: [],
				impresiones: [],

				dataTable: null,
			};
		},

		mounted() {
			this.listarRegistrosActuaciones();

			this.dataTable = $("#divTable").DataTable({
				responsive: true,
				order: [],
			});
		},

		methods: {
			listarRegistrosActuaciones() {
				let url = "api/actuaciones/" + this.crr_id;
				axios.get(url).then((response) => {
					this.registrosActuaciones = response.data.data; //twice data
					this.registrosActuaciones.forEach(function (row) {
						row.act_data = JSON.parse(row.act_data);
					});
				});
			},

			doVer(index) {
				// Para tomarCorrespondencia() y liberarCorrespondencia()
				this.registroActuacion = this.registrosActuaciones[index];
			},

			doLimpiar() {
				this.registroActuacion = { act_crr_id: this.act_crr_id, act_data: {} };
			},

			insertarRegistro(e) {
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth() + 1;
				var yyyy = today.getFullYear();
				if (dd < 10) {
					dd = "0" + dd;
				}
				if (mm < 10) {
					mm = "0" + mm;
				}
				today = yyyy + "-" + mm + "-" + dd;

				this.errores = [];
				/*
									if (!this.registroActuacion.trb_data.trb_ci) {
										this.errores.push('Falta CI');
									}
									if (!this.registroActuacion.trb_data.trb_nombres || !this.registroActuacion.trb_data.trb_materno) {
										this.errores.push('Falta Nombres y Materno');
									}
									if (!this.registroActuacion.trb_data.trb_direccion) {
										this.errores.push('Falta Dirección');
									}
									if (!this.registroActuacion.trb_data.trb_telefono) {
										this.errores.push('Falta Teléfono');
									}
									*/
				if (this.errores.length === 0) {
					let gRegistro = this.registro;
					gRegistro.crr_usr_id = this.usrId;
					gRegistro.crr_nodo_id = 1;
					gRegistro.crr_data = JSON.stringify(gRegistro.crr_data);
					gRegistro.crr_modificado = today;
					gRegistro.crr_estado = "T";
					let that = this;
					let url = "api/ceroPapel/";
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
				/*
									if (!this.registroActuacion.trb_data.trb_ci) {
										this.errores.push('Falta CI');
									}
									if (!this.registroActuacion.trb_data.trb_nombres || !this.registroActuacion.trb_data.trb_materno) {
										this.errores.push('Falta Nombres y Materno');
									}
									if (!this.registroActuacion.trb_data.trb_direccion) {
										this.errores.push('Falta Dirección');
									}
									if (!this.registroActuacion.trb_data.trb_telefono) {
										this.errores.push('Falta Teléfono');
									}
									*/
				if (this.errores.length === 0) {
					let gRegistro = this.registro;
					gRegistro.crr_usr_id = this.usrId;
					gRegistro.crr_data = JSON.stringify(gRegistro.crr_data);
					let that = this;
					let url = "api/ceroPapel/" + gRegistro.crr_id;
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
		},

		beforeUpdate: function () {
			if (this.dataTable) {
				this.dataTable.destroy();
			}
		},

		updated: function () {
			this.dataTable = $("#divTable").DataTable({ responsive: true, order: [] });
		},
	};
</script>
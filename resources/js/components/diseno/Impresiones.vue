<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
				<div class="row col-md-12">
					<div class="col-6">
						Catalogo:
						<select v-model="seleccionado" class="form-control mt3"
							@change="listarProcesos(); seleccionado2 = ''; seleccionado3 = ''; procesos = []; actividades = []; registros = [];"
							size="10">
							<option value="-1" disabled>-- Seleccione Catalogo --</option>
							<option v-for="item in catalogos" :value="item.cat_id">
								<span v-for="index in item.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
								[ {{ item.cat_codigo}} ] {{ item.cat_descripcion}}
							</option>
						</select>
						
					</div>
					<div class="col-6">
						Proceso:
						<select v-model="seleccionado2" class="form-control mt3"
							@change="listarActividades(); seleccionado3 = ''; actividades = []; registros = []; listarActividades2();"
							size="10">
							<option value="-1" disabled>-- Seleccione Proceso --</option>
							<option v-for="item in procesos" :value="item.prc_id">
								{{ item.prc_data.prc_descripcion}} ( {{ item.prc_data.prc_codigo}} )</option>
						</select>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="row col-md-12">
					<!-- col izquierda -->
					<div class="col-md-6">
						Actividades:
						<select v-model="seleccionado3" class="form-control mt3" @change="listarRegistros(); limpiarModalReasignar();" size="20">
							<option value="-1" disabled>-- Seleccione Actividad --</option>
							<!--option v-for="(item, index) in actividades" :value="item.act_id" @click="validarTipoActividad(index)"-->
							<option v-for="(item, index) in actividades" :value="item.act_id"
								:disabled="item.act_tact_id==1 || item.act_tact_id==4">
								[ {{ item.act_data.act_orden}} ] {{ item.act_data.act_descripcion}} - ({{
								item.tact_descripcion }})
							</option>
						</select>
					</div>
					<!-- col derecha -->
					<div class="col-md-6">
						{{ plural }}:
						<table class="table table-hover table-striped table-responsive" id="divTable">
							<thead class="thead-dark">
								<tr>
									<th scope="col">#</th>
									<th scope="col" style="text-align: center;">
										<button v-if="seleccionado3 && verNuevo" class="btn btn-success btn-circle"
											size="sm" @click="doLimpiar()" data-toggle="modal" data-target="#modal"
											title="Nuevo">
											<i class="fa fa-plus white" aria-hidden="true"></i>
										</button>
									</th>
									<th scope="col">NOMBRE IMPRESION</th>
									<th scope="col">TIPO FIRMA</th>
									<th scope="col" style="text-align: center;">ID IMPRESION</th>
									<th scope="col">ACTIVIDAD</th>
									<th scope="col" style="text-align: center;">ESTADO</th>
									<th scope="col" style="text-align: center;">
										<button v-if="seleccionado3 && verReasignarImpresion" class="btn btn-primary btn-circle btn-xl" 
										data-toggle="modal" data-target="#modalReasignarImpresion" title="Reasignar Impresión" 
										@change="listarActividades2()">
											<i class="fa fa-print white" aria-hidden="true"></i>
										</button>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(r, index) in registros">
									<td width="3%" scope="row">{{ index + 1 }}</td>
									<td width="20%" scope="row" align="center">
										<button class="btn btn-warning btn-circle btn-xl"
											v-on:click="doVer(index, 'Editar')" data-toggle="modal" data-target="#modal"
											title="Editar">
											<i class="fa fa-pen white" aria-hidden="true"></i>
										</button>
										<button class="btn btn-danger btn-circle btn-xl"
											v-on:click="doVer(index, 'Eliminar')" data-toggle="modal"
											data-target="#modal" title="Eliminar">
											<i class="fa fa-trash white" aria-hidden="true"></i>
										</button>
									</td>
									<td>{{ r.imp_nombre }} </td>
									<td>{{ r.imp_tipo_firma }} </td>
									<td style="text-align: center;">{{ r.impact_imp_id }}</td>
									<td style="text-align: center;">{{ r.impact_act_id }}</td>
									<td align="center">
										<span v-if="r.imp_estado == 'A'" class="badge badge-success">A</span>
										<span v-else-if="r.imp_estado !== 'A'" class="badge badge-warning">{{
											r.imp_estado }}</span>
									</td>
									<td width="10%" scope="row" align="center"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- modalReasignarImpresion -->
		<div class="modal fade" id="modalReasignarImpresion" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 30%;">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<div class="card-head">
							<h3>Reasignar Impresión</h3>
						</div>
					</div>
					<div class="modal-body">						
						<div class="row justify-content-left">							
							<div class="col-md-10">
								<label> Seleccione Actividad: </label>
								<select v-model="listaImpAct.impact_act_id" class="form-control mt3" @change="listarImpresiones()">
									<option value="-1" disabled>-- Seleccione Actividad --</option>
									<option v-for="(item, index) in actividades2" :value="item.act_id"
										:disabled="item.act_tact_id==1 || item.act_tact_id==4">
										[ {{ item.act_data.act_orden}} ] {{ item.act_data.act_descripcion}} - ({{
										item.tact_descripcion }})
									</option>
								</select>
							</div>
							
							<div class="col-md-10">
								<label> Seleccione Impresión: </label>
								<select v-model="listaImpAct.impact_imp_id" class="form-control" @change="cargarImpDataReglas()">
									<option value="-1" disabled>-- Seleccione Impresión --</option>
									<option v-for="i in impresiones" v-bind:value="i.imp_id">
										[{{ i.imp_id }}] - {{ i.imp_nombre }}
									</option>
								</select>
							</div>

							<div class="col-md-12">
								<label> Regla de Impresión: </label>
								<textarea class="form-control" rows="7" v-model="listaImpAct.impact_data_reglas"
									placeholder='[{"impact_data_regla": "`#RESULTADO_1#` = `1`"}]'></textarea>
							</div>

						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal" @click="limpiarModalReasignar()">Cerrar</button>
						<button type="button" class="btn btn-success" @click="reasignar($event)"
							data-dismiss="modal">Reasignar</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Ventana para Nuevo, Editar y Eliminar  -->
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
								<label>Nombre</label>
								<input v-model="registro.imp_nombre" class="form-control" placeholder="Nombre"
									:disabled="disable">
								<p v-if="!registro.imp_nombre" class="mensaje">Complete</p>
							</div>


							<div class="col-md-6">
								<label for="frutas">Selecciona el Tipo de Formulario :</label>
								<select id="frutas" v-model="frutaSeleccionada" class="selectimp">
									<option :value="null" disabled selected>Selecciona un Tipo de Formulario</option>
									<option v-for="fruta in frutas" :key="fruta.id" :value="fruta.id">{{ fruta.nombre }}
									</option>
								</select>
								<p>Formulario seleccionada: {{ frutaSeleccionada }}</p>
								<!-- <p v-if="!registro.imp_nombre" class="mensaje">Complete</p> -->
							</div>

							<div class="col-md-6">
								<label for="firmas"> Seleccione el Tipo de Firma: </label>
								<select id="firmas" v-model="registro.imp_tipo_firma" class="selectimp">
									<option :value="null" disabled selected>-- Seleccione Tipo de Firma --</option>
									<option v-for="firma in firmas" :key="firma.nombres" :value="firma.nombres">
										{{ firma.nombres}}
									</option>
								</select>
								<p>Firma seleccionada: {{ registro.imp_tipo_firma }}</p>
							</div>

							<br><br>
							<!---*****************campo para generar la regla de impresiones**************--->
							<div class="col-md-12">
								<label>Reglas de Bifurcación
									<button class="btn btn-primary btn-sm" v-on:click="doEjemplo()" title="ejemplo"
										:disabled="!swVerBifurcacion">
										<i class="far fa-lightbulb yellow" aria-hidden="true"></i> Ejemplo
									</button>
									<label style="font-size:smaller;"> Utilizar (')</label>
								</label>
								<textarea type="number" v-model="registro.imp_data_reglas" class="form-control" rows="6"
									placeholder='[{"imp_regla": "`#RESULTADO_1#` = `1`"}]'
									:disabled="!swVerBifurcacion"></textarea>
							</div>
							<!---*****************fin campo para generar la regla de impresiones**************--->
							<div class="col-md-12">
								<button class="form-control btn btn-primary" @click="doSwVerCodigo()">Ver
									Código</button>
								<!-- antiguo TinyMCE Editor
								<tinymce id="contenido" v-model="registro.imp_data"
									toolbar1='formatselect | bold italic strikethrough forecolor backcolor | image link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | table code'
									:other_options="{ menubar:false }"
									:height="600"
								></tinymce>
								-->
								<vue2-tinymce-editor v-if="!swVerCodigo" v-model="registro.imp_data"
									:height="600"></vue2-tinymce-editor>
							</div>
							<hr>
							<div class="col-md-12">
								<textarea v-if="swVerCodigo" v-model="registro.imp_data" cols="100%" rows="25"
									:options="options"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary" @click="registrar($event)"
							data-dismiss="modal">Guardar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import tinymce from 'vue-tinymce-editor'
	import { Vue2TinymceEditor } from "vue2-tinymce-editor";
	import Swal from 'sweetalert2';

	export default {
		name: '',
		data() {
			return {
				filtro: {},
				plural: 'Impresiones',
				singular: 'Impresión',
				seleccionado: '',
				seleccionado2: '',
				seleccionado3: '',
				errores: [],
				registro: { imp_data_reglas: {} },
				registros: [],
				catalogos: [],
				nodos: [],
				procesos: [],
				actividades: [],
				tactividades: [],
				campos: [],
				verNuevo: true,
				accion: '',
				disable: false,
				clase: '',
				swVerCodigo: false,
				swVerBifurcacion: false,
				options: {
					menubar: true,
					plugins: 'advlist autolink charmap code directionality emoticons table',
					toolbar1: 'fontselect | fontsizeselect | formatselect | bold italic underline strikethrough forecolor backcolor',
					toolbar2: 'alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | link table removeformat code',
				},
				frutaSeleccionada: null,
				frutas: [
					{ id: 1, nombre: 'Formulario' },
					{ id: 2, nombre: 'Formulario de Recepcion de Documentos' },
					{ id: 3, nombre: 'Carta' },
					{ id: 4, nombre: 'Form certificado de verificacion' }
				],

				firmaSeleccionada: null,
				firmas: [
					{ nombres: 'Firma Digital' },
					{ nombres: 'Firma Manual' }
				],
				listaImpAct: { impact_data_reglas: {} },				
				verReasignarImpresion: true,
				actividades2: {},
				impresiones: {},
			}
		},

		components: {
			'tinymce': tinymce, Vue2TinymceEditor,
		},
		created() {
			// Establecer la primera opción como seleccionada por defecto
			if (this.frutas.length > 0) {
				this.frutaSeleccionada = this.frutas[0].id;
			}

			if (this.firmas.length > 0) {
				this.firmaSeleccionada = this.firmas[0].id;
			}
		},
		mounted() {
			this.listarCatalogos();
		},

		methods: {
			listarRegistros() {
				let url = "api/impresiones/" + this.seleccionado3;
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
				});
			},

			listarCatalogos() {
				let url = "api/catalogos/";
				axios.get(url).then(response => {
					this.catalogos = response.data.data; //twice data
				});
			},

			listarProcesos() {
				let url = "api/procesos/" + this.seleccionado;
				axios.get(url).then(response => {
					this.procesos = response.data.data; //twice data
					this.procesos.forEach(function (row) {
						row.prc_data = JSON.parse(row.prc_data);
					});
				});
			},

			listarActividades() {
				let url = "api/actividades/" + this.seleccionado2;
				axios.get(url).then(response => {
					this.actividades = response.data.data; //twice data
					this.actividades.forEach(function (row) {
						row.act_data = JSON.parse(row.act_data);
					});
				});
			},

			listarActividades2() {
				let url = "api/actividades/" + this.seleccionado2;
				axios.get(url).then(response => {
					this.actividades2 = response.data.data;
					this.actividades2.forEach(function (row) {
						row.act_data = JSON.parse(row.act_data);
					});
				});
			},

			listarImpresiones() {
				let url = "api/impresiones/" + this.listaImpAct.impact_act_id;
				axios.get(url).then(response => {
					this.impresiones = response.data.data;

				});
			},

			
			cargarImpDataReglas() {
				let seleccion = this.impresiones.find(impresion => impresion.imp_id === this.listaImpAct.impact_imp_id);
				if (seleccion) {
					this.listaImpAct.impact_imp_nombre = seleccion.imp_nombre;
					this.listaImpAct.impact_tipo_firma = seleccion.imp_tipo_firma;
					this.listaImpAct.impact_data_reglas = seleccion.imp_data_reglas;
					this.listaImpAct.impact_data = seleccion.imp_data;
					this.listaImpAct.impact_tipo = seleccion.imp_tipo;
				} else {
					this.listaImpAct.impact_imp_nombre = '';
					this.listaImpAct.impact_tipo_firma = '';
					this.listaImpAct.impact_data_reglas = '';
					this.listaImpAct.impact_data = '';
					this.listaImpAct.impact_tipo = '';
				}
			},

			limpiarModalReasignar(){
				this.listaImpAct = {
					impact_act_id: -1,
					impact_imp_id: -1,
					impact_data_reglas: ''
				};
			},

			reasignar(e) {
				Swal.fire({
					title: '¿Reasignar la impresión a esta actividad?',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Sí',
					cancelButtonText: 'No',
					willOpen: () => {
						Swal.showLoading();
					}
				}).then((result) => {
					if (result.isConfirmed) {
						this.errores = [];
						if (!this.listaImpAct || !this.listaImpAct.impact_data_reglas) {
							this.errores.push('Verifique las reglas de impresión');
						}
						if (this.errores.length === 0) {
							this.listaImpAct.impact_act_id = this.seleccionado3;
							this.listaImpAct.impact_data_reglas = JSON.stringify(JSON.parse(this.listaImpAct.impact_data_reglas.replace(/`/g, "'")));
							var datos = this.listaImpAct;
							let url = '/api/reasignarImpresion';
							console.log('var datos enviados: ', datos);
							axios.post(url, datos).then(response => {
								Swal.fire('Impresión reasignada exitosamente', '', 'success');
								console.log(response.data);
								this.listarRegistros();
								this.limpiarModalReasignar();
							}).catch((error) => {
								console.error(error);
								if (error.response && error.response.status === 500) {
									Swal.fire('Error al reasignar la impresión', 'Por favor, inténtelo de nuevo', 'error');
								} else {
									Swal.fire('Se produjo un error inesperado', 'Intente nuevamente', 'error');
								}
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
					} else if (result.dismiss === Swal.DismissReason.cancel) {
					}
				});
			},

			registrar(e) {
				Swal.fire({
					title: '¿Esta seguro de realizar esta acción?',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Sí',
					cancelButtonText: 'No',
					willOpen: () => {
						Swal.showLoading();
					}
				}).then((result) => {
					if (result.isConfirmed) {
						this.errores = [];
						console.log(this.registro);
						if (this.accion == 'Nuevo') {
							if (!this.registro.imp_data_reglas) {
								this.errores.push('Verifique las reglas de impresión');
							}
							if (this.errores.length === 0) {
								var reg = this.registro;
								reg.imp_act_id = this.seleccionado3;
								reg.id_combo = this.frutaSeleccionada;
								reg.imp_tipo_firma = this.registro.imp_tipo_firma;
								reg.imp_data_reglas = JSON.stringify(JSON.parse(reg.imp_data_reglas.replace(/`/g, "'")));
								console.log('datos de l post', reg);
								axios.post('/api/impresiones', reg).then(response => {
									Swal.fire('Impresión añadida exitosamente', '', 'success');
									this.listarRegistros();
								}).catch((error) => {
									console.error(error);
									if (error.response && error.response.status === 500) {
										Swal.fire('Error al añadir la nueva impresión', 'Por favor, inténtelo de nuevo', 'error');
									} else {
										Swal.fire('Se produjo un error inesperado', 'Intente nuevamente', 'error');
									}
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
						} else if (this.accion == 'Eliminar') {
							if (!this.registro.imp_data_reglas) {
								this.errores.push('Verifique las reglas de impresión');
							}
							if (this.errores.length === 0) {
								var url = 'api/impresiones/' + this.registro.imp_id;
								axios.post(url, this.registro).then(response => {
									Swal.fire('Impresión eliminada exitosamente', '', 'success');
									this.listarRegistros();
								}).catch((error) => {
									console.error(error);
									if (error.response && error.response.status === 500) {
										Swal.fire('Error al eliminar la impresión', 'Por favor, revise los datos', 'error');
									} else {
										Swal.fire('Se produjo un error inesperado', 'Intente nuevamente', 'error');
									}
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
						} else if (this.accion == 'Editar') {
							if (!this.registro.imp_data_reglas) {
								this.errores.push('Verifique las reglas de impresión');
							}
							if (this.errores.length === 0) {
								axios.put('/api/impresiones/' + this.registro.imp_id, this.registro).then(response => {
									Swal.fire('Impresión actualizada exitosamente', '', 'success');
									this.listarRegistros();
								}).catch((error) => {
									console.error(error);
									if (error.response && error.response.status === 500) {
										Swal.fire('Error al editar la impresión', 'Por favor, revise los datos', 'error');
									} else {
										Swal.fire('Se produjo un error inesperado', 'Intente nuevamente', 'error');
									}
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
						}
					} else if (result.dismiss === Swal.DismissReason.cancel) {
					}
				});

			},

			listarTActividades() {
				let url = "api/tactividades/";
				axios.get(url).then(response => {
					this.tactividades = response.data.data; //twice data
				});
			},

			listarNodos() {
				let url = "api/nodos/";
				axios.get(url).then(response => {
					this.nodos = response.data.data; //twice data
				});
			},

			doVer(index, accion) {
				this.accion = accion;
				this.registro = this.registros[index];
				this.registro.imp_data_reglas = this.registros[index].imp_data_reglas;

				if (this.accion == 'Eliminar') {
					document.getElementById("mTitulo").className = "modal-header bg-danger";
					this.disable = true;
				} else {
					this.swVerBifurcacion = true;
					document.getElementById("mTitulo").className = "modal-header bg-warning";
					this.disable = false;
				}
			},

			doLimpiar() {
				this.registro = {};
				this.accion = 'Nuevo';
				document.getElementById("mTitulo").className = "modal-header bg-primary";
				this.disable = false;
				this.swVerBifurcacion = true;
			},
			doEjemplo() {
				this.registro.imp_data_reglas = '['
					+ '{'
					+ '"imp_regla": "`#RESULTADO_1#` = `1`"'
					+ '}'
					+ ']';
			},
			/*
			validarTipoActividad(idx) {
				let that = this;
				let res = true;
				if ((this.actividades[idx].act_tact_id == 1) || (this.actividades[idx].act_tact_id == 4)) {
					res = false;
				}
				this.verNuevo = res;
			},
			*/

			doSwVerCodigo() {
				this.swVerCodigo = !this.swVerCodigo;
			}
		},
	}
</script>

<style>
	.selectimp {
		appearance: none;
		padding: 0.5rem 1rem;
		font-size: 1rem;
		border: 1px solid #ced4da;
		border-radius: 0.25rem;
		background-color: #fff;
		width: 100%;
		transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
	}

	.selectimp:focus {
		border-color: #80bdff;
		outline: 0;
		box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
	}
</style>
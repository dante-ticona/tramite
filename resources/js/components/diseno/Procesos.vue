<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5>
				<div class="col-md-12">
					<div class="row col-6">
						Catálogo:
						<select v-model="seleccionado" class="form-control mt3" @change="listarRegistros()" size="10">
							<option value="-1" disabled>-- Seleccione Catalogo --</option>
							<option v-for="item in catalogos" :value="item.cat_id">
								<span v-for="index in item.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
								[ {{ item.cat_codigo }} ] {{ item.cat_descripcion }}
							</option>
						</select>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-striped table-responsive" id="divTable">
					<thead class="thead-dark">
						<tr align="center">
							<th scope="col">#</th>
							<th>
								<button v-if="seleccionado" class="btn btn-success form-control" @click="doLimpiar()"
									data-toggle="modal" data-target="#modalNuevo">
									<i class="fa fa-plus white" aria-hidden="true"></i> Nuevo
								</button>
							</th>
							<th scope="col">CÓDIGO / DESCRIPCIÓN</th>
							<th scope="col">VER.</th>
							<th scope="col">DETALLE</th>
							<th scope="col">CAMPOS CLAVE</th>
							<th scope="col">E</th>
							<th scope="col">
								<button v-if="seleccionado" type="button" class="btn btn-primary btn-circle btn-xl"
									title="Importar" data-toggle="modal" data-target="#modalUpload">
									<i class="fa fa-upload white" aria-hidden="true"></i>
								</button>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.prc_id }}</td>
							<td width="15%" scope="row" align="center">
								<button type="button" class="btn btn-primary btn-circle btn-xl"
									v-on:click="doVer( index )" data-toggle="modal" data-target="#modalVer" title="Ver">
									<i class="fa fa-eye white" aria-hidden="true"></i>
								</button>
								<router-link :to="'/modeladoProceso/' + r.prc_id">
								<button type="button" class="btn btn-primary btn-circle btn-xl" title="Modelado">
									<i class="fa fa-copy white" aria-hidden="true"></i>
								</button>
								</router-link>
								<button type="button" class="btn btn-warning btn-circle btn-xl"
									v-on:click="doVer( index )" data-toggle="modal" data-target="#modalEditar" title="Editar">
									<i class="fa fa-pen white" aria-hidden="true"></i>
								</button>
								<button type="button" class="btn btn-danger btn-circle btn-xl"
									v-on:click="doVer( index )" data-toggle="modal" data-target="#modalEliminar" title="Eliminar">
									<i class="fa fa-trash white" aria-hidden="true"></i>
								</button>
							</td>
							<td>
								<strong>{{ r.prc_data.prc_codigo }}</strong><br>
								{{ r.prc_data.prc_descripcion }}
							</td>
							<td align="center">
								{{ r.prc_data.prc_version }}
							</td>
							<td align="center">
								<div v-if="r.prc_data.prc_detalle != undefined && r.prc_data.prc_detalle.length > 0" :title="r.prc_data.prc_detalle">
									<i class="fa fa-eye white" aria-hidden="true"></i> Ver
								</div>
							</td>
							<td align="center">
								<div v-if="r.prc_data_campos_clave != undefined && r.prc_data_campos_clave.length > 0" :title="r.prc_data_campos_clave">
									<i class="fa fa-eye white" aria-hidden="true"></i> Ver
								</div>
							</td>
							<td align="center">
								<span v-if="r.prc_estado === 'A'" class="badge badge-success">ACTIVO</span>
								<span v-else-if="r.prc_estado !== 'A'" class="badge badge-warning">{{ r.prc_estado
									}}</span>
							</td>
							<td align="center">
								<button type="button" class="btn btn-success btn-circle btn-xl" title="Exportar"
									v-on:click="doExportar( index )">
									<i class="fa fa-download white" aria-hidden="true"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<!-- modalVer -->
		<div class="modal fade" id="modalVer" tabindex="-1" role="dialog" aria-labelledby="modalVer" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Ver {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Catálogo</label>
								<select v-model="registro.prc_cat_id" class="form-control" placeholder="Catálogo"
									disabled>
									<option value="-1">-- Seleccione Catálogo --</option>
									<option v-for="s in catalogos" v-bind:value="s.cat_id">
										<span v-for="index in s.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
										[{{ s.cat_codigo }}] {{ s.cat_descripcion }}
									</option>
								</select>
							</div>
							<div class="col-md-6">
								<label>Código</label>
								<input v-model="registro.prc_data.prc_codigo" class="form-control" placeholder="Código"
									disabled>
							</div>
							<div class="col-md-6">
								<label>Versión</label>
								<input v-model="registro.prc_data.prc_version" class="form-control" placeholder="Versión"
									disabled>
							</div>
							<div class="col-md-12">
								<label>Descripción</label>
								<input v-model="registro.prc_data.prc_descripcion" class="form-control"
									placeholder="Descripción" disabled>
							</div>
							<div class="col-md-12">
								<label>Detalle</label>
								<textarea v-model="registro.prc_data.prc_detalle" class="form-control"
									placeholder="Detalle" rows="4" disabled></textarea>
								<p v-if="!registro.prc_data_campos_clave" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Campos Clave (formato JSON)</label>
								<textarea v-model="registro.prc_data_campos_clave" class="form-control"
									placeholder="Campos Clave" rows="6" disabled></textarea>
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
			<div class="modal-dialog modal-lg modal-dialog-right" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Nuevo {{ singular }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Catálogo</label>
								<select v-model="seleccionado" class="form-control" placeholder="Catálogo" disabled>
									<option value="-1">-- Seleccione Catálogo --</option>
									<option v-for="s in catalogos" v-bind:value="s.cat_id">
										<span v-for="index in s.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
										[{{ s.cat_codigo }}] {{ s.cat_descripcion }}
									</option>
								</select>
								<p v-if="!registro.prc_cat_id=='-1'" class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-6">
								<label>Código</label>
								<input v-model="registro.prc_data.prc_codigo" class="form-control" placeholder="Código">
                                <p v-if="!registro.prc_data.prc_codigo" class="mensaje">Complete</p>
							</div>
							<div class="col-md-6">
								<label>Versión</label>
								<input v-model="registro.prc_data.prc_version" class="form-control" placeholder="Versión">
								<p v-if="!registro.prc_data.prc_version" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Descripción</label>
								<input v-model="registro.prc_data.prc_descripcion" class="form-control"
									placeholder="Descripción">
								<p v-if="!registro.prc_data.prc_descripcion" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Detalle</label>
								<textarea v-model="registro.prc_data.prc_detalle" class="form-control"
									placeholder="Detalle" rows="4"></textarea>
							</div>
							<div class="col-md-12">
								<label>Campos Clave (escriba en formato JSON)</label>
								<button class="btn btn-primary btn-xl" v-on:click="doEjemplo()" title="Eliminar">
									<i class="far fa-lightbulb white" aria-hidden="true"></i> Ejemplo
								</button>
								<textarea v-model="registro.prc_data_campos_clave" class="form-control"
									placeholder="Campos Clave" rows="6"></textarea>
								<p v-if="!registro.prc_data_campos_clave" class="mensaje">Complete</p>
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
			<div class="modal-dialog modal-lg modal-dialog-right" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Editar {{ singular}}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-12">
								<label>Catálogo</label>
								<select v-model="seleccionado" class="form-control" placeholder="Catálogo" disabled>
									<option value="-1">-- Seleccione Catálogo --</option>
									<option v-for="s in catalogos" v-bind:value="s.cat_id">
										<span v-for="index in s.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
										[{{ s.cat_codigo }}] {{ s.cat_descripcion }}
									</option>
								</select>
								<p v-if="!registro.prc_cat_id=='-1'" class="mensaje">Seleccione</p>
							</div>
							<div class="col-md-6">
								<label>Código</label>
								<input v-model="registro.prc_data.prc_codigo" class="form-control" placeholder="Código">
                                <p v-if="!registro.prc_data.prc_codigo" class="mensaje">Complete</p>
							</div>
							<div class="col-md-6">
								<label>Versión</label>
								<input v-model="registro.prc_data.prc_version" class="form-control" placeholder="Versión">
								<p v-if="!registro.prc_data.prc_version" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Descripción</label>
								<input v-model="registro.prc_data.prc_descripcion" class="form-control"
									placeholder="Descripción">
								<p v-if="!registro.prc_data.prc_descripcion" class="mensaje">Complete</p>
							</div>
							<div class="col-md-12">
								<label>Detalle</label>
								<textarea v-model="registro.prc_data.prc_detalle" class="form-control"
									placeholder="Detalle" rows="4"></textarea>
							</div>
							<div class="col-md-12">
								<label>Campos Clave (escriba en formato JSON)</label>
								<textarea v-model="registro.prc_data_campos_clave" class="form-control"
									placeholder="Campos Clave" rows="6"></textarea>
								<p v-if="!registro.prc_data_campos_clave" class="mensaje">Complete</p>
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

		<!-- modalUpload -->
		<div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUpload"
			aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<h5 class="modal-title" id="exampleModalLabel">Importar Proceso</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row justify-content-left">
							<div class="col-md-6">
								<label>Archivo
									<input type="file" id="file" ref="file" />
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-primary" @click="uploadFile()"
							data-dismiss="modal">Importar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import datatables from 'datatables';

	export default {
		name: 'procesos',
		data() {
			return {
				filtro: {},
				plural: 'Procesos',
				singular: 'Proceso',
				seleccionado: '',
				errores: [],
				registro: { prc_data: {}, prc_data_campos_clave: {} },
				registros: [],
				catalogos: [],
				file: '',

				dataTable: null,
			}
		},

		mounted() {
			//this.listarRegistros();
			this.listarCatalogos();

			this.dataTable = $('#divTable').DataTable({ responsive: true });
		},

		methods: {
			listarRegistros() {
				let url = "api/procesos/" + this.seleccionado;
				axios.get(url).then(response => {
					this.registros = response.data.data; //twice data
					this.registros.forEach(function (row) {
						row.prc_data = JSON.parse(row.prc_data);
						//row.prc_data_campos_clave = JSON.stringify(JSON.parse(row.prc_data_campos_clave));
					});
				});
			},

			listarCatalogos() {
				let url = "api/catalogos/";
				axios.get(url).then(response => {
					this.catalogos = response.data.data; //twice data
				});
			},

			doVer(index) {
				this.registro = this.registros[index];
			},

			doExportar(index) {
				this.registro = this.registros[index];
				window.open("api/prc_exportar/" + this.registro.prc_id);
			},

			doLimpiar() {
				//this.seleccionado = '-1';
				this.registro = {
					prc_cat_id: '-1',
					prc_data: {},
					prc_data_campos_clave: ''
				};
			},

			insertarRegistro(e) {
				this.errores = [];
				
				if (this.registro.prc_data && !this.registro.prc_data.prc_codigo) {
                    this.errores.push('Falta Código');
                }
				if (this.registro.prc_data && !this.registro.prc_data.prc_descripcion) {
                    this.errores.push('Falta Descripción');
                }
				if (this.registro.prc_data && !this.registro.prc_data.prc_version) {
                    this.errores.push('Falta Versión');
                }
                if (this.registro && !this.registro.prc_data_campos_clave) {
                    this.errores.push('Falta Campos Clave');
                }
                if (this.seleccionado == '-1') {
                    this.errores.push('Falta Catalogo');
                }
				
				if (this.errores.length === 0) {
					let gRegistro = this.registro;
					gRegistro.prc_cat_id = this.seleccionado;
					gRegistro.prc_data = JSON.stringify(gRegistro.prc_data);
					gRegistro.prc_data_campos_clave = gRegistro.prc_data_campos_clave;
					gRegistro.prc_modelo = '{}';
					gRegistro.prc_usr_id = 1;
					let that = this;
					let url = "api/procesos";
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
				
				if (this.registro.prc_data && !this.registro.prc_data.prc_codigo) {
                    this.errores.push('Falta Código');
                }
				if (this.registro.prc_data && !this.registro.prc_data.prc_descripcion) {
                    this.errores.push('Falta Descripción');
                }
				if (this.registro.prc_data && !this.registro.prc_data.prc_version) {
                    this.errores.push('Falta Versión');
                }
                if (this.registro && !this.registro.prc_data_campos_clave) {
                    this.errores.push('Falta Campos Clave');
                }
                if (this.seleccionado == '-1') {
                    this.errores.push('Falta Catalogo');
                }
								
				if (this.errores.length === 0) {
					let gRegistro = this.registro;
					gRegistro.prc_data = JSON.stringify(gRegistro.prc_data);
					gRegistro.prc_data_campos_clave = gRegistro.prc_data_campos_clave;
					gRegistro.prc_usr_id = 1;
					let that = this;
					let url = "api/procesos/" + gRegistro.prc_id;
					axios.put(url, gRegistro)
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

			eliminarRegistro() {
				let gRegistro = this.registro;
				gRegistro.prc_usr_id = 1;

				let that = this;
				let url = "api/procesos/" + gRegistro.prc_id;
				axios.post(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			doEjemplo() {
				this.registro.prc_data_campos_clave = '['
					+ '{"prc_campo_clave":"NOMBRES_1"}'
					+ ']';
			},

			uploadFile() {
				this.file = this.$refs.file.files[0];
				let formData = new FormData();
				formData.append('file', this.file);
				this.$refs.file.value = '';
				axios.post('/api/prc_importar/' + this.seleccionado, formData,
					{
						headers: {
							'Content-Type': 'multipart/form-data'
						}
					}
				).then(response => {
					this.listarRegistros();
					this.$swal.fire({
						icon: 'success',
						text: 'El proceso y sus dependencias fueron importados. Por favor, reasignar las Actividades a sus respectivos Nodos.'
					});
				}).catch(error => {
					console.log(error);
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

	.combo {
		font-size: 10px;
		height: 25px !important;
	}
</style>
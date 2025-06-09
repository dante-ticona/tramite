<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-md-12">
						<h5>{{ plural }}</h5>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div>
					<div class="row">
						<div class="col-md-4">
							<label>Tipo Correspondencia:</label>
							<select v-model="registro.crr_tcrr_id" class="form-control">
								<option v-for="s in tipos" v-bind:value="s.tcrr_id">
									{{ s.tcrr_codigo }} - {{ s.tcrr_descripcion }}
								</option>
							</select>
						</div>
						<div class="col-md-4">
							<label>Tipo Documental:</label>
							<select v-model="registro.crr_data.tdoc_id" class="form-control" @change="verSubDoc()">
								<option v-for="s in docs" v-bind:value="s.tdoc_id">
									{{ s.tdoc_codigo }} - {{ s.tdoc_descripcion }}
								</option>
							</select>
						</div>
						<div class="col-md-4">
							<label>Subtipo Documental:</label>
							<select v-model="registro.crr_data.stdoc_id" class="form-control" @change="verContenido()" ref="mySel">
								<option v-for="s in subdocs" v-bind:value="s.stdoc_id">
									{{ s.stdoc_codigo }} - {{ s.stdoc_descripcion }}
								</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3">
							<label>Publicación:</label>
							<select v-model="registro.crr_pub_id" class="form-control">
								<option value="1">Público</option>
								<option value="2">Privado</option>
							</select>
						</div>
						<div class="col-md-6">
							<label>CITE:</label>
							<input v-model="registro.crr_data.crr_cite" class="form-control" />
						</div>
						<div class="col-md-8">
							<label>Asunto:</label>
							<input v-model="registro.crr_data.crr_asunto" class="form-control" />
						</div>
						<div class="col-md-4">
							<label>Fojas:</label>
							<input type="number" v-model="registro.crr_data.crr_fojas" class="form-control" />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-12">
							<hr />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
							<label>CI:</label>
							<input v-model="registro.crr_data.crr_ciudadano_ci" class="form-control" />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-6">
							<label>Nombre Completo:</label>
							<input v-model="registro.crr_data.crr_ciudadano_nombres" class="form-control" />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
							<label>Celular:</label>
							<input v-model="registro.crr_data.crr_ciudadano_celular" class="form-control" />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-2">
							<label>Teléfono:</label>
							<input v-model="registro.crr_data.crr_ciudadano_telefono" class="form-control" />
						</div>
						<div v-if="registro.crr_tcrr_id == 2" class="col-md-12">
							<label>Correo Electrónico:</label>
							<input v-model="registro.crr_data.crr_correo" class="form-control" />
						</div>
						<div class="col-md-12">
							<hr/>
							<label>Contenido:</label>
							<tinymce id="contenido" v-model="registro.contenido"
								toolbar1='formatselect | bold italic strikethrough forecolor backcolor | image link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | table code'
								:other_options="{ menubar:false }"
							></tinymce>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import datatables from "datatables";
	import jsPDF from "jspdf";
	import tinymce from 'vue-tinymce-editor'

	export default {
		name: "MiCorrespondencia",
		props: ['crr_id'],
		data() {
			return {
				usrId: window.Laravel.usr_id,
				plural: "#CeroPapel",
				singular: "Documento",
				seleccionado: "",
				errores: [],
				registro: { crr_data: {}, contenido: "" },
				registros: [],
				tipos: [],
				docs: [],
				subdocs: [],
				impresiones: [],
				dataTable: null,
			};
		},

		// Rich Editor
		components: {
			'tinymce': tinymce,
		},

		mounted() {
			this.listarTiposCorrespondencia();
			this.listarTiposDocumental();
			this.listarRegistros();

			this.dataTable = $("#divTable").DataTable({
				responsive: true,
				order: [],
			});
		},

		created() { },

		methods: {
			listarRegistros() {
/*
				let url = "api/ceroPapel/" + this.crr_id;
				axios.get(url).then((response) => {
					this.registros = response.data.data; //twice data
					this.registros.forEach(function (row) {
						row.crr_data = JSON.parse(row.crr_data);
					});
					console.log("Registros: ", this.registros);
				});
*/
			},

			listarTiposCorrespondencia() {
				let url = "api/tiposCorrespondencia";
				axios.get(url).then((response) => {
					this.tipos = response.data.data; //twice data
//					console.log("Tipos: ", this.tipos);
				});
			},

			listarTiposDocumental() {
				let url = "api/tiposDoc";
				axios.get(url).then((response) => {
					this.docs = response.data.data; //twice data
				});
			},

			doVer(index) {
				// Para tomarCorrespondencia() y liberarCorrespondencia()
				this.registro = this.registros[index];
			},

			doLimpiar() {
				this.registro = {
					crr_tcrr_id: 1,
					crr_pub_id: 1,
					crr_data: { crr_fojas: 0 },
				};
			},

			tomarCorrespondencia(e) {
				let gRegistro = {
					crr_estado: "T",
					crr_usr_id: 1,
				};
				let that = this;
				let url = "api/estadoCorrespondencia/" + this.registro.crr_id;
				axios
					.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			liberarCorrespondencia(e) {
				let gRegistro = {
					crr_estado: "A",
					crr_usr_id: 1,
				};
				let that = this;
				let url = "api/estadoCorrespondencia/" + this.registro.crr_id;
				axios
					.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			verSubDoc() {
				let url = "api/subtiposDoc/" + this.registro.crr_data.tdoc_id;
				axios.get(url).then((response) => {
					this.subdocs = response.data.data; //twice data
					this.registro.crr_data.stdoc_id = null;
					this.registro.contenido = '';
				});
			},

			verContenido() {
				this.subdocs.forEach(row => {
					if (this.registro.crr_data.stdoc_id == row.stdoc_id) {
						this.registro.contenido = row.stdoc_contenido;
						return false;
					}
				});
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
					gRegistro.crr_usr_id = this.usrId;
					gRegistro.crr_nodo_id = 1;
					gRegistro.crr_data = JSON.stringify(gRegistro.crr_data);
					gRegistro.crr_modificado = today;
					gRegistro.crr_estado = "A";
					let that = this;
					let url = "api/ceroPapel";
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

			derivarCorrespondencia(e) {
				let gRegistro = this.registro;
				gRegistro.cas_estado = "A";
				gRegistro.cas_act_id = this.siguiente.act_id;
				gRegistro.cas_nodo_id = this.siguiente.act_nodo_id;
				gRegistro.cas_estado = "A";
				gRegistro.cas_usr_id = 1;
				let that = this;
				let url = "api/casos/" + this.registro.cas_id;
				axios
					.put(url, gRegistro)
					.then(function (response) {
						that.output = response.data;
						that.listarRegistros();
					})
					.catch(function (error) {
						that.output = error;
					});
			},

			//	INICIO JOJO
			doImprimir(i) {
				var impress = this.impresiones[i].imp_data;
				this.registro.cas_data_valores.forEach(function (valor) {
					// console.log('Convertir a PDF: ', row.imp_data);
					impress = impress.replaceAll(
						"#" + valor.frm_campo + "#",
						valor.frm_value
					);
				});

				var doc = new jsPDF("p", "pt", "letter");
				var elementHandler = {
					"#ignorePDF": function (element, renderer) {
						return true;
					},
				};
				doc.fromHTML(impress, 15, 15, {
					elementHandlers: elementHandler,
				});
				doc.output("dataurlnewwindow");
			},
			//	FIN JOJO
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
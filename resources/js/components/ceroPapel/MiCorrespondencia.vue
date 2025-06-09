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
							<th>
							</th>
							<th scope="col">TIPO</th>
							<th scope="col">ASUNTO</th>
							<th scope="col">REGISTRADO</th>
							<th scope="col">ACTUALIZADO</th>
							<th scope="col">E</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.crr_id }}</td>
							<td width="17%" scope="row">
								<router-link v-if="r.crr_estado === 'T'" :to="'/atenderContenido/' + r.crr_id">
									<button type="button" class="btn btn-primary btn-circle" title="Actuaciones">
										<i class="fa fa-file-word white" aria-hidden="true"></i>
									</button>
								</router-link>
								<router-link v-if="r.crr_estado === 'T'" :to="'/atenderActuaciones/' + r.crr_id">
									<button type="button" class="btn btn-primary btn-circle" title="Actuaciones">
										<i class="fa fa-pen white" aria-hidden="true"></i>
									</button>
								</router-link>
								<button v-if="r.crr_estado === 'A'" type="button" class="btn btn-warning btn-circle"
									title="Tomar" v-on:click="doVer( index )" data-toggle="modal"
									data-target="#modalTomar">
									<i class="fa fa-lock white" aria-hidden="true"></i>
								</button>
								<button v-if="r.crr_estado === 'T'" type="button" class="btn btn-success btn-circle"
									title="Liberar" v-on:click="doVer( index )" data-toggle="modal"
									data-target="#modalLiberar">
									<i class="fa fa-lock-open white" aria-hidden="true"></i>
								</button>
								<button v-if="r.crr_estado === 'T'" type="button" class="btn btn-primary btn-circle"
									title="Histórico" v-on:click="listarHistorico( r.crr_id )" data-toggle="modal"
									data-target="#modalHistorico">
									<i class="fa fa-list-ol" aria-hidden="true"></i>
								</button>
								<router-link v-if="r.crr_estado === 'T'" :to="'/atenderCorrespondencia/' + r.crr_id">
									<button type="button" class="btn btn-danger btn-circle" title="Atender">
										<i class="fa fa-paper-plane white" aria-hidden="true"></i>
									</button>
								</router-link>
							</td>
							<td>
								<span v-if="r.tcrr_codigo === 'I'" class="badge badge-primary">{{ r.tcrr_descripcion
									}}</span>
								<span v-else class="badge badge-warning">{{ r.tcrr_descripcion }}</span>
							</td>
							<td width="25%">{{ r.crr_data.crr_asunto }}</td>
							<td>{{ r.crr_registrado.substr(0, 16) }}</td>
							<td>{{ r.crr_actualizado ? r.crr_actualizado.substring(0, 16) : 'por definir' }}</td>
							<td>
								<span v-if="r.crr_estado === 'A'" class="badge badge-primary">Libre</span>
								<span v-else-if="r.crr_estado === 'T'" class="badge badge-warning">Tomado</span>
								<span v-else class="badge badge-dark">{{ r.crr_estado}}</span>
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
									<select v-model="registro.crr_data.stdoc_id" class="form-control">
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
								<label>Asunto: {{ registro.crr_data.crr_asunto }}</label>
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
								<label>Asunto: {{ registro.crr_data.crr_asunto }}</label>
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
								<label>Asunto: {{ registro.crr_data.crr_asunto }}</span></label>
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
  name: "MiCorrespondencia",
  data() {
    return {
      usrId: window.Laravel.usr_id,
      plural: "#CeroPapel",
      singular: "Documento",
      seleccionado: "",
      errores: [],
      registro: { crr_data: {} },
      registros: [],
      tipos: [],
      impresiones: [],
	  docs: [],
	  subdocs: [],
      dataTable: null,
    };
  },

  mounted() {
    this.listarTiposCorrespondencia();
    this.listarRegistros();
    this.listarTiposDocumental();

    this.dataTable = $("#divTable").DataTable({
      responsive: true,
      order: [],
    });
  },

  created() {},

  methods: {
    listarRegistros() {
      let url = "api/correspondencia/" + "1";
      axios.get(url).then((response) => {
        this.registros = response.data.data; //twice data
        this.registros.forEach(function (row) {
          row.crr_data = JSON.parse(row.crr_data);
        });
        console.log("Registros: ", this.registros);
      });
    },

    listarTiposCorrespondencia() {
      let url = "api/tiposCorrespondencia";
      axios.get(url).then((response) => {
        this.tipos = response.data.data; //twice data
        console.log("Tipos: ", this.tipos);
      });
    },

	listarTiposDocumental() {
		let url = "api/tiposDoc";
		axios.get(url).then((response) => {
			this.docs = response.data.data; //twice data
		});
	},

	verSubDoc() {
		let url = "api/subtiposDoc/" + this.registro.crr_data.tdoc_id;
		axios.get(url).then((response) => {
			this.subdocs = response.data.data; //twice data
			this.registro.crr_data.stdoc_id = null;
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

	insertarRegistro(e) {
		let gRegistro = this.registro;
		gRegistro.crr_nodo_id = 4;
		gRegistro.crr_usr_id = this.usrId;
		let url = 'api/correspondencia';
		axios.post(url, gRegistro).then(response => {
			this.listarRegistros();
		});
	},

    insertarRegistro_BAK(e) {
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
        gRegistro.crr_nodo_id = 4;
//        gRegistro.crr_data = JSON.stringify(gRegistro.crr_data);
        gRegistro.crr_modificado = today;
        gRegistro.crr_estado = "A";
        let that = this;
        let url = "api/correspondencia";
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
        let url = "api/correspondencia/" + gRegistro.crr_id;
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
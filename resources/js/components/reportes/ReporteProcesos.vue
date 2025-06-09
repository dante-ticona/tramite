<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>Reporte de Procesos</h5>
				<div class="row">
					<div class="col-md-2">
						<select v-model="filtro.prc_codigo" class="form-control">
							<option value="">-- Todas --</option>
							<option v-for="proceso in procesos" :value="proceso.prc_data.prc_codigo">{{ proceso.prc_data.prc_codigo }} - {{ proceso.prc_data.prc_descripcion }}</option>
						</select>
					</div>
					<div class="col-md-2">
						<input type="number" v-model="filtro.cas_nro_caso" class="form-control" placeholder="Nro. Caso">
					</div>
					<div class="col-md-2">
						<select v-model="filtro.id_departamento" class="form-control"  @change="listarRegional(filtro.id_departamento)">
							<option value="">-- DEPARTAMENTO --</option>
							<option v-for="departamento in departamento" :value="departamento.id_sip_departamento">{{ departamento.descripcion_dep }} </option>
						</select>
					</div>
					<div class="col-md-3">
						<select v-model="filtro.id_regional" class="form-control" @change="listarAgencia(filtro.id_regional)">
							<option value="">-- REGIONAL --</option>
							<option v-for="regional in regional" :value="regional.id_sip_regional">{{ regional.descripcion_doc }}</option>
						</select>
					</div>
					<div class="col-md-3">
						<select v-model="filtro.id_agencia" class="form-control">
							<option value="">-- AGENCIA --</option>
							<option v-for="agencia in agencia" :value="agencia.id_sip_agencia">{{ agencia.descripcion_agencia }}</option>
						</select>
					</div>
					<div class="col-md-12 mt-2">
						<div class="row">
							<div class="col-md-3">
								<select v-model="filtro.id_area" class="form-control">
									<option value="">-- Todas AREAS --</option>
									<option v-for="area in areas" :value="area.nodo_id">{{ area.nodo_descripcion }}</option>
								</select>
							</div>
							<div class="col-md-3">
								<input type = "date" class="form-control"  v-model="filtro.fecha_ini">
							</div>
							<div class="col-md-3">
								<input type = "date" class="form-control" v-model="filtro.fecha_fin">
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-2">
						<div class="row">
							<!--
							<div class="col-md-3">
								<select v-model="filtro.cas_tipo" class="form-control" placeholder="Tipo Identificacion">
									<option value="">-- Tipo de Identificacion --</option>
									<option value='AS_CI'>CI</option>
									<option value='AS_CUA'>CUA</option>
								</select>
							</div>
							<div class="col-md-2" v-if="filtro.cas_tipo">
								<input type="number" v-model="filtro.num_identificacion" class="form-control" placeholder="Numero" v-validate="{ required: filtro.cas_tipo }">
							</div>
							-->
							<div class="col-md-2">
								<button class="form-control btn btn-primary" @click="listarRegistrosReporte()">
									<i class="fa fa-search white" aria-hidden="true"></i> Buscar
								</button>
								<div id="overlay" ref="overlay" class="overlay" v-if="loading">
									<div class="loader"></div>
                                    <span class="loader-text">TramiteSIP</span>
                                    <span class="loading-text">Cargando...</span>
								</div>
							</div>
							<div class="col-md-2">
								<!--<button class="btn btn-button btn-success float-right" :disabled="!this.registros.length" @click="generarReporte()">-->
									<button class="btn btn-button btn-success float-right" @click="generarReporte()">
									<i class="fa fa-file-excel" aria-hidden="true"></i> Generar Reporte Excel
								</button>
							</div>

							<div class="col-md-2">
									<button class="btn btn-button btn-warning float-right" @click="generarReporteCsv()">
									<i class="fa fa-file-csv" aria-hidden="true"></i> Generar Reporte CSV
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card-body">
				<table class="table table-hover table-striped table-responsive" id="divTable">
					<thead class="thead-dark">
						<tr>
							<th scope="col">#</th>
							<th scope="col">NODO</th>
							<th scope="col">PROCESO<br>ACTIVIDAD</th>
							<th scope="col">No. CASO</th>
							<th scope="col">CAMPOS CLAVE</th>
							<th scope="col">USUARIO</th>
							<th scope="col">REGISTRADO<br>MODIFICADO</th>
							<th scope="col">ESTADO</th>
							<th scope="col">OPCIONES</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.cas_id }}</td>
							<td scope="row">
								<span class="badge badge-success">{{ r.nodo_codigo }}</span> -
								{{ r.nodo_descripcion }}
								<span class="badge badge-success">{{r.cas_estado}}</span>
							</td>
							<td>
								<strong>{{ r.prc_descripcion }}</strong><br>
								<span class="badge badge-dark">{{ r.act_orden }}</span> -
								{{ r.act_descripcion }}
							</td>
							<td align="center">
								{{ r.cas_cod_id }}
							</td>
							<td>
								<span v-html="r.cas_nombre_caso"></span>
							</td>
							<td>
								{{ r.nom_usuario }}
							</td>
							<td>{{ r.cas_registrado.substr(0, 16) }} <br> {{ r.cas_modificado ? r.cas_modificado.substr(0, 16) : '-' }}</td>
							<td>
								<span class="badge badge-warning">{{ r.est_codigo }}</span>
							</td>
							<td>
								<button  type="button" class="btn btn-primary btn-circle"
										title="Histórico1" v-on:click="doHistorico( r.cas_id, r.cas_padre_id )" data-toggle="modal"
										data-target="#modalHistorico">
										<i class="fa fa-list-ol" aria-hidden="true"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- modalHistorico -->
		<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
				aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document" style="max-width:55%;">
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<h5 class="modal-title" id="exampleModalLabel">Historico {{ singular }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row justify-content-left">
								<div class="col-md-12">
									<label>Caso:</label><br>
									<table class="table table-hover table-striped table-responsive">
										<thead class="thead-dark">
											<tr>
												<th>Nro</th>
												<th>Actividad / ESTADO</th>
												<th>Nodo</th>
												<th>Fecha Derivación</th>
												<th>Usuario</th>
												<th>Estado</th>
												<th>Descripcion</th>
												<th>Listado de Documentos</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(h, index) in historico">
												<td>{{ index + 1 }}</td>
												<td>{{ (JSON.parse(h.act_data)).act_orden  + " - " + (JSON.parse(h.act_data)).act_descripcion }}
													<span class="badge badge-warning"><strong>{{h.est_codigo}}</strong></span>
												</td>
												<td>{{ h.nodo_descripcion }}</td>
												<td>{{ h.htc_cas_registrado }}</td>
												<td>{{ h.nom_usuario }}</td>
												<td>{{ (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</td>
												<td>{{ (JSON.parse(h.htc_cas_data)).DESCRIPCION_DERIVACION }}</td>
												<td align="center">
													<button  type="button" class="btn btn-primary btn-circle"
														title="Histórico1111" v-on:click="doDocumentoPdf(h.htc_id)" data-toggle="modal"
														data-target="#modalDocumentoPdf">
														<i class="fa fa-list-ol" aria-hidden="true"></i>
													</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		<!-- modalDocumentoPdf -->
		<div class="modal fade" id="modalDocumentoPdf" tabindex="-3" role="dialog" aria-labelledby="modalDocumentoPdf"
				aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
					<div class="modal-content">
						<div class="modal-header bg-primary">
							<h5 class="modal-title" id="exampleModalLabel">Documentos {{ singular }}</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="row justify-content-left">
								<div class="col-md-12">
									<label>Documento:</label><br>
									<table class="table table-hover table-striped table-responsive">
										<thead class="thead-dark">
											<tr>
												<th>Nro</th>
												<th>tipo</th>
												<th>Descripcion </th>
												<th>Ver Documento </th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(h, index) in documento">
												<td>{{ index + 1 }}</td>
												<td>{{ h.tipo }}</td>
												<td>{{ h.descripcion }}</td>
												<td align="center">
													<button v-if="h.nombre === ''"  type="button" class="btn  btn-danger  btn-circle "
														title="Derivar" >
														<i class="far fa-file-pdf white " aria-hidden="true"></i>
													</button>

													<!-- <button v-if ="h.nombre != ''" type="button" class="btn  btn-success btn-circle "
														title="Documento" v-on:click="openModal(h.doc_id, h.nombre)">
														<i class="far fa-file-pdf white " aria-hidden="true"></i>
													</button> -->

                                                    <template v-if ="h.nombre!= '' && h.id_doc_base64 == 1">
                                                        <button type="button"
                                                            class="btn  btn-secondary btn-circle " title="Documento"
                                                            v-on:click="openModalXid(h.nombre)">
                                                            <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                        </button>
                                                    </template>
                                                    <template v-else>
                                                        <button v-if="h.nombre != ''" type="button"
                                                            class="btn  btn-success btn-circle " title="Documento"
                                                            v-on:click="openModal(h.doc_id, h.nombre)">
                                                            <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                        </button>
                                                    </template>

												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>

	</div>
</template>

<script>
export default {
	name: 'servicios',
	data() {
		return {
			singular: 'Caso',
			registro: { cas_data: {} },
			registros: [],
			procesos: [],
			areas: [],
			siguiente: { act_data: {} },
			filtro: { prc_codigo: '', cas_nro_caso: '', cas_tipo:'', fecha_ini: this.fechaIni, fecha_fin: '', id_departamento:'', id_agencia: '', id_regional:'' , id_area:''},
			dataTable: null,
			historico: [],
			documento: [],
			departamento:[],
			regional:[],
			agencia:[],
			loading: false,
		}
	},

	mounted() {
		this.dataTable = $('#divTable').DataTable({});
		this.cas_cod_id = '';
		this.listarProcesos();
		this.listarAreas();
		const date = new Date();
		date.setDate(1);
		const year = date.getFullYear();
		const month = date.getMonth() + 1;
		const day = date.getDate();
		const fechaIni = `${year}-${month.toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;

		const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
		const year1 = lastDay.getFullYear();
		const month1 = lastDay.getMonth() + 1;
		const day1 = lastDay.getDate();
		const fechaFin = `${year1}-${month1.toString().padStart(2, '0')}-${day1.toString().padStart(2, '0')}`;
		this.filtro = { prc_codigo: '', cas_nro_caso: '', cas_tipo: '', fecha_ini: fechaIni, fecha_fin: fechaFin, id_departamento: '', id_agencia: '', id_regional:'', id_area:'' };
		//this.listarDepartamento();
		this.listarDepartamento().then(departamentoData => {
			if(departamentoData && departamentoData.length > 0){
				this.listarRegional(departamentoData[0].id_sip_departamento);
			}
		});
	},

	created() {

	},

	methods: {

		openModal(id_documento, url_documento) {
			var url = "/api/verDocumentoPdfNfs/" + id_documento;
			const partes = url_documento.split('.');
			const partes2 = url_documento.split('/');
			axios.get(url, { responseType: 'blob' })
				.then(response => {
					console.log("verDocumentoData", response.data);
					if (partes[1] == 'pdf') {
						const documento = response.data;
						const url = window.URL.createObjectURL(documento);
						const windowProps = 'top=0,left=0,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=650';
						const newWindow = window.open(url, '_blank', windowProps);
						newWindow.document.body.innerHTML = `<iframe src="${url}" width="100%" height="100%"></iframe>`;
					} else {
						const documento = response.data;
						const blob = new Blob([documento], { type: 'application/pdf' });
						const url = window.URL.createObjectURL(blob);
						const link = document.createElement('a');
						link.href = url;
						link.setAttribute('download', partes2[6]); // Cambia 'nombre_del_archivo.pdf' según necesites
						document.body.appendChild(link);
						link.click();
						document.body.removeChild(link);
					}
				})
				.catch(error => {
					console.error('Error al mostrar el documento:', error);
				});
		},

        openModalXid(id_documento) {
            console.log('id_documento', id_documento);

            const payload = {
                id_documento: id_documento
            };

            axios.post('/api/getDocumentosIdApiUcpp', payload, { responseType: 'blob' })
                .then(response => {
                    if (response && response.data) {
                        const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
                        const url = URL.createObjectURL(pdfBlob);

                        let modal = document.getElementById('modalPdfBase64');
                        if (!modal) {
                            modal = document.createElement('div');
                            modal.id = 'modalPdfBase64';
                            modal.className = 'modal fade';
                            modal.tabIndex = -1;
                            modal.role = 'dialog';
                            modal.innerHTML = `
                                <div class="modal-dialog modal-lg" role="document" style="max-width: 50%;">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title">Documento PDF</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modalPdfBase64').modal('hide')">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="height:80vh;">
                                            <iframe id="iframePdfBase64" src="" width="100%" height="100%" style="border:none;"></iframe>
                                        </div>
                                    </div>
                                </div>
                            `;
                            document.body.appendChild(modal);
                        }
                        document.getElementById('iframePdfBase64').src = url;
                        $('#modalPdfBase64').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo obtener el PDF.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al llamar getDocumentosIdApiUcpp:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo obtener el PDF.',
                    });
                });
        },


		listarRegistrosReporte() {
			this.loading = true;
			let gRegistro = this.filtro;
			let url = "api/buscarCasosReporte";
			axios.post(url, gRegistro)
			.then(response => {
				this.registros = response.data.data;
			})
			.finally(() => {
				this.loading = false;
				});
		},

		generarReporte() {
    this.loading = true;
    let gRegistro = this.filtro;
    let url = "api/generarReporte";
    axios.post(url, gRegistro, { responseType: 'blob' }) // Agrega responseType: 'blob'
        .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            // Obtener el nombre del archivo de la cabecera Content-Disposition
            let filename = response.headers['content-disposition'].split('filename=')[1].replace(/['"]/g, '');
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            link.remove();
        })
        .finally(() => {
            this.loading = false;
        });
},

		generarReporteCsv(){
			this.loading = true;
			let gRegistro = this.filtro;
			let url = "api/generarReporteCsv";
			axios.post(url, gRegistro, { responseType: 'blob' })
			.then(response =>{
				 const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
			let filename = response.headers['content-disposition'].split('filename=')[1].replace(/['"]/g, '');
            link.setAttribute('download', filename);
            //link.setAttribute('download', 'reporte.csv'); // Usar nombre del archivo
            document.body.appendChild(link);
            link.click();
            link.remove();
			})
			.finally(() => {
				this.loading = false;
			});
		},

		listarProcesos() {
			let url = "api/procesosTodos";
			axios.get(url).then(response => {
				this.procesos = response.data.data; //twice data
				this.procesos.forEach(function (row) {
					row.prc_data = JSON.parse(row.prc_data);
				});
			});
		},

		listarAreas() {
			let url = "api/listarAreas";
			axios.get(url).then(response => {
				this.areas = response.data.data;
			});
		},
		//historico
		doHistorico(id, id_padre) {
			console.log("doHistorico",id);
			console.log("id_padre",id_padre);
			var id_caso;
			if(id_padre == 0){
				id_caso = id;
			} else {
				id_caso = id_padre;
			}
			let that = this;
			let url = "api/casosHistorico/" + id_caso;
			axios.get(url)
				.then((response) => {
					this.historico = response.data.data;
				})
				.catch(function (error) {
					that.output = error;
				});
		},
		doDocumentoPdf(htc_id) {
			console.log("htc_id",htc_id);
			const datos = { htc_id: htc_id };
			axios.post('api/obtenerDocumento', datos)
				.then(response => {
					console.log("doDocumentoPdf",response.data.data);
					this.documento = response.data.data;
				})
				.catch(error => {
					console.error('Error al generar al listado', error);
				});
		},

		verImagen: function (ruta) {
			window.open(ruta, '_blank');
		},

		async listarDepartamento() {
			let url = "api/buscarDepartamento";
			axios.get(url).then(response => {
				this.departamento = response.data.data;
				return response.data.data;
			});
		},

		async listarRegional(id_departamento){
			this.regional = [];
			this.agencia = [];

			if(!id_departamento){
				this.filtro.id_agencia= '';
				this.filtro.id_regional='';
				return;
			}
			console.log("333");
			let url = 'api/buscarRegional';
			var params = {"id_departamento": id_departamento};
			axios.post(url, params).then(response => {
				this.regional = response.data.data;
				return response.data.data;
			});
		},

		async listarAgencia(id_regional){
			this.agencia = [];
			if(!id_regional){
				return;
			}
			let url = 'api/buscarAgencia';
			var params = {"id_regional": id_regional};
			axios.post(url, params).then(response =>{
				this.agencia = response.data.data;
				return response.data.data;
			})
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

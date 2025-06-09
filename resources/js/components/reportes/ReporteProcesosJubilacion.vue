<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>Reporte Jubilacion</h5>
				<div class="row">

			
					<div class="col-md-12 mt-2">
						<div class="row">
					
							<div class="col-md-2">
								<button class="form-control btn btn-primary" @click="listarRegistrosReporte()">
									<i class="fa fa-search white" aria-hidden="true"></i> Buscar
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
							<th scope="col">DEPARTAMENTO</th>
							<th scope="col">REGIONAL</th>
							<th scope="col">AGENCIA</th>
							<th scope="col">NOMBRE ASEGURADO</th>
							<th scope="col">CUA</th>
							<th scope="col">SUBCLASIFICACION</th>
							<th scope="col">FECHA DE NOTIFICACION DE VERIFICACION DE EAP</th>
							<th scope="col">ESTADO DE SOLICITUD</th>
							<th scope="col">ANALISTA QUE REALIZO EL CALCULO</th>
							<th scope="col">CUMPLE/ NO CUMPLE</th>
							<th scope="col">FECHA DE DERIVACION REVISOR</th>
							<th scope="col">ANALISTA REVISOR</th>
							<th scope="col">FECHA DERIVACION APROBADOR</th>
							<th scope="col">ANALISTA APROBADOR</th>
							<th scope="col">FECHA DERIVACION OFICINA NACIONAL</th>
							<th scope="col">OFICINA NACIONAL</th>
							<th scope="col">FECHA DERIVACION ATC</th>
							<th scope="col">PLAZO PARA CALCULO</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(r, index) in registros">
							<td width="3%" scope="row">{{ r.departamento }}</td>
							<td width="3%" scope="row">{{ r.regional }}</td>
                            <td width="3%" scope="row">{{ r.agencia }}</td>
                            <td width="3%" scope="row">{{ r.nombre_asegurado }}</td>
                            <td width="3%" scope="row">{{ r.cua }}</td>
                            <td width="3%" scope="row">{{ r.subclasificacion }}</td>
                            <td width="3%" scope="row">{{ r.fecha_solicitud }}</td>
                            <td width="3%" scope="row">{{ r.fecha_notificacion_verificacion_eap }}</td>
                            <td width="3%" scope="row">{{ r.estado_solicitud }}</td>
                            <td width="3%" scope="row">{{ r.analista_calculo }}</td>
                            <td width="3%" scope="row">{{ r.estado_calculo }}</td>
                            <td width="3%" scope="row">{{ r.fecha_derivacion_revisor }}</td>
                            <td width="3%" scope="row">{{ r.analista_revisor }}</td>
                            <td width="3%" scope="row">{{ r.fecha_derivacion }}</td>
                            <td width="3%" scope="row">{{ r.analista_aprobador }}</td>
                            <td width="3%" scope="row">{{ r.fecha_derivacion_nacional }}</td>
                            <td width="3%" scope="row">{{ r.oficina_nacional }}</td>
                            <td width="3%" scope="row">{{ r.fecha_derivacion_atc }}</td>
                            <td width="3%" scope="row">{{ r.plazo_calculo_dias }}</td>


							
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- modalHistorico -->
		<div class="modal fade" id="modalHistorico" tabindex="-1" role="dialog" aria-labelledby="modalHistorico"
				aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
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
												<th>Actividad</th>
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
												<td>{{ (JSON.parse(h.act_data)).act_orden  + " - " + (JSON.parse(h.act_data)).act_descripcion }}</td>
												<td>{{ h.nodo_descripcion }}</td>
												<td>{{ h.htc_cas_registrado }}</td>
												<td>{{ h.nom_usuario }}</td>
												<td>{{ (JSON.parse(h.htc_cas_data)).ESTADO_DERIVACION }}</td>
												<td>{{ (JSON.parse(h.htc_cas_data)).DESCRIPCION_DERIVACION }}</td>
												<td align="center">
													<button  type="button" class="btn btn-primary btn-circle"
														title="Histórico" v-on:click="doDocumentoPdf(h.htc_id)" data-toggle="modal"
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
						
													<button v-if ="h.nombre != ''" type="button" class="btn  btn-success btn-circle "
														title="Derivar" v-on:click="openModal(h.url_documento)">
														<i class="far fa-file-pdf white " aria-hidden="true"></i>
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

		async openModal(htc_id) {
			window.open(`${htc_id}`, '_blank');
		},

		listarRegistrosReporte() {
			let gRegistro = this.filtro;
			let url = "api/casosReportejubilacion";
			console.log("gRegistro", gRegistro);
			axios.post(url, gRegistro).then(response => {

            console.log('data  de respuesta ',response.data.data);
				this.registros = response.data.data; //twice data
				
			});
		},


		generarReporte(){
			let gRegistro = this.filtro;
			let url = "api/generarReporte";
			axios.post(url, gRegistro)
			.then(response =>{
				const currentURL = window.location.href;
				const baseURL = new URL(currentURL).origin + '/' + new URL(currentURL).pathname.split('/')[0];
				const relativePath = 'reportes_generados/'+ response.data.nombreArchivo;
				const completeUrl = `${baseURL}${relativePath}`;
				const nombreArchivo = response.data.nombreArchivo;
				var link = document.createElement('a');
				link.href = completeUrl;
    			link.download = nombreArchivo;
				console.log("link.download ",link.download ); 
				link.click();
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
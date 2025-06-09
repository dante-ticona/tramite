<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5>{{ plural }}</h5><br>
				<div class="row">
					<div class="col-md-2">
						<select v-model="codigo_doc.prc_codigo" class="form-control">
							<option value="">-- Seleccione Trámite --</option>
							<option v-for="proceso in procesos" :value="proceso.prc_data.prc_codigo">{{
								proceso.prc_data.prc_codigo }} - {{ proceso.prc_data.prc_descripcion }}</option>
						</select>
					</div>
					/
					<div class="col-md-2">
						<input type="number" v-model="codigo_doc.cas_nro_caso" class="form-control"
							placeholder="Nro. Caso">
					</div>
					/
					<div class="col-md-2">
						<select v-model="codigo_doc.cas_gestion" class="form-control">
                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
						</select>
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-success" @click="buscarDocumentos()">
							<i class="fa fa-search white" aria-hidden="true"></i> Buscar Documentos
						</button>
					</div>
					<div class="col-md-2">
						<button class="form-control btn btn-primary" @click="limpiarBusqueda()">
							<i class="fa fa-eraser white" aria-hidden="true"></i> Limpiar Búsqueda
						</button>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive" v-if="registros.length > 0">
					<div style="background-color:lightgrey; margin-top:10px;">
						<strong>
							<h4>Trámite Seleccionado:</h4>
						</strong>
					</div>
					<table class="table table-hover table-striped">
						<thead class="thead-dark">
							<tr>
								<th style="text-align: center">CASO ID</th>
								<th style="text-align: center">NRO TRÁMITE</th>
								<th>TIPO TRÁMITE</th>
								<th style="text-align: center">DATOS ASEGURADO</th>
								<th>ACTIVIDAD</th>
								<th>NODO</th>
								<th style="text-align: center">ESTADO DERIVACIÓN</th>
								<th style="text-align: center">USUARIO</th>
							</tr>
						</thead>
						<tbody>
							<tr v-if="registro">
								<td align="center"><span class="badge badge-dark">{{ registro.cas_id }}</span></td>
								<td align="center"><span class="badge badge-success">{{ registro.cas_cod_id }}</span></td>
								<td>{{ registro.cas_data.NOMBRE_PROCESO }}</td>
								<td align="center"><span v-html="registro.cas_data.cas_nombre_caso"></span></td>
								<td><span class="badge badge-danger">{{ (JSON.parse(registro.act_data)).act_orden }}</span> -
									{{ (JSON.parse(registro.act_data)).act_descripcion }}
								</td>
								<td>{{ registro.nodo_codigo }} - {{ registro.nodo_descripcion }}</td>
								<td align="center">
									<span v-if="registro.cas_data.ESTADO_DERIVACION !== 'ANULADO' && registro.cas_data.ESTADO_DERIVACION !== 'DESISTIDO'"
									class="badge badge-success">
									{{ registro.cas_data.ESTADO_DERIVACION }}</span>
									<span v-else class="badge badge-danger">
									{{ registro.cas_data.ESTADO_DERIVACION }}</span>
								</td>
								<td align="center"><span v-if="registro.nom_usuario === ''" class="badge badge-secondary">SIN
										USUARIO</span>
									<span v-if="registro.nom_usuario !== ''" class="badge badge-secondary">{{
										registro.nom_usuario }}</span>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<br>
				<div class="table-responsive" v-if="registros.length > 0">
					<div style="background-color:lightgrey; margin-top:10px;">
						<strong>
							<h4>Lista de Documentos:</h4>
						</strong>
					</div>
					<table class="table table-hover table-striped" id="divTable">
						<thead class="thead-dark">
							<tr>
								<th scope="col">#</th>
								<th scope="col">DESCRIPCIÓN DOCUMENTO</th>
								<th scope="col" style="text-align: center;">ESTADO</th>
								<th scope="col">REFERENCIA</th>
								<th scope="col" style="text-align: center;">FECHA REGISTRO</th>
								<th scope="col" style="text-align: center;">TIPO DOCUMENTO</th>
								<th scope="col" style="text-align: center;">DOCUMENTO</th>
							</tr>
						</thead>
						<tbody>

							<tr v-for="(r, index) in registros">
								<td> <span class="badge badge-dark">{{ index + 1 }}</span></td>
								<td scope="row"><strong>{{ r.doc_descripcion }}</strong></td>
								<td align="center">
									<span v-if="r.doc_estado === 'A'" class="badge badge-success">ACTIVO</span>
									<span v-if="r.doc_estado === 'B'" class="badge badge-danger">BAJA</span>
									<span v-if="r.doc_estado === ''" class="badge badge-dark">SIN ESTADO</span>
								</td>
								<td>
									<span v-if="r.doc_referencia === '0-TIT'">DOCUMENTO TITULAR</span>
									<span v-else-if="r.doc_referencia === '0-SOL'">DOCUMENTO SOLICITANTE</span>
									<span v-else-if="r.doc_referencia === '1-HIJ'">DOCUMENTO HIJO (A)</span>
									<span v-else-if="r.doc_referencia === 'ADJUNTOS'">DOCUMENTO ADJUNTO</span>
									<span v-else-if="r.doc_referencia === '4-RES'">RESPALDO</span>
									<span v-else-if="r.doc_referencia === 'documento_EAP'">DOCUMENTO EAP</span>
									<span v-else-if="r.doc_referencia === '1-CONY'">DOCUMENTO CONYUGUE</span>
									<span v-else-if="r.doc_referencia === '3-SOB'">DOCUMENTO SOBRINO (A)</span>
									<span v-else-if="r.doc_referencia === '1-CONV'">DOCUMENTO CONVIVIENTE</span>
									<span v-else-if="r.doc_referencia === '2-HER'">DOCUMENTO HERMANO (A)</span>
									<span v-else-if="r.doc_referencia === '3-TIO'">DOCUMENTO TIO (A)</span>
									<span v-else-if="r.doc_referencia === '2-MAD'">DOCUMENTO MADRE</span>
									<span v-else-if="r.doc_referencia === '2-PAD'">DOCUMENTO PADRE</span>
									<span v-else-if="r.doc_referencia === '3-OTR'">OTROS DOCUMENTOS</span>
									<span v-else>{{ r.doc_referencia }}</span>
								</td>
								<td align="center">
                                    <i class="fas fa-calendar" style="color:#274690"></i> {{ r.doc_registrado.substr(0, 10) }} <br>
                                    <i class="fas fa-clock" style="color:#274690"></i>
                                    {{ r.doc_registrado.substr(10, 5) }} <br>
								</td>
								<td align="center">
									<span v-if="r.doc_copia_original === 'false'"
										class="badge badge-success">ORIGINAL</span>
									<span v-if="r.doc_copia_original === 'true'"
										class="badge badge-secondary">FOTOCOPIA</span>
									<span v-if="r.doc_copia_original === null" class="badge badge-warning">NO
										ESPECIFICADO</span>
								</td>
								<td align="center">
									<button v-if="r.doc_url === ''" type="button" class="btn btn-danger btn-circle "
										title="Sin Documentos" v-on:click="mensajeDocumento()">
										<i class="far fa-file-pdf white " aria-hidden="true"></i>
									</button>
									<!-- <button v-if="r.doc_url != ''" type="button" class="btn btn-success btn-circle "
										title="Ver Documento" v-on:click="verDocumento(r.doc_id, r.doc_url)">
										<i class="far fa-file-pdf white " aria-hidden="true"></i>
									</button> -->

                                    <template v-if ="r.doc_url!= '' && r.id_doc_base64 == 1">
                                        <button type="button"
                                            class="btn  btn-secondary btn-circle " title="Documento"
                                            v-on:click="verDocumentoXId(r.doc_url)">
                                            <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button v-if="r.doc_url != ''" type="button"
                                            class="btn  btn-success btn-circle " title="Documento"
                                            v-on:click="verDocumento(r.doc_id, r.doc_url)">
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
	</div>
</template>

<script>
import datatables from 'datatables';
import jsPDF from 'jspdf';
import Swal from 'sweetalert2';
export default {
	name: 'servicios',
	data() {
		return {
			plural: 'Buscar Documentos',
			singular: 'Documentos',
			usrId: window.Laravel.usr_id,
			id_regional: window.Laravel.id_regional,
			id_agencia: window.Laravel.id_agencia,
			es_atc: window.Laravel.es_atc,
			es_supervisor: window.Laravel.es_supervisor,
			registros: [],
			registro: null,
			procesos: [],
			codigo_doc: { prc_codigo: '', cas_nro_caso: '', cas_gestion: '' },
			dataTable: null,

            years: [],

		}
	},

	mounted() {
		const current = new Date();
		const yyyy = current.getFullYear();
		this.codigo_doc = { prc_codigo: '', cas_nro_caso: '', cas_gestion: yyyy };
		this.dataTable = $('#divTable').DataTable({});
		this.listarProcesos();
        this.populateYears();
	},

	created() {

	},

	methods: {

		listarProcesos() {
			let url = "api/procesosTodos";
			axios.get(url).then(response => {
				this.procesos = response.data.data;
				this.procesos.forEach(function (row) {
					row.prc_data = JSON.parse(row.prc_data);
				});
			});
		},

        populateYears() {
            const currentYear = new Date().getFullYear();
            const startYear = 2023;
            for (let year = startYear; year <= currentYear; year++) {
                this.years.push(year);
            }
        },

        mensajeDocumento() {
            this.$swal.fire({
                icon: 'warning',
                title: 'Sin documento disponible',
                text: 'No se ha encontrado el documento solicitado',
                confirmButtonText: 'Aceptar'
            });
        },

		verDocumento(id_documento, url_documento) {
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
						link.setAttribute('download', partes2[6]);
						document.body.appendChild(link);
						link.click();
						document.body.removeChild(link);
					}
				})
				.catch(error => {
					console.error('Error al mostrar el documento:', error);
				});
		},

        verDocumentoXId(id_documento) {
            console.log('id_documento >>>>', id_documento);

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

		buscarDocumentos() {
			let url = "api/buscarDocumentos";
			let gRegistro = this.codigo_doc;
			axios.post(url, gRegistro).then(response => {
				if (response.data.data.length === 0) {
					this.limpiarBusqueda();
					this.$swal.fire({
						icon: 'warning',
						title: 'No Existen Documentos Para Este Trámite',
						text: 'Revise su búsqueda',
                        confirmButtonText: 'Aceptar'
					});
				}
				else if (response.data.data.length !== 0) {
					this.$swal.fire({
						icon: 'success',
						title: 'Documentos Encontrados Exitosamente!',
                        confirmButtonText: 'Aceptar'
					});
					this.registros = response.data.data;
					this.registros.forEach(function (row) {
						row.cas_data = JSON.parse(row.cas_data);
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso ? row.cas_data.cas_nombre_caso : ''
						row.cas_data.cas_nombre_caso = row.cas_data.cas_nombre_caso.replaceAll('undefined', '-');
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('null', "") : '';
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('||', "<br/>") : '';
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('|', "<br/>") : '';
						row.cas_data.cas_nombre_caso = (row.cas_data.cas_nombre_caso) ? row.cas_data.cas_nombre_caso.replaceAll('<br/><br/>', "<br/>") : '';
					});
					this.registro = (this.registros.length > 0) ? this.registros[0] : null;
					console.log('registros', this.registros);
					console.log('registro', this.registro);

				}
			});
		},

		limpiarBusqueda() {
			this.$swal.fire({
				icon: 'success',
				title: 'Búsqueda Limpiada',
                confirmButtonText: 'Aceptar'
			});
			this.codigo_doc = { prc_codigo: '', cas_nro_caso: '', cas_gestion: new Date().getFullYear() };
			this.registros = [];
			this.registro = null;
			if (this.dataTable) {
				this.dataTable.destroy();
			}
			this.$nextTick(() => {
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
							"previous": "Atras"
						},
					}
				});
			});
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
					"previous": "Atras"
				},
			}
		})
	}
}
</script>
<style>
.color-gris {
	background-color: #f2f2f2;
	color: white;
}

.color-gris-light {
	background-color: #f7f7f7;
}

.margen-bottom {
	margin-bottom: 15px;
}
</style>

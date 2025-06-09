<style>
@import 'vue-select/dist/vue-select.css';

.custom-width-select {
    width: 70%;
    /* O ajusta el ancho según tus necesidades */
}
</style>

<style scoped>
.modalNoti1 {
    position: fixed;
    top: 90%;
    right: 10px;
    margin: 0;
    width: 220px;
}

.modalNoti3 {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    background-color: rgba(255, 255, 255, 0.9);
    max-width: 400px;
}

.modalnoti2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

.notification-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: #333333;
    text-align: center;
}

.noti-icon {
    margin-right: 10px;
    background-color: rgba(40, 167, 69, 0.2);
    padding: 5px;
    border-radius: 50%;
}

.noti-icon i {
    color: #28a745;
}

.notificacion-menssage {
    display: flex;
    align-items: center;
    padding-left: 10px;
    border-left: 2px solid #ccc;
    font-size: 15px;
    font-family: 'Roboto', sans-serif;
}

/* Estilo para el mensaje de carga */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    /* Fondo semi-transparente */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.loading-message {
    background-color: #fff;
    /* Fondo del mensaje */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    /* Sombra ligera */
    text-align: center;
}

.loading-message img {
    width: 50px;
    /* Ajusta el tamaño del ícono según sea necesario */
    height: 50px;
    margin-bottom: 10px;
    /* Espacio entre el ícono y el texto */
}

.tabla-con-borde-transparente {
    border-collapse: collapse;
    margin: 0;
    /* Para fusionar los bordes de las celdas */
}

.tabla-con-borde-transparente th,
.tabla-con-borde-transparente td {
    border: 1px solid transparent;
    margin: 0;
    padding: 0px;
    /* Establece el borde transparente */
}

.navegacion {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #1a1e23;
}

.selectRegistros {
    padding: 0.5rem 1rem;
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    background-color: #fff;
    line-height: 1.5;
    color: #212529;
}

.btnEAvance {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    font-weight: 400;
    text-align: center;
    line-height: 1.5;
    background-color: #007bff;
    color: #fff;
    user-select: none;
    border: 1px solid transparent;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btnEAvance:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #0056b3;
}

.btnEAvance.anterior {
    margin-right: 5px;
}

.btnEAvance.siguiente {
    margin-left: 5px;
}

@media only screen and (max-width: 768px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 1135px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}

@media only screen and (max-width: 486px) {
    .navegacion {
        flex-direction: column;
        align-items: center;
    }

    .btnEAvance {
        margin: 5px 0;
    }

    .selectRegistros {
        align-content: center;
    }

    .btnEAvance.anterior {
        margin-right: 0;
    }

    .btnEAvance.siguiente {
        margin-left: 0;
    }
}
</style>

<style scoped>
.break-text {
    display: inline-block;
    max-width: 70ch;
    word-wrap: break-word;
    white-space: pre-wrap;
    text-align: left;
}
</style>
<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5 class="tittle-margin">{{ plural }}</h5>
			</div>

			<div class="card-body">

                <div v-if="showLoader2" class="loader-container2">
                    <div class="loader-wrapper">
                        <div class="loader"></div>
                        <span class="loader-text">TramiteSip</span>
                        <span class="loading-text">Cargando...</span>
                    </div>
                </div>

				<div class="row">
					<div class="col-md-3">
						<label>Tipo de Documento</label>
                        <select v-model="registro.tipoDocumento" class="form-control" required>
                            <option value="I">CEDULA DE IDENTIDAD</option>
                            <option value="E">CEDULA EXTRANJERO</option>
                            <option value="P">PASAPORTE</option>
                        </select>
						<p v-if="!registro.tipoDocumento" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-2">
						<label v-show="registro.tipoDocumento !== 'T'">Número de Documento</label>
						<input type="text" v-model="registro.numeroDocumento" class="form-control"
							placeholder="Ingrese Nro. Documento" v-show="registro.tipoDocumento !== 'T'" @keyup.enter="consultarRegistro($event)">
						<p v-if="registro.tipoDocumento !== 'T' && !registro.numeroDocumento" class="mensaje">
							Obligatorio</p>
					</div>

					<div class="col-md-2">
						<label>Complemento</label>
						<input type="text" v-model="registro.complemento" class="form-control"
							placeholder="Ingrese Complemento" @keyup.enter="consultarRegistro($event)">
							<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.complemento" class="mensaje2">Opcional</p>
					</div>

                    <div class="col-md-2">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" v-model="registro.fechaNacimiento" class="form-control" @keyup.enter="consultarRegistro($event)">
                        <p v-if="registro.fechaNacimiento == ''" class="mensaje">Obligatorio</p>
                    </div>

                    <div class="col-md-2">
                        <button class="custom-btn"  @click="consultarRegistro($event)">
                            <i class="fa fa-search white" aria-hidden="true"></i> Consultar </button>
                    </div>
				</div>

			</div>
			<div class="card-footer">
				<div class="modal fade" id="modalConsultar" tabindex="-1" role="dialog" aria-labelledby="modalRegistrar"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-primary">
								<h5 class="modal-title" id="exampleModalLabel"> ¿Enviar {{ singular }}?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row justify-content-left">
									<div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{
										error }}</span>
									</div>
								</div>
								¿COMPLETO TODOS LOS DATOS?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" @click="guardarRegistro($event)"
									data-dismiss="modal">Guardar</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="modalRegistrar" tabindex="-1" role="dialog" aria-labelledby="modalRegistrar"
					aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header bg-primary">
								<h5 class="modal-title" id="exampleModalLabel"> ¿Enviar {{ singular }}?</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row justify-content-left">
									<div v-for="error in errores" class="col-md-3"><span class="badge badge-danger">{{
										error }}</span>
									</div>
								</div>
								¿COMPLETO TODOS LOS DATOS?
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
								<button type="button" class="btn btn-primary" @click="guardarRegistro($event)"
									data-dismiss="modal">Guardar</button>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="container-fluid">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" >
                        <thead v-if="ordenarDatos.length" class="thead-dark">
                            <tr>
                                <th scope="col" class="text-center">NRO</th>
                                <th scope="col" class="text-center">TIPO PERSONA</th>
                                <th scope="col" class="text-center">ACCEDE</th>
                                <th scope="col" class="text-center">TIPO</th>
                                <th scope="col" class="text-center">TITULAR</th>
                                <th scope="col" class="text-center">CUA</th>
                                <th scope="col" class="text-center">OPCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(r, index) in ordenarDatos" >
                                <th scope="col" class="text-center">
                                    {{ index+1 }}
                                </th>
                                <td width="3%" scope="row" class="text-center">{{ r.tipoPersona }}</td>
                                <td scope="row" class="text-center">
                                    <span class="badge badge-success" v-if="r.accede == 'ACCEDE'"> {{ r.accede }} </span>
                                    <span class="badge badge-warning" v-else>{{ r.accede }} </span>
                                </td>
                                <td class="text-center"><div v-if="r.accede == 'ACCEDE'"></div>{{ r.tipo }}</td>
                                <td align="center">{{ r.titular }}</td>
                                <td class="text-center">{{ r.cuaTitular }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary" @click="verificarDatos(r)" data-dismiss="modal" v-if="r.estado == 'CREADO' || r.estado == 'ELABORADO' || r.estado == 'RECHAZADO'">
                                        <i class="fa fa-address-book" aria-hidden="true"></i> Procesar
                                    </button>

                                    <div v-if="r.estado == 'GRABADO'">LA SOLICITUD YA SE ENCUENTRA EN PROCESO</div>

                                    <div v-if="r.estado != 'GRABADO' && r.estado != 'CREADO'">YA SE ATENDIÓ LA SOLICITUD</div>

                                    <div v-if="r.estado === 'RECHAZADO'"></div>
                                        <button v-if="opciones === 'success'" type="button"  v-on:click="visualizarPDF1582RechazadoSIP(r, r.id, r.reporteRechazo, 2)" class="btn btn-success btn-circle" title="VER PDF GUARDADO">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                        <button v-else-if="opciones === 'warning'" type="button"  v-on:click="visualizarPDF1582RechazadoSIP(r, r.id, r.reporteRechazo, 1)" class="btn btn-warning btn-circle" title="RECUPERAR NOTA DE RECHAZO">
                                            <i class="fas fa-bars" aria-hidden="true"></i>
                                        </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
		</div>

        <!-- modalSubirArchivos -->
        <div class="modal fade" id="modalSubirArchivos" tabindex="-1" role="dialog" aria-labelledby="modalSubirArchivos"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="exampleModalLabel">Derivar el {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal Previzualizar PDF -->
        <div class="modal fade" id="modalPrevisualizar" tabindex="-1" role="dialog" aria-labelledby="modalPrevisualizar" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Mostrar PDF</h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="showLoader" class="espaciado"></div>
                        <div v-if="showLoader" class="loader-container">
                            <div class="loader-wrapper">
                                <div class="loader"></div>
                                <span class="loader-text">TramiteSip</span>
                                <span class="loading-text">Cargando...</span>
                            </div>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-primary"
                                @click="downloadPDF()">
                                <i class="fas fa-file-text" aria-hidden="true"></i> Descargar PDF
                            </button> <br><br>
                            <embed :src="getSanitizedPdfSrc()" type="application/pdf" width="100%" height="400px">
                            <!-- <canvas id="pdfCanvas" style="max-width: 150%; max-height: 800px;"></canvas> -->
                        </div>
                        <div class="col-md-8">
                            <label for="firmado_archivo">SUBIR ARCHIVO FIRMADO POR SOLICITANTE:*</label>
                            <input type="file" id="firmado_archivo" name="firmado_archivo" class="form-control" @change="subirPdfParafirmar($event)" required />
                            <p v-if="documento !== '' && !documento" class="mensaje"> Obligatorio</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="guardarDatosNoAccede()" >No subir Archivo(El asegurado no quiere firmar)</button>
                        <button type="button" class="btn btn-secondary" @click="guardarPDF(1)" >Guardar documento firmado - Finalizar Proceso</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modalPrevisualizarRechazo" tabindex="-1" role="dialog" aria-labelledby="modalPrevisualizar" aria-hidden="true">
            <div class="modal-dialog lg" role="document" style="max-width: 60%;">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Mostrar - PDF </h5>
                    </div>
                    <div class="modal-body">
                        <div v-if="showLoader" class="espaciado"></div>
                        <div v-if="showLoader" class="loader-container">
                            <div class="loader-wrapper">
                                <div class="loader"></div>
                                <span class="loader-text">TramiteSip</span>
                                <span class="loading-text">Cargando...</span>
                            </div>
                        </div>
                        <div v-else>
                            <button type="button" class="btn btn-primary"
                                @click="downloadPDF()">
                                <i class="fas fa-file-text" aria-hidden="true"></i> Descargar PDF
                            </button> <br><br>
                            <embed :src="getSanitizedPdfSrc()" type="application/pdf" width="100%" height="400px">

                            <div class="col-md-8" v-if="swVer">
                                <label for="firmado_archivo">SUBIR ARCHIVO FIRMADO POR SOLICITANTE:*</label>
                                <input type="file" id="firmado_archivo" name="firmado_archivo" class="form-control" @change="subirPdfParafirmar($event)" required />
                                <p class="mensaje"> Obligatorio</p>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="guardarPDF(2)" v-if="swVer">Guardar Documento</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modalCrear -->
        <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="modalCrear"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="exampleModalLabel">Crear {{ singular }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label> {{mensaje}} </label> <br>
                                <label>Corresponde Recalculo</label> <br>
                                <label>¿ Confirma crear el {{ singular }} ?</label> <br>
                                {{ codigo }} - {{ descripcion }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="generarRechazo()" data-dismiss="modal">No, Generar nota de Rechazo</button>
                        <button type="button" class="btn btn-success" @click="crearCaso()" data-dismiss="modal"> Si, CREAR TRÁMITE </button>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import axios from 'axios';
import config from './config.js';
import Swal from 'sweetalert2';
import jsPDF from 'jspdf';
import VisorPDF from './VisorPDF.vue';
import Cargando from './Cargando.vue';
import vSelect from 'vue-select';
import {encryptId} from './shared/AuxiliaryFunctions';

export default {
	data() {
		return {
			plural: 'Registro de Trámites Recalculo 1582',
			singular: 'Registro',
			registro: {
				tipoDocumento: "I",
				numeroDocumento: "",
				complemento: "",
				fechaNacimiento: "",
				documentoIdentidad: "",
				usuCre: "SistemaJubilacion",
				urlPersonas: window.Laravel.url_personas,  //config.URL_PERSONAS + '',
				urlGestora: window.Laravel.url_gestora_sgg,  //config.URL_GESTORA_SGG + '',
				urlGestora1: window.Laravel.url_gestora_sgg1, //config.URL_GESTORA_SGG1 + '',
			},

            documento: '',
            codigo: '',
			errores: [],
			listaPaises: [],
			ordenarDatos:[],
            pdfSrc: '',
            currentPage: 1,
            totalPage: 0,
            pdf: null,

            // CREAR CASO
			plural: 'Ley 1582',
			singular: 'Caso',
			descripcion : '',
			usrId: window.Laravel.usr_id,
			usr_user: window.Laravel.usr_user,
			id_regional: window.Laravel.id_regional,
			id_agencia: window.Laravel.id_agencia,
			id_departamento: window.Laravel.id_departamento,
			seleccionado: '',
			errores: [],
			// registro: {},
			registros: [],
			procesos: [],
			departamento: {},
			primer_act_id: 0,
			primer_act_nodo_id: 0,
			primer_prc_id: 0,
			dataTable: null,
			prc_codigo: '',
			datosCua:{},
            _cua: '',
            showLoader: false,
            showLoader2: false,

            // variables servicio
            _id:'',
            _tipoActualizacion:'',
            _rutaDocumento:'',
            _nroTramite: '',
            _motivoRechazo: '',
            _usuario:'',
            _cuaTitular:'',
            _reporteRechazo:'',
            mensaje:'',
            doc_url: '',

            firmado_archivo: null,
            opciones : '',
            tipoConsumo:'',
            _estado:'',
            swVer : false
		};
	},
	created() {
	},

	mounted() {
		this.listarProcesos();
		this.listarRegistros();
	},

	methods: {
		guardarRegistro(){

		},
		consultarRegistro(e) {
            this.opciones = '';
			let datos = this.registro.fechaNacimiento.split('-');
			let fechaNac = datos[2]+'/'+datos[1]+'/'+datos[0];
			let url = "api/buscarBeneficiario1582";
            var params = {
                "tipo_documento": this.registro.tipoDocumento,
                "numero_documento": this.registro.numeroDocumento,
                "complemento": this.registro.complemento,
                "fecha_nacimiento": fechaNac
            };

            if (!this.registro.fechaNacimiento) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "La fecha de nacimiento es obligatoria",
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar',
                    timer: 2000
                });
                return;
            }
            try {
                axios.post(url, params).then(response => {
                    console.log('response.data.data ',response.data);

                    if (response.data.data && response.data.data.length > 0) {
                        this.visualizarBotones(response.data.data[0].id);
                    }

                    this.ordenarDatos = [];
                    if(response.data.codigo == '200'){
                        console.log("POR 200");
                        this.ordenarDatos = response.data.data;
                        console.log("ordenarDatos: ", this.ordenarDatos);
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: response.data.mensaje,
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                timer: 2000
                            });

                    } else {
                        console.log("POR 400");
                        Swal.fire({
                            position: "center",
                            icon: "warning",
                            title: response.data.mensaje,
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar',
                            timer: 2000
                        });
                    }
                });
            }
            catch(e){
                console.error("Error al subir el archivo:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Advertencia',
                    text: 'Favor volver a realizar la consulta, si el error continua, favor ',
                    showConfirmButton: true
                });
            }
		},

        generarRechazo(){
            
            if (this._motivoRechazo == 'REQUIERE INGRESAR SOLICITUD POR 033 O 430 O 985' || this._motivoRechazo == 'CONCURRENCIA DE PENSIONES')
            {    Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "No se puede la nota de Rechazo, favor verificar el Caso",
                    showConfirmButton: true
                });
            } else {
                $('#modalPrevisualizar').on('shown.bs.modal', () => {
                    this.showLoader = true;
                    setTimeout(() => {
                        this.showLoader = false;
                    }, 1000);
                    //this.renderPDF(e.reporteRechazo, 'pdfCanvas');
                    this._cua = this._cuaTitular
                    this.pdfSrc = this._reporteRechazo;
                });

                // Abre el modal
                $('#modalPrevisualizar').modal('show');
            }
        },
		verificarDatos(e) {
            console.log('El valor de EE >>> ',e);
            this._id = e.id;
            this._tipoActualizacion = e.tipo;
            this._rutaDocumento = e.documento;
            this._nroTramite = e.tramite;
            this._usuario = e.titular;
            this._motivoRechazo = e.motivoRechazo;
            this._cuaTitular = e.cuaTitular;
            this._reporteRechazo = e.reporteRechazo;
            this._estado=e.estado;

            console.log('e ',e);

			this.datosCua = e;
			console.log("Errores:", e);
			let accede = e.accede;

            console.log("EL E ES: ", e.accede);

			let evalua_acceso = e.evaluaAcceso;
            if (accede === 'ACCEDE' && accede !== '') {
                this.doLimpiar();
                this.tipoConsumo = 'G';
                // Abre el modal
                $('#modalCrear').modal('show');
                // } else {
                // 	Swal.fire({
                // 		position: "center",
                // 		icon: "success",
                // 		title: "Se debe realizar un recalculo "+ e.evaluaAcceso +" antes, favor realizar el registro del trámite",
                // 		showConfirmButton: true
                // 	});
                // 	this.$router.push("/crearCaso");
                // }
            } else {
                this.datosCua.tipo='MANUAL';
                // Configura el evento para renderizar después de que se muestre el modal
                this.mensaje="VERIFICAR EL REGISTRO DE LA SOLICITUD: "+ e.motivoRechazo + " ¿Está seguro de continuar?";
                    // Swal.fire({
                    //     position: "center",
                    //     icon: "warning",
                    //     title: "No se puede iniciar el trámite ya que existe "+e.motivoRechazo,
                    //     showConfirmButton: true
                    // });
                    this.doLimpiar();
                    // Abre el modal
                    $('#modalCrear').modal('show');
            }

            // if(e.motivoRechazo != 'REQUIERE INGRESAR SOLICITUD POR 033 O 430 O 985' && e.motivoRechazo != 'CONCURRENCIA DE PENSIONES' )
            // {    if (accede === 'ACCEDE' && accede !== '') {
            //         // if (evalua_acceso === 'EVALUAR 1582') {
            //             this.doLimpiar();
            //         // Abre el modal
            //         $('#modalCrear').modal('show');
            //         // } else {
            //         // 	Swal.fire({
            //         // 		position: "center",
            //         // 		icon: "success",
            //         // 		title: "Se debe realizar un recalculo "+ e.evaluaAcceso +" antes, favor realizar el registro del trámite",
            //         // 		showConfirmButton: true
            //         // 	});
            //         // 	this.$router.push("/crearCaso");
            //         // }
            //     } else {
            //         // Configura el evento para renderizar después de que se muestre el modal
            //         $('#modalPrevisualizar').on('shown.bs.modal', () => {
            //             this.showLoader = true;
            //             setTimeout(() => {
            //                 this.showLoader = false;
            //             }, 1000);
            //             //this.renderPDF(e.reporteRechazo, 'pdfCanvas');
            //             this._cua = e.cuaTitular
            //             this.pdfSrc = e.reporteRechazo;
            //         });

            //         // Abre el modal
            //         $('#modalPrevisualizar').modal('show');
            //     }
            // } else {
            //     if (e.motivoRechazo == 'REQUIERE INGRESAR SOLICITUD POR 033 O 430 O 985')
            //     {    Swal.fire({
            //             position: "center",
            //             icon: "warning",
            //             title: "No se puede iniciar el trámite ya que "+e.motivoRechazo,
            //             showConfirmButton: true
            //         });
            //     } else if(e.motivoRechazo == 'CONCURRENCIA DE PENSIONES'){
            //         this.mensaje="Este CUA tiene "+ e.motivoRechazo;
            //         // Swal.fire({
            //         //     position: "center",
            //         //     icon: "warning",
            //         //     title: "No se puede iniciar el trámite ya que existe "+e.motivoRechazo,
            //         //     showConfirmButton: true
            //         // });
            //         this.doLimpiar();
            //         // Abre el modal
            //         $('#modalCrear').modal('show');
            //     }
            // }
		},
		crearCaso(){
            console.log('this.motivoRechazo: ', this._motivoRechazo);
            console.log('this._estado: ', this._estado);
            if(this._estado=='RECHAZADO'){
                this.tipoConsumo = 'A';
            } else {
                this.tipoConsumo = 'G';
            }
            console.log('this.tipoConsumo: ', this.tipoConsumo);

            
            this.showLoader2 = true;

			let gRegistro = { cas_data: {}, cas_data_valores: {} };
                var fechaActual = new Date();
                gRegistro.cas_data.cas_gestion = fechaActual.getFullYear();
                console.log(fechaActual.getFullYear());
                //gRegistro.cas_data.cas_nro = "2020";
                gRegistro.cas_act_id = this.primer_act_id;
                gRegistro.cas_nodo_id = this.primer_act_nodo_id;
                gRegistro.cas_data = this.registro;
                gRegistro.cas_data_valores = [];
                gRegistro.cas_data_campos_clave = [];
                gRegistro.cas_usr_id = this.usrId;
                gRegistro.prc_codigo = this.prc_codigo;
                console.log("gRegistro.prc_codigo: ", this.registro.TIPO_PROCESO);
                // ini
                gRegistro.primer_act_id = this.registros[0].act_id;
                gRegistro.primer_act_nodo_id = this.registros[0].act_nodo_id;
                gRegistro.cua = this.datosCua.cuaTitular;
                //gRegistro.cas_id = this.cas_id;
                gRegistro.tipo = this.datosCua.tipo;
                gRegistro.id = this.datosCua.id;
                //gRegistro.codigo = this.cas_id;
                gRegistro.detalles = this.datosCua.detalles;
                gRegistro.tipoConsumo = this.tipoConsumo;
                let that = this;
                let url = "api/cargar1582";
                console.log(gRegistro);
                console.log("that.datosCua: ", that.datosCua);
                axios.post(url, gRegistro)
                    .then(function (response) {
                        console.log('response ',response.data.codigoRespuesta);
                        if(response.data.codigoRespuesta == '200'){
							that.cas_id = response.data.data;
							Swal.fire({
								position: "center",
								icon: "success",
								title: " Atender el caso:  "  + response.data.codigo,
								showConfirmButton: false,
								timer: 2000
							});
							console.log("otros");
							that.$router.push("/atenderCaso/" + encryptId(that.cas_id));
                        } else if(response.data.codigoRespuesta == '201' ){
                            Swal.fire({
                                position: "center",
                                icon: "warning",
                                title: "Debe corregir el conflicto antes de proseguir",
                                text: response.data.mensaje,
								showConfirmButton: true
                            });
						}
						else {
                            Swal.fire({
                                position: "center",
                                icon: "warning",
                                title: "Registro duplicado: cas_cod_id:  " + response.data.codigo,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                        that.showLoader2 = false;
                    });
		},

		listarProcesos() {
			//let url = "api/procesosTodos/";
			var params = {"usr_id": this.usrId};
			let url2 = "api/obtenerDepartamento";
			var params = {"id_regional": this.id_regional,
			"id_agencia": this.id_agencia,
			"id_departamento": this.id_departamento};
			axios.post(url2, params).then(response => {
				this.departamento = response.data.data[0]; //twice data
			});
		},

		listarRegistros() {
			let prc_id = 15;
			let prc_codigo = 'JUB1582';
			let prc_descripcion = 'JUBILACIÓN LEY 1582';
			let that = this;
			let url = "api/actividades/" + prc_id;
			this.descripcion = prc_descripcion;
			this.codigo = prc_codigo;
			axios.get(url).then(response => {
				this.primer_act_id = 0;
				this.registros = response.data.data; //twice data
				this.registros.forEach(function (row) {
					row.act_data = JSON.parse(row.act_data);
					that.prc_codigo = prc_codigo;
					if (that.primer_act_id == 0 && (row.act_tact_id !== 1 && row.act_tact_id !== 4)) { // la actividad no es inicio ni fin
						that.primer_act_id = row.act_id;
						that.primer_act_nodo_id = row.act_nodo_id;
						that.primer_prc_id = row.prc_id;
					}
				});
			});
		},

		doLimpiar() {
			var fechaActual = new Date();
			var fechaActualDate = fechaActual.toLocaleDateString();
			this.registro = {
				cas_gestion: fechaActual.getFullYear(),
				cas_departamento: this.departamento.departamento,
				cas_regional: this.departamento.regional,
				cas_agencia: this.departamento.agencia,
				cas_nro_caso: '',
				cas_nombre_caso: '',
				cas_cod_id: '',
				NOMBRE_PROCESO: 'JUBILACIÓN LEY 1582',
				TIPO_PROCESO: 'JUB1582',
				USUARIO_REGISTRO: this.usr_user,
				ESTADO_DERIVACION: 'INICIADO',
				DESCRIPCION_DERIVACION: '',
				id_cas_departamento: this.id_departamento,
				id_cas_agencia: this.id_agencia,
				id_cas_regional: this.id_regional,
				cas_registrado : fechaActualDate,
				de_usuario : '',
				a_usuario : this.usr_user,
				ORIGEN : 'TRAMITESIP'
			};
		},

        nextPage() {
            if (this.currentPage < this.totalPage) {
                this.currentPage++;
                this.renderPage(this.currentPage, 'pdfCanvas');
            }
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.renderPage(this.currentPage, 'pdfCanvas');
            }
        },
        downloadPDF() {
            // Crear un enlace temporal para descargar el PDF
            var nombre = this._cua;
            var link = document.createElement('a');
            link.href = 'data:application/pdf;base64,' + this.pdfSrc;
            link.download = nombre + this.$store.state.nombreDocumento + '.pdf';
            link.click();
        },

		getSanitizedPdfSrc() {
			// Llama al método que calcula la URL del PDF sanitizado
			return this.sanitizedPdfSrc();
		},

		sanitizedPdfSrc() {
			// Convierte la cadena Base64 a un formato seguro usando btoa()
			this.pdfData = `data:application/pdf;base64,` + this.pdfSrc;
			return `data:application/pdf;base64,` + this.pdfSrc;
		},

        subirPdfParafirmar(event) {
            const file = event.target.files[0];

            if (file.type !== 'application/pdf') {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "El archivo debe ser un PDF",
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar',
                    timer: 2000
                });
                this.$refs.firmado_archivo.value = null;
                this.firmado_archivo = null;
                this.errorArchivo = 'El archivo debe ser un PDF';
                return;
            }

            if (!file) {
                console.log('No se seleccionó ningún archivo');
                this.firmado_archivo = null;
            } else {
                this.firmado_archivo = file;
                const reader = new FileReader();
                reader.onload = () => {
                    const base64data = reader.result.split(',')[1];
                    this.pdfSrc = base64data;
                    console.log('Archivo en Base64:', base64data);
                };
                reader.readAsDataURL(file);
            }
        },

        guardarPDF(tipo) {
            if (!this.firmado_archivo) {
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "Es requerido añadir un PDF",
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar',
                    timer: 2000
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = () => {
                const base64data = reader.result.split(',')[1];
                let payload = {
                    apiuuid: this._id,
                    cua: this._cua,
                    usucre: this.usr_user,
                    documento: base64data
                };

                axios.post('api/subirPDF1582', payload)
                    .then(response => {
                        if (response.data.codigoRespuesta === 200) {
                            console.log('EL RESPONSE', response);
                            this.doc_url = response.data.data;

                            let datos = {
                                _id: this._id,
                                _tipoActualizacion: 'R',
                                _rutaDocumento: this.doc_url,
                                _nroTramite: '1582',
                                _usuario: this.usr_user,
                            };

                            if(tipo==1){
                                axios.put('api/prestaciones1582', datos)
                                    .then(response => {

                                        if (response.data.codigo === '200') {
                                            Swal.fire({
                                                position: "center",
                                                icon: "success",
                                                title: response.data.mensaje,
                                                showConfirmButton: true,
                                                confirmButtonText: 'Aceptar',
                                                timer: 2000
                                            });
                                            this.ordenarDatos = [];
                                            this.limpiarFormulario1582();
                                            $('#modalPrevisualizar').modal('hide');
                                        } else {
                                            Swal.fire({
                                                position: "center",
                                                icon: "warning",
                                                title: response.data.data.mensaje,
                                                showConfirmButton: true,
                                                confirmButtonText: 'Aceptar',
                                                timer: 2000
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error', error);
                                    });
                            } else if(tipo==2){
                                this.limpiarFormulario1582();
                                $('#modalPrevisualizarRechazo').modal('hide');
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: 'Documento subido Exitosamente',
                                    showConfirmButton: true,
                                    confirmButtonText: 'Aceptar',
                                    timer: 2000
                                });

                                this.ordenarDatos = [];
                            }
                            this.firmado_archivo = null;

                        } else {
                            console.error('Error al guardar el documento', response);
                        }
                    })
                    .catch(error => {
                        console.error('Error al guardar el documento', error);
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error al guardar el documento",
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                    });
            };
            reader.readAsDataURL(this.firmado_archivo);
        },

        visualizarPDF1582Rechazado(r) {
            var base64Pdf = r;
            console.warn("PREVISUALIZAR PDF RECHAZADO");

            $('#modalPrevisualizarRechazo').on('shown.bs.modal', () => {
                this.showLoader = true;
                setTimeout(() => {
                    this.showLoader = false;
                }, 1000);
                this.pdfSrc = base64Pdf;
                this.renderPDF(this.pdfSrc, 'pdfCanvasRechazo');
            });

            $('#modalPrevisualizarRechazo').modal('show');
        },

        visualizarBotones(id){
            console.log('opciones', this.opciones);
            axios.get(`api/verDocumentoPdf1582Doc/${id}`).then(response => {
                if(response.data.codigoRespuesta === 200){
                    this.opciones = 'success';
                } else if(response.data.codigoRespuesta === 404){
                    this.opciones = 'warning';
                }
                })
                .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    position: "center",
                    icon: "warning",
                    title: "inconvenientes en petición",
                    showConfirmButton: true,
                    confirmButtonText: 'Aceptar'
                });
            });
        },

        visualizarPDF1582RechazadoSIP(r, id, reporteRechazo, tipo){
            console.log('EL >>>> id', id);
            if(tipo==1) this.swVer=true;
            if(tipo==2) this.swVer=false;

            this._cua = r.cuaTitular;
            this._id = id;
            
            axios.get(`api/verDocumentoPdf1582Doc/${id}`).then(response => {
                    // console.log('La respuesta >>>>', response.data);

                    if(response.data.codigoRespuesta === 200){
                        this.opciones = 'success';

                        // si esta en el opt
                        var respuestaBase64 = response.data.data;
                        console.log(respuestaBase64);

                        $('#modalPrevisualizarRechazo').on('shown.bs.modal', () => {
                            this.showLoader = true;
                            setTimeout(() => {
                                this.showLoader = false;
                            }, 1000);
                            this.pdfSrc = respuestaBase64;
                            this.renderPDF(this.pdfSrc, 'pdfCanvasRechazo');
                        });

                        $('#modalPrevisualizarRechazo').modal('show');
                    } else if(response.data.codigoRespuesta === 404){
                            this.opciones = 'warning';

                            console.warn("DOCUMENTO NO ENCONTRADO TRAER EL PDF DE REPORTERECHAZO");
                            var base64Pdf = reporteRechazo;
                            console.warn("PREVISUALIZAR PDF RECHAZADO");

                            $('#modalPrevisualizarRechazo').on('shown.bs.modal', () => {
                                this.showLoader = true;
                                setTimeout(() => {
                                    this.showLoader = false;
                                }, 1000);
                                this.pdfSrc = base64Pdf;
                                this.renderPDF(this.pdfSrc, 'pdfCanvasRechazo');
                            });

                            $('#modalPrevisualizarRechazo').modal('show');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        position: "center",
                        icon: "warning",
                        title: "Documento no encontrado",
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                });
        },

        guardarDatosNoAccede() {
            let datos = {
                _id: this._id,
                _tipoActualizacion: 'R',
                _rutaDocumento: 'No subir Archivo(El asegurado no quiere firmar)',
                _nroTramite: '1582',
                _usuario: this.usr_user,
            };
            let payload = {
                apiuuid: this._id,
                cua: this._cua,
                usucre: this.usr_user
            };

            console.log('payload', payload);

            axios.post('api/grabarRechazo1582', payload)
                .then(response => {
                    console.log("response: ", response);
                })
                .catch(error => {
                    console.error('Error', error);
                });
            axios.put('api/prestaciones1582', datos)
                .then(response => {
                    console.log("response: ", response);
                    console.log("response: ", response.data);
                    console.log("response: ", response.data.data);
                    console.log("response: ", response.data.codigo);
                    if (response.data.codigo == '200') {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: response.data.mensaje,
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar',
                                timer: 2000
                            });
                            this.ordenarDatos = [];
                            this.limpiarFormulario1582();
                            $('#modalPrevisualizar').modal('hide');
                    } else if (response.data.mensaje === 'Error interno no controlado') {
                        Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Error interno no controlado",
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error', error);
                });

        },
        limpiarFormulario1582() {
            this.registro = {
                tipoDocumento: "I",
                numeroDocumento: "",
                complemento: "",
                fechaNacimiento: "",
            };
        },

		renderPDF(base64, canvasContainer) {
			const pdfData = atob(base64);
			const pdfAsArray = new Uint8Array(pdfData.length);
			for (let i = 0; i < pdfData.length; i++) {
				pdfAsArray[i] = pdfData.charCodeAt(i);
			}

			pdfjsLib.getDocument({ data: pdfAsArray }).promise.then(pdf => {
				this.pdf = pdf;
				this.totalPage = pdf.numPages;
				this.currentPage = 1; // Reiniciar siempre a la primera página
				this.renderPage(this.currentPage, canvasContainer);
			}).catch(error => {
				console.error("Error al cargar el PDF:", error);
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'No se pudo cargar el documento PDF',
				});
			});
		},

		renderPage(pageNumber, canvasContainer) {
			this.pdf.getPage(pageNumber).then(page => {
				const viewport = page.getViewport({ scale: 1.0 });
				const canvas = document.getElementById(canvasContainer);
				const context = canvas.getContext("2d");

				canvas.height = viewport.height;
				canvas.width = viewport.width;

				const renderContext = {
					canvasContext: context,
					viewport: viewport
				};
				page.render(renderContext);
			}).catch(error => {
				console.error("Error al renderizar la página:", error);
			});
		},
		

		limpiarFormulario() {
			this.registro = {
				tipoDocumento: "I",
				numeroDocumento: "",
				complemento: "",
				cua: null,
				fechaNacimiento: "",
				fechaDefuncion: null,
				primerNombre: "",
				segundoNombre: "",
				primerApellido: "",
				segundoApellido: "",
				apellidoEsposo: "",
				idGenero: "",
				idNacionalidad: "",
				idEstadoCivil: "",
				usuCre: "SistemaJubilacion"
			};
		}
	}
};
</script>

<style scoped>
.custom-btn {
	display: block;
	margin: 20px auto;
	padding: 10px 20px;
	background-color: #007bff;
	color: #ffffff;
	border-style: solid;
	border-color: #007bff;
	border-radius: 5px;
	border-width: 2px;
	cursor: pointer;
	transition: background-color 0.3s;
}

.custom-btn:hover {
	background-color: #0057b300;
	border-color: #0056b3;
	border-style: solid;
	color: #0056b3;
}

.custom-btn-red {
	display: block;
	margin: 20px auto;
	padding: 10px 20px;
	background-color: #d30b0b;
	color: #ffffff;
	border-style: solid;
	border-color: #d30b0b;
	border-radius: 5px;
	border-width: 2px;
	cursor: pointer;
	transition: background-color 0.3s;
}

.custom-btn-red:hover {
	background-color: #0057b300;
	border-color: #d30b0b;
	border-style: solid;
	color: #d30b0b;
}

.title-margin {
	margin-top: 20px;
	margin-bottom: 20px;
}

.card-footer {
	display: flex;
	align-items: center;
	align-content: center;
	flex-direction: column;
}

.swal-text {
	font-size: 2000px;
	/* Tamaño de fuente deseado */
}

.mensaje{
	color: #ff0000;
	font-size: x-small;
	font-weight: bold;
	font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.mensaje2{
	color: #0319e4;
	font-size: x-small;
	font-weight: bold;
	font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.espaciado {
    margin-top: 300px;
}

#modalPrevisualizar .loader-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
}
#modalPrevisualizar .loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.91);
}

.loader-container2 {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(255, 255, 255, 0.8); /* Fondo semitransparente */
    z-index: 1000; /* Asegúrate de que esté por encima de otros elementos */
}

.table-responsive {
    position: relative; /* Necesario para que el loader se posicione correctamente */
}
</style>

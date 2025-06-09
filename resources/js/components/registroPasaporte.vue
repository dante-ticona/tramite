<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h5 class="tittle-margin">{{ plural }}</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-3">
						<label>Tipo de Documento</label>
						<select v-model="registro.tipoDocumento" class="form-control" required>
							<option value="" disabled>-- Seleccione Tipo de Documento --</option>
							<option value="P">PASAPORTE</option>
							<option value="T">TEMPORALES</option>
						</select>
						<p v-if="!registro.tipoDocumento" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'">Número de Documento</label>
						<input type="text" v-model="registro.numeroDocumento" class="form-control"
							placeholder="Ingrese Nro. Documento" v-show="registro.tipoDocumento === 'P'">
						<p v-if="registro.tipoDocumento === 'P' && !registro.numeroDocumento" class="mensaje">
							Obligatorio</p>
					</div>

					<div class="col-md-2">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Complemento</label>
						<input type="text" v-model="registro.complemento" class="form-control"
							placeholder="Ingrese Complemento" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
							<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.complemento" class="mensaje2">Opcional</p>
					</div>

					<div class="col-md-2">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Fecha de Nacimiento</label>
						<input type="date" v-model="registro.fechaNacimiento" class="form-control" 
						v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
						<p v-if="registro.tipoDocumento === 'P'   | registro.tipoDocumento === 'T' && !registro.fechaNacimiento" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-2">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Fecha de Defunción</label>
						<input type="date" v-model="registro.fechaDefuncion" class="form-control"
						v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
						<p v-if="registro.tipoDocumento === 'T' && !registro.fechaDefuncion" class="mensaje">Obligatorio</p>
						<p v-if="registro.tipoDocumento === 'P' && !registro.fechaDefuncion" class="mensaje2">Opcional</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Primer Nombre</label>
						<input type="text" v-model="registro.primerNombre" class="form-control"
							placeholder="Ingrese Primer Nombre" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.primerNombre" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Segundo Nombre</label>
						<input type="text" v-model="registro.segundoNombre" class="form-control"
							placeholder="Ingrese Segundo Nombre" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
							<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.segundoNombre" class="mensaje2">Opcional</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Primer Apellido</label>
						<input type="text" v-model="registro.primerApellido" class="form-control"
							placeholder="Ingrese Primer Apellido" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.primerApellido" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Segundo Apellido</label>
						<input type="text" v-model="registro.segundoApellido" class="form-control"
							placeholder="Ingrese Segundo Apellido" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' ">
							<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.segundoApellido" class="mensaje2">Opcional</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Apellido Esposo</label>
						<input type="text" v-model="registro.apellidoEsposo" class="form-control"
							placeholder="Ingrese Apellido Esposo" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.apellidoEsposo" class="mensaje2">Opcional</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Género</label>
						<select v-model="registro.idGenero" class="form-control" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
							<option value="" disabled>-- Seleccione Género --</option>
							<option value="M">MASCULINO</option>
							<option value="F">FEMENINO</option>
						</select>
						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.idGenero" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Nacionalidad</label>

						<select v-model="registro.idNacionalidad" class="form-control" placeholder="Nacionalidad" 
						v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
							<option value="" disabled>-- Seleccione Nacionalidad --</option>
							<option v-for="pais in listaPaises" :value="pais.codigo">
								{{ pais.codigo }} - {{ pais.descripcion }}
							</option>
						</select>

						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.idNacionalidad" class="mensaje">Obligatorio</p>
					</div>

					<div class="col-md-3">
						<label v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">Estado Civil</label>
						<select v-model="registro.idEstadoCivil" class="form-control" v-show="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T'">
							<option value="" disabled>-- Seleccione Estado Civil --</option>
							<option value="C">CASADO(A)</option>
							<option value="D">DIVORCIADO(A)</option>
							<option value="P">CONVIVIENTE</option>
							<option value="S">SOLTERO(A)</option>
							<option value="V">VIUDO(A)</option>
						</select>
						<p v-if="registro.tipoDocumento === 'P'  | registro.tipoDocumento === 'T' && !registro.idEstadoCivil" class="mensaje">Obligatorio</p>
					</div>
				</div>
			</div>
			<div class="card-footer">
				<div class="col-md-3 d-flex">
					<button class="custom-btn" data-toggle="modal" data-target="#modalRegistrar">
						<i class="fa fa-save white" aria-hidden="true"></i> Registrar </button>
					<button class="custom-btn-red" @click="limpiarFormulario">
						<i class="fa fa-eraser white" aria-hidden="true"></i> Limpiar </button>
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
		</div>
	</div>
</template>

<script>
import axios from 'axios';
import config from './config.js';

export default {
	data() {
		return {
			plural: 'Registro de Pasaporte',
			singular: 'Registro',
			registro: {
				tipoDocumento: "",
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
				documentoIdentidad: "",
				usuCre: "SistemaJubilacion",
				urlPersonas: window.Laravel.url_personas,  //config.URL_PERSONAS + '',
				urlGestora: window.Laravel.url_gestora_sgg,  //config.URL_GESTORA_SGG + '',
				urlGestora1: window.Laravel.url_gestora_sgg1 //config.URL_GESTORA_SGG1 + '',
			},
			errores: [],
			listaPaises: []
		};
	},
	created() {
		this.obtenerPaises();
	},
	methods: {
		async obtenerPaises() {
			try {
                // const response = await axios.get(this.registro.urlGestora1 + '/actualiza-empleador/api/personas/paises');
				const response = await axios.get('https://sgg.gestora.bo/actualiza-empleador/api/personas/paises');
			if (response.data.codigoRespuesta === 200) {
					this.listaPaises = response.data.data;
				} else {
					console.error('Error al obtener la lista de países:', response.data.mensaje);
				}
			} catch (error) {
				console.error('Error al obtener la lista de países:', error);
			}
		},

		guardarRegistro(e) {
			e.preventDefault();
			this.errores = [];

			console.log("Errores:", this.errores);

			if (this.errores.length === 0) {
                // /api/v1/personasip/buscaPersonaRegistroDirectoSip
				axios.post(this.registro.urlPersonas + '/api/v1/personasip/buscaPersona/Pasaporte/Temporal', this.registro)
				.then(response => {
						console.log('Registro guardado exitosamente:', response.data);
						const documentoIdentidad = response.data.data.documentoIdentidad;
						this.$swal({
							title: 'Enviado!',
							text: 'Datos enviados exitosamente',
							icon: 'success',
							confirmButtonText: 'Aceptar'
						}).then(() => {
							this.$swal({
								title: 'Documento de Identidad',
								// text: 'CI: ' + documentoIdentidad,
                                html: `
                                    <div style="display: flex; flex-direction: column; align-items: center;">
                                    <div style="display: flex; align-items: center;">
                                        <p style="margin: 0;">CI: ${documentoIdentidad}</p>
                                        <button id="copyButton" class="swal2-confirm swal2-styled" style="margin-left: 10px; padding: 5px;">
                                        <i class="fas fa-copy"></i> Copiar
                                        </button>
                                    </div>
                                    </div>
                                `,
								icon: 'info',
                                confirmButtonText: 'Aceptar',
                                didOpen: () => {
                                    document.getElementById('copyButton').addEventListener('click', () => {
                                    this.copiarAlPortapapeles(documentoIdentidad);
                                    });
                                }
							}).then(() => {
								this.limpiarFormulario();
							});
						});
					})
					.catch(error => {
						console.error('Error al guardar el registro:', error);
						this.$swal({
							title: 'Error!',
							text: 'Ocurrió un error al guardar el registro',
							icon: 'error',
							confirmButtonText: 'Aceptar'
						});
					});
			} else {
				console.log("Hay errores en el formulario");
				this.$swal({
					title: 'Error!',
					text: 'Corrija los errores en el formulario',
					icon: 'error',
					confirmButtonText: 'Aceptar'
				});
			}

			console.log("Datos a guardar:", this.registro);
		},

        copiarAlPortapapeles(texto) {
            const textarea = document.createElement('textarea');
            textarea.value = texto;
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, 99999);
            document.execCommand('copy');
            document.body.removeChild(textarea);
            alert('Texto copiado al portapapeles');
        },

		limpiarFormulario() {
			this.registro = {
				tipoDocumento: "",
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
</style>
style
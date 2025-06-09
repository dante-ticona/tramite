<template>
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>

                <div class="navegacion">
                    <select name="porPagina" @change="listaNotificaciones" v-model="RegistrosXPagina"
                        class="selectRegistros">
                        <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                    </select>

                    <button class="btn btn-warning" type="button" style="float: left;" @click="refrescarListaNotificaciones">
                        <i :class="{'fas fa-sync-alt': true, 'fa-spin': showLoader}"></i> Refrescar
                    </button>

                    <div class="select-container">
                        <button @click="anteriorPagina()" class="btnEAvance">
                            <i class="fa fa-arrow-left white" aria-hidden="true" :class="{ 'fa-spin': isArrowSpinningLeft }"></i>
                                Anterior
                        </button> &nbsp; &nbsp;

                        <select name="paginacion" @change="listaNotificaciones()" v-model="PaginaActual" class="selectRegistros">
                            <option v-for="pagina in paginas" :key="pagina" :value="pagina">
                                {{ pagina }}
                            </option>
                        </select>

                        &nbsp; &nbsp;
                            <button @click="siguientePagina()" class="btnEAvance">
                                iguiente <i class="fa fa-arrow-right white" aria-hidden="true" :class="{ 'fa-spin': isArrowSpinningRight }"></i>
                            </button>
                    </div>

                    <div class="buscarEAvance">
                        <label style="color: white;">Buscar: </label>
                        <input type="search" v-model="buscarRegistro" @keyup.enter="convertirAMayusculas(); listaNotificaciones()" class="selectRegistros"
                            placeholder="Buscar ...">
                    </div>

                </div>

            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover table-striped table-responsive-lg" id="divTable">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th scope="col">CÓDIGO DOCUMENTO</th>
                            <th scope="col">ESTADO</th>
                            <th scope="col">USUARIO <br> NOTIFICADOR</th>
                            <th scope="col">FECHA NOTIFICACIÓN</th>
                            <th scope="col">USUARIO ATENCIÓN</th>
                            <th scope="col">OPCIONES</th>
                            <th scope="col">COMPLEMENTARIO</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <div v-if="showLoader" class="loader-container" style="background-color: rgba(255, 255, 255, 0.9);">
                            <div class="loader-wrapper">
                                <div class="loader"></div>
                                <span class="loader-text">TramiteSIP</span>
                                <span class="loading-text">Actualizando...</span>
                            </div>
                        </div>

                        <template v-if="notificaciones.length > 0">
                            <tr v-for="notificacion in notificaciones">
                                <td width="15%"> {{ notificacion.id }} - {{ notificacion.nro_solicitud }}</td>
                                <td>
                                    <span class="badge badge-warning" v-if="notificacion.estado === 'RECIBIDO'"><strong>RECIBIDO</strong></span>
                                    <span class="badge badge-secondary" v-else-if="notificacion.estado === 'ENVIADO'"><strong><i class="fas fa-paper-plane"></i> ENVIADO</strong></span>
                                    <span class="badge badge-primary" v-else-if="notificacion.estado === 'NOTIFICADO'"><strong> <i class="fas fa-bell"></i> NOTIFICADO</strong></span>
                                    <span class="badge badge-success" v-else-if="notificacion.estado === 'ATENDIDO'"><strong><i class="fas fa-tags"> </i> ATENDIDO</strong></span>
                                </td>
                                <td>{{ notificacion.usu_cre }}</td>
                                <td>
                                    <i class="fas fa-calendar"></i> {{ notificacion.fec_cre.split(' ')[0] }} -
                                    <i class="fas fa-clock"></i> {{ notificacion.fec_cre.split(' ')[1] }}
                                </td>
                                <td>{{ notificacion.usuario }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-circle"
                                        v-on:click="verDocumentos(notificacion)" title="Ver Documento">
                                        <i class="far fa-file-pdf white" aria-hidden="true"></i>
                                    </button>

                                    <button v-if="notificacion.estado !='ENVIADO' && notificacion.estado != 'ATENDIDO'" type="button" class="btn btn-danger btn-circle"
                                        v-on:click="atenderNotificacion(notificacion, 'ATENDIDO' )" title="Atender">
                                        <i class="fas fa-hand-holding white" aria-hidden="true"></i>
                                    </button>
                                </td>
                                <td>
                                    <button v-if="notificacion.estado != 'ENVIADO'" type="button" class="btn btn-primary btn-circle respuesta-pdf" title="Subir PDF"
                                        v-on:click="verificarID_SOLICITUDPRESTACION(notificacion.cas_data_valores, notificacion , notificacion.id, notificacion.nro_solicitud)"
                                        style="background-color: #17a2b8; border-color: #17a2b8;">
                                        <i class="fa fa-file-pdf" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="7" class="text-center">No hay registros para mostrar.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- modalVerDocumentos -->
        <div class="modal fade" id="modalVerDocumentos" tabindex="-1" role="dialog" aria-labelledby="modalVerDocumentos"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="modalVerDocumentos">Notificación Documentos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <table class="table table-hover table-striped table-responsive">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>DOCUMENTO</th>
                                            <th>DESCRIPCION</th>
                                            <th>VER DOCUMENTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="documento in documentos">
                                            <td width="3%">{{ documento.documento }}</td>
                                            <td>{{ documento.descripcion }}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-circle"
                                                    v-on:click="verDocumento(documento.doc_id, documento.doc_ruta)">
                                                    <i class="far fa-file-pdf white" aria-hidden="true"></i>
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
        <!-- modalVerDocumentos -->

        <!-- SUBIR RESPUESTA PDF -->
        <div class="modal fade" id="modalSubirRespuestaPdf" tabindex="-1" role="dialog" aria-labelledby="esos"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary" style="background-color: #17a2b8 !important;">
                        <h5 class="modal-title" id="exampleModalLabel">Anexar Documento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Documento</th>
                                        <th>Descripción</th>
                                        <!-- <th>Acciones</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(document, index) in documentos" :key="index">
                                        <td style="width: 250px;">
                                            <div class="custom-file">
                                                <input type="file" :id="'archivo-' + index"
                                                    :name="'archivo-' + index"
                                                    class="form-control form-control-sm"
                                                    @change="tamanoDocumento($event, index)" required
                                                    :ref="'fileInput-' + index" />
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control" :id="'descripcion-' + index"
                                                v-model="document.descripcion" required
                                                :class="{ 'is-invalid': document.descripcionError }"></textarea>
                                        </td>
                                        <!-- <td>
                                            <div class="d-flex align-items-center">
                                                <button type="button" @click="eliminarDocumento(index)"
                                                    class="btn btn-danger" :disabled="index === 0" title="Eliminar">
                                                    <i class="fas fa-minus-circle" aria-hidden="true"></i>
                                                </button>

                                                <button v-if="index === 0" type="button" @click="agregarDocumento"
                                                    class="btn btn-success ml-2" title="Agregar">
                                                    <i class="fas fa-plus-square"></i>
                                                </button>
                                            </div>
                                        </td> -->
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="subirRespuestaPDF" class="btn btn-primary">Subir</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- SUBIR RESPUESTA PDF -->
    </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            plural: 'Notificaciones',
            usrId: window.Laravel.usr_id,
            searchTerm: '',
            showLoader: false,
            notificaciones: [],
            documentos: [
                { archivo: '', descripcion: '', descripcionError: false }
            ],
            descripcion: '',
            id_seguimiento_tramite: '',
            archivoSeleccionado: null,
            notificacionActual: null,
            base64data: null,
            descripcionError: false,
            PaginaActual: 1,
            RegistrosXPagina: 10,

            opcionesRegistrosPorPagina: [5, 10, 15, 20, 25],
            buscarRegistro: '',
            paginas: Array.from({ length: 100 }, (_, index) => index + 1),
            isArrowSpinningRight: false,
            isArrowSpinningLeft: false,
            selectedEstado: ''
        };
    },
    mounted() {
        this.listaNotificaciones();
    },
    computed: {
        filteredNotificaciones() {
            return this.notificaciones.filter(notificacion => {
                return Object.values(notificacion).some(value =>
                    String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
                );
            });
        }
    },
    methods: {

        sanitizedPdfSrc(doc) {
            // Convierte la cadena Base64 a un formato seguro usando btoa()
            this.pdfData = `data:application/pdf;base64,` + doc;
            console.log("convitiendo ",this.pdfData);
            return `data:application/pdf;base64,` + this.pdfSrc;
        },
        convertirArchivoABase64(archivo) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(archivo);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        },
        listaNotificaciones(estado = '') {
            let url = "/api/listaNotificaciones" + '/' + this.RegistrosXPagina + '/' + this.PaginaActual;

            if (this.buscarRegistro) {
                url += `?buscar=${this.buscarRegistro}`;
            }

            axios.get(url).then(response => {
                this.notificaciones = response.data.data;
                console.log(this.notificaciones);
            }).catch(error => {
                console.log(error);
            });
        },
        convertirAMayusculas() {
            this.buscarRegistro = this.buscarRegistro.toUpperCase();
        },

        filtrarPorEstado() {
            this.listaNotificaciones(this.selectedEstado);
        },
        verDocumentos(notificacion) {
            this.documentos = [];
            this.documentos.push({ documento: "NOTIFICADO", descripcion: notificacion.descripcion_envio, doc_id: notificacion.doc_id_envio, doc_ruta: notificacion.doc_url_envio });
            if (notificacion.doc_url_respuesta != null) {
                this.documentos.push({ documento: "ENVIADO", descripcion: notificacion.descripcion_respuesta, doc_id: notificacion.doc_url_respuesta, doc_ruta: notificacion.doc_url_respuesta });
            }
            $('#modalVerDocumentos').modal('show');
        },
        verDocumento(doc_id, doc_url) {
            var url = "/api/verDocumentoPdfNfs/" + doc_id;
            const partes = doc_url.split('.');
            const partes2 = doc_url.split('/');
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
        anteriorPagina() {
            this.isArrowSpinningLeft = true;

            setTimeout(() => {
                if (this.PaginaActual > 1) {
                    this.PaginaActual--;
                    this.listaNotificaciones();
                }
                this.isArrowSpinningLeft = false;
            }, 200);

        },
        siguientePagina() {
            this.isArrowSpinningRight = true;

            setTimeout(() => {
                if (this.PaginaActual < this.paginas.length) {
                    this.PaginaActual++;
                    this.listaNotificaciones();
                }
                this.isArrowSpinningRight = false;
            }, 200);
        },
        atenderNotificacion(notificacion, estado) {

            console.log('usuario >> ', this.usrId);

            // axios.put(`/api/actualizarEstadoNotificacion/${notificacion.id}`, { estado: estado }, { usuario:this.usrId }).then(response => {
            axios.put(`/api/actualizarEstadoNotificacion/${notificacion.id}`, { estado: estado, usuario: this.usrId }).then(response => {
                console.log('Actualización exitosa:', response.data);
                Swal.fire({
                    title: 'Actualización exitosa',
                    html: '<strong>' + notificacion.nro_solicitud + '</strong> ha sido atendido.',
                    icon: "success",
                    confirmButtonText: 'Aceptar'
                });

                this.listaNotificaciones();
            }).catch(error => {
                console.error('Error en la actualización:', error);
            });
        },
        verificarID_SOLICITUDPRESTACION(cas_data_valores, notificacion , not_id , codigo_documento) {
            if (!notificacion.usuario) {
                Swal.fire({
                    title: 'Usuario no definido !!',
                    text: 'El usuario no está definido para esta notificación.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            console.log('cas_data_valores:', typeof cas_data_valores);

            if (typeof cas_data_valores === 'string') {
                try {
                    cas_data_valores = JSON.parse(cas_data_valores);
                    console.log('cas_data_valores convertido:', cas_data_valores);
                } catch (error) {
                    console.error('Error al convertir cas_data_valores a objeto:', error);
                    return;
                }
            }

            // Verificar ID_SOLICITUDPRESTACION del cas_daa_valores
            if (cas_data_valores.some(item => item.frm_campo === 'ID_SOLICITUDPRESTACION')) {
                const idSolicitudPrestacion = cas_data_valores.find(item => item.frm_campo === 'ID_SOLICITUDPRESTACION').frm_value;
                this.id_seguimiento_tramite = idSolicitudPrestacion;

                console.log('ID_SOLICITUDPRESTACION:', idSolicitudPrestacion);
            } else {
                console.log('ID_SOLICITUDPRESTACION no existe en el objeto.');
            }

            $('#modalSubirRespuestaPdf').modal('show');

            this.notificacionActual = notificacion;
            this.not_id = not_id;
            this.codigo_documento = codigo_documento;
            this.id_notificacion = not_id;
        },

        async subirRespuestaPDF() {
            if (!this.validarDescripcion()) {
                Swal.fire(
                    {
                        title: 'Por favor, completa todas las descripciones.',
                        icon: 'warning',
                        confirmButtonText: 'Aceptar'
                    });
                return;
            }

            const file = this.archivoSeleccionado;

            if (!file) {
                Swal.fire({
                    title:'Por favor, selecciona un archivo antes de subir.',
                    icon:'warning',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            console.log("Los documentos >> ", this.documentos);

            console.log('Archivo seleccionado :', this.documentos[0].archivo);

            const doc = this.documentos[0].archivo;

            console.log("EL DOCUMENTO >>> ", doc);

            const reader = new FileReader();
            reader.onloadend = () => {
                const base64Doc = reader.result.split(',')[1]; // Obtener la parte base64 del resultado

                console.log('Base64 del documento:', base64Doc);

                const formData = new FormData();

                console.log(this.codigo_documento);


                formData.append('id_seguimiento_tramite', this.codigo_documento);
                formData.append('descripcion', `${this.documentos[0].descripcion}.pdf`);
                formData.append('id_respaldos', this.documentos.length); // cantidad de documentos
                formData.append('documento', base64Doc);
                formData.append('id_notificacion', this.not_id);
                formData.append('nroTramite', this.codigo_documento);
                formData.append('usrId', this.usrId);

                console.log(formData.get('descripcion'));

                axios.post('api/subirRespuestaPDF', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    console.log('Respuesta del servidor:', response.data);
                    const message = response.data.message;
                    const mensaje = response.data.data.mensaje;
                    Swal.fire({
                        title: mensaje,
                        text: message,
                        icon: "success",
                        confirmButtonText: 'Aceptar'
                    });
                    this.limpiarDocumentos();
                    $('#modalSubirRespuestaPdf').modal('hide');
                    this.listaNotificaciones();
                }).catch(error => {
                    console.error('Error al ejecutar la petición:', error);
                });
            };
            reader.readAsDataURL(doc)
        },
        tamanoDocumento(event) {
            const file = event.target.files[0];
            const maxSizeInBytes = 10485760;
            if (file.size > maxSizeInBytes) {
                Swal.fire({
                    title:'El archivo seleccionado supera el tamaño máximo de 10 MB.',
                    icon:'warning',
                    confirmButtonText: 'Aceptar'
                });
                event.target.value = '';
            } else if (file.type !== 'application/pdf') {
                Swal.fire({
                    title:'Por favor, selecciona solo archivos PDF.',
                    icon:'warning',
                    confirmButtonText: 'Aceptar'
                });
                event.target.value = '';
            } else {
                this.archivoSeleccionado = file;  //Almacenar el archivo seleccionado
                this.documentos[0].archivo = file;
            }
        },
        validarDescripcion() {
            let isValid = true;
            this.documentos.forEach((document, index) => {
                if (!document.descripcion) {
                    this.$set(this.documentos[index], 'descripcionError', true);
                    isValid = false;
                } else {
                    this.$set(this.documentos[index], 'descripcionError', false);
                }
            });
            return isValid;
        },
        agregarDocumento() {
            this.documentos.push({ file: null, descripcion: '', descripcionError: false });
        },
        eliminarDocumento(index) {
            this.documentos.splice(index, 1);
        },
        limpiarDocumentos() {
            this.archivoSeleccionado = null;
            this.documentos = [
                { archivo: '', descripcion: '', descripcionError: false }
            ];
            this.$nextTick(() => {
                this.documentos.forEach((document, index) => {
                    const fileInput = this.$refs[`fileInput-${index}`];
                    if (fileInput && fileInput.length > 0) {
                        fileInput[0].value = '';
                    }
                });
            });
        },

        refrescarListaNotificaciones() {
            this.showLoader = true;
            this.listaNotificaciones();
            setTimeout(() => {
                this.showLoader = false;
            }, 500);
        }
    }
};
</script>

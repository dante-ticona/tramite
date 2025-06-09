<template>
    <div>
        <div>
            <h2>VALIDACIONES DEL TRÁMITE</h2>
            <div class="row justify-content-left">
                <div class="col-md-12">
                    <label>Caso:</label> {{ casCodId }} <br>
                    <table class="table table-hover table-striped table-responsive">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nro Trámite</th>
                                <th>Actividad</th>
                                <th>Estado</th>
                                <th>Descripción derivación</th>
                                <th>Documento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="resultadosLegalTramite.length > 0">
                                <tr v-for="(h, index) in resultadosLegalTramite">
                                    <td align="center"><span class="badge badge-dark">{{ index + 1 }}</span>
                                    </td>
                                    <td>
                                        {{ h.cas_cod_id }}
                                    </td>
                                    <td>
                                        {{ h.act_orden }} - {{ h.act_descripcion }} <br>
                                        <strong> {{ h.nodo_descripcion }} </strong>
                                    </td>
                                    <td>{{ JSON.parse(h.cas_data).ESTADO_DERIVACION }} </td>
                                    <td> {{ JSON.parse(h.cas_data).DESCRIPCION_DERIVACION }}</td>
                                    <td>
                                        <!-- {{ h.htc_id }} -->
                                        <button type="button" class="btn btn-primary btn-circle" title="Histórico Legal"
                                            v-on:click="obtenerDocumentoLegalGral(h.cas_id)" data-toggle="modal"
                                            data-target="#modalDocumentoLegalPdf">
                                            <i class="far fa-file-pdf white" aria-hidden="true"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" class="text-center">Sin registros</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div id="overlay_legal" ref="overlay_legal" class="overlay_legal" style="z-index: 2000;">
            <div class="loader-wrapper">
                <div class="loader"></div>
                <span class="loader-text">TramiteSip</span>
                <span class="loading-text">Cargando...</span>
            </div>
        </div>
        <!-- modalDocumentoLegalPdf -->
        <div class="modal fade" id="modalDocumentoLegalPdf" tabindex="-3" role="dialog"
                aria-labelledby="modalDocumentoLegalPdf" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document" style="max-width: 40%;">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title" id="exampleModalLabel">Documentos Medicos </h5>
                            <button type="button" class="close"  aria-label="Close" v-on:click="closeModalDocumentosMedicos()">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row justify-content-left">
                                <div class="col-md-12">
                                    <label>Listado de Documentos Medicos:</label><br>
                                    <table class="table table-hover table-striped table-responsive">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Nro</th>
                                                <th>tipo</th>
                                                <th>Descripcion</th>
                                                <th>Ver Documento</th>
                                                <th>Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(h, index) in documento">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ h.tipo }}</td>
                                                <td>{{ h.descripcion }}</td>
                                                <td align="center">
                                                    <button v-if="h.nombre === ''" type="button"
                                                        class="btn  btn-danger  btn-circle " title="Ver Documento">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>
    
                                                    <button v-if="h.nombre != ''" type="button"
                                                        class="btn  btn-success btn-circle " title="Ver Documento"
                                                        v-on:click="verDocumento(h.doc_id, h.nombre)">
                                                        <i class="far fa-file-pdf white " aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-circle"
                                                        title="Dar baja documento"
                                                        @click="limpiarDocumentoMedicoAdjunto(h.doc_id, h);"
                                                        :disabled="h.nombre == ''">
                                                        <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="closeModalDocumentosMedicos()">Cerrar</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    props: ["casId","casCodId"],
    inject: ['legalHistoryService'],
    data() {
        return {
            // Create a local copy of the value prop to manage changes locally
            resultadosLegalTramite: [],
            documento: [],
            usrUser:null,
        };
    },
    created:  function () {
        this.usrUser = window.Laravel.usr_user;
    },  
    watch: {
        casId: async function (newValue) {
            //2025-04-11
            if(newValue !== null && newValue != '') {
                this.resultadosLegalTramite = await this.legalHistoryService.getLegalHistory(newValue);
            }
        }
    },  

    methods: {
        mostrarOverlay() {
            // Mostrar el overlay_legal cambiando el estilo de display
            this.$refs.overlay_legal.style.display = 'flex';
        },
        ocultarOverlay() {
            // Ocultar el overlay_legal cambiando el estilo de display
            this.$refs.overlay_legal.style.display = 'none';
        },
        closeModalDocumentosMedicos() {
            $('#modalDocumentoLegalPdf').modal('hide');
        },
        obtenerDocumentoLegalGral(htc_cas_id) {
            const datos = { htc_cas_id: htc_cas_id };
            axios.post('api/obtenerDocumentoLegalGral', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado', error);
                })
                .finally(() => {
                    // Mostrar el modal después de obtener los datos
                    this.ocultarOverlay();
                }); 
        },
        verDocumento: function (ruta, nombre) {
            var url = "/api/verDocumentoPdfNfs/"  + ruta + '?usuario=' + this.usrUser + '@gestora.bo&pro=atender_caso';
            const partes = nombre.split('.');
            const partes2 = nombre.split('/');
            console.log('nombre', nombre);
            axios.get(url, { responseType: 'blob' })
                .then(response => {
                    if (partes[1] == 'pdf') {
                        const documento = response.data;
                        const url = window.URL.createObjectURL(documento);
                        const windowProps = 'top=0,left=0,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=650';
                        const newWindow = window.open(url, '_blank', windowProps);
                        newWindow.document.body.innerHTML = `<iframe src="${url}" width="100%" height="100%"></iframe>`;
                    } else if (nombre == '') {
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
                })
                .finally(() => {
                    // Mostrar el modal después de obtener los datos
                    this.ocultarOverlay();
                }); 
        },
        limpiarDocumentoMedicoAdjunto(doc_id_adjunto, h) {
            Swal.fire({
                title: '¿Estás seguro de limpiar ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.mostrarOverlay();
                    let doc_id_adj = { doc_id_adjunto: doc_id_adjunto };
                    axios.post('api/limpiarDocumentoAdjunto', doc_id_adj)
                        .then(response => {
                            if (response.data.codigoRespuesta.code == '200') {
                                this.doDocumentoPdfAdjuntoMedico();
                                this.ocultarOverlay();
                                Swal.fire({
                                    position: "center",
                                    icon: "success",
                                    title: "Documento Medico Limpiado Correctamente",
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                }
            })
        },
        doDocumentoPdfAdjuntoMedico() {
            // TODO: Check if the this.casId is correct when has cas_padre_id (ISSUE)
            const datos = { cas_id: this.casId };
            axios.post('api/obtenerDocumentoAdjuntoMedico', datos)
                .then(response => {
                    this.documento = response.data.data;
                })
                .catch(error => {
                    console.error('Error al generar al listado documentos medicos', error);
                });
        },
    }
};
</script>

<style>
.overlay_legal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    justify-content: center;
    align-items: center;
}
</style>
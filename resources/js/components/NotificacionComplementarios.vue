<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>

            </div>

            <div class="card-body">
                <div class="navegacion">
                    <select name="porPagina" @change="listarRegistrosCom" v-model="RegistrosXPagina"
                        class="selectRegistros">
                        <option v-for="n in opcionesRegistrosPorPagina" :key="n" :value="n">{{ n }}</option>
                    </select>

                    <button class="btn btn-warning" type="button" style="float: left;" @click="refrescarListNotificaciones">
                        <i :class="{'fas fa-sync-alt': true, 'fa-spin': showLoader}"></i> Refrescar
                    </button>

                    <div class="select-container">
                        <button @click="anteriorPagina()" class="btnEAvance">
                            <i class="fa fa-arrow-left white" aria-hidden="true" :class="{ 'fa-spin': isArrowSpinningLeft }"></i>
                                Anterior
                        </button> &nbsp; &nbsp;

                        <select name="paginacion" @change="listarRegistrosCom()" v-model="PaginaActual" class="selectRegistros">
                            <option v-for="pagina in paginas" :key="pagina" :value="pagina">
                                {{ pagina }}
                            </option>
                        </select>

                        &nbsp; &nbsp;
                            <button @click="siguientePagina()" class="btnEAvance">
                                Siguiente <i class="fa fa-arrow-right white" aria-hidden="true" :class="{ 'fa-spin': isArrowSpinningRight }"></i>
                            </button>
                    </div>

                    <div class="buscarEAvance">
                        <label style="color: white;">Buscar: </label>
                        <input type="search" v-model="buscarRegistro" @keyup.enter="convertirAMayusculas(); listarRegistrosCom()" class="selectRegistros"
                            placeholder="Buscar ...">
                    </div>

                </div>
                <table class="table table-bordered table-hover table-striped table-responsive-lg" id="divTable">

                    <thead class="thead-dark text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">#</th>
                            <th scope="col">CÓDIGO DEL <br> DOCUMENTO</th>
                            <th scope="col">REFERENCIA DEL <br> DOCUMENTO</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col">USUARIO REGISTRADOR</th>
                            <th scope="col">USUARIO TRAMITE</th>
                            <th scope="col">ALERTA</th>
                            <th scope="col">FECHA</th>
                            <th scope="col">OPCIONES</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        <tr v-for="adj in adjuntos " :key="adj.doc_id">
                            <td width="3%" scope="row">{{ adj.doc_cas_id }}</td>
                            <td width="3%" scope="row">{{ adj.doc_id }}</td>
                            <td>{{ adj.doc_codigo }}</td>
                            <td>{{ adj.doc_referencia }}</td>
                            <td>{{ adj.doc_descripcion }}</td>
                            <td>{{ adj.doc_user_name }} <br></td>
                            <td>
                                {{ adj.htc_user_email }} <br>
                                <span class="badge badge-dark">{{ (JSON.parse(adj.act_data)).act_orden }}</span>
                                - {{ (JSON.parse(adj.act_data)).act_descripcion }} <br>
                              </td>
                            <td>
                                <span v-if="adj.alerta === 'R'" class="badge badge-warning"><strong> REGISTRADO </strong></span>
                                <span v-else class="badge badge-secondary"><strong>{{ adj.alerta }}</strong></span>
                            </td>

                            <td>
                                <i class="fas fa-calendar" style="color:#297373"></i> {{ adj.doc_modificado ? adj.doc_modificado.substr(0, 10) : '-' }} <br> <i class="fas fa-clock" style="color:#297373"></i> {{ adj.doc_modificado ? adj.doc_modificado.substr(10, 5) : '-' }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-circle" title="Ver Documento"
                                        v-on:click="verDocumento(adj.doc_id, adj.doc_url)">
                                    <i class="far fa-file-pdf white" aria-hidden="true"></i>
                                </button>

                                <button type="button" class="btn btn-success btn-circle pulseBtn" title="Marcar como leido"
                                        v-on:click="actualizarRegistrosCom(adj.doc_id, adj.doc_url)" style="background-color: #0b63bb;">
                                    <i class="fas fa-folder white" aria-hidden="true"></i>
                                </button>

                                <div id="toast" class="toast">.</div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
                <!-- Loader -->
        <div v-if="showLoader" class="loader-container" style="background-color: rgba(255, 255, 255, 0.9);">
            <div class="loader-wrapper">
                <div class="loader"></div>
                <span class="loader-text">TramiteSIP</span>
                <span class="loading-text">Actualizando...</span>
            </div>
        </div>

    </div>
</template>

<style>
    .pulseBtn {
        background: #0b63bb;
        color: #fff;
        border: 1px solid #0b63bb;
        font-size: 1rem;
        box-shadow: 0 0 0 0 rgba(88, 120, 243, 0.4);
        -moz-animation: pulse 2s infinite;
        -webkit-animation: pulse 2s infinite;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(88, 120, 243, 1);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(88, 120, 243, 0);
        }
        100% {
            box-shadow: 0 0 0 50px rgba(88, 120, 243, 0);
        }
    }

    /* Notificaciones */
    .toast {
        visibility: hidden;
        max-width: 50%;
        background: linear-gradient(90deg, #d69242, #f0a500);
        color: #fff;
        text-align: center;
        border-radius: 0.8rem;
        padding: 1rem;
        position: fixed;
        z-index: 1;
        top: 80px;
        right: 20px;
        font-size: 1rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: visibility 0s, opacity 0.8s linear, transform 0.8s ease-out;
        transform: translateY(-100%);
    }

    .toast.show {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }

    .toast.hide {
        visibility: hidden;
        opacity: 0;
        transform: translateY(-100%);
        transition: transform 0.8s ease-in, opacity 0.8s ease-in;
    }
</style>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        name: "notificacionComplementarios",
        data() {
            return {
                plural: 'Notificación Complementarios',
                dataTable: null,
                adjuntos: [],
                error: null,
                showLoader: false,
                PaginaActual: 1,
                RegistrosXPagina: 10,
                opcionesRegistrosPorPagina: [5, 10, 15, 20, 25],
                buscarRegistro: '',
                paginas: Array.from({ length: 100 }, (_, index) => index + 1),
                isArrowSpinningRight: false,
                isArrowSpinningLeft: false,
                selectedEstado: ''
            }
        },

        mounted() {
            this.listarRegistrosCom();
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
            refrescarListNotificaciones() {
                this.showLoader = true;
                this.listarRegistrosCom();
                setTimeout(() => {
                    this.showLoader = false;
                }, 500);
            },
            listarRegistrosCom() {
                let url = "/api/listarRegisComplementario" + '/' + this.RegistrosXPagina + '/' + this.PaginaActual;

                if (this.buscarRegistro) {
                    url += `?buscar=${this.buscarRegistro}`;
                }

                axios.get(url)
                    .then(response => {
                        this.adjuntos = response.data.data;
                    })
                    .catch(error => {
                        console.error('Error al visualizar los Adjuntos Complementarios:', error);
                        this.error = "Se ha producido un error al cargar las notificaciones.";
                    });
            },
            anteriorPagina() {
                this.isArrowSpinningLeft = true;

                setTimeout(() => {
                    if (this.PaginaActual > 1) {
                        this.PaginaActual--;
                        this.listarRegistrosCom();
                    }
                    this.isArrowSpinningLeft = false;
                }, 200);

            },
            siguientePagina() {
                this.isArrowSpinningRight = true;

                setTimeout(() => {
                    if (this.PaginaActual < this.paginas.length) {
                        this.PaginaActual++;
                        this.listarRegistrosCom();
                    }
                    this.isArrowSpinningRight = false;
                }, 200);
            },
            actualizarRegistrosCom(doc_id ,doc_codigo) {
                const url = "api/actualizarRegisComplementario";
                axios.post(url, { doc_id })
                    .then(response => {
                        this.listarRegistrosCom();
                        Swal.fire({
                            title: 'Documento marcado como Leído',
                            // html: '<strong>' + doc_codigo + '</strong> marcado.',
                            icon: "success",
                            confirmButtonText: 'Aceptar'
                        });
                    })
                    .catch(error => {
                        console.error('Error al actualizar los Adjuntos Complementarios:', error);
                        this.error = "Se ha producido un error al actualizar los Adjuntos.";
                    });
            },

            convertirAMayusculas() {
                this.buscarRegistro = this.buscarRegistro.toUpperCase();
            },

            verDocumento: function (doc_id, doc_url) {
                const url = "/api/verDocumentoPdfNotiAdjuntos/" + doc_id;
                const partes = doc_url.split('.');
                axios.get(url, { responseType: 'blob' })
                    .then(response => {
                        const extension = partes[partes.length - 1].toLowerCase();

                        if (extension === 'pdf') {
                            const pdfBlob = new Blob([response.data], { type: 'application/pdf' });
                            const pdfUrl = window.URL.createObjectURL(pdfBlob);
                            const windowProps = 'top=0,left=0,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=550,height=650';
                            const newWindow = window.open(pdfUrl, '_blank', windowProps);
                            newWindow.document.body.innerHTML = `<iframe src="${pdfUrl}" width="100%" height="100%"></iframe>`;
                        } else {
                            const blob = new Blob([response.data], { type: 'application/pdf' });
                            const downloadUrl = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = downloadUrl;
                            link.setAttribute('download', doc_url.split('/').pop());
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    })
                    .catch(error => {
                        this.showToast('No se puede mostrar el documento');
                        console.error('Error al mostrar el documento:', error);
                    });
            },
            showToast(message) {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.className = 'toast show';
                setTimeout(() => {
                    toast.className = 'toast hide';
                }, 3000);
            }
        }
    };
</script>

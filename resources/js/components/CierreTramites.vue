<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>CIERRE DE TRAMITES</h5>
            </div>
            <div class="card-body">
                <h5>AGENCIA: {{ agencia }}</h5>
                <div class="actions-container">
                    <div class="container-actions">
                        <h5>Cierre</h5>
                        <button v-if="this.btnCierre" class="btn btn-danger" data-toggle="modal" data-target="#modalCierreDiario">
                            <i class="fas fa-file-alt white" aria-hidden="true"></i> Cierre Diario
                        </button>
                        <button v-if="!this.btnCierre" class="btn btn-success" data-toggle="modal" data-target="#modalAnularCierreDiario">
                            <i class="far fa-file-alt white" aria-hidden="true"></i> Anular Cierre
                        </button>
                    </div>
                    <div class="container-actions">
                        <h5>Reimpresión</h5>
                        <div class="date-action">
                            <input type="date" class="form-control-date mb-2" v-model="fechaReimpresion">
                            <button class="btn btn-primary" @click="obtenerCierre()">
                                <i class="fa fa-print white" aria-hidden="true"></i> Reimpresión de Cierre
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Cierre Diario -->
        <div class="modal fade" id="modalCierreDiario" tabindex="-1" role="dialog" aria-labelledby="modalCierreDiario"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="modalCierreDiarioLabel">Cierre Diario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-9">
                                <label>¿ Esta seguro de realizar el Cierre Diario de Tramites ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger" @click="cierreDiarioTramite()" data-dismiss="modal">Si</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Cierre Diario -->
        <div class="modal fade" id="modalAnularCierreDiario" tabindex="-1" role="dialog" aria-labelledby="modalAnularCierreDiario"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="modalAnularCierreDiarioLabel">Anular Cierre</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-9">
                                <label>¿ Esta seguro de realizar la Anulación del Cierre Diario de Tramites ?</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" @click="anularCierre()" data-dismiss="modal">Si</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Cierre Diario Pendientes-->
        <div class="modal fade" id="modalTramitesPendientes" tabindex="-1" role="dialog" aria-labelledby="modalTramitesPendientes"
            aria-hidden="true">
            <div class="modal-dialog" style="max-width: 45%; margin: auto;" role="document">
                <div class="modal-content" style="height: 90vh; overflow-y: auto;">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="modalTramitesPendientesLabel">Cierre - Tramites Pendientes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-12">
                                <label>Existen Tramites en estado INICIADO: </label>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-hover table-striped table-responsive" id="tramitesPendientesTable">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Nro.</th>
                                            <!-- <th>Usuario</th> -->
                                            <th style="width: 130px;">Usuario Actual</th>
                                            <th style="width: 130px;">Estado Actual</th>
                                            <th style="width: 150px;">Actividad</th>
                                            <th style="width: 150px;">Tramite</th>
                                            <th style="width: 150px;">Nro. Tramite</th>
                                            <th style="width: 100px;">CUA</th>
                                            <th style="width: 150px;">Asegurado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(tramite, index) in this.tramitesPendientes">
                                            <td>{{ index + 1 }}</td>
                                            <!-- <td>{{ tramite.usuario_registro }}</td> -->
                                            <td>{{ tramite.usuario_actual }}</td>
                                            <td>{{ tramite.estado_actual }}</td>
                                            <td>
                                                <span class="badge badge-dark">{{ tramite.actividad_orden }}</span> 
                                                - {{ tramite.actividad_descripcion }}
                                            </td>
                                            <td>{{ tramite.tipo_tramite }}</td>
                                            <td>{{ tramite.nro_solicitud }}</td>
                                            <td>{{ tramite.cua }}</td>
                                            <td>{{ tramite.nombre_asegurado }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
                        <!--<button type="button" class="btn btn-danger" data-dismiss="modal">Si</button>-->
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Reimpresion Cierre Diario -->
        <div class="modal fade" id="modalReimpresionCierre" tabindex="-1" role="dialog" aria-labelledby="modalReimpresionCierre"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="modalReimpresionCierreoLabel">Reimpresión Cierre Diario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-9">
                                <label id="mensajeReimpresion"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Respuesta Anular Cierre Diario -->
        <div class="modal fade" id="modalRespuestaAnularCierre" tabindex="-1" role="dialog" aria-labelledby="modalRespuestaAnularCierre"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title" id="modalRespuestaAnularCierreLabel">Anular Cierre</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row justify-content-left">
                            <div class="col-md-9">
                                <label id="mensajeRespuestaAnulacion"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            usrId: window.Laravel.usr_id,
			id_regional: window.Laravel.id_regional,
			id_agencia: window.Laravel.id_agencia,
			es_atc: window.Laravel.es_atc,
			es_supervisor: window.Laravel.es_supervisor,
            id_departamento: window.Laravel.id_departamento,
            agencia: '',
            regional: '',
            departamento: '',
            fechaCierre: new Date().toISOString().substring(0, 10),
            fechaReimpresion: new Date().toISOString().substring(0, 10),
            tramitesPendientes: [],
            gerenteRegional: '',
            btnCierre: true,
        }
    },
    mounted() {
        this.obtenerAgencia();
    },
    methods: {
        obtenerAgencia() {
            let url = "api/obtenerDepartamento";
            var params = {
                "id_regional": this.id_regional,
                "id_agencia": this.id_agencia,
                "id_departamento": this.id_departamento
            };
            //console.log(params);
            axios.post(url, params).then(response => {
                this.agencia = response.data.data[0].agencia;
                this.regional = response.data.data[0].regional;
                this.departamento = response.data.data[0].departamento;
                this.verificaCierre();
            });
        },
        cierreDiarioTramite() {
            //console.log('cierreDiarioTramite');
            let url = "api/tramitesCierre";
            var params = {
                "idAgencia": this.id_agencia,
                "agencia": this.agencia,
                "regional": this.regional,
                "departamento": this.departamento,
                "usuario": this.usrId
            };
            axios.post(url, params).then(response => {
                if (response.data[0].codigoRespuesta === 200) {
                    //console.log(response.data[0].data.original);
                    var pdfBase64 = response.data[0].data.original;
                    var binaryString = window.atob(pdfBase64);
					var binaryLen = binaryString.length;
					var bytes = new Uint8Array(binaryLen);
					for (var i = 0; i < binaryLen; i++) {
						var ascii = binaryString.charCodeAt(i);
						bytes[i] = ascii;
					}
					var blob = new Blob([bytes], { type: 'application/pdf' });
					var url = URL.createObjectURL(blob);
					window.open(url);
                    this.btnCierre = false;
                } else {
                    $('#modalTramitesPendientes').modal('show');
                    this.tramitesPendientes = response.data[0].data;
                    console.log(this.tramitesPendientes);
                }
            });
        },
        obtenerCierre() {
            //console.log('obtenerCierre');
            let url = "api/obtenerDocumentoCierre";
            var params = {
                "regional": this.regional,
                "agencia": this.agencia,
                "fechaCierre": this.fechaReimpresion
            };
            axios.post(url, params).then(response => {
                //console.log(response.data);
                var pdfBase64 = response.data;
                if(pdfBase64 == 401) {
                    document.getElementById('mensajeReimpresion').textContent = `No Existe el documento de cierre para la fecha ${this.fechaReimpresion}`;
                    $('#modalReimpresionCierre').modal('show');
                } else {
                    if(pdfBase64 == 402) {
                        document.getElementById('mensajeReimpresion').textContent = `Hubo un problema al encontrar el documento de cierre para la fecha ${this.fechaReimpresion}`;
                        $('#modalReimpresionCierre').modal('show');
                    } else {
                        var binaryString = window.atob(pdfBase64);
                        var binaryLen = binaryString.length;
                        var bytes = new Uint8Array(binaryLen);
                        for (var i = 0; i < binaryLen; i++) {
                            var ascii = binaryString.charCodeAt(i);
                            bytes[i] = ascii;
                        }
                        var blob = new Blob([bytes], { type: 'application/pdf' });
                        var url = URL.createObjectURL(blob);
                        window.open(url);
                    }
                }
            });
        },
        anularCierre() {
            //console.log('anularCierre');
            let url = "api/anularCierre";
            var params = {
                "regional": this.regional,
                "agencia": this.agencia,
                "fechaCierre": this.fechaCierre,
                "usuario": this.usrId
            };
            axios.put(url, params).then(response => {
                //console.log(response.data[0].data);
                document.getElementById('mensajeRespuestaAnulacion').textContent = response.data[0].data;
                $('#modalRespuestaAnularCierre').modal('show');
                if(response.data[0].codigoRespuesta === 200) {
                    this.btnCierre = true;
                }
            });
        },
        verificaCierre() {
            //console.log('verificaCierre');
            let url = "api/verificaCierre";
            var params = {
                "regional": this.regional,
                "agencia": this.agencia,
                "fechaCierre": this.fechaCierre
            };
            //console.log(params);
            axios.post(url, params).then(response => {
                //console.log(response.data[0].data);
                this.btnCierre = (response.data[0].data === 0);
                //return (response.data[0].data !== 0);
            });
        }
    },
}
</script>
<style>
.actions-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}

.container-actions {
    flex: 1;
    margin: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    min-width: 300px;
}

.date-action {
    display: flex;
    align-items: center; 
}

.form-control-date {
    width: 30%;
    margin-right: 10px;
}
</style>
<template>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>{{ plural }}</h5>
                <div class="row col-12">
                    <table class="table table-hover table-striped table-responsive" id="divTable000">
                        <tr v-for="(p, index) in procesos">
                            <td>
                                <span v-for="index in p.cat_codigo.length" :key="index">&nbsp;&nbsp;</span>
                                {{ p.cat_codigo }} - {{ p.cat_descripcion }}
                            </td>
                            <td>{{ p.prc_data.prc_codigo }} - {{ p.prc_data.prc_descripcion }}</td>
                            <td>
                                <button
                                    class="btn btn-button btn-success" v-on:click="listarRegistros(p.prc_id, p.prc_data.prc_codigo, p.prc_data.prc_descripcion);doLimpiar(p.prc_data)" data-toggle="modal"
                                    data-target="#modalCrear">
                                    <i class="fa fa-plus white" aria-hidden="true"></i> Crear Caso
                                </button>
                            </td>
                            <td>
                                <button @click="listarRegistros(p.prc_id, p.prc_data.prc_codigo, p.prc_data.prc_descripcion)" class="btn btn-button btn-primary">
                                    <i class="fa fa-list white" aria-hidden="true"></i> Ver Actividades
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card-body">
                <h5>{{ codigo }} - {{ descripcion }}  </h5>
                <table class="table table-hover table-striped table-responsive" id="divTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">ACTIVIDAD</th>
                            <th scope="col" align="center">ORDEN</th>
                            <th scope="col">DESCRIPCIÓN</th>
                            <th scope="col" align="center">DURACIÓN HORAS</th>
                            <th scope="col" align="center">SIGUIENTE ACTIVIDAD</th>
                            <th scope="col" align="center">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(r, index) in registros">
                            <td style="text-align: center;"> <strong>{{ index + 1}}</strong></td>
                            <td style="text-align: center;" width="3%" scope="row"><span class="badge badge-primary">{{ r.act_id }}</span></td>
                            <td width="5%" style="text-align: center;">
                                {{ r.act_data.act_orden}}
                                <i v-if="r.tact_codigo == 'I'" class="far fa-circle green" aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'A'" class="far fa-square blue" aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'B'" class="fas fa-project-diagram blue" aria-hidden="true"></i>
                                <i v-if="r.tact_codigo == 'F'" class="far fa-circle red" aria-hidden="true"></i>
                            </td>
                            <td>
                                {{ r.act_data.act_descripcion}}
                            </td>
                            <td style="text-align: center;">
                                {{ r.act_data.act_duracion_horas_maximo}}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge badge-warning">{{ r.act_data.act_siguiente}}</span>
                            </td>
                            <td style="text-align: center;">
                                <span v-if="r.act_estado === 'A'" class="badge badge-success">ACTIVO</span>
                                <span v-else-if="r.act_estado !== 'A'" class="badge badge-warning">{{
                                    r.act_estado}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                                <label>¿ Confirma crear el {{ singular }} ?</label> <br>
                                {{ codigo }} - {{ descripcion }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-success" :disabled="isDisable" @click="debouncedUpdate()" data-dismiss="modal">Si,
                            crear</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    //import datatables from 'datatables';
    // import Vue from 'vue'
    // import VueRouter from 'vue-router';
    // Vue.use(VueRouter);
    import Swal from 'sweetalert2';
    import debounce from 'lodash.debounce';

    import {encryptId} from './shared/AuxiliaryFunctions';
    export default {
        name: 'crear-caso',
        created() {
            this.debouncedUpdate = debounce(this.crearCaso, 1000);
        },
        beforeDestroy() {
            this.debouncedUpdate.cancel();
        },
        data() {
            return {
                plural: 'Crear Caso',
                singular: 'Caso',
                descripcion : '',
                usrId: window.Laravel.usr_id,
                usr_user: window.Laravel.usr_user,
				id_regional: window.Laravel.id_regional,
				id_agencia: window.Laravel.id_agencia,
				id_departamento: window.Laravel.id_departamento,
                seleccionado: '',
                errores: [],
                registro: {},
                registros: [],
                procesos: [],
                departamento: {},
                primer_act_id: 0,
                primer_act_nodo_id: 0,
                primer_prc_id: 0,
                dataTable: null,
                prc_codigo: '',
                isDisable:false,
            }
        },

        mounted() {
            this.listarProcesos();
        },

        methods: {
            listarRegistros(prc_id, prc_codigo, prc_descripcion) {
                console.log("prc_id: ", prc_id);
                console.log("prc_codigo: ", prc_codigo);
                console.log("prc_descripcion: ", prc_descripcion);
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

            listarProcesos() {
                //let url = "api/procesosTodos/";
                var params = {"usr_id": this.usrId};
                let url = "api/procesosXUsrId";
                axios.post(url, params).then(response => {
                    this.procesos = response.data.data; //twice data
                    this.procesos.forEach(function (row) {
                        row.prc_data = JSON.parse(row.prc_data);
                    });
                });
                let url2 = "api/obtenerDepartamento";
                var params = {"id_regional": this.id_regional,
                "id_agencia": this.id_agencia,
                "id_departamento": this.id_departamento};
                axios.post(url2, params).then(response => {
                    this.departamento = response.data.data[0]; //twice data
                });
            },

            doLimpiar(datos) {
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
                    NOMBRE_PROCESO: datos.prc_descripcion,
                    TIPO_PROCESO: datos.prc_codigo,
                    USUARIO_REGISTRO: this.usr_user,
                    ESTADO_DERIVACION: 'INICIADO',
                    DESCRIPCION_DERIVACION: '',
                    id_cas_departamento: this.id_departamento,
                    id_cas_agencia: this.id_agencia,
                    id_cas_regional: this.id_regional,
                    cas_registrado : fechaActualDate,
                    de_usuario : '',
                    a_usuario : this.usr_user,
                };
            },

            crearCaso() {
                //1) prevent multiple clicks
                if(this.isDisable) return;
                this.isDisable = true;
                $("#modalCrear").modal('hide');
                setTimeout(()=>{
                    this.isDisable = false;
                },4000);
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
                let that = this;
                let url = "api/casos";
                console.log(gRegistro);
                axios.post(url, gRegistro)
                    .then(function (response) {
                        console.log('response ',response.data.codigoRespuesta);
                        if(response.data.codigoRespuesta.code == '200' ){
                              Swal.fire({
                                position: "center",
                                icon: "success",
                                title: " Atender el caso:  "  + response.data.codigo,
                                showConfirmButton: false,
                                timer: 2000
                            });
                                that.cas_id = response.data.data;
                                //window.location = '/home#/misCasos';
                                const urlEncodeCasId = encryptId(response.data.data);
                                if (that.registro.TIPO_PROCESO=='LEGAL'){
                                    console.log("legal");
                                    that.$router.push("/atenderCasoRender/" + urlEncodeCasId);
                                } else {
                                    console.log("otros");
                                    that.$router.push("/atenderCaso/" + urlEncodeCasId);
                                }
                                ////that.$router.push("/atenderCaso/" + that.encryptId(that.cas_id));
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: "warning",
                                title: "Registro duplicado: cas_cod_id:  " + response.data.codigo,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    });
            }
        },
    }
</script>

<style>
    .btn-circle {
        width: 30px;
        height: 30px;
        padding: 6px 0px;
        border-radius: 15px;
        text-align: center;
        font-size: 12px;
        line-height: 1.42857;
    }

    .combo {
        font-size: 10px;
        height: 25px !important;
    }
</style>

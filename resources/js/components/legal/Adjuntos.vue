<template>

    <div>
        <div v-show="isVisible" id="divDocumentosLegal" class="container" style="max-width: 100%; padding: 20px;">

            <div class="card-body">

                <!--<div class="form-group">
                    <label for="prestacionSelect">Seleccione Prestación:</label>
                    <select v-model="selectedPrestacion" id="prestacionSelect" class="form-control" multiple>
                        <option v-for="prestacion in prestaciones" :key="prestacion.lgp_id" :value="prestacion.lgp_id">
                            {{ prestacion.lgp_nombre }}
                        </option>
                    </select>
                </div>
                <div class="form-group" v-if="requisitos.length > 0">
                    <label for="requisitoSelect">Seleccione Requisito:</label>
                    <select v-model="selectedRequisito" id="requisitoSelect" class="form-control">
                        <option value="" disabled>Seleccione un requisito</option>
                        <option v-for="requisito in requisitos" :key="requisito.lgp_id" :value="requisito.lgp_id">
                            {{ requisito.lgp_nombre }}
                        </option>
                    </select>
                </div>-->
                <!--<table class="table table-hover table-striped table-responsive" id="tabla_requisitos_legal">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" style="text-align: center;">#</th>
                            <th scope="col" style="text-align: center;">Descripción</th>
                            <th scope="col" style="text-align: center;">Documento</th>
                            <th scope="col" style="text-align: center;">Original/Fotocopia1</th>
                            <th scope="col" style="text-align: center;">Opción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(d, index) in documento">
                            <td scope="row">{{ d.lgreq_id }}</td>
                            <td>{{ d.lgreq_descripcion }}
                                <input :id="'descripcion_legal_' + index" v-model="d.lgreq_descripcion"
                                    class="form-control" placeholder="Ingrese Documento" hidden=false>
                                <input :id="'id_legal_' + index" v-model="d.lgreq_id" class="form-control"
                                    placeholder="Ingrese Documento" hidden=false>
                            </td>
                            <td style="text-align: center;">
                                <div class="input-group mb-3">
                                    <input v-if="d.nombre != ''" v-model="d.descripcionRespaldo" class="form-control"
                                        placeholder="Ingrese Documento" disabled>
                                    <input v-if="d.nombre == ''" class="form-control" placeholder="Ingrese Documento"
                                        disabled>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-xs"
                                            v-on:click.stop.prevent="verDocumento(d.rdoc_id, d.nombre)">
                                            <i class="fa fa-eye white" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <input :id="'pdf_sol_legal_' + index" type="file"
                                    @change="tamanoDocumento($event, index)" accept=".pdf">

                            </td>
                            <td style="text-align: center;">
                                <label :for="'switch_legal' + index" class="switch">
                                    <input :id="'switch_legal' + index" v-model="d.rdoc_copia_original" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-danger btn-circle" title="Dar baja documento"
                                    @click="eliminarDocumento(index)">
                                    <i class="fa fa-eraser" style="font-size:16px;color:white"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>-->

                <div v-if="documentosContratacionEventual.length != 0">
                    <div style="background-color:lightgrey; margin-top:10px;">
                        <h5>Adjuntos</h5>
                    </div>

                    <table class="table table-hover table-striped table-responsive" id="tabla_titular">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" style="text-align: center;">#</th>
                                <th scope="col" style="text-align: center;">Descripción</th>
                                <th scope="col" style="text-align: center;">Documento</th>
                                <th scope="col" style="text-align: center;">Fotocopia/Original</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(d, index) in documentosContratacionEventual">
                                <td width="3%" scope="row">{{ index + 1 }}</td>
                                <td>{{ d.config__nombre }}
                                    <input v-model="d.config__documentoOriginalObligatorio__model"
                                        :required="d.config__documentoOriginalObligatorio" hidden=false>
                                    <input v-model="d.config__presentacionObligatoria__model"
                                        :required="d.config__presentacionObligatoria" placeholder="Ingrese Documento"
                                        hidden=false>
                                    <span v-if="d.config__presentacionObligatoria">
                                        <!-- <p v-if="!d.config__presentacionObligatoria__model && d.docPersistido__doc_value_url===undefined" class="gestora-error-input">Presentacion obligatoria.</p> -->
                                        <p v-if="!d.config__presentacionObligatoria__model" class="gestora-error-input">
                                            Presentacion obligatoria.</p>
                                    </span>
                                    <span v-if="d.config__documentoOriginalObligatorio">
                                        <p v-if="!d.config__documentoOriginalObligatorio__model"
                                            class="gestora-error-input">Se requiere documento original.</p>
                                    </span>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input :id="'documento_' + d.config__id"
                                            v-model="d.docPersistido__doc_value_url"
                                            v-if="d.docPersistido__doc_value_url != ''" class="form-control"
                                            placeholder="Ingrese Documento" disabled>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary btn-xs"
                                                v-on:click.stop.prevent="verDocumento(d.docPersistido__doc_id, d.docPersistido__doc_value_url)">
                                                <i class="fa fa-upload white" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <input v-if="actividad.act_orden == '20'" :id="d.front__file_dom_id" type="file"
                                        name="file" @change="tamanoDocumento($event, d)" accept=".pdf">

                                </td>
                                <td style="text-align: center;">

                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" @click="registrarRequisitos">Registrar
                        Requisitos</button>
                </div>-->
                <div id="overlay" ref="overlay" class="overlay">
                    <div class="loader-wrapper">
                        <div class="loader"></div>
                        <span class="loader-text">TramiteSip</span>
                        <span class="loading-text">Cargando...</span>
                    </div>
                </div>


            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Alert } from 'bootstrap';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            isVisible: true,
            documentosContratacionEventual: [],
            docCargadosContratacionEventual: [],
            title: '',
            requisitos: [],
            documento: [],
            archivos: {},
            cas_id: '',
            ci: '',
            id_persona_sip: '',
            file: null,
            prestaciones: [],
            selectedPrestacion: [],
            cas_cod_id: '',
            reasons: [],
            isModalVisibel: false,
            caso: '',
            id_caso: '',
            selectedValue: '',
            selectedValueHijo: '',

            prc_id: null,
            requisitos: [],
            selectedRequisito: null,
            registro: [],
            actividad: [{ act_data: {} }],
            valoresArray: [],
            usrId: window.Laravel.usr_id,
        };
    }, mounted() {
        // Ejemplo: Inicializar la tabla con una fila en el montaje del componente
        //this.gridAddRow(this.c.frm_cols, this.c.frm_value);
        this.cas_id = atob(this.cas_id);
        this.ocultarOverlay();
        this.$once('hook:mounted', () => {
            const esperarRegistro = async (intentos = 10) => {

                const valorDelPadre = this.$parent?.registro;

                if (!valorDelPadre || !Array.isArray(valorDelPadre.cas_data_valores)) {
                    if (intentos > 0) {
                        console.warn(`⏳ Esperando carga del padre... intentos restantes: ${intentos}`);
                        setTimeout(() => esperarRegistro(intentos - 1), 300); // espera 300ms y vuelve a intentar
                    } else {
                        console.error("❌ No se pudo obtener 'registro' del padre tras varios intentos.");
                    }
                    return;
                }

                // ✅ Ya tienes los datos, puedes seguir con tu lógica
                console.log("✅ valorDelPadre cargado:", valorDelPadre);

                const res = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_TIPO_EAP_LEGAL');
                const res1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_SUB_SOLICITUD');
                const res2 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_TIPO_EAP');

                console.log("✔️ Datos encontrados:", { res, res1, res2 });
                var SUBTITULO_4_44 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITULO_4_44');
                var HA_PAGOS_SUPENDIDOS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_PAGOS_SUPENDIDOS');
                var HA_PAGOS_SUPENDIDOS_VAL = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_PAGOS_SUPENDIDOS_VAL');
                var HA_PAGO_AGUINALDO_VA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_PAGO_AGUINALDO_VA');
                var TIPO_PODER = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'TIPO_PODER');
                var PERIODOS_INGRESADOS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'PERIODOS_INGRESADOS');
                var MENSAJE_PERIODOS_INGRESADOS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'MENSAJE_PERIODOS_INGRESADOS');
                var CA_BUSCAR = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'CA_BUSCAR');
                console.log("🚀 ~ mounted5 ~ CA_BUSCAR======:", CA_BUSCAR);
                var CASO_HEREDARO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'CASO_HEREDARO');
                var AS_TIPO_DOCUMENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_TIPO_DOCUMENTO');
                var AS_CI = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_CI');
                var AS_COMPLEMENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_COMPLEMENTO');
                var SUBTITULO_4_4 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITULO_4_4');
                var GRILLA_DAHE = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'GRILLA_DAHE');
                var SUBTITULO_4_4_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITULO_4_4_1');
                var GRILLA_DAHERDERO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'GRILLA_DAHERDERO');
                var ACEPTACION_DE_HERENCIA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'ACEPTACION_DE_HERENCIA');
                var NRO_SENTENCIA_RESOLUCION = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NRO_SENTENCIA_RESOLUCION');
                var JUZGADO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'JUZGADO');


                var FORM_JUB_MES_INI = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'FORM_JUB_MES_INI');
                var FORM_JUB_MES_FIN = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'FORM_JUB_MES_FIN');
                var HA_PAGO_AGUINALDO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_PAGO_AGUINALDO');
                var HA_GESTION_AGUINALDO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_GESTION_AGUINALDO');
                //************************************************************* */
                var SUBTITULO_1_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITULO_1_1');
                var BE_TIPO_DOCUMENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_TIPO_DOCUMENTO');
                var BE_CI = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_CI');
                var BE_COMPLEMENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_COMPLEMENTO');
                var BE_BUSCAR = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_BUSCAR');
                var BE_NACIMIENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_NACIMIENTO');
                var BE_CUA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_CUA');
                var BE_PRIMER_APELLIDO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_PRIMER_APELLIDO');
                var BE_SEGUNDO_APELLIDO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_SEGUNDO_APELLIDO');
                var BE_APELLIDO_CASADA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_APELLIDO_CASADA');
                var BE_PRIMER_NOMBRE = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_PRIMER_NOMBRE');
                var BE_SEGUNDO_NOMBRE = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_SEGUNDO_NOMBRE');
                var BE_ESTADO_CIVIL = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_ESTADO_CIVIL');
                var BE_GENERO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_GENERO');
                var BE_CELULAR = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_CELULAR');
                var BE_DIFERENTE_AS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_DIFERENTE_AS');

                var PODER_DIRNOPLU = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'PODER_DIRNOPLU');
                console.log("🚀 ~ mounted5 ~ PODER_DIRNOPLU======:", PODER_DIRNOPLU);
                var BE_PARENTESCO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_PARENTESCO');
                var SUBTITLE_11 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITLE_11');
                var NRO_PODER_SOL_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NRO_PODER_SOL_1');

                var NRO_NOTARIA_SOL_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NRO_NOTARIA_SOL_1');
                var NOMBRE_NOTARIO_SOL_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NOMBRE_NOTARIO_SOL_1');
                var FECHA_DE_EMISION = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'FECHA_DE_EMISION');
                var DEPARTAMENTO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'DEPARTAMENTO');
                var MUNICIPIO_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'MUNICIPIO_1');
                var PODER_SCANER = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'PODER_SCANER');
                var ESTADO_PODER_PRESENTADO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'ESTADO_PODER_PRESENTADO');
                var OBSERVACION_PODER = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'OBSERVACION_PODER');
                var MUNICIPIO_1 = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'MUNICIPIO_1');
                var AS_BUSCAR = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'AS_BUSCAR');
                var SOL_BUSCAR = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SOL_BUSCAR');
                var EXTRANGERO_PODER = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'EXTRANGERO_PODER');
                var PAIS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'PAIS');
                var SUBTITLE_11_APOS = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITLE_11_APOS');
                var NUMERO_APOSTILLA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NUMERO_APOSTILLA');
                var CARTA_APOSTILLA = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'CARTA_APOSTILLA');
                var TIENE_PODER = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'TIENE_PODER');
                var HA_PAGO_UNICO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'HA_PAGO_UNICO');
                var BE_CI_DOC = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'BE_CI_DOC');
                var NRO_PODER_REVOCATORIO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'NRO_PODER_REVOCATORIO');

                var SUBTITULO_4_4_BE = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'SUBTITULO_4_4_BE');
                var GRILLA_DACO = valorDelPadre.cas_data_valores.find(item => item.frm_campo === 'GRILLA_DACO');

                // Lógica si están los datos
                if (res?.frm_value?.length > 0) {
                    console.log("👉 Ejecutando lógica con res.frm_value:", res.frm_value);
                    if (valorDelPadre.act_data.act_orden == 20) {
                        // alert(131);
                        this.__show(BE_BUSCAR.frm_campo);
                        this.__show(CA_BUSCAR.frm_campo);
                        this.__show(AS_BUSCAR.frm_campo);
                        this.__show(SOL_BUSCAR.frm_campo);
                    } else {
                        //alert(132);
                        this.__hide(BE_BUSCAR.frm_campo);
                        this.__hide(CA_BUSCAR.frm_campo);
                        this.__hide(AS_BUSCAR.frm_campo);
                        this.__hide(SOL_BUSCAR.frm_campo);
                    }
                    //2025-04-03

                    const response = await axios.post('/api/parametricaDeParametricas', {
                        agrupador: "LEGAL-GRUPO",
                    });

                    // Verifica la estructura real de `data`
                    console.log("Respuesta del API:", response);
                    const parametricasLegal = response.data.data; // data ya es un array
                    console.log("🚀 ~ mounted3 ~ parametricasLegal:", parametricasLegal);
                    // Busca el objeto donde `pdp_parameter_name` es 'GRUPO-COBRO'
                    const grupoCobro = parametricasLegal.find(item => item.pdp_parameter_name === 'GRUPO-COBRO');
                    console.log(grupoCobro);
                    // Extrae el array de valores si existe, sino deja un array vacío
                    const parametricasLegalGrupoCobro = grupoCobro ? grupoCobro.pdp_parameter_value : [];
                    console.log("🚀 ~ parametricasLegalGrupoCobro============================:", parametricasLegalGrupoCobro);
                    const prestaacion_id = Number(res2.frm_value);
                    // alert("prestacion_id: " + prestaacion_id);
                    if (parametricasLegalGrupoCobro.includes(prestaacion_id)) {
                        // this.__show(SUBTITULO_4_44.frm_campo + "_idd");
                        //****BENIFICIARIO******* */
                        this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                        this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                        this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                        this.__hide(BE_CI.frm_campo + "_idd");
                        this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                        this.__hide(BE_BUSCAR.frm_campo + "_idd");
                        this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                        this.__hide(BE_CUA.frm_campo + "_idd");
                        this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                        this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                        this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                        this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                        this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                        this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                        this.__hide(BE_GENERO.frm_campo + "_idd");
                        this.__hide(BE_CELULAR.frm_campo + "_idd");
                        this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                        this.__hide(BE_CI_DOC.frm_campo + "_idd");

                        document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                        document.querySelector('#' + BE_CI.frm_campo).required = false;
                        document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                        document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                        document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                        document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                        document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                        //*****TABLA DE BENIFICIARIO****** */
                        if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                            const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                            }
                        }
                        if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                            const idCampo = GRILLA_DACO.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                            }
                        }
                        //*****FIN TABLA DE BENIFICIARIO****** */
                        //*****INICIO DE PODER*******/
                        this.__show(SUBTITLE_11.frm_campo + "_idd");
                        this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                        //this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                        this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                        this.__show(FECHA_DE_EMISION.frm_campo + "_idd");
                        this.__show(DEPARTAMENTO.frm_campo + "_idd");
                        this.__show(MUNICIPIO_1.frm_campo + "_idd");
                        this.__show(PODER_SCANER.frm_campo + "_idd");
                        //this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                        if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                            const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                                document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                            }
                        }
                        if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                            const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                                document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                            }
                        }
                        this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                        this.__show(SUBTITULO_4_4.frm_campo + "_idd");
                        this.__show(GRILLA_DAHE.frm_campo + "_idd");
                        this.__show(EXTRANGERO_PODER.frm_campo + "_idd");
                        this.__show(PAIS.frm_campo + "_idd");

                        if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                            const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                                document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                            }
                        }
                        document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                        //document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                        document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                        document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                        document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                        document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                        document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                        document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;

                        document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;
                        document.querySelector('#' + PAIS.frm_campo).required = true;
                        //*****FIN DE PODER*********/ HEREDEROS
                        this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                        this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                        if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                            const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                                document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                            }
                        }
                        if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                            const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                                document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                            }
                        }
                        if (JUZGADO && JUZGADO.frm_campo) {
                            const idCampo = JUZGADO.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                                document.querySelector('#' + JUZGADO.frm_campo).required = false;
                            }
                        }
                        //******COBRO************ */
                        this.__show(SUBTITULO_4_44.frm_campo + "_idd");
                        this.__show(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                        if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__show(idCampo);
                            }
                        }

                        this.__show(TIPO_PODER.frm_campo + "_idd");
                        //this.__show(FORM_JUB_MES_INI.frm_campo + "_idd");
                        //this.__show(FORM_JUB_MES_FIN.frm_campo + "_idd");
                        this.__show(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                        if (HA_PAGO_AGUINALDO.frm_value === "1" || HA_PAGO_AGUINALDO.frm_value === 1) {

                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = true;
                                }
                            }
                        }
                        else {
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                        }

                        this.__show(PERIODOS_INGRESADOS.frm_campo + "_idd");
                        this.__show(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");


                        document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = true;
                        document.querySelector('#' + TIPO_PODER.frm_campo).required = true;
                        //document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = true;
                        //document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = true;
                        document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = true;
                        document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = true;
                        document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = true;


                        //*****APOSTILLA */
                        this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                        this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                        this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                        this.__hide(TIENE_PODER.frm_campo + "_idd");
                        document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                        document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                        document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                        document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                        if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                            this.__show(PAIS.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                            this.__hide(MUNICIPIO_1.frm_campo + "_idd");

                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                            document.querySelector('#' + PAIS.frm_campo).required = true;
                            this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                        } else {

                            this.__hide(PAIS.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            this.__show(DEPARTAMENTO.frm_campo + "_idd");
                            this.__show(MUNICIPIO_1.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                            document.querySelector('#' + PAIS.frm_campo).required = false;
                            this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                        }
                        const grupoCF = [199, 200, 201, 202];
                        if (grupoCF.includes(prestaacion_id)) {
                            /**/

                            if (HA_PAGO_UNICO.frm_value == undefined || HA_PAGO_UNICO.frm_value == null || HA_PAGO_UNICO.frm_value == "") {
                                document.querySelector('#' + HA_PAGO_UNICO.frm_campo).addEventListener('change', function () {
                                    if (document.getElementById("HA_PAGO_UNICO").value === "1" || document.getElementById("HA_PAGO_UNICO").value === 1) {
                                        this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                                        this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                                        document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                                        document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                                        document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).frm_value = "";
                                        document.getElementById('#' + FORM_JUB_MES_INI.frm_campo).dispatchEvent(new Event('change'));
                                        document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).frm_value = "";
                                        document.getElementById('#' + FORM_JUB_MES_FIN.frm_campo).dispatchEvent(new Event('change'));
                                    } else {
                                        //alert("Grupo CF1");
                                        this.__show(FORM_JUB_MES_INI.frm_campo + "_idd");
                                        this.__show(FORM_JUB_MES_FIN.frm_campo + "_idd");
                                        document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = true;
                                        document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = true;
                                    }
                                }.bind(this));
                            }
                            else {
                                this.__show(HA_PAGO_UNICO.frm_campo + "_idd");
                                document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = true;
                                if (HA_PAGO_UNICO.frm_value === "1" || HA_PAGO_UNICO.frm_value === 1) {
                                    this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                                    this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                                    document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                                    document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                                } else {
                                    this.__show(FORM_JUB_MES_INI.frm_campo + "_idd");
                                    this.__show(FORM_JUB_MES_FIN.frm_campo + "_idd");
                                    document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = true;
                                    document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = true;
                                }

                            }
                        }
                        else {
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            this.__show(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__show(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = true;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = true;
                        }


                        /* const idDiferente = BE_DIFERENTE_AS.frm_campo + "_idd";
                         const idBuscar = BE_BUSCAR.frm_campo + "_idd";

                         if (document.getElementById(idDiferente)) {
                             this.__show(idDiferente);
                         } else {
                             console.warn("Elemento no encontrado:", idDiferente);
                         }

                         if (document.getElementById(idBuscar)) {
                             this.__show(idBuscar);
                         } else {
                             console.warn("Elemento no encontrado:", idBuscar);
                         }*/

                        //**************BUSCAR CASO**************************/
                        /* this.__hide(CASO_HEREDARO.frm_campo + "_idd");
                         if (CA_BUSCAR && CA_BUSCAR.frm_campo) {
                             // Construye el id del campo
                             const campoId = CA_BUSCAR.frm_campo + "_idd";
                             // Verifica si el campo existe en el DOM antes de intentar ocultarlo
                             const campoElemento = document.getElementById(campoId);
                             if (campoElemento) {
                                 this.__hide(campoId);  // Si el elemento existe, lo ocultamos
                             } else {
                                 console.log(`El campo con id ${campoId} no existe en el DOM.`);
                             }
                         } else {
                             console.log("CA_BUSCAR no se encontró o no tiene frm_campo.");
                         }
                         document.querySelector('#' + CASO_HEREDARO.frm_campo).required = false;

                         if (valorDelPadre.act_data.act_orden == 20) {
                             // alert(131);
                             this.__enable(AS_TIPO_DOCUMENTO.frm_campo);
                             this.__enable(AS_CI.frm_campo);
                             this.__enable(AS_COMPLEMENTO.frm_campo);
                         } else {
                             //alert(132);
                             this.__disable(AS_TIPO_DOCUMENTO.frm_campo);
                             this.__disable(AS_CI.frm_campo);
                             this.__disable(AS_COMPLEMENTO.frm_campo);
                         }*/
                    } else {

                        const grupo3 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-3');
                        const grupo3Values = grupo3?.[0]?.pdp_parameter_value || [];//b) Validación de Poderes de inicio, seguimiento conclusión de trámite
                        const grupo1 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-1');
                        const grupo1Values = grupo1?.[0]?.pdp_parameter_value || [];// c) Masa Hereditaria - Poderes de inicio, seguimiento conclusión de trámite
                        const grupo4 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-4');
                        const grupo4Values = grupo4?.[0]?.pdp_parameter_value || []; //d) Masa Hereditaria – Declaratoria de Herederos
                        const grupo2 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-2');// APOSTILLA
                        const grupo2Values = grupo2?.[0]?.pdp_parameter_value || [];

                        const grupo5 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-5');
                        const grupo5Values = grupo5?.[0]?.pdp_parameter_value || [];

                        const grupo6 = parametricasLegal.filter(item => item.pdp_parameter_name === 'GRUPO-6');
                        const grupo6Values = grupo6?.[0]?.pdp_parameter_value || [];

                        if (grupo3Values.includes(prestaacion_id)) {
                            // alert("Grupo 3");
                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;

                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */

                            //*****INICIO DE PODER*******/
                            /* this.__show(SUBTITLE_11.frm_campo+"_idd");
                             this.__show(NRO_PODER_SOL_1.frm_campo+"_idd");
                             this.__show(NRO_NOTARIA_SOL_1.frm_campo+"_idd");
                             this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo+"_idd");
                             this.__show(FECHA_DE_EMISION.frm_campo+"_idd");
                             this.__show(DEPARTAMENTO.frm_campo+"_idd");
                             this.__show(MUNICIPIO_1.frm_campo+"_idd");
                             this.__show(PODER_SCANER.frm_campo+"_idd");
                             this.__show(PODER_DIRNOPLU.frm_campo+"_idd");
                             this.__show(SUBTITULO_4_4.frm_campo+"_idd");
                             this.__show(GRILLA_DAHE.frm_campo+"_idd");
                             this.__show(EXTRANGERO_PODER.frm_campo+"_idd");
                             this.__show(PAIS.frm_campo+"_idd");


                             document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                             document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                             document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                             document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                             document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                             document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                             document.querySelector('#' + PODER_SCANER.frm_campo).required = true;
                             document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                             document.querySelector('#' +EXTRANGERO_PODER.frm_campo).required = true;
                             document.querySelector('#' +PAIS.frm_campo).required = true;*/

                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                            //**************************************** */


                            this.__show(SUBTITLE_11.frm_campo + "_idd");
                            //   this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__show(FECHA_DE_EMISION.frm_campo + "_idd");

                            this.__show(PODER_SCANER.frm_campo + "_idd");
                            // this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__show(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__show(GRILLA_DAHE.frm_campo + "_idd");
                            this.__show(EXTRANGERO_PODER.frm_campo + "_idd");


                            //document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;

                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                }
                            }

                            if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                this.__show(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = true;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            } else {

                                this.__hide(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;



                            }



                        } else if (grupo4Values.includes(prestaacion_id)) {

                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */

                            //*****INICIO DE PODER*******/
                            this.__show(SUBTITLE_11.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            // this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__show(FECHA_DE_EMISION.frm_campo + "_idd");
                            this.__show(DEPARTAMENTO.frm_campo + "_idd");
                            this.__show(MUNICIPIO_1.frm_campo + "_idd");
                            this.__show(PODER_SCANER.frm_campo + "_idd");
                            // this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__hide(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHE.frm_campo + "_idd");
                            this.__show(EXTRANGERO_PODER.frm_campo + "_idd");
                            this.__show(PAIS.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                            // document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;
                            document.querySelector('#' + PAIS.frm_campo).required = true;
                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__show(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__show(GRILLA_DAHERDERO.frm_campo + "_idd");

                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                                if (ACEPTACION_DE_HERENCIA.frm_value === "1" || ACEPTACION_DE_HERENCIA.frm_value === 1) {
                                  //  alert("Grupo 4");
                                    if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                        const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                        if (document.getElementById(idCampo)) {
                                            this.__hide(idCampo);
                                            document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                        }
                                    }
                                    if (JUZGADO && JUZGADO.frm_campo) {
                                        const idCampo = JUZGADO.frm_campo + "_idd";
                                        if (document.getElementById(idCampo)) {
                                            this.__hide(idCampo);
                                            document.querySelector('#' + JUZGADO.frm_campo).required = true;
                                        }
                                    }
                                    if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                        this.__show(PAIS.frm_campo + "_idd");
                                        this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                        this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                        this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                        document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                        document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                        document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                        document.querySelector('#' + PAIS.frm_campo).required = true;
                                        this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                        document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                                    } else {

                                        this.__hide(PAIS.frm_campo + "_idd");
                                        this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                        this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                        this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                        document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                        document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                        document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                        document.querySelector('#' + PAIS.frm_campo).required = false;
                                        this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                        document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                                    }
                                }
                                else {


                                    if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                        const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                        if (document.getElementById(idCampo)) {
                                            this.__show(idCampo);
                                            document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = true;
                                        }
                                    }
                                    if (JUZGADO && JUZGADO.frm_campo) {
                                        const idCampo = JUZGADO.frm_campo + "_idd";
                                        if (document.getElementById(idCampo)) {
                                            this.__show(idCampo);
                                            document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                        }
                                    }
                                    this.__hide(EXTRANGERO_PODER.frm_campo + "_idd");
                                    this.__hide(NRO_PODER_SOL_1.frm_campo + "_idd");
                                    this.__hide(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                                    this.__hide(FECHA_DE_EMISION.frm_campo + "_idd");
                                    this.__hide(PAIS.frm_campo + "_idd");
                                    this.__hide(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                                    this.__hide(OBSERVACION_PODER.frm_campo + "_idd");
                                    this.__hide(PODER_DIRNOPLU.frm_campo + "_idd");
                                    this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                    this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                    this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                }
                            }



                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                }
                            }
                          /*  if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                this.__show(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = true;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            } else {

                                this.__hide(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                            }*/


                        }
                        else if (grupo6Values.includes(prestaacion_id)) {

                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */
                            //*****INICIO DE PODER*******/
                            this.__show(SUBTITLE_11.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            //  this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__show(FECHA_DE_EMISION.frm_campo + "_idd");
                            this.__show(DEPARTAMENTO.frm_campo + "_idd");
                            this.__show(MUNICIPIO_1.frm_campo + "_idd");
                            this.__show(PODER_SCANER.frm_campo + "_idd");
                            // this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__hide(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHE.frm_campo + "_idd");
                            this.__show(EXTRANGERO_PODER.frm_campo + "_idd");
                            this.__show(PAIS.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                            //document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;
                            document.querySelector('#' + PAIS.frm_campo).required = true;
                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = true;
                                }
                            }
                            if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                this.__show(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = true;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            } else {

                                this.__hide(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;


                            }


                        }
                        else if (grupo5Values.includes(prestaacion_id)) {
                            //alert("Grupo 5");

                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */

                            //*****INICIO DE PODER*******/
                            this.__show(SUBTITLE_11.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            //  this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__show(FECHA_DE_EMISION.frm_campo + "_idd");
                            this.__show(DEPARTAMENTO.frm_campo + "_idd");
                            this.__show(MUNICIPIO_1.frm_campo + "_idd");
                            this.__show(PODER_SCANER.frm_campo + "_idd");
                            // this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__show(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__show(GRILLA_DAHE.frm_campo + "_idd");
                            this.__show(EXTRANGERO_PODER.frm_campo + "_idd");
                            this.__show(PAIS.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                            // document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;
                            document.querySelector('#' + PAIS.frm_campo).required = true;
                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                }
                            }
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                            if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                this.__show(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = true;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            } else {

                                this.__hide(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                            }


                        }

                        else if (grupo1Values.includes(prestaacion_id)) {

                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */
                            //*****INICIO DE PODER*******/
                            this.__show(SUBTITLE_11.frm_campo + "_idd");
                            this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                            //this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__show(FECHA_DE_EMISION.frm_campo + "_idd");
                            this.__show(DEPARTAMENTO.frm_campo + "_idd");
                            this.__show(MUNICIPIO_1.frm_campo + "_idd");
                            this.__show(PODER_SCANER.frm_campo + "_idd");
                            //this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__show(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                }
                            }
                            this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__show(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__show(GRILLA_DAHE.frm_campo + "_idd");
                            this.__show(EXTRANGERO_PODER.frm_campo + "_idd");
                            this.__show(PAIS.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                            // document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;
                            document.querySelector('#' + PAIS.frm_campo).required = true;

                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__show(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__show(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");

                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;

                            if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                this.__show(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = true;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            } else {

                                this.__hide(PAIS.frm_campo + "_idd");
                                this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                            }



                        } else if (grupo2Values.includes(prestaacion_id)) {
                            this.__hide(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__hide(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__hide(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__hide(BE_CI.frm_campo + "_idd");
                            this.__hide(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__hide(BE_BUSCAR.frm_campo + "_idd");
                            this.__hide(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__hide(BE_CUA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__hide(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__hide(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__hide(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__hide(BE_GENERO.frm_campo + "_idd");
                            this.__hide(BE_CELULAR.frm_campo + "_idd");
                            this.__hide(BE_PARENTESCO.frm_campo + "_idd");
                            this.__hide(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = false;
                            document.querySelector('#' + BE_CI.frm_campo).required = false;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = false;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = false;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = false;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */
                            //*****INICIO DE PODER*******/
                            this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__show(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__show(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__show(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__show(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = true;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = true;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = true;
                            //**************************************** */
                            if (TIENE_PODER.frm_value === "1" || TIENE_PODER.frm_value === 1) {

                                this.__show(SUBTITLE_11.frm_campo + "_idd");
                                // this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                this.__show(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                                this.__show(FECHA_DE_EMISION.frm_campo + "_idd");

                                this.__show(PODER_SCANER.frm_campo + "_idd");
                                // this.__show(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                                if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                    const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__show(idCampo);
                                        document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = true;
                                    }
                                }
                                if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                    const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__show(idCampo);
                                        document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                    }
                                }

                                if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                    const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__hide(idCampo);
                                        document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                    }
                                }
                                this.__show(PODER_DIRNOPLU.frm_campo + "_idd");
                                this.__show(SUBTITULO_4_4.frm_campo + "_idd");
                                this.__show(GRILLA_DAHE.frm_campo + "_idd");
                                this.__show(EXTRANGERO_PODER.frm_campo + "_idd");


                                // document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = true;
                                document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = true;
                                document.querySelector('#' + PODER_SCANER.frm_campo).required = true;

                                document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = true;
                                document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = true;



                                if (EXTRANGERO_PODER.frm_value === "1" || EXTRANGERO_PODER.frm_value === 1) {
                                    this.__show(PAIS.frm_campo + "_idd");
                                    this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                    this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                    this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                    document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                    document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                    document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                    document.querySelector('#' + PAIS.frm_campo).required = true;
                                    this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                    document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                                } else {

                                    this.__hide(PAIS.frm_campo + "_idd");
                                    this.__show(NRO_PODER_SOL_1.frm_campo + "_idd");
                                    this.__show(DEPARTAMENTO.frm_campo + "_idd");
                                    this.__show(MUNICIPIO_1.frm_campo + "_idd");
                                    document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = true;
                                    document.querySelector('#' + DEPARTAMENTO.frm_campo).required = true;
                                    document.querySelector('#' + MUNICIPIO_1.frm_campo).required = true;
                                    document.querySelector('#' + PAIS.frm_campo).required = false;

                                    this.__show(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                    document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = true;

                                }
                            }
                            else {
                                this.__hide(SUBTITLE_11.frm_campo + "_idd");
                                this.__hide(NRO_PODER_SOL_1.frm_campo + "_idd");
                                // this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                this.__hide(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                                this.__hide(FECHA_DE_EMISION.frm_campo + "_idd");
                                this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                                this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                                this.__hide(PODER_SCANER.frm_campo + "_idd");
                                //this.__hide(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                                if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                    const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__hide(idCampo);
                                        document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = false;
                                    }
                                }
                                if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                    const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__hide(idCampo);
                                        document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                    }
                                }
                                if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                    const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                    if (document.getElementById(idCampo)) {
                                        this.__hide(idCampo);
                                        document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                    }
                                }
                                this.__hide(PODER_DIRNOPLU.frm_campo + "_idd");
                                this.__hide(SUBTITULO_4_4.frm_campo + "_idd");
                                this.__hide(GRILLA_DAHE.frm_campo + "_idd");
                                this.__hide(EXTRANGERO_PODER.frm_campo + "_idd");
                                this.__hide(PAIS.frm_campo + "_idd");
                                document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                                //   document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = false;
                                document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = false;
                                document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                                document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                                document.querySelector('#' + PODER_SCANER.frm_campo).required = false;

                                document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = false;
                                document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = false;
                                document.querySelector('#' + PAIS.frm_campo).required = false;
                                this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                                document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;

                            }


                        } else //a) Validación de Certificados y documentos
                        {


                            this.__show(BE_DIFERENTE_AS.frm_campo + "_idd");
                            this.__show(SUBTITULO_1_1.frm_campo + "_idd");
                            this.__show(BE_TIPO_DOCUMENTO.frm_campo + "_idd");
                            this.__show(BE_CI.frm_campo + "_idd");
                            this.__show(BE_COMPLEMENTO.frm_campo + "_idd");
                            this.__show(BE_BUSCAR.frm_campo + "_idd");
                            this.__show(BE_NACIMIENTO.frm_campo + "_idd");
                            this.__show(BE_CUA.frm_campo + "_idd");
                            this.__show(BE_PRIMER_APELLIDO.frm_campo + "_idd");
                            this.__show(BE_SEGUNDO_APELLIDO.frm_campo + "_idd");
                            this.__show(BE_APELLIDO_CASADA.frm_campo + "_idd");
                            this.__show(BE_PRIMER_NOMBRE.frm_campo + "_idd");
                            this.__show(BE_SEGUNDO_NOMBRE.frm_campo + "_idd");
                            this.__show(BE_ESTADO_CIVIL.frm_campo + "_idd");
                            this.__show(BE_GENERO.frm_campo + "_idd");
                            this.__show(BE_CELULAR.frm_campo + "_idd");
                            this.__show(BE_PARENTESCO.frm_campo + "_idd");
                            this.__show(BE_CI_DOC.frm_campo + "_idd");
                            document.querySelector('#' + BE_TIPO_DOCUMENTO.frm_campo).required = true;
                            document.querySelector('#' + BE_CI.frm_campo).required = true;
                            document.querySelector('#' + BE_PARENTESCO.frm_campo).required = true;
                            document.querySelector('#' + BE_ESTADO_CIVIL.frm_campo).required = true;
                            document.querySelector('#' + BE_CELULAR.frm_campo).required = true;
                            document.querySelector('#' + BE_CI_DOC.frm_campo).required = true;
                            document.querySelector('#' + BE_CI_DOC.frm_campo + '_ID').required = false;
                            //*****TABLA DE BENIFICIARIO****** */
                            if (SUBTITULO_4_4_BE && SUBTITULO_4_4_BE.frm_campo) {
                                const idCampo = SUBTITULO_4_4_BE.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            if (GRILLA_DACO && GRILLA_DACO.frm_campo) {
                                const idCampo = GRILLA_DACO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                }
                            }
                            //*****FIN TABLA DE BENIFICIARIO****** */

                            //*****INICIO DE PODER*******/
                            this.__hide(SUBTITLE_11.frm_campo + "_idd");
                            this.__hide(NRO_PODER_SOL_1.frm_campo + "_idd");
                            this.__hide(NRO_NOTARIA_SOL_1.frm_campo + "_idd");
                            this.__hide(NOMBRE_NOTARIO_SOL_1.frm_campo + "_idd");
                            this.__hide(FECHA_DE_EMISION.frm_campo + "_idd");
                            this.__hide(DEPARTAMENTO.frm_campo + "_idd");
                            this.__hide(MUNICIPIO_1.frm_campo + "_idd");
                            this.__hide(PODER_SCANER.frm_campo + "_idd");
                            //this.__hide(ESTADO_PODER_PRESENTADO.frm_campo + "_idd");
                            if (ESTADO_PODER_PRESENTADO && ESTADO_PODER_PRESENTADO.frm_campo) {
                                const idCampo = ESTADO_PODER_PRESENTADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ESTADO_PODER_PRESENTADO.frm_campo).required = false;
                                }
                            }
                            if (OBSERVACION_PODER && OBSERVACION_PODER.frm_campo) {
                                const idCampo = OBSERVACION_PODER.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + OBSERVACION_PODER.frm_campo).required = false;
                                }
                            }
                            if (NRO_PODER_REVOCATORIO && NRO_PODER_REVOCATORIO.frm_campo) {
                                const idCampo = NRO_PODER_REVOCATORIO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_PODER_REVOCATORIO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PODER_DIRNOPLU.frm_campo + "_idd");
                            this.__hide(SUBTITULO_4_4.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHE.frm_campo + "_idd");
                            this.__hide(EXTRANGERO_PODER.frm_campo + "_idd");
                            this.__hide(PAIS.frm_campo + "_idd");
                            document.querySelector('#' + NRO_PODER_SOL_1.frm_campo).required = false;
                            document.querySelector('#' + NRO_NOTARIA_SOL_1.frm_campo).required = false;
                            document.querySelector('#' + NOMBRE_NOTARIO_SOL_1.frm_campo).required = false;
                            document.querySelector('#' + FECHA_DE_EMISION.frm_campo).required = false;
                            document.querySelector('#' + DEPARTAMENTO.frm_campo).required = false;
                            document.querySelector('#' + MUNICIPIO_1.frm_campo).required = false;
                            document.querySelector('#' + PODER_SCANER.frm_campo).required = false;

                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo).required = false;
                            document.querySelector('#' + EXTRANGERO_PODER.frm_campo).required = false;
                            document.querySelector('#' + PAIS.frm_campo).required = false;
                            //*****FIN DE PODER*********/ HEREDEROS
                            this.__hide(SUBTITULO_4_4_1.frm_campo + "_idd");
                            this.__hide(GRILLA_DAHERDERO.frm_campo + "_idd");
                            if (ACEPTACION_DE_HERENCIA && ACEPTACION_DE_HERENCIA.frm_campo) {
                                const idCampo = ACEPTACION_DE_HERENCIA.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + ACEPTACION_DE_HERENCIA.frm_campo).required = false;
                                }
                            }
                            if (NRO_SENTENCIA_RESOLUCION && NRO_SENTENCIA_RESOLUCION.frm_campo) {
                                const idCampo = NRO_SENTENCIA_RESOLUCION.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + NRO_SENTENCIA_RESOLUCION.frm_campo).required = false;
                                }
                            }
                            if (JUZGADO && JUZGADO.frm_campo) {
                                const idCampo = JUZGADO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + JUZGADO.frm_campo).required = false;
                                }
                            }
                            //******COBRO************ */
                            this.__hide(SUBTITULO_4_44.frm_campo + "_idd");
                            this.__hide(HA_PAGOS_SUPENDIDOS.frm_campo + "_idd");
                            if (HA_PAGOS_SUPENDIDOS_VAL && HA_PAGOS_SUPENDIDOS_VAL.frm_campo) {
                            const idCampo = HA_PAGOS_SUPENDIDOS_VAL.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                        if (HA_PAGO_AGUINALDO_VA && HA_PAGO_AGUINALDO_VA.frm_campo) {
                            const idCampo = HA_PAGO_AGUINALDO_VA.frm_campo + "_idd";
                            if (document.getElementById(idCampo)) {
                                this.__hide(idCampo);
                            }
                        }
                            this.__hide(TIPO_PODER.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_INI.frm_campo + "_idd");
                            this.__hide(FORM_JUB_MES_FIN.frm_campo + "_idd");
                            this.__hide(HA_PAGO_AGUINALDO.frm_campo + "_idd");
                            if (HA_GESTION_AGUINALDO && HA_GESTION_AGUINALDO.frm_campo) {
                                const idCampo = HA_GESTION_AGUINALDO.frm_campo + "_idd";
                                if (document.getElementById(idCampo)) {
                                    this.__hide(idCampo);
                                    document.querySelector('#' + HA_GESTION_AGUINALDO.frm_campo).required = false;
                                }
                            }
                            this.__hide(PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(MENSAJE_PERIODOS_INGRESADOS.frm_campo + "_idd");
                            this.__hide(HA_PAGO_UNICO.frm_campo + "_idd");
                            document.querySelector('#' + HA_PAGOS_SUPENDIDOS.frm_campo).required = false;
                            document.querySelector('#' + TIPO_PODER.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_INI.frm_campo).required = false;
                            document.querySelector('#' + FORM_JUB_MES_FIN.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_AGUINALDO.frm_campo).required = false;
                            document.querySelector('#' + PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + MENSAJE_PERIODOS_INGRESADOS.frm_campo).required = false;
                            document.querySelector('#' + HA_PAGO_UNICO.frm_campo).required = false;
                            //*****APOSTILLA */
                            this.__hide(SUBTITLE_11_APOS.frm_campo + "_idd");
                            this.__hide(NUMERO_APOSTILLA.frm_campo + "_idd");
                            this.__hide(CARTA_APOSTILLA.frm_campo + "_idd");
                            this.__hide(TIENE_PODER.frm_campo + "_idd");
                            document.querySelector('#' + NUMERO_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo).required = false;
                            document.querySelector('#' + TIENE_PODER.frm_campo).required = false;

                            document.querySelector('#' + CARTA_APOSTILLA.frm_campo + '_ID').required = false;
                            //document.querySelector('#' + PODER_SCANER.frm_campo + '_ID').required = false;
                            document.querySelector('#' + PODER_DIRNOPLU.frm_campo + '_ID').required = false;


                        }
                    }
                    this.getPrestaciones(res1.frm_value);
                }






            }// Ajusta el tiempo según sea necesario
            esperarRegistro(); // inicia el proceso
        });
    },
    props: ["casId"],
    created() {
        __GestoraRenderEventBus.$on('ADJUNTOS_FIRST_LOADING', (newValue) => {
            //2025-02-24
            this.obtenerDocumentosContratacionEventual(newValue, this.casId);
        });
    },
    watch: {
        selectedPrestacion(newVal) {
            document.getElementById("REQ_TIPOS").value = newVal;
            document.getElementById("REQ_TIPOS").dispatchEvent(new Event('input'));
            console.log("🚀 ~ watch:selectedPrestacion ~ newVal", newVal);
            console.log("🚀 ~ watch:selectedPrestacion 1 ~ newVal", document.getElementById("REQ_TIPOS").value);
            // Verificar si hay valores seleccionados
            if (newVal && newVal.length > 0) {
                // Llamar a la función con el array
                this.obtenerDocumentosContratacionEventual(newVal, this.cas_id);
            }

        },

        selectedRequisito(newVal) {
            this.documento = [];
            if (newVal) {
                const selectedOption = document.getElementById("requisitoSelect").options[
                    document.getElementById("requisitoSelect").selectedIndex
                ];
                const selectedValue = selectedOption.value;
                const selectedText = selectedOption.text;
                this.selectedValueHijo = selectedValue;
                this.listarRequisitosPrestacionesHijos(selectedValue, selectedText);
            }
        }
    },
    methods: {

        __show(campo) {
            document.getElementById(campo).style.display = "block";
        },

        __hide(campo) {
            document.getElementById(campo).style.display = "none";
        },

        __enable(campo) {
            //document.getElementById(campo).disabled = false;
            //document.getElementById(campo).toggleAttribute("disabled", false);
        },

        __disable(campo) {
            // document.getElementById(campo).disabled = true; //**** */
            //document.getElementById(campo).toggleAttribute("disabled", true);
        },
        //******************COMBO******* */
        getPrestaciones(selectedValueProcesoSub) {
            //alert("Entró a getPrestaciones");

            var requestData = {
                // "pdoc_codigo": document.getElementById("AS_TIPO_EAP_LEGAL").value
                "id_subCategoria": selectedValueProcesoSub
            };

            $.ajax({
                dataType: 'json',
                contentType: 'application/json',
                type: 'POST',
                data: JSON.stringify(requestData),
                url: '/api/prestacionesProcesos',
                success: function (response) {
                    //var selectPrestacion = document.getElementById("AS_TIPO_EAP");
                    var selectPrestacion = document.getElementById("AS_TIPO_EAP");

                    // ✅ 1. LIMPIAR SELECT antes de agregar nuevas opciones
                    selectPrestacion.innerHTML = '<option value="">Seleccione una opción</option>';

                    var datos = response.data;
                    console.log(datos);

                    // ✅ 2. AGREGAR NUEVAS OPCIONES
                    for (var i = 0; i < datos.length; i++) {
                        var option = document.createElement("option");
                        option.value = datos[i].lgp_id;
                        option.text = datos[i].lgp_nombre;
                        selectPrestacion.appendChild(option);
                    }

                    // ✅ 3. ASIGNAR EL VALOR POR DEFECTO SI EXISTE
                    var dataFrmValue = selectPrestacion.getAttribute("data-frm-value");
                    if (dataFrmValue) {
                        selectPrestacion.value = dataFrmValue;
                        __GestoraRenderEventBus.$emit("ADJUNTOS_FIRST_LOADING", dataFrmValue);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        },
        //****************************** */

        listarRequisitosPrestacionesHijos(tipo_req, tipo_texto) {
            this.mostrarOverlay();
            const data_validacion = {
                pdoc_codigo: this.cas_cod_id,
                pdoc_referencia: '0-SOL-LEGAL',
                pdoc_categoria: this.ci,
                pdoc_legal: this.selectedValueHijo,
            };
            axios.post('api/existeDocumentosRequisitosLegal', data_validacion)
                .then(response => {
                    console.log('sata ddasdas', response.data[0].sp_existe_documento_inicial_legal);
                    if (response.data[0].sp_existe_documento_inicial_legal) {
                        const data = {
                            pdoc_codigo: this.cas_cod_id,
                            pdoc_referencia: '0-SOL-LEGAL',
                            pdoc_categoria: this.ci,
                            pdoc_legal: this.selectedValue,
                        };
                        axios.post('api/obtenerDocumentosRequisitosLegal', data)
                            .then(response => {
                                this.documento = response.data.data;
                                this.ocultarOverlay();
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    } else {
                        const data = {
                            tipo_proceso: tipo_req
                        };
                        axios.post('/api/prestacionesReq', data)
                            .then(response => {
                                this.documento = response.data.data;
                                this.ocultarOverlay();
                            })
                            .catch(error => {
                                console.error('Error al obtener los requisitos', error);
                            });
                    }
                })
                .catch(error => {
                    console.error('Error al generar o abrir el PDF', error);
                });



        },

        listarRequisitosPrestaciones(tipo_req, tipo_texto) {
            const data_1 = {
                selectedValue: this.selectedValue
            };
            axios.post('/api/postApiPrestacionesIdPadre', data_1)
                .then(response => {
                    this.requisitos = response.data.data;
                    if (this.requisitos.length > 0) {
                        this.selectedRequisito = null; //
                    } else {
                        this.mostrarOverlay();
                        const data_validacion = {
                            pdoc_codigo: this.cas_cod_id,
                            pdoc_referencia: '0-SOL-LEGAL',
                            pdoc_categoria: this.ci,
                            pdoc_legal: this.selectedValue,
                        };
                        axios.post('api/existeDocumentosRequisitosLegal', data_validacion)
                            .then(response => {
                                console.log('sata ddasdas', response.data[0].sp_existe_documento_inicial_legal);
                                if (response.data[0].sp_existe_documento_inicial_legal) {
                                    const data = {
                                        pdoc_codigo: this.cas_cod_id,
                                        pdoc_referencia: '0-SOL-LEGAL',
                                        pdoc_categoria: this.ci,
                                        pdoc_legal: this.selectedValue,
                                    };
                                    axios.post('api/obtenerDocumentosRequisitosLegal', data)
                                        .then(response => {
                                            this.documento = response.data.data;
                                            this.ocultarOverlay();
                                        })
                                        .catch(error => {
                                            console.error('Error al generar o abrir el PDF', error);
                                        });
                                } else {
                                    console.log("🚀 ~ listarRequisitosPrestaciones ~ tipo_req:", tipo_req)
                                    const data = {
                                        tipo_proceso: tipo_req
                                    };
                                    axios.post('/api/prestacionesReq', data)
                                        .then(response => {
                                            this.documento = response.data.data;
                                            this.ocultarOverlay();
                                        })
                                        .catch(error => {
                                            console.error('Error al obtener los requisitos', error);
                                        });
                                }
                            })
                            .catch(error => {
                                console.error('Error al generar o abrir el PDF', error);
                            });
                    }
                })
                .catch(error => {
                    console.error('Error al obtener los requisitos', error);
                });
        },

        verDocumento: function (ruta, nombre) {
            var url = "/api/verDocumentoPdfNfs/" + ruta;
            const partes = nombre.split('.');
            const partes2 = nombre.split('/');
            axios.get(url, { responseType: 'blob' })
                .then(response => {
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
        tamanoDocumento(event, objectInProcess) {
            const file = event.target.files[0];
            const maxSizeInBytes = 52428800;
            if (file.size > maxSizeInBytes) {
                Swal.fire('El archivo seleccionado supera el tamaño máximo de 50 MB.', '', 'warning');
                event.target.value = '';
                objectInProcess.config__presentacionObligatoria__model = null;
                return;
            }
            if (file.type !== 'application/pdf') {
                Swal.fire('Por favor, selecciona solo archivos PDF.', '', 'warning');
                event.target.value = '';
                objectInProcess.config__presentacionObligatoria__model = null;
                return;
            }
            objectInProcess.config__presentacionObligatoria__model = file.name;
            objectInProcess.config__documentoOriginalObligatorio__model = null;
            objectInProcess.docPersistido__doc_value_es_original = null;
            // objectInProcess.docPersistido__doc_value_url=null;
            objectInProcess.front__has_change_record = true;

        },
        eliminarDocumento(index) {
            this.documento.splice(index, 1);
            this.$delete(this.archivos, index);
        },
        onFileChange(event) {
            this.file = event.target.files[0];
        },
        async registrarRequisitos() {
            this.mostrarOverlay();
            const arregloDatos = [];
            const tabla = document.getElementById("tabla_requisitos_legal");
            const filas = tabla.querySelectorAll("tr");
            const tam = filas.length - 1;
            var bandera = 0;
            let datos;
            let selectedValue_ = '';

            if (this.requisitos.length > 0) {
                selectedValue_ = this.selectedValueHijo;
            } else {
                selectedValue_ = this.selectedValue;
            }
            for (var i = 0; i < tam; i++) {
                const switch_ = 'switch_legal' + bandera;
                const switchElement = document.querySelector(`#${switch_}`);
                const valorSwitch = switchElement.checked;
                const fileInput = document.getElementById('pdf_sol_legal_' + bandera);
                const file = fileInput.files[0];
                const descripcion = "descripcion_legal_" + bandera;
                const id = "id_legal_" + bandera;
                const valor_id = document.getElementById(id).value;
                const valor_descripcion = document.getElementById(descripcion).value;
                if (!file) {
                    datos = {
                        tam: 'tam',
                        valor_id: valor_id,
                        valor_descripcion: valor_descripcion,
                        pdf: '',
                        caso: this.caso,
                        id_caso: this.id_caso,
                        documentoOriginalObligatorio: '',
                        presentacionObligatoria: '',
                        ci: this.ci,
                        parentesco: '0-SOL-LEGAL',
                        switch: valorSwitch,
                        id_persona_sip: this.id_persona_sip,
                        id_observacion: '',
                        detalle_documento: '',
                        selectedValue: selectedValue_,
                        usr_id: this.usrId,
                    };
                    arregloDatos.push(datos);
                } else {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const base64data = reader.result.split(',')[1];
                        datos = {
                            tam: 'tam',
                            valor_id: valor_id,
                            valor_descripcion: valor_descripcion,
                            pdf: base64data,
                            caso: this.caso,
                            id_caso: this.id_caso,
                            documentoOriginalObligatorio: '',
                            presentacionObligatoria: '',
                            ci: this.ci,
                            parentesco: '0-SOL-LEGAL',
                            switch: '',
                            id_persona_sip: this.id_persona_sip,
                            id_observacion: '',
                            detalle_documento: '',
                            selectedValue: selectedValue_,
                            usr_id: this.usrId,
                        };
                        arregloDatos.push(datos);
                    };
                    reader.readAsDataURL(file);
                }
                bandera++;

            };
            setTimeout(() => {
                console.log('sasasasas====>', arregloDatos);
                axios.post('api/guardarDocumentosRequisitosNfsLegal', arregloDatos)
                    .then(response => {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Tus Documentos Fueron Guardados",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        this.closeModal();
                    })
                    .catch(error => {
                        console.error('Error al generar o abrir el PDF', error);
                    });
            }, 1500);
        },
        closeModal() {
            $('#modalDocumentosLegal').modal('hide');
        },
        mostrarOverlay() {
            this.$refs.overlay.style.display = 'flex';
        },
        ocultarOverlay() {
            this.$refs.overlay.style.display = 'none';
        },


        async getprestacioneshijos_sub(vcodigo) {
            //alert("getprestacioneshijos_sub");
            //alert(vcodigo);
            try {

                console.log("Entró a getPrestaciones" + vcodigo);

                var requestData = {
                    "pdoc_codigo": vcodigo//document.getElementById("AS_TIPO_EAP_LEGAL").value
                };

                $.ajax({
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'POST',
                    data: JSON.stringify(requestData),
                    url: '/api/prestacionesSubCategoria',
                    success: function (response) {
                        var selectPrestacion = document.getElementById("AS_SUB_SOLICITUD");

                        // ✅ 1. LIMPIAR SELECT antes de agregar nuevas opciones
                        selectPrestacion.innerHTML = '<option value="">Seleccione una opción</option>';

                        var datos = response.data;
                        console.log(datos);

                        // ✅ 2. AGREGAR NUEVAS OPCIONES
                        for (var i = 0; i < datos.length; i++) {
                            var option = document.createElement("option");
                            option.value = datos[i].lgpsub_id;
                            option.text = datos[i].lgpsub_nombre;
                            selectPrestacion.appendChild(option);
                        }

                        // ✅ 3. ASIGNAR EL VALOR POR DEFECTO SI EXISTE
                        var dataFrmValue = selectPrestacion.getAttribute("data-frm-value");
                        if (dataFrmValue) {
                            selectPrestacion.value = dataFrmValue;
                            __GestoraRenderEventBus.$emit("ADJUNTOS_FIRST_LOADING", dataFrmValue);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


            } catch (error) {
                console.error('Error fetching prestaciones:', error);
            }
        },

        async getprestacioneshijos(vcodigo) {
            //  alert("getprestacioneshijos");
            // alert(vcodigo);
            try {

                console.log("Entró a getPrestaciones" + vcodigo);

                var requestData = {
                    "id_subCategoria": vcodigo//document.getElementById("AS_TIPO_EAP_LEGAL").value
                };

                $.ajax({
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'POST',
                    data: JSON.stringify(requestData),
                    url: '/api/prestacionesProcesos',
                    success: function (response) {
                        var selectPrestacion = document.getElementById("AS_TIPO_EAP");

                        // ✅ 1. LIMPIAR SELECT antes de agregar nuevas opciones
                        selectPrestacion.innerHTML = '<option value="">Seleccione una opción</option>';

                        var datos = response.data;
                        console.log("🚀 ~ getprestacioneshijos ~ datos:", datos)
                        console.log(datos);

                        // ✅ 2. AGREGAR NUEVAS OPCIONES
                        for (var i = 0; i < datos.length; i++) {
                            var option = document.createElement("option");
                            option.value = datos[i].lgp_id;
                            option.text = datos[i].lgp_nombre;
                            selectPrestacion.appendChild(option);
                        }

                        // ✅ 3. ASIGNAR EL VALOR POR DEFECTO SI EXISTE
                        var dataFrmValue = selectPrestacion.getAttribute("data-frm-value");
                        if (dataFrmValue) {
                            selectPrestacion.value = dataFrmValue;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


            } catch (error) {
                console.error('Error fetching prestaciones:', error);
            }
        },


        async getRequisitos(requisito_id, prc_id, casdata) {

            this.cas_id = casdata.cas_id;
            this.registro = casdata;
            this.actividad = this.registro.act_data;
            this.prc_id = prc_id;
            this.cas_cod_id = document.getElementById("_CASO_NOMBRE").value;
            // this.isVisible = !this.isVisible;
            this.obtenerDocumentosContratacionEventual(requisito_id, this.cas_id);

        },



        //************************************PRUEBA***************************** */
        obtenerDocumentosContratacionEventual(vpres, casid) {
            //alert("obtenerDocumentosContratacionEventual");
            const vpresArray = Array.isArray(vpres) ? vpres : [vpres];

            console.log("🚀 ~ obtenerDocumentosContratacionEventual ~ vpres:", vpres);
            this.documentosContratacionEventual = [];
            const data = {
                id_caso: casid, //this.cas_id,
                req_prest_id: vpresArray
            };
            axios.post('api/getApiRequisitosId', data)
                .then(response => {

                    console.log("🚀 ~ obtenerDocumentosContratacionEventual ~ response.data:", response.data)
                    this.documentosContratacionEventual = response.data.data.map(item => {
                        //a) primer barrido tramite-nuevo
                        console.log("🚀 ~ obtenerDocumentosContratacionEventual===== ~ item.ad_actdoc_config_es_obligatorio:", item.ad_actdoc_config_es_obligatorio)
                        const itemFormatoTitular = {
                            config__id: item.ad_actdoc_id,//valor a persistir en la tabla _gp_documentos
                            config__nombre: item.ad_doc_nombre,//valor a persistir en la tabla _gp_documentos
                            config__presentacionObligatoria: item.ad_actdoc_config_es_obligatorio,
                            config__presentacionObligatoria__model: item.ad_actdoc_config_es_obligatorio ? null : String(item.ad_actdoc_config_es_obligatorio),
                            docPersistido__doc_id: item.doc_id,
                            docPersistido__doc_value_es_original: item.doc_value_es_original ? true : false,
                            docPersistido__doc_value_url: item.doc_url,//valor a persistir en la tabla _gp_documentos
                            front__has_change_record: false,// valor que indica q este registro cambio x lo tanto es necesario persistirlo en base
                            front__file_dom_id: "pdf_tit_" + item.ad_actdoc_id // este es el dom-id para capturar el objeto-file-input-file
                        };
                        console.log("🚀 ~ obtenerDocumentosContratacionEventual ====================~ itemFormatoTitular:", itemFormatoTitular)
                        //b) segundobarrido (update-tramite-existente)

                        if (item.doc_url) {
                            itemFormatoTitular.config__documentoOriginalObligatorio__model = item.ad_actdoc_config_es_original === item.doc_value_es_original ? String(item.doc_value_es_original) : null;
                            itemFormatoTitular.config__presentacionObligatoria__model = item.doc_url;
                        }
                        console.log("🚀 ~ obtenerDocumentosContratacionEventual ~ itemFormatoTitular:", itemFormatoTitular)

                        return itemFormatoTitular;
                    });
                    //2025-02-24

                })
                .catch(error => {
                    console.error('Error servicio, ', error);
                });
        }

    }
};
</script>
<style>
img {
    width: 30%;
    margin: auto;
    display: block;
    margin-bottom: 10px;
}


.btn_respaldos_minus {
    height: 50px;
    line-height: 45px;
    width: 50px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 25%;
    background-color: #e3342f;
    border-color: #e3342f;
    color: white;
    text-align: center;
    cursor: pointer;
}


.btn_respaldos_plus2 {
    height: 70px;
    line-height: 55px;
    width: 70px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 45%;
    background-color: #38c1b7;
    color: white;
    text-align: center;
    cursor: pointer;

}

.btn_respaldos_plus {
    height: 50px;
    line-height: 45px;
    width: 50px;
    font-size: 2em;
    font-weight: bold;
    border-radius: 25%;
    background-color: #38c172;
    color: white;
    text-align: center;
    cursor: pointer;

}

/* Estilos CSS para el switch */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    border-radius: 50%;
}

input:checked+.slider {
    background-color: #2196F3;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Estilos adicionales para el diseño del switch */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(13, 218, 81, 0.5);
    /* Color oscuro con opacidad */
    z-index: 999;
    /* Asegura que esté sobre otros elementos */
    display: flex;
    justify-content: center;
    align-items: center;
}

.loader {
    border: 16px solid #f3f3f3;
    /* Light grey */
    border-top: 16px solid #3498db;
    /* Azul */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.modal-generico {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    z-index: 9999;
}

.modal-generico-contenido {
    background-color: #fefefe;
    margin: 20% auto;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    max-width: 400px;

}

.cerrar-modal-generico {
    color: #aaa;
    padding: 5px;
    float: right;
    font-size: 20px;
    font-weight: bold;
}

.cerrar-modal-generico:hover,
.cerrar-modal-generico:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

#modalGenerico-titulo {
    background-color: #007bff;
    /* Color de fondo del título */
    color: #fff;
    /* Color del texto del título */
    padding: 10px;
    /* Espaciado interno del título */
    margin: 0;
    /* Eliminar márgenes del título */
    font-size: 1.2em;
    /* Tamaño de fuente del título */
    text-transform: uppercase;
    /* Convertir texto a mayúsculas */
    font-weight: bold;
    /* Negrita */
}

#modalGenerico-mensaje {
    padding: 10px;
    color: #666;
    /* Color del texto del mensaje */
    font-size: 16px;
    /* Tamaño de fuente del mensaje */
}

.cerrar-btn {
    bottom: 80px;
    right: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.cerrar-btn:hover {
    background-color: #0056b3;
}
</style>

<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use TCPDF;
use Illuminate\Support\Facades\File;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use \Exception;
use App\Http\Controllers\externalServices\LegalAPIController;
use Illuminate\Support\Facades\DB;



class documentosPrevisualizacionController extends Controller
{
    public function generatePdfPrueba($act_id, $act_usr_id, $cas_id, $impid, $nombre_doc)
    {
        $datos = array(
            'act_id' => 14,
            'act_usr_id' => 1,
            'cas_id' => 671,
            'impid' => 24,
            'nombre_doc' => "2.1 Carta Informativa JUB"
        );
        $requestEnvio = new Request($datos);
        $resultado = $this->generatePdf($requestEnvio);
        return $resultado;
    }

    public function generatePdf(Request $request)
    {
        $tipo_dibujo = $request["tipo"];
        $cuerpox = "";
        $cuerpofinal = "";
        $actId = $request["act_id"];
        $usr_id = $request["act_usr_id"];
        $cas_id = $request["cas_id"]; //35;
        $impid = $request["impid"]; //35;
        $nombre_doc = $request["nombre_doc"]; //35;
        $firmaSolicitante = $request["firma"];
        $request = new Request(['act_id' => $actId, 'imp_id' => $impid]);
        $response = $this->listarImpresionId($request);
        $content = $response->getContent();
        $data = json_decode($content, true);
        $grilla = '';
        $AS_ENTE_GESTOR = '';
        $AS_TIPO_DOCUMENTO = '';
        $AS_FECHA_APERSONAMIENTO = '';
        $SOL_TIPO_DOCUMENTO = '';
        $SOL_PARENTESCO = '';
        $AS_FECHA_FALLECIMIENTO = '';
        $AS_CERT_DEFUNCION = '';
        $RMI_OPCION = '';
        $AS_APORTES_EXTRANJERO = '';
        $derecho_hambiente_muerto = '';
        $derecho_hambiente_viva = '';
        $cantidad_firmas = 0;
        $firmas_lote = '';
        $AS_TIPO_EAP = '';
        $AS_TIPO_EAP_ID = '';
        $AS_TIENE_CC = '';
        $AS_CC = '';
        $AS_FECHA_INICIO_COTIZACION = '';
        $AS_NUM_CUOTAS = '';
        $AS_VALOR_CUOTA = '';
        $AS_SALDO_ACUMULADO = '';
        $FECHA_SINIESTRO_A = '';
        $AS_CI = '';
        $SOL_CI = '';
        $SOL_FAC_REC = '';
        $FECHA_SUPERA_6 = '';

        $PENS_NO_COBRADAS = '';
        $doc_id = '';
        $VALIDADOR = 0;

        $FECHA_DE_SOLICITUD = '';
        $FECHA_DE_SOLICITUD_PENSION = '';

        $VALIDAR_PODER = '';
        $FECHA_REVISION = '';
        $DECLARATORIA_HEREDEROS = '';
        $AS_ZONA = '';

        $AS_DIRECCION = '';
        $AS_NUM = '';
        $generohtmlsol2 = '';

        $AS_CUA = '';
        $GRILLA_MRCHZ = '';
        $DESCRIPCION_FUNDAMENTACION = '';
        $VER_FUNDAMENTOS = '';

        /* ------------------------------------------------------------------------------------------ */
        $SOL_PRIMER_APELLIDO = '';
        $SOL_SEGUNDO_APELLIDO = '';
        $SOL_APELLIDO_CASADA = '';
        $SOL_PRIMER_NOMBRE = '';
        $SOL_SEGUNDO_NOMBRE = '';
        $SOL_CELULAR = '';
        $SOL_TELEFONO = '';
        $SOL_CORREO = '';
        $SOL_ZONA = '';
        $SOL_DIRECCION = '';
        $AS_NUM = '';
        $CASO_HEREDARO = '';
        $FORM_JUB_MES_INI = '';
        $FORM_JUB_MES_FIN = '';
        $HA_GESTION_AGUINALDO = '';
        $AS_TIPO_PRESTACIONES = '';
        $HA_PAGO_AGUINALDO = '';
        $BE_PRIMER_APELLIDO = '';
        $BE_SEGUNDO_APELLIDO = '';
        $BE_PRIMER_NOMBRE = '';
        $BE_SEGUNDO_NOMBRE = '';
        $BE_APELLIDO_CASADA = '';
        $BE_TIPO_DOCUMENTO = '';
        $BE_CI = '';
        $BE_GENERO = '';
        $BE_DIRECCION = '';
        $BE_CELULAR = '';
        $BE_CORREO = ''; //***** */
        $ORIGEN = ''; //***** /
        $CAUSA = ''; //***** */
        $OFICIAL_SERVICIO_REGISTRO = ''; //***** */
        $AS_ESTADO_CIVIL = '';
        $CAUSADICTAMEN_REC = '';
        $ORIGENDICTAMEN_REC = '';
        $HA_PAGO_UNICO = '';
        $EXTRANGERO_PODER = '';
        $EXTRANGERO_PODER_TEXTO = '';
        $ACEPTACION_DE_HERENCIA = '';
        $ACEPTACION_DE_HERENCIA_TEXTO = '';
        $NRO_SENTENCIA_RESOLUCION = '';
        $JUZGADO = '';
        $TIENE_PODER = '';
        $NRO_NOTARIA_SOL_1 = '';
        $NOMBRE_NOTARIO_SOL_1 = '';
        $FECHA_DE_EMISION = '';
        $PAIS = '';
        $NRO_PODER_SOL_1 = '';
        $DEPARTAMENTO = '';
        $MUNICIPIO_1 = '';
        $NUMERO_APOSTILLA = '';
        $html_documento_apostilla = '';
        $ESTADO_PODER_PRESENTADO = '';
        $ESTADO_PODER_PRESENTADO_TEXT = '';
        $OBSERVACION_PODER = '';
        $BE_COMPLEMENTO = '';
        $NRO_PODER_REVOCATORIO = '';

        //  $SOL_ESTADO_CIVIL='';//***** */
        /* ------------------------------------------------------------------------------------------ */
        $imp_tipo = '';
        $imp_tipo_control = '';


        $success = array("code" => 200, "mensaje" => 'OK',);
        if (isset($data['data'])) {
            $responseData = $data['data'];
            if (isset($data['data'][0]['imp_data'])) {
                $imp_tipo_control = $data['data'][0]['imp_tipo'];
                $impData = $data['data'][0]['imp_data'];
                $imp_tipo = 3;
                $cuerpox = $impData;
            } else {
                //echo "No se tiene el valor imp_data";
            }
        } else {
            echo "No se tienen valores";
        }
        $request1 = new Request(['usr_id' => $usr_id, 'cas_id' => $cas_id, 'cas_act_id' => $actId]);
        $response1 = $this->listarCasosXImpresion($request1);
        $content1 = $response1->getContent();

        $data1 = json_decode($content1, true);
        $cas_data = $data1['data'][0]['cas_data'];
        if (isset($data1['data'])) {
            if (isset($data1['data'][0]['cas_data_valores'])) {
                $impData1 = $data1['data'][0]['cas_data_valores'];
                if (is_string($impData1)) {
                    $impData1 = json_decode($impData1, true);
                }
                if (is_array($impData1)) {
                    foreach ($impData1 as $item) {
                        if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                            $frmCampo = $item['frm_campo'];
                            $frmValue = $item['frm_value'];
                            if ($frmCampo != "GRILLA_DERECHOHABIENTES") {
                                if ($frmCampo == "AS_CI") {
                                    $AS_CI = $item['frm_value'];
                                    continue;
                                }
                                if ($frmCampo == "SOL_CI") {
                                    $SOL_CI = $item['frm_value'];
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($impid == 4 || $impid == 17 || $impid == 64 || $impid == 32 || $impid == 68 || $impid == 54 || $impid == 49 || $impid == 132 || $impid == 249) {
            if ($AS_CI == $SOL_CI) {
                $cuerpox = $cuerpox = str_replace('#SOL_PRIMER_APELLIDO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_SEGUNDO_APELLIDO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_APELLIDO_CASADA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PRIMER_NOMBRE#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_SEGUNDO_NOMBRE#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_TIPO_DOCUMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CI#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_COMPLEMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PARENTESCO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_GENERO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CELULAR#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_TELEFONO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_DEPARTAMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ZONA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_NUM#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CORREO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_POSTAL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NRO_PODER_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NRO_NOTARIA_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NOMBRE_NOTARIO_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PROVINCIA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CIUDAD#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_DIRECCION#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_WHATSAPP#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_AGENCIA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_USR_NOMBRE#', '', $cuerpox);

                $html_documento_solicitante2 = '<td class="style84" colspan="2"></td>   <td class="style85" colspan="2"> </td>';
                $cuerpox = str_replace('#documento_solicitante#', $html_documento_solicitante2, $cuerpox);

                $generohtmlsol = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan=""></td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan=""></td>';

                $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
            } else {
                if ($impid == 249) {
                    $cuerpox = $cuerpox = str_replace('#SOL_AGENCIA#', '#_AGENCIA#', $cuerpox);
                    $cuerpox = $cuerpox = str_replace('#SOL_USR_NOMBRE#', '#_USR_NOMBRE#', $cuerpox);
                }
            }
        }

        if (isset($data1['data'])) {
            if (isset($data1['data'][0]['cas_data_valores'])) {
                $impData1 = $data1['data'][0]['cas_data_valores'];

                if (is_string($impData1)) {
                    $impData1 = json_decode($impData1, true);
                }
                if (is_array($impData1)) {

                    foreach ($impData1 as $item) {
                        if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                            $frmCampo = $item['frm_campo'];
                            $frmValue = $item['frm_value'];

                            $frmTipo = isset($item['frm_tipo']) ? $item['frm_tipo'] : null;

                            $act_data = json_decode($data1['data'][0]['act_data'], true);

                            if ($act_data['act_orden'] == '20') {
                                if (isset($frmTipo) && $frmTipo == "GRID_1582") {
                                    $temporal = $this->getCasoOrdenActividad($cas_id);

                                    if ($this->getCasoOrdenActividad($cas_id) > 10) {
                                        continue;
                                    } else {
                                        $error = array("message" => "error de instancia", "code" => 500);
                                        return array("data" => '', "codigoRespuesta" => $error);
                                    }
                                }

                                if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                    if ($item['frm_value'] != []) {
                                        $VALIDADOR = $this->validacionDocumentos($item, $cas_id);
                                        if ($VALIDADOR != 0) {
                                            continue;
                                        } else {
                                            if ($this->getCasoOrdenActividad($cas_id) > 30) {
                                                continue;
                                            } else {
                                                $error = array("message" => "error de instancia", "code" => 500);
                                                return array("data" => '', "codigoRespuesta" => $error);
                                            }
                                        }
                                    } else {
                                        continue;
                                    }
                                }
                            }

                            if (!empty($frmValue)) {
                                if (isset($item['frm_tipo']) && $item['frm_tipo'] == 'DATE') {
                                    $fecha_exp = explode("-", $frmValue);
                                    $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                    $cuerpox = str_replace('#' . $frmCampo . '#', $fecha_espa, $cuerpox);
                                } else {
                                }
                                if ($frmCampo == "GRILLA_MRCHZ") {
                                    $GRILLA_MRCHZ = $item;
                                }

                                if ($frmCampo != "GRILLA_DERECHOHABIENTES" & $frmCampo != "GRILLA_DAHE" & $frmCampo != "GRILLA_MRCHZ" & $frmCampo != "DATA_DETALLES" & $frmCampo != "GRILLA_DACO") {

                                    if ($frmCampo == "AS_TIPO_DOCUMENTO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIPO_DOCUMENTO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FORM_JUB_FECHA_APERSONAMIENTO") {
                                        $AS_FECHA_APERSONAMIENTO = $item['frm_value'];
                                        $fecha_exp = explode("-", $AS_FECHA_APERSONAMIENTO);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                        $AS_FECHA_APERSONAMIENTO = $fecha_espa;
                                        continue;
                                    }
                                    if ($frmCampo == "FORM_JUB_MES_INI") {
                                        $FORM_JUB_MES_INI = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $FORM_JUB_MES_INI, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "FORM_JUB_MES_FIN") {
                                        $FORM_JUB_MES_FIN = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $FORM_JUB_MES_FIN, $cuerpox);
                                        continue;
                                    }
                                    if ($frmCampo == "HA_GESTION_AGUINALDO") {
                                        $HA_GESTION_AGUINALDO = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $HA_GESTION_AGUINALDO, $cuerpox);
                                        continue;
                                    }
                                    if ($frmCampo == "CAUSA") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $CAUSA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "ORIGEN") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $ORIGEN = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "OFICIAL_SERVICIO_REGISTRO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $OFICIAL_SERVICIO_REGISTRO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_ESTADO_CIVIL") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_ESTADO_CIVIL = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "ORIGENDICTAMEN_REC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $ORIGENDICTAMEN_REC = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "CAUSADICTAMEN_REC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $CAUSADICTAMEN_REC = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "TIENE_PODER") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $TIENE_PODER = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "EXTRANGERO_PODER") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $EXTRANGERO_PODER = $item['frm_value'];
                                        $EXTRANGERO_PODER_TEXTO = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "ACEPTACION_DE_HERENCIA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $ACEPTACION_DE_HERENCIA = $item['frm_value'];
                                        $ACEPTACION_DE_HERENCIA_TEXTO = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "NRO_SENTENCIA_RESOLUCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $NRO_SENTENCIA_RESOLUCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "JUZGADO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $JUZGADO = $item['frm_value'];
                                        continue;
                                    }
                                    /*if ($frmCampo == "SOL_ESTADO_CIVIL") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_ESTADO_CIVIL = $item['frm_value'];
                                        continue;
                                    }*/


                                    //*****LEGAL*************************** */
                                    if ($frmCampo == "BE_PRIMER_APELLIDO") {
                                        $BE_PRIMER_APELLIDO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "NUMERO_APOSTILLA") {
                                        $NUMERO_APOSTILLA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "NRO_PODER_REVOCATORIO") {
                                        $NRO_PODER_REVOCATORIO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "NRO_NOTARIA_SOL_1") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);

                                        $NRO_NOTARIA_SOL_1 = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "NOMBRE_NOTARIO_SOL_1") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);

                                        $NOMBRE_NOTARIO_SOL_1 = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_DE_EMISION") {
                                        $frm_value = $item['frm_value'];
                                        $fecha_exp = explode("-", $frm_value);
                                        $FECHA_DE_EMISION = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                        continue;
                                    }


                                    if ($frmCampo == "PAIS") {
                                        $PAIS = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "NRO_PODER_SOL_1") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);

                                        $NRO_PODER_SOL_1 = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "ESTADO_PODER_PRESENTADO") {
                                        $ESTADO_PODER_PRESENTADO = $item['frm_value'];
                                        $ESTADO_PODER_PRESENTADO_TEXT = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "OBSERVACION_PODER") {
                                        $OBSERVACION_PODER = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "DEPARTAMENTO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $DEPARTAMENTO = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "MUNICIPIO_1") {
                                        $MUNICIPIO_1 = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "BE_SEGUNDO_APELLIDO") {
                                        $BE_SEGUNDO_APELLIDO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "BE_PRIMER_NOMBRE") {
                                        $BE_PRIMER_NOMBRE = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "BE_SEGUNDO_NOMBRE") {
                                        $BE_SEGUNDO_NOMBRE = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo ==  'BE_TIPO_DOCUMENTO') {

                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $BE_TIPO_DOCUMENTO = $item['frm_value_label'];
                                    }
                                    if ($frmCampo == "BE_APELLIDO_CASADA") {
                                        $BE_APELLIDO_CASADA = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "BE_CI") {
                                        $BE_CI = $item['frm_value'];
                                        continue;
                                    }


                                    if ($frmCampo == "BE_GENERO") {
                                        $BE_GENERO = $item['frm_value'];
                                        continue;
                                    }


                                    if ($frmCampo == "BE_DIRECCION") {
                                        $BE_DIRECCION = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "BE_CELULAR") {
                                        $BE_CELULAR = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "BE_CORREO") {
                                        $BE_CORREO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "BE_COMPLEMENTO") {
                                        $BE_COMPLEMENTO = $item['frm_value'];
                                        continue;
                                    }

                                    //***************************+ */

                                    if ($frmCampo == "AS_TIPO_PRESTACIONES") {
                                        $AS_TIPO_PRESTACIONES = $item['frm_value'];
                                        $frm_value_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value_label, $cuerpox);
                                        continue;
                                    }
                                    if ($frmCampo == "HA_PAGO_AGUINALDO") {
                                        $HA_PAGO_AGUINALDO = $item['frm_value_label'];
                                        $frm_value_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value_label, $cuerpox);
                                        continue;
                                    }
                                    if ($frmCampo == "HA_PAGO_UNICO") {
                                        $HA_PAGO_UNICO = $item['frm_value_label'];
                                        $frm_value_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value_label, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "FORM_PAGCC_FECHA_APERSONAMIENTO") {
                                        $AS_FECHA_APERSONAMIENTO = $item['frm_value'];
                                        $fecha_exp = explode("-", $AS_FECHA_APERSONAMIENTO);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                        $AS_FECHA_APERSONAMIENTO = $fecha_espa;
                                        continue;
                                    }

                                    if ($frmCampo == "_FECHA") {
                                        $frm_value = $item['frm_value'];

                                        $fecha_exp = explode("-", $frm_value);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];

                                        $cuerpox = str_replace('#' . $frmCampo . '#', $fecha_espa, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "AS_CUA") {

                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_CUA = $item['frm_value'];
                                    }
                                    if ($frmCampo == "DESCRIPCION_FUNDAMENTACION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $DESCRIPCION_FUNDAMENTACION = $item['frm_value'];
                                    }
                                    if ($frmCampo == "VER_FUNDAMENTOS") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $VER_FUNDAMENTOS = $item['frm_value'];
                                    }


                                    if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol2 = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan="2">' . $valorM . '</td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan="1">' . $valorF . '</td>';
                                    }

                                    if ($frmCampo == "ESTADO_RESPUESTA_CALCULO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "EJEC":
                                                $valorM = "X";
                                                break;
                                            case "VAE":
                                                $valorF = "X";
                                                break;
                                        }
                                        $coverturahtmlsol = '  <td class="style85" colspan="">SI</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">NO</td>
                                         <td class="style85" colspan="1">' . $valorF . '</td>';
                                    }
                                    if ($frmCampo == "AS_ZONA") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_ZONA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_DIRECCION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_DIRECCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_NUM") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_NUM = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "CASO_HEREDARO") {
                                        $CASO_HEREDARO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_TIPO_EAP") {
                                        $AS_TIPO_EAP_ID = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "SOL_FAC_REC") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $SOL_FAC_REC = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FECHA_SUPERA_6") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $FECHA_SUPERA_6 = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "VALIDAR_PODER") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $VALIDAR_PODER = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_REVISION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $FECHA_REVISION = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "DECLARATORIA_HEREDEROS") {
                                        $DECLARATORIA_HEREDEROS = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FECHA_DE_SOLICITUD") {
                                        $FECHA_DE_SOLICITUD = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_DE_SOLICITUD_PENSION") {
                                        $FECHA_DE_SOLICITUD_PENSION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CI") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CI = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "SOL_CI") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CI = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "PENS_NO_COBRADAS") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $PENS_NO_COBRADAS = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "SOL_TIPO_DOCUMENTO") {
                                        $SOL_TIPO_DOCUMENTO = $item['frm_value'];
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_PARENTESCO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PARENTESCO = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_FECHA_FALLECIMIENTO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_FECHA_FALLECIMIENTO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_FECHA_DEFUNCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_FECHA_FALLECIMIENTO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CERT_DEFUNCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CERT_DEFUNCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "RMI_OPCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $RMI_OPCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_APORTES_EXTRANJERO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_APORTES_EXTRANJERO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_TIPO_EAP") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIPO_EAP = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_TIENE_CC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIENE_CC = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_CC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CC = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_FECHA_INICIO_COTIZACION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_FECHA_INICIO_COTIZACION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_NUM_CUOTAS") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_NUM_CUOTAS = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_VALOR_CUOTA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_VALOR_CUOTA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_SALDO_ACUMULADO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_SALDO_ACUMULADO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_SINIESTRO_A") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $FECHA_SINIESTRO_A = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CIUDAD" || $frmCampo == "EM_CIUDAD" || $frmCampo == "SOL_CIUDAD") {
                                        $frm_value_label = $item['frm_value_label'];
                                        $frm_value_label_explote = explode("-", $frm_value_label);
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value_label_explote[0], $cuerpox);
                                        $FECHA_SINIESTRO_A = $item['frm_value'];
                                        continue;
                                    }

                                    /*  */

                                    if ($frmCampo == "SOL_PRIMER_APELLIDO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PRIMER_APELLIDO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_SEGUNDO_APELLIDO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_SEGUNDO_APELLIDO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_APELLIDO_CASADA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_APELLIDO_CASADA = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_PRIMER_NOMBRE") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PRIMER_NOMBRE = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_SEGUNDO_NOMBRE") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_SEGUNDO_NOMBRE = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_CELULAR") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CELULAR = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_TELEFONO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_TELEFONO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_CORREO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CORREO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_ZONA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_ZONA = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_DIRECCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_DIRECCION = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_NUM") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_NUM = $item['frm_value'];
                                        continue;
                                    }

                                    if (!empty($item['frm_value_label'])) {

                                        if (
                                            $item['frm_campo'] == 'SOL_PROVINCIA' ||
                                            $item['frm_campo'] == 'SOL_DEPARTAMENTO' ||
                                            $item['frm_campo'] == 'DEPARTAMENTO' ||
                                            $item['frm_campo'] == 'AS_CIUDAD' ||
                                            $item['frm_campo'] == 'AS_DEPARTAMENTO' ||
                                            $item['frm_campo'] == 'SOL_ESTADO_CIVIL' ||
                                            $item['frm_campo'] == 'ORIGENDICTAMEN' ||
                                            $item['frm_campo'] == 'CAUSADICTAMEN' ||



                                            $item['frm_campo'] == 'PROVINCIA' ||
                                            $item['frm_campo'] == 'EM_CIUDAD' ||
                                            $item['frm_campo'] == 'EM_PROVINCIA' ||
                                            $item['frm_campo'] == 'EM_DEPARTAMENTO' ||
                                            $item['frm_campo'] == 'AS_TIPO_PRESTACIONES' ||
                                            $item['frm_campo'] == 'AS_TIPO_EAP_LEGAL' ||
                                            $item['frm_campo'] == 'EM_TIPO_AS'

                                        ) {
                                            $frm_value_label = $item['frm_value_label'];
                                            $frm_campo = $item['frm_campo'];
                                            $cuerpox = str_replace('#' . $frm_campo . '#', $frm_value_label, $cuerpox);
                                            continue;
                                        }

                                        if ($frmCampo == "AS_ENTE_GESTOR") {
                                            $cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);
                                            $AS_ENTE_GESTOR = $item['frm_value_label'];
                                            continue;
                                        }


                                        // if ($frmCampo == "AS_ENTE_GESTOR") {
                                        //     $cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);
                                        //     $AS_ENTE_GESTOR = $item['frm_value_label'];
                                        //     continue;
                                        // } else if ($frmCampo == "AS_TIPO_EAP_LEGAL") {
                                        //     $frm_label = $item['frm_value_label'];
                                        //     $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        //     continue;
                                        // } else if ($frmCampo == "AS_TIPO_PRESTACIONES") {
                                        //     $frm_label = $item['frm_value_label'];
                                        //     $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        //     continue;
                                        // }
                                        //else if ($frmCampo == "AS_ESTADO_CIVIL") {
                                        //     $frm_label = $item['frm_value_label'];
                                        //     $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        //     continue;
                                        // }

                                    } else {


                                        /*$cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);
                                        continue;*/
                                        try {
                                            // Verifica si $frmValue es un array
                                            if (is_array($frmValue)) {
                                                // Convierte el array a una cadena separada por comas
                                                // $frmValue = implode(", ", $frmValue);
                                                continue;
                                            }
                                            // Verifica si $frmValue es un entero (o cualquier otro tipo que necesite conversión)
                                            elseif (is_int($frmValue)) {
                                                // Convierte el valor entero a cadena
                                                $frmValue = (string) $frmValue;
                                            }

                                            if (is_bool($frmValue)) {
                                                $frmValue = $frmValue ? 'true' : 'false';
                                            }

                                            // Verifica si ahora $frmValue es una cadena
                                            if (!is_string($frmValue)) {
                                                $campo = $item['frm_campo'];
                                                throw new Exception('El argumento ' . $item['frm_campo'] . 'debe ser una cadena. ' . var_export($frmValue, true));
                                            }

                                            // Realiza el reemplazo con str_replace
                                            $cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);

                                            // Continuar con el siguiente ciclo
                                            continue;
                                        } catch (Exception $e) {
                                            // Aquí puedes manejar el error, como registrarlo o mostrar un mensaje
                                            echo 'Error: ' . $e->getMessage();
                                        }
                                    }
                                }
                            }
                            if ($frmValue == 0) {



                                $AS_TELEFONO = $item['frm_campo'];
                                $frm_label = $item['frm_value'];

                                $cuerpox = str_replace('#' . $item['frm_campo'] . '#', $frm_label, $cuerpox);
                                continue;
                            }
                        } else {

                            $frmCampo = $item['frm_campo'];
                            $cuerpox = str_replace('#' . $frmCampo . '#', '', $cuerpox);
                        }
                    }
                    if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN') {
                        $conyugue = '';
                        $primer_grado_grilla = '';
                        $segunda_grado_grilla = '';
                        $tercera_grado_grilla = '';
                        $hijo_grilla = '';
                        $primero = '';
                        $segundo = '';
                        $tercero = '';
                        $cabecera_derecho_hambientes = '';
                        $cabecera_derecho_hambientes = '
                                                                <tr class="row51">
                                                                    <td class="style105" colspan="1"></td>
                                                                    <td class="style104" colspan="5"></td>
                                                                    <td class="style103"></td>
                                                                    <td class="style104" colspan="5"></td>
                                                                    <td class="style103" colspan="1"></td>
                                                                    <td class="style104" colspan="6"></td>
                                                                    <td class="style103" colspan="1"></td>
                                                                    <td class="style104" colspan="7"></td>
                                                                    <td class="style107"></td>
                                                                </tr>
                                                                <tr class="row50">
                                                                    <td class="style107" colspan="1"></td>
                                                                    #CONY_CONV#
                                                                    <td class="style107" colspan="1"></td>
                                                                    <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                                    <td class="style108">#VIVO#</td>
                                                                    <td class="style107" colspan="1"></td>
                                                                    <td class="style108" colspan="6">N° Hijos Fallecidos</td>
                                                                    <td class="style108">#MUERTO#</td>
                                                                    <td class="style107"></td>
                                                                </tr>';
                        $tipo_habiente = '';
                        $bandera = 0;
                        $contar_hijo_vivo = 0;
                        $contar_hijo_muerto = 0;

                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td>
                                                                            <td class="style108"></td>
                                                                            <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td>
                                                                            <td class="style108"></td>';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '   <tr class="row23">
                                                        <td class="style40" colspan="28"></td>
                                                    </tr><tr class="row47"><td class="style43" colspan="28"> IV. DERECHOHABIENTES</td> </tr>';
                                        $bandera++;
                                        $primero = '';
                                        $badera_cony = 0;
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[16]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];

                                            if ($grado == 1) {
                                                $primero = '<tr class="row49"> <td class="style11" colspan="28">  PRIMER GRADO</td></tr>';
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    if ($item2[16]['col_value'] == '1-CONY') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108">X</td> <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"></td>';
                                                    } else if ($item2[16]['col_value'] == '1-CONV') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td><td class="style108"></td> <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td><td class="style108">X</td>';
                                                    }

                                                    $badera_cony = $badera_cony + 1;
                                                    $parentesco = $item2[16]['col_value'];
                                                    $copia_carnet = '';
                                                    $copia_nacimiento = '';
                                                    $copia_testimonio = '';
                                                    $copia_nacimiento_o_c = '';
                                                    $copia_matrimonio = '';
                                                    $copia_matrimonio_o_c = '';
                                                    $doc_id = $item2[2]['col_value'];
                                                    $ci_ver = '';
                                                    $nacimiento_ver = '';
                                                    $matrimonio_ver = '';
                                                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                    $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");

                                                    foreach ($dataHistorico as $historico) {
                                                        if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_carnet = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_carnet = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                $ci_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_nacimiento = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_nacimiento_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Matrimonio') {
                                                            $copia_testimonio = '  <td class="style85" colspan="3"></td>   <td class="style85" colspan="3"></td>';
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_matrimonio = '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_matrimonio = ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                $matrimonio_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_matrimonio_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_matrimonio_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_matrimonio_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        } else if ($historico->doc_descripcion == 'Certificado de Convivencia') {
                                                            $copia_matrimonio = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            $copia_matrimonio_o_c = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_testimonio = '  <td class="style85" colspan="3"></td>   <td class="style85" colspan="3">X</td>';
                                                            } else {
                                                                $copia_testimonio = '  <td class="style85" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                    if ($item2[0]['col_value'] == 1) {
                                                        $fecha_fallecimiento = $item2[14]['col_value'];
                                                        $fecha_data = explode("-", $fecha_fallecimiento);
                                                    } else {
                                                        $fecha_data = explode("-", '- - -');
                                                    }
                                                    $conyugue .= '
                                                                <tr class="row51"><td class=" style106" colspan="28"></td>
                                                                </tr>' . $HtmlFilaNombre . '
                                                                <tr class="row57">
                                                                    <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                                    <td class="style17" colspan="2">Cód. Ver.</td>
                                                                    <td class="style17" colspan="8">Certificado de Nacimiento</td>
                                                                    <td class="style17" colspan="2">Cód. Ver.</td>
                                                                    <td class="style17" colspan="8">Certificado de Matrimonio </td>
                                                                    <td class="style18" colspan="4">Cód. Ver.</td>
                                                                </tr>
                                                                <tr class="row58">
                                                                    <td class="style16" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style85" colspan="2" rowspan="2">' . $ci_ver . '</td>
                                                                    <td class="style17" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style85" colspan="2" rowspan="2">' . $nacimiento_ver . '</td>
                                                                    <td class="style17" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style86"  colspan="4" rowspan="2">' . $matrimonio_ver . '</td>
                                                                </tr>
                                                                <tr class="row59">
                                                                    ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . ' ' . $copia_matrimonio . '' . $copia_matrimonio_o_c . '
                                                                </tr>
                                                                <tr class="row60">
                                                                    <td class="style16" colspan="6">Testimonio de Convivencia</td>
                                                                    <td class="style17" colspan="10">Certificado de Defunción</td>
                                                                    <td class="style17" colspan="4">Cód. Ver.</td>
                                                                    <td class="style18" colspan="8">Fecha de fallecimiento</td>
                                                                </tr>
                                                                <tr class="row61">
                                                                    <td class="style16" colspan="3">SI</td>
                                                                    <td class="style17" colspan="3">NO</td>
                                                                    <td class="style17" colspan="3">SI</td>
                                                                    <td class="style17" colspan="3">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style17" colspan="4" rowspan="2"></td>
                                                                    <td class="style17" colspan="2">Dia</td>
                                                                    <td class="style17" colspan="3">Mes</td>
                                                                    <td class="style18" colspan="3">Año</td>
                                                                </tr>
                                                                <tr class="row62">
                                                                 ' . $copia_testimonio . '
                                                                    <td class="style85" colspan="3"></td>
                                                                    <td class="style85" colspan="3"></td>
                                                                    <td class="style85" colspan="2"></td>
                                                                    <td class="style85" colspan="2"></td>
                                                                    <td class="style85" colspan="2">' . $fecha_data[2] . '</td>
                                                                    <td class="style85" colspan="3">' . $fecha_data[1] . '</td>
                                                                    <td class="style86" colspan="3">' . $fecha_data[0] . '</td>
                                                                </tr>';
                                                } else if ($parentesco == 'HIJ') {
                                                    if ($item2[0]['col_value'] == 1) {
                                                        $contar_hijo_muerto++;
                                                    } else {
                                                        $contar_hijo_vivo++;
                                                    }
                                                    $tipo_doc = $item2[1]['col_value'];
                                                    $tipo = '';

                                                    if ($tipo_doc == 'I') {
                                                        $tipo = 'CI';
                                                    } else if ($tipo_doc == 'E') {
                                                        $tipo = 'EXTRANJERO';
                                                    } else if ($tipo_doc == 'P') {
                                                        $tipo = 'PASAPORTE';
                                                    } else if ($tipo_doc == 'T') {
                                                        $tipo = 'TEMPORAL';
                                                    }


                                                    $tipo_paren = '';
                                                    if ($parentesco == 'CONY') {
                                                        $tipo_paren = 'CÓYUGE';
                                                    } else if ($parentesco == 'HIJ') {
                                                        $tipo_paren = 'HIJO(A)';
                                                    } else if ($parentesco == 'SOB') {
                                                        $tipo_paren = 'SOBRINO(A)';
                                                    } else if ($parentesco == 'HER') {
                                                        $tipo_paren = 'HERMANO(A)';
                                                    } else if ($parentesco == 'CONV') {
                                                        $tipo_paren = 'CONVIVIENTE';
                                                    } else if ($parentesco == 'TIO') {
                                                        $tipo_paren = 'TIO(A)';
                                                    } else if ($parentesco == 'ABU') {
                                                        $tipo_paren = 'ABUELO(A)';
                                                    } else if ($parentesco == 'OTR') {
                                                        $tipo_paren = 'OTROS';
                                                    }
                                                    $sexo = '';
                                                    if ($item2[11]['col_value'] == 'M') {
                                                        $sexo = '<td class="style17">M</td> <td class="style85">X</td> <td class="style17">F</td> <td class="style85"></td>';
                                                    } else {
                                                        $sexo = '<td class="style17">M</td> <td class="style85"></td> <td class="style17">F</td> <td class="style85">X</td>';
                                                    }

                                                    $fecha_nacimiento = $item2[6]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $parentesco = $item2[16]['col_value'];
                                                    $doc_id = $item2[2]['col_value'];
                                                    $copia_carnet = '';
                                                    $copia_nacimiento = '';
                                                    $copia_nacimiento_o_c = '';
                                                    $carnet_ver = '';
                                                    $nacimiento_ver = '';
                                                    $copia_defuncion = '';
                                                    $copia_defuncion_o_c = '';
                                                    $defuncion_ver = '';

                                                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                    $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");
                                                    foreach ($dataHistorico as $historico) {
                                                        if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_carnet .= '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_carnet .= '<td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento .= '<td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                                                            } else {
                                                                $copia_nacimiento .= '<td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                                                                $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '') {
                                                                $copia_nacimiento_o_c = '<td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_nacimiento_o_c .= '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_nacimiento_o_c .= '<td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }


                                                        if ($historico->doc_descripcion == 'Certificado de Defunción') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_defuncion = '     <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                                                            } else {
                                                                $copia_defuncion = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                                                                $defuncion_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }

                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {

                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_defuncion_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $nombres = $item2[7]['col_value'];
                                                    $nombres_data = explode(" ", $nombres);
                                                    $nombre_1 = '';
                                                    $nombre_2 = '';
                                                    if (count($nombres_data) == 2) {
                                                        $nombre_1 = $nombres_data[0];
                                                        $nombre_2 = $nombres_data[1];
                                                    } else if (count($nombres_data) == 1) {
                                                        $nombre_1 = $nombres_data[0];
                                                    } else if (count($nombres_data) > 2) {
                                                        $tam = count($nombres_data) - 1;
                                                        $nombre_1 = $nombres_data[0];
                                                        for ($i = 1; $i <= $tam; $i++) {
                                                            $nombre_2 .= $nombres_data[$i] . ' ';
                                                        }
                                                    }


                                                    $html_invalidez = '';
                                                    if ($item2[15]['col_value'] == true) {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85">X</td><td class="style17">NO</td><td class="style86"></td>';
                                                    } else if ($item2[15]['col_value'] == false) {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85"></td> <td class="style17">NO</td><td class="style86">X</td>';
                                                    } else {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85"></td> <td class="style17">NO</td><td class="style86"></td>';
                                                    }
                                                    if ($item2[14]['col_value'] != null) {
                                                        $fecha_fallecimiento = $item2[14]['col_value'];
                                                        $fecha_data = explode("-", $fecha_fallecimiento);
                                                    } else {
                                                        $fecha_data = explode("-", '- - -');
                                                    }
                                                    $hijo = '
                                                            <tr class="row63"><td class="style44" colspan="28"></td></tr>
                                                            <tr class="row64">
                                                                <td class="style16" colspan="6">Primer Apellido</td>
                                                                <td class="style17" colspan="6">Segundo Apellido</td>
                                                                <td class="style17" colspan="6">Apellido Casada</td>
                                                                <td class="style17" colspan="5">Primer nombre</td>
                                                                <td class="style18" colspan="5">Segundo Nombre</td>
                                                            </tr>
                                                            <tr class="row65">
                                                                <td class="style84" colspan="6">' . $item2[8]['col_value'] . '</td>
                                                                <td class="style85" colspan="6">' . $item2[9]['col_value'] . '</td>
                                                                <td class="style85" colspan="6">' . $item2[10]['col_value'] . '</td>
                                                                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                                                                <td class="style86" colspan="5">' . $nombre_2 . '</td>
                                                            </tr>
                                                               <tr class="row66">
                                                                <td class="style16" colspan="3" rowspan="2">Tipo Doc. Ident.</td>
                                                                <td class="style17" colspan="4" rowspan="2">N° Doc. Identidad</td>
                                                                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                                                                <td class="style17" colspan="5" rowspan="2">Parentesco</td>
                                                                <td class="style17" colspan="4" rowspan="2">Sexo</td>
                                                                <td class="style17" colspan="6">Fecha de Nacimiento</td>
                                                                <td class="style18" colspan="4" rowspan="2">Inválido</td>
                                                            </tr>
                                                            <tr class="row67">
                                                                <td class="style17" colspan="2">Día</td>
                                                                <td class="style17" colspan="2">Mes</td>
                                                                <td class="style17" colspan="2">Año</td>
                                                            </tr>
                                                            <tr class="row68">
                                                                <td class="style84" colspan="3"> ' . $tipo . '</td>
                                                                <td class="style85" colspan="4">' . $item2[3]['col_value'] . '</td>
                                                                <td class="style85" colspan="2">' . $item2[4]['col_value'] . '</td>
                                                                <td class="style85" colspan="5">' . $tipo_paren . '</td>' . $sexo . '
                                                                <td class="style85" colspan="2">' . $fecha_exp[2] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_exp[1] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_exp[0] . '</td>
                                                         ' . $html_invalidez . '
                                                            </tr>
                                                            <tr class="row69">
                                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style17" colspan="6">Certificado de Defunción</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style18" colspan="6">Fecha de fallecimiento</td>
                                                            </tr>
                                                            <tr class="row70">
                                                                <td class="style16" colspan="2">SI</td>
                                                                <td class="style17" colspan="2">NO</td>
                                                                <td class="style85" rowspan="2"  colspan="2">' . $carnet_ver . '</td>
                                                                <td class="style17" colspan="1">SI</td>
                                                                <td class="style17" colspan="1">NO</td>
                                                                <td class="style17" colspan="2">Original</td>
                                                                <td class="style17" colspan="2">Copia</td>
                                                                <td class="style85" rowspan="2" colspan="2">' . $nacimiento_ver . '</td>
                                                                <td class="style17" colspan="1">SI</td>
                                                                <td class="style17" colspan="1">NO</td>
                                                                <td class="style17" colspan="2">Original</td>
                                                                <td class="style17" colspan="2">Copia</td>
                                                                <td class="style85" rowspan="2" colspan="2">' . $defuncion_ver . '</td>
                                                                <td class="style17" colspan="2">Día</td>
                                                                <td class="style17" colspan="2">Mes</td>
                                                                <td class="style18" colspan="2">Año</td>
                                                            </tr>
                                                            <tr class="row71">
                                                               ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '
                                                               ' . $copia_defuncion . '
                                                          ' . $copia_defuncion_o_c . '
                                                                <td class="style85" colspan="2">' . $fecha_data[2] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_data[1] . '</td>
                                                                <td class="style86" colspan="2">' . $fecha_data[0] . '</td>
                                                            </tr>
                                                        ';
                                                    $hijo_grilla .= $hijo;
                                                }
                                            } else if ($grado == 2) {
                                                $segundo = '   <tr class="row155"> <td class="style11" colspan="28">  SEGUNDO GRADO</td> <td class="column6 style165 null style139" colspan="22"></td> </tr>';
                                                $parentesco = $item2[16]['col_value'];
                                                $doc_id = $item2[2]['col_value'];
                                                $copia_carnet = '';
                                                $copia_nacimiento = '';
                                                $copia_nacimiento_o_c = '';
                                                $carnet_ver = '';
                                                $nacimiento_ver = '';
                                                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");

                                                foreach ($dataHistorico as $historico) {
                                                    if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_carnet = '<td class="style84" colspan="3"> </td> <td class="style84" colspan="3">X</td>';
                                                        } else {
                                                            $copia_carnet = '<td class="style84" colspan="3">X</td> <td class="style84" colspan="3"></td>';
                                                            $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                    }
                                                    if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento = '<td class="style84" colspan="3"></td> <td class="style84" colspan="2">X</td>';
                                                        } else {
                                                            $copia_nacimiento = '<td class="style84" colspan="3">X</td> <td class="style84" colspan="2"></td>';
                                                            $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td> <td class=" style84" colspan="3"></td>';
                                                        } else {
                                                            if ($historico->doc_copia_original == 'true') {
                                                                $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td>  <td class="style84" colspan="3">X</td>';
                                                            } else {
                                                                $copia_nacimiento_o_c = ' <td class="style84" colspan="2">X</td>  <td class="style84" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                }

                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                $grilla2 = $HtmlFilaNombre . '

                                                <tr class="row161">
                                                    <td class="style16" colspan="6">Copia Doc. Ident.</td>
                                                    <td class="style17" colspan="3">Cód. Ver.</td>
                                                    <td class="style17" colspan="10">Certificado de Nacimiento</td>
                                                    <td class="style17" colspan="3">Cód. Ver.</td>
                                                    <td class=" style18" colspan="6">Excluir</td>
                                                </tr>
                                                <tr class="row162">
                                                    <td class="style16" colspan="3">SI</td>
                                                    <td class="style17" colspan="3">NO</td>
                                                    <td class="style85" colspan="3" rowspan="2">' . $carnet_ver . '</td>
                                                    <td class="style17" colspan="3">SI</td>
                                                    <td class="style17" colspan="2">NO</td>
                                                    <td class="style17" colspan="2">Original</td>
                                                    <td class="style17" colspan="3">Copia</td>
                                                    <td class="style85" colspan="3" rowspan="2">' . $nacimiento_ver . '</td>
                                                    <td class="style17" colspan="3">SI</td>
                                                    <td class="style18" colspan="3">NO</td>
                                                </tr>
                                                <tr class="row163">
                                                    ' . $copia_carnet . '
                                                    ' . $copia_nacimiento . '
                                                    ' . $copia_nacimiento_o_c . '
                                                    <td class="style84" colspan="3"></td>
                                                    <td class="style85" colspan="3"></td>
                                                </tr>';
                                                $segunda_grado_grilla .= $grilla2;
                                            } else {
                                                $tercero = '<tr class="row165"> <td class=" style11 " colspan="28">  TERCER GRADO</td> </tr>';
                                                $parentesco = $item2[16]['col_value'];
                                                $doc_id = $item2[2]['col_value'];
                                                $copia_carnet = '';
                                                $copia_nacimiento = '';
                                                $copia_nacimiento_o_c = '';
                                                $carnet_ver = '';
                                                $nacimiento_ver = '';
                                                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");
                                                foreach ($dataHistorico as $historico) {
                                                    if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_carnet .= '  <td class="style84" colspan="3"> </td> <td class="style85" colspan="3">X</td>';
                                                        } else {
                                                            $copia_carnet .= '  <td class="style84" colspan="3">X</td> <td class="style85" colspan="3"></td>';
                                                            $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                    }
                                                    if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento .= ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                        } else {
                                                            $copia_nacimiento .= ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                            $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }

                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td> <td class=" style84" colspan="3"></td>';
                                                        } else {
                                                            if ($historico->doc_copia_original == 'true') {
                                                                $copia_nacimiento_o_c .= '   <td class="style85" colspan="2"></td>  <td class="style85" colspan="3">X</td>';
                                                            } else {
                                                                $copia_nacimiento_o_c .= '  <td class="style85" colspan="2">X</td> <td class="style85" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                }
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                $grilla3 = $HtmlFilaNombre . '
                                                        <tr class="row171">
                                                            <td class="style16" colspan="6">Copia Doc. Ident.</td>
                                                            <td class="style17" colspan="3">Cód. Ver.</td>
                                                            <td class="style17" colspan="9">Certificado de Nacimiento</td>
                                                            <td class="style17" colspan="3">Cód. Ver.</td>
                                                            <td class="style18" colspan="7">% Asignación</td>
                                                        </tr>
                                                        <tr class="row172">
                                                            <td class="style16" colspan="3">SI</td>
                                                            <td class="style17" colspan="3">NO</td>
                                                            <td class="style85" colspan="3" rowspan="2">' . $carnet_ver . '</td>
                                                            <td class="style17" colspan="2">SI</td>
                                                            <td class="style17" colspan="2">NO</td>
                                                            <td class="style17" colspan="2">Original</td>
                                                            <td class="style17" colspan="3">Copia</td>
                                                            <td class="style85" colspan="3" rowspan="2">' . $nacimiento_ver . '</td>
                                                            <td class="style86" colspan="7" rowspan="2">' . $item2[17]['col_value'] . '</td>
                                                        </tr>
                                                        <tr class="row173">' . $copia_carnet . '' . $copia_nacimiento . '' . $copia_nacimiento_o_c . '
                                                        </tr>';
                                                $tercera_grado_grilla .= $grilla3;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $cabecera_derecho_hambientes = str_replace('#MUERTO#', $contar_hijo_muerto, $cabecera_derecho_hambientes);
                        $cabecera_derecho_hambientes = str_replace('#VIVO#', $contar_hijo_vivo, $cabecera_derecho_hambientes);
                        if ($bandera == 0) {
                            $cabecera_derecho_hambientes = '';
                        } else {
                            $cabecera_derecho_hambientes = str_replace('#CONY_CONV#', $tipo_habiente, $cabecera_derecho_hambientes);
                        }

                        $grilla .= $cabecera_derecho_hambientes . $primero . $conyugue . $hijo_grilla . $segundo . $segunda_grado_grilla . $tercero . $tercera_grado_grilla;
                    } else
                        if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY NRO 1582-2024') {
                        $conyugue = '';
                        $primer_grado_grilla = '';
                        $segunda_grado_grilla = '';
                        $tercera_grado_grilla = '';
                        $hijo_grilla = '';
                        $primero = '';
                        $segundo = '';
                        $tercero = '';
                        $cabecera_derecho_hambientes = '';
                        $cabecera_derecho_hambientes = '
                                                                <tr class="row51">
                                                                    <td class="style105" colspan="1"></td>
                                                                    <td class="style104" colspan="5"></td>
                                                                    <td class="style103"></td>
                                                                    <td class="style104" colspan="5"></td>
                                                                    <td class="style103" colspan="1"></td>
                                                                    <td class="style104" colspan="6"></td>
                                                                    <td class="style103" colspan="1"></td>
                                                                    <td class="style104" colspan="7"></td>
                                                                    <td class="style107"></td>
                                                                </tr>
                                                                <tr class="row50">
                                                                    <td class="style107" colspan="1"></td>
                                                                    #CONY_CONV#

                                                                    <td class="style107" colspan="1"></td>
                                                                    <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                                    <td class="style108">#VIVO#</td>
                                                                    <td class="style107" colspan="1"></td>
                                                                    <td class="style108" colspan="6">N° Hijos Fallecidos</td>
                                                                    <td class="style108">#MUERTO#</td>
                                                                    <td class="style107"></td>
                                                                </tr>';
                        $tipo_habiente = '';
                        $bandera = 0;
                        $contar_hijo_vivo = 0;
                        $contar_hijo_muerto = 0;

                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td>
                                                                            <td class="style108"></td>
                                                                            <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td>
                                                                            <td class="style108"></td>';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '   <tr class="row23">
                                                        <td class="style40" colspan="28"></td>
                                                    </tr><tr class="row47"><td class="style43" colspan="28"> IV. DERECHOHABIENTES</td> </tr>';
                                        $bandera++;
                                        $primero = '';
                                        $badera_cony = 0;
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[16]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];

                                            if ($grado == 1) {
                                                $primero = '<tr class="row49"> <td class="style11" colspan="28">  PRIMER GRADO</td></tr>';
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    if ($item2[16]['col_value'] == '1-CONY') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108">X</td> <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"></td>';
                                                    } else if ($item2[16]['col_value'] == '1-CONV') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td><td class="style108"></td> <td class="style107"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td><td class="style108">X</td>';
                                                    }

                                                    $badera_cony = $badera_cony + 1;
                                                    $parentesco = $item2[16]['col_value'];
                                                    $copia_carnet = '';
                                                    $copia_nacimiento = '';
                                                    $copia_testimonio = '';
                                                    $copia_nacimiento_o_c = '';
                                                    $copia_matrimonio = '';
                                                    $copia_matrimonio_o_c = '';
                                                    $doc_id = $item2[2]['col_value'];
                                                    $ci_ver = '';
                                                    $nacimiento_ver = '';
                                                    $matrimonio_ver = '';
                                                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                    $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");

                                                    foreach ($dataHistorico as $historico) {
                                                        if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_carnet = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_carnet = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                $ci_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_nacimiento = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_nacimiento_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Matrimonio') {
                                                            $copia_testimonio = '  <td class="style85" colspan="3"></td>   <td class="style85" colspan="3"></td>';
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_matrimonio = '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_matrimonio = ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                $matrimonio_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_matrimonio_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_matrimonio_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_matrimonio_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        } else if ($historico->doc_descripcion == 'Certificado de Convivencia') {
                                                            $copia_matrimonio = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            $copia_matrimonio_o_c = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_testimonio = '  <td class="style85" colspan="3"></td>   <td class="style85" colspan="3">X</td>';
                                                            } else {
                                                                $copia_testimonio = '  <td class="style85" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                    if ($item2[0]['col_value'] == 1) {
                                                        $fecha_fallecimiento = $item2[14]['col_value'];
                                                        $fecha_data = explode("-", $fecha_fallecimiento);
                                                    } else {
                                                        $fecha_data = explode("-", '- - -');
                                                    }
                                                    $conyugue .= '
                                                                <tr class="row51"><td class=" style106" colspan="28"></td>
                                                                </tr>' . $HtmlFilaNombre . '
                                                                <tr class="row57">
                                                                    <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                                    <td class="style17" colspan="2">Cód. Ver.</td>
                                                                    <td class="style17" colspan="8">Certificado de Nacimiento</td>
                                                                    <td class="style17" colspan="2">Cód. Ver.</td>
                                                                    <td class="style17" colspan="8">Certificado de Matrimonio </td>
                                                                    <td class="style18" colspan="4">Cód. Ver.</td>
                                                                </tr>
                                                                <tr class="row58">
                                                                    <td class="style16" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style85" colspan="2" rowspan="2">' . $ci_ver . '</td>
                                                                    <td class="style17" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style85" colspan="2" rowspan="2">' . $nacimiento_ver . '</td>
                                                                    <td class="style17" colspan="2">SI</td>
                                                                    <td class="style17" colspan="2">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style86"  colspan="4" rowspan="2">' . $matrimonio_ver . '</td>
                                                                </tr>
                                                                <tr class="row59">
                                                                    ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . ' ' . $copia_matrimonio . '' . $copia_matrimonio_o_c . '
                                                                </tr>
                                                                <tr class="row60">
                                                                    <td class="style16" colspan="6">Testimonio de Convivencia</td>
                                                                    <td class="style17" colspan="10">Certificado de Defunción</td>
                                                                    <td class="style17" colspan="4">Cód. Ver.</td>
                                                                    <td class="style18" colspan="8">Fecha de fallecimiento</td>
                                                                </tr>
                                                                <tr class="row61">
                                                                    <td class="style16" colspan="3">SI</td>
                                                                    <td class="style17" colspan="3">NO</td>
                                                                    <td class="style17" colspan="3">SI</td>
                                                                    <td class="style17" colspan="3">NO</td>
                                                                    <td class="style17" colspan="2">Original</td>
                                                                    <td class="style17" colspan="2">Copia</td>
                                                                    <td class="style17" colspan="4" rowspan="2"></td>
                                                                    <td class="style17" colspan="2">Dia</td>
                                                                    <td class="style17" colspan="3">Mes</td>
                                                                    <td class="style18" colspan="3">Año</td>
                                                                </tr>
                                                                <tr class="row62">
                                                                 ' . $copia_testimonio . '
                                                                    <td class="style85" colspan="3"></td>
                                                                    <td class="style85" colspan="3"></td>
                                                                    <td class="style85" colspan="2"></td>
                                                                    <td class="style85" colspan="2"></td>
                                                                    <td class="style85" colspan="2">' . $fecha_data[2] . '</td>
                                                                    <td class="style85" colspan="3">' . $fecha_data[1] . '</td>
                                                                    <td class="style86" colspan="3">' . $fecha_data[0] . '</td>
                                                                </tr>';
                                                } else if ($parentesco == 'HIJ') {
                                                    if ($item2[0]['col_value'] == 1) {
                                                        $contar_hijo_muerto++;
                                                    } else {
                                                        $contar_hijo_vivo++;
                                                    }
                                                    $tipo_doc = $item2[1]['col_value'];
                                                    $tipo = '';

                                                    if ($tipo_doc == 'I') {
                                                        $tipo = 'CI';
                                                    } else if ($tipo_doc == 'E') {
                                                        $tipo = 'EXTRANJERO';
                                                    } else if ($tipo_doc == 'P') {
                                                        $tipo = 'PASAPORTE';
                                                    }

                                                    $tipo_paren = '';
                                                    if ($parentesco == 'CONY') {
                                                        $tipo_paren = 'CÓYUGE';
                                                    } else if ($parentesco == 'HIJ') {
                                                        $tipo_paren = 'HIJO(A)';
                                                    } else if ($parentesco == 'SOB') {
                                                        $tipo_paren = 'SOBRINO(A)';
                                                    } else if ($parentesco == 'HER') {
                                                        $tipo_paren = 'HERMANO(A)';
                                                    } else if ($parentesco == 'CONV') {
                                                        $tipo_paren = 'CONVIVIENTE';
                                                    } else if ($parentesco == 'TIO') {
                                                        $tipo_paren = 'TIO(A)';
                                                    } else if ($parentesco == 'ABU') {
                                                        $tipo_paren = 'ABUELO(A)';
                                                    } else if ($parentesco == 'OTR') {
                                                        $tipo_paren = 'OTROS';
                                                    }
                                                    $sexo = '';
                                                    if ($item2[11]['col_value'] == 'M') {
                                                        $sexo = '<td class="style17">M</td> <td class="style85">X</td> <td class="style17">F</td> <td class="style85"></td>';
                                                    } else {
                                                        $sexo = '<td class="style17">M</td> <td class="style85"></td> <td class="style17">F</td> <td class="style85">X</td>';
                                                    }

                                                    $fecha_nacimiento = $item2[6]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $parentesco = $item2[16]['col_value'];
                                                    $doc_id = $item2[2]['col_value'];
                                                    $copia_carnet = '';
                                                    $copia_nacimiento = '';
                                                    $copia_nacimiento_o_c = '';
                                                    $carnet_ver = '';
                                                    $nacimiento_ver = '';
                                                    $copia_defuncion = '';
                                                    $copia_defuncion_o_c = '';
                                                    $defuncion_ver = '';

                                                    $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                    $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                    $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");
                                                    foreach ($dataHistorico as $historico) {
                                                        if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_carnet .= '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                            } else {
                                                                $copia_carnet .= '<td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                        }
                                                        if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_nacimiento .= '<td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                                                            } else {
                                                                $copia_nacimiento .= '<td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                                                                $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }
                                                            if ($historico->doc_url == '') {
                                                                $copia_nacimiento_o_c = '<td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {
                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_nacimiento_o_c .= '<td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_nacimiento_o_c .= '<td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }


                                                        if ($historico->doc_descripcion == 'Certificado de Defunción') {
                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_defuncion = '     <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                                                            } else {
                                                                $copia_defuncion = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                                                                $defuncion_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                            }

                                                            if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                                $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                                                            } else {

                                                                if ($historico->doc_copia_original == 'true') {
                                                                    $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                                } else {
                                                                    $copia_defuncion_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $nombres = $item2[7]['col_value'];
                                                    $nombres_data = explode(" ", $nombres);
                                                    $nombre_1 = '';
                                                    $nombre_2 = '';
                                                    if (count($nombres_data) == 2) {
                                                        $nombre_1 = $nombres_data[0];
                                                        $nombre_2 = $nombres_data[1];
                                                    } else if (count($nombres_data) == 3) {
                                                        $nombre_1 = $nombres_data[0];
                                                        $nombre_2 = $nombres_data[1] . ' ' . $nombres_data[2];
                                                    } else {
                                                        $nombre_1 = $nombres_data[0];
                                                    }
                                                    $html_invalidez = '';
                                                    if ($item2[15]['col_value'] == true) {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85">X</td><td class="style17">NO</td><td class="style86"></td>';
                                                    } else if ($item2[15]['col_value'] == false) {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85"></td> <td class="style17">NO</td><td class="style86">X</td>';
                                                    } else {
                                                        $html_invalidez = '<td class="style17">SI</td><td class="style85"></td> <td class="style17">NO</td><td class="style86"></td>';
                                                    }
                                                    if ($item2[14]['col_value'] != null) {
                                                        $fecha_fallecimiento = $item2[14]['col_value'];
                                                        $fecha_data = explode("-", $fecha_fallecimiento);
                                                    } else {
                                                        $fecha_data = explode("-", '- - -');
                                                    }
                                                    $hijo = '
                                                            <tr class="row63"><td class="style44" colspan="28"></td></tr>
                                                            <tr class="row64">
                                                                <td class="style16" colspan="6">Primer Apellido</td>
                                                                <td class="style17" colspan="6">Segundo Apellido</td>
                                                                <td class="style17" colspan="6">Apellido Casada</td>
                                                                <td class="style17" colspan="5">Primer nombre</td>
                                                                <td class="style18" colspan="5">Segundo Nombre</td>
                                                            </tr>
                                                            <tr class="row65">
                                                                <td class="style84" colspan="6">' . $item2[8]['col_value'] . '</td>
                                                                <td class="style85" colspan="6">' . $item2[9]['col_value'] . '</td>
                                                                <td class="style85" colspan="6">' . $item2[10]['col_value'] . '</td>
                                                                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                                                                <td class="style86" colspan="5">' . $nombre_2 . '</td>
                                                            </tr>
                                                               <tr class="row66">
                                                                <td class="style16" colspan="3" rowspan="2">Tipo Doc. Ident.</td>
                                                                <td class="style17" colspan="4" rowspan="2">N° Doc. Identidad</td>
                                                                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                                                                <td class="style17" colspan="5" rowspan="2">Parentesco</td>
                                                                <td class="style17" colspan="4" rowspan="2">Sexo</td>
                                                                <td class="style17" colspan="6">Fecha de Nacimiento</td>
                                                                <td class="style18" colspan="4" rowspan="2">Inválido</td>
                                                            </tr>
                                                            <tr class="row67">
                                                                <td class="style17" colspan="2">Día</td>
                                                                <td class="style17" colspan="2">Mes</td>
                                                                <td class="style17" colspan="2">Año</td>
                                                            </tr>
                                                            <tr class="row68">
                                                                <td class="style84" colspan="3"> ' . $tipo . '</td>
                                                                <td class="style85" colspan="4">' . $item2[3]['col_value'] . '</td>
                                                                <td class="style85" colspan="2">' . $item2[4]['col_value'] . '</td>
                                                                <td class="style85" colspan="5">' . $tipo_paren . '</td>' . $sexo . '
                                                                <td class="style85" colspan="2">' . $fecha_exp[2] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_exp[1] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_exp[0] . '</td>
                                                         ' . $html_invalidez . '
                                                            </tr>
                                                            <tr class="row69">
                                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style17" colspan="6">Certificado de Defunción</td>
                                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                                <td class="style18" colspan="6">Fecha de fallecimiento</td>
                                                            </tr>
                                                            <tr class="row70">
                                                                <td class="style16" colspan="2">SI</td>
                                                                <td class="style17" colspan="2">NO</td>
                                                                <td class="style85" rowspan="2"  colspan="2">' . $carnet_ver . '</td>
                                                                <td class="style17" colspan="1">SI</td>
                                                                <td class="style17" colspan="1">NO</td>
                                                                <td class="style17" colspan="2">Original</td>
                                                                <td class="style17" colspan="2">Copia</td>
                                                                <td class="style85" rowspan="2" colspan="2">' . $nacimiento_ver . '</td>
                                                                <td class="style17" colspan="1">SI</td>
                                                                <td class="style17" colspan="1">NO</td>
                                                                <td class="style17" colspan="2">Original</td>
                                                                <td class="style17" colspan="2">Copia</td>
                                                                <td class="style85" rowspan="2" colspan="2">' . $defuncion_ver . '</td>
                                                                <td class="style17" colspan="2">Día</td>
                                                                <td class="style17" colspan="2">Mes</td>
                                                                <td class="style18" colspan="2">Año</td>
                                                            </tr>
                                                            <tr class="row71">
                                                               ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '
                                                               ' . $copia_defuncion . '
                                                          ' . $copia_defuncion_o_c . '
                                                                <td class="style85" colspan="2">' . $fecha_data[2] . '</td>
                                                                <td class="style85" colspan="2">' . $fecha_data[1] . '</td>
                                                                <td class="style86" colspan="2">' . $fecha_data[0] . '</td>
                                                            </tr>
                                                        ';
                                                    $hijo_grilla .= $hijo;
                                                }
                                            } else if ($grado == 2) {
                                                $segundo = '   <tr class="row155"> <td class="style11" colspan="28">  SEGUNDO GRADO</td> <td class="column6 style165 null style139" colspan="22"></td> </tr>';
                                                $parentesco = $item2[16]['col_value'];
                                                $doc_id = $item2[2]['col_value'];
                                                $copia_carnet = '';
                                                $copia_nacimiento = '';
                                                $copia_nacimiento_o_c = '';
                                                $carnet_ver = '';
                                                $nacimiento_ver = '';
                                                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");

                                                foreach ($dataHistorico as $historico) {
                                                    if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_carnet = '<td class="style84" colspan="3"> </td> <td class="style84" colspan="3">X</td>';
                                                        } else {
                                                            $copia_carnet = '<td class="style84" colspan="3">X</td> <td class="style84" colspan="3"></td>';
                                                            $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                    }
                                                    if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento = '<td class="style84" colspan="3"></td> <td class="style84" colspan="2">X</td>';
                                                        } else {
                                                            $copia_nacimiento = '<td class="style84" colspan="3">X</td> <td class="style84" colspan="2"></td>';
                                                            $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td> <td class=" style84" colspan="3"></td>';
                                                        } else {
                                                            if ($historico->doc_copia_original == 'true') {
                                                                $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td>  <td class="style84" colspan="3">X</td>';
                                                            } else {
                                                                $copia_nacimiento_o_c = ' <td class="style84" colspan="2">X</td>  <td class="style84" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                }

                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                $grilla2 = $HtmlFilaNombre . '

                                                <tr class="row161">
                                                    <td class="style16" colspan="6">Copia Doc. Ident.</td>
                                                    <td class="style17" colspan="3">Cód. Ver.</td>
                                                    <td class="style17" colspan="10">Certificado de Nacimiento</td>
                                                    <td class="style17" colspan="3">Cód. Ver.</td>
                                                    <td class=" style18" colspan="6">Excluir</td>
                                                </tr>
                                                <tr class="row162">
                                                    <td class="style16" colspan="3">SI</td>
                                                    <td class="style17" colspan="3">NO</td>
                                                    <td class="style85" colspan="3" rowspan="2">' . $carnet_ver . '</td>
                                                    <td class="style17" colspan="3">SI</td>
                                                    <td class="style17" colspan="2">NO</td>
                                                    <td class="style17" colspan="2">Original</td>
                                                    <td class="style17" colspan="3">Copia</td>
                                                    <td class="style85" colspan="3" rowspan="2">' . $nacimiento_ver . '</td>
                                                    <td class="style17" colspan="3">SI</td>
                                                    <td class="style18" colspan="3">NO</td>
                                                </tr>
                                                <tr class="row163">
                                                    ' . $copia_carnet . '
                                                    ' . $copia_nacimiento . '
                                                    ' . $copia_nacimiento_o_c . '
                                                    <td class="style84" colspan="3"></td>
                                                    <td class="style85" colspan="3"></td>
                                                </tr>';
                                                $segunda_grado_grilla .= $grilla2;
                                            } else {
                                                $tercero = '<tr class="row165"> <td class=" style11 " colspan="28">  TERCER GRADO</td> </tr>';
                                                $parentesco = $item2[16]['col_value'];
                                                $doc_id = $item2[2]['col_value'];
                                                $copia_carnet = '';
                                                $copia_nacimiento = '';
                                                $copia_nacimiento_o_c = '';
                                                $carnet_ver = '';
                                                $nacimiento_ver = '';
                                                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                                                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                                                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_id_persona_sip = '$doc_id' and doc_referencia = '$parentesco' order by doc_id desc");
                                                foreach ($dataHistorico as $historico) {
                                                    if ($historico->doc_descripcion == 'Cedula de Identidad') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_carnet .= '  <td class="style84" colspan="3"> </td> <td class="style85" colspan="3">X</td>';
                                                        } else {
                                                            $copia_carnet .= '  <td class="style84" colspan="3">X</td> <td class="style85" colspan="3"></td>';
                                                            $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }
                                                    }
                                                    if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento .= ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                                                        } else {
                                                            $copia_nacimiento .= ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                                                            $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                                                        }

                                                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                                                            $copia_nacimiento_o_c = '<td class="style84" colspan="2"></td> <td class=" style84" colspan="3"></td>';
                                                        } else {
                                                            if ($historico->doc_copia_original == 'true') {
                                                                $copia_nacimiento_o_c .= '   <td class="style85" colspan="2"></td>  <td class="style85" colspan="3">X</td>';
                                                            } else {
                                                                $copia_nacimiento_o_c .= '  <td class="style85" colspan="2">X</td> <td class="style85" colspan="3"></td>';
                                                            }
                                                        }
                                                    }
                                                }
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('JB', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[16]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                $grilla3 = $HtmlFilaNombre . '
                                                        <tr class="row171">
                                                            <td class="style16" colspan="6">Copia Doc. Ident.</td>
                                                            <td class="style17" colspan="3">Cód. Ver.</td>
                                                            <td class="style17" colspan="9">Certificado de Nacimiento</td>
                                                            <td class="style17" colspan="3">Cód. Ver.</td>
                                                            <td class="style18" colspan="7">% Asignación</td>
                                                        </tr>
                                                        <tr class="row172">
                                                            <td class="style16" colspan="3">SI</td>
                                                            <td class="style17" colspan="3">NO</td>
                                                            <td class="style85" colspan="3" rowspan="2">' . $carnet_ver . '</td>
                                                            <td class="style17" colspan="2">SI</td>
                                                            <td class="style17" colspan="2">NO</td>
                                                            <td class="style17" colspan="2">Original</td>
                                                            <td class="style17" colspan="3">Copia</td>
                                                            <td class="style85" colspan="3" rowspan="2">' . $nacimiento_ver . '</td>
                                                            <td class="style86" colspan="7" rowspan="2">' . $item2[17]['col_value'] . '</td>
                                                        </tr>
                                                        <tr class="row173">' . $copia_carnet . '' . $copia_nacimiento . '' . $copia_nacimiento_o_c . '
                                                        </tr>';
                                                $tercera_grado_grilla .= $grilla3;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $cabecera_derecho_hambientes = str_replace('#MUERTO#', $contar_hijo_muerto, $cabecera_derecho_hambientes);
                        $cabecera_derecho_hambientes = str_replace('#VIVO#', $contar_hijo_vivo, $cabecera_derecho_hambientes);
                        if ($bandera == 0) {
                            $cabecera_derecho_hambientes = '';
                        } else {
                            $cabecera_derecho_hambientes = str_replace('#CONY_CONV#', $tipo_habiente, $cabecera_derecho_hambientes);
                        }

                        $grilla .= $cabecera_derecho_hambientes . $primero . $conyugue . $hijo_grilla . $segundo . $segunda_grado_grilla . $tercero . $tercera_grado_grilla;
                    } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN POR MUERTE') {
                        $generohtml = '';
                        $generohtmlsol = '';
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        $fechaNacimiento = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $bandera = 0;
                        $contar_hijo = 0;
                        $tipo_habiente = '';

                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';

                        $cabecera_derecho_hambientes = '
                                                        <tr class="row51">
                                                            <td class="style105" colspan="4"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="7"></td>
                                                            <td class="style106" colspan="5"></td>

                                                        </tr>
                                                        <tr class="row50">
                                                            <td class="style107" colspan="4"></td>
                                                            #CONY_CONV#

                                                            <td class="style107" colspan="2"></td>
                                                            <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                            <td class="style108" colspan="2">#VIVO#</td>
                                                            <td class="style107" colspan="5"></td>
                                                        </tr>
                                                        ';
                        foreach ($impData1 as $item) {

                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];

                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {

                                        $bandera++;
                                        $grilla .= '  <tr class="row23"><td class="style40" colspan="28"></td></tr><tr class="row47"><td class="style43" colspan="28">  V. DERECHOHABIENTES </td> </tr>';

                                        foreach ($item['frm_value'] as $item2) {

                                            $parentesco_value = $item2[16]['col_value'];

                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($grado == 1) {
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    if ($item2[16]['col_value'] == '1-CONY') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" >X</td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';
                                                    } else if ($item2[16]['col_value'] == '1-CONV') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td><td class="style108"  >X</td>';
                                                    }
                                                    ///<tr class="row63"><td class="style44" colspan="28"></td></tr>

                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre(
                                                        'RM',
                                                        $item2[8]['col_value'],
                                                        $item2[9]['col_value'],
                                                        $item2[10]['col_value'],
                                                        $item2[7]['col_value'],
                                                        $item2[6]['col_value'],
                                                        $item2[1]['col_value'],
                                                        $item2[3]['col_value'],
                                                        $item2[4]['col_value'],
                                                        $item2[16]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        ''
                                                    );

                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('PMDERCON', $cas_id, $parentesco_value, $item2[6]['col_value'], $item2[2]['col_value'], 'NO');

                                                    $fecha_nacimiento = $item2[6]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $primer_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento . ' <tr class="row63">
                                                                                <td class="style44" colspan="28"></td>
                                                                                </tr>';
                                                } else if ($parentesco == 'HIJ') {
                                                    $contar_hijo++;

                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre(
                                                        'PMH',
                                                        $item2[8]['col_value'],
                                                        $item2[9]['col_value'],
                                                        $item2[10]['col_value'],
                                                        $item2[7]['col_value'],
                                                        $item2[6]['col_value'],
                                                        $item2[1]['col_value'],
                                                        $item2[3]['col_value'],
                                                        $item2[4]['col_value'],
                                                        $item2[16]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[11]['col_value'],
                                                        $item2[15]['col_value'],
                                                        ''
                                                    );

                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento(
                                                        '3BPM',
                                                        $cas_id,
                                                        $parentesco_value,
                                                        $item2[6]['col_value'],
                                                        $item2[2]['col_value'],
                                                        'NO'
                                                    );

                                                    $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                }
                                            } else {
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre(
                                                    'RMS',
                                                    $item2[8]['col_value'],
                                                    $item2[9]['col_value'],
                                                    $item2[10]['col_value'],
                                                    $item2[7]['col_value'],
                                                    $item2[7]['col_value'],
                                                    $item2[1]['col_value'],
                                                    $item2[3]['col_value'],
                                                    $item2[4]['col_value'],
                                                    $item2[16]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[11]['col_value'],
                                                    ''
                                                );

                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento(
                                                    '3B',
                                                    $cas_id,
                                                    $parentesco_value,
                                                    $item2[6]['col_value'],
                                                    $item2[2]['col_value'],
                                                    'NO'
                                                );

                                                $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }

                                        $cabecera_derecho_hambientes = str_replace('#VIVO#', $contar_hijo, $cabecera_derecho_hambientes);

                                        if ($bandera == 0) {
                                            $cabecera_derecho_hambientes = '';
                                        } else {
                                            $cabecera_derecho_hambientes = str_replace('#CONY_CONV#', $tipo_habiente, $cabecera_derecho_hambientes);
                                        }

                                        $grilla .= $cabecera_derecho_hambientes . $primer_grilla . $segunda_grilla;
                                    } else if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtml = '<table><tr class="row12">
                                        <td class="style17" >M</td>
                                        <td class="style85" >' . $valorM . '</td>
                                        <td class="style17" >F</td>
                                        <td class="style86" >' . $valorF . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                                                <td class="style85" colspan="2">' . $dia . '</td>
                                                                <td class="style85" colspan="2">' . $mes . '</td>
                                                                <td class="style85" colspan="2">' . $anio . '</td>
                                                            </tr></table>';
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                        <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                        <td class="style84" colspan="2">' . $mes . '</td>
                                        <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";

                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol = '  <td class="style17" colspan="">M</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style17" colspan="">F</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);

                        //FORMULARIO DE SOLICITUD DE CCM
                    } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024') {
                        $generohtml = '';
                        $generohtmlsol = '';
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        $fechaNacimiento = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $bandera = 0;
                        $contar_hijo = 0;
                        $tipo_habiente = '';

                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';

                        $cabecera_derecho_hambientes = '
                                                        <tr class="row51">
                                                            <td class="style105" colspan="4"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="7"></td>
                                                            <td class="style106" colspan="5"></td>

                                                        </tr>
                                                        <tr class="row50">
                                                            <td class="style107" colspan="4"></td>
                                                            #CONY_CONV#

                                                            <td class="style107" colspan="2"></td>
                                                            <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                            <td class="style108" colspan="2">#VIVO#</td>
                                                            <td class="style107" colspan="5"></td>
                                                        </tr>
                                                        ';

                        foreach ($impData1 as $item) {

                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];

                                if (!empty($frmValue)) {
                                    if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtml = '<table><tr class="row12">
                                                <td class="style17" >M</td>
                                                <td class="style85" >' . $valorM . '</td>
                                                <td class="style17" >F</td>
                                                <td class="style86" >' . $valorF . '</td>
                                            </tr></table>';
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                                                <td class="style85" colspan="2">' . $dia . '</td>
                                                                <td class="style85" colspan="2">' . $mes . '</td>
                                                                <td class="style85" colspan="2">' . $anio . '</td>
                                                            </tr></table>';
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                                                <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                                                <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                                                <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                                                <td class="style84" colspan="2">' . $mes . '</td>
                                                                <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";

                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol = '  <td class="style17" colspan="">M</td>
                                                                <td class="style85" colspan="">' . $valorM . '</td>
                                                                <td class="style17" colspan="">F</td>
                                                                <td class="style85" colspan="">' . $valorF . '</td>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                    } else if ($nombre_doc == 'ADENDA LEY 1582/2024') {
                        $fila_detalles = '';

                        $sol_primer_nombre = '';
                        $sol_segundo_nombre = '';
                        $sol_primer_apellido = '';
                        $sol_segundo_apellido = '';

                        foreach ($impData1 as $valores) {

                            if (isset($valores['frm_value'])) {

                                if ($valores['frm_campo'] == 'SOL_PRIMER_NOMBRE') {
                                    $sol_primer_nombre = $valores['frm_value'];
                                } elseif ($valores['frm_campo'] == 'SOL_SEGUNDO_NOMBRE') {
                                    $sol_segundo_nombre = $valores['frm_value'];
                                } elseif ($valores['frm_campo'] == 'SOL_PRIMER_APELLIDO') {
                                    $sol_primer_apellido = $valores['frm_value'];
                                } elseif ($valores['frm_campo'] == 'SOL_SEGUNDO_APELLIDO') {
                                    $sol_segundo_apellido = $valores['frm_value'];
                                }

                                if ($valores['frm_campo'] == 'DATA_DETALLES') {
                                    $DATA_DETALLES = $valores['frm_value'];
                                    if (isset($DATA_DETALLES) && is_array($DATA_DETALLES)) {
                                        foreach (array_reverse($DATA_DETALLES) as $detalle) {
                                            $fila = '<tr style="line-height: 1;">
                                                                        <td>' . (isset($detalle['fechaSolicitud']) ? $detalle['fechaSolicitud'] : '1/10/2024') . '</td>
                                                                        <td></td>
                                                                        <td style="text-align: center; color: red;">X</td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td style="text-align: center;">' . (isset($detalle['porcentajeCua']) ? $detalle['porcentajeCua'] : '') . '</td>
                                                                        <td style="text-align: center;">0.00</td>
                                                                        <td style="text-align: center;">' . $detalle['fsa'] . '</td>
                                                                        <td style="text-align: center;">' . $detalle['ccm'] . '</td>
                                                                        <td style="text-align: center;">' . $detalle['fs'] . '</td>
                                                                        <td style="text-align: center;">0.00</td>
                                                                        <td style="text-align: center;">' . $detalle['pension'] . '</td>
                                                                    </tr>';
                                            $fila_detalles = $fila_detalles . $fila;
                                        }
                                    }
                                }
                            }
                        }
                        //FORMULARIO DE SOLICITUD DE CCM
                    } else if ($nombre_doc == 'SOLICITUD DE PENSIÓN POR INVALIDEZ') {
                        $data_filas = '';
                        $cantidad_filas = 0;
                        $fechaNacimiento = '';
                        $generohtml = '';
                        $generohtmlsol = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $rango_conyugue = 60;
                                        $rango_conviviente = 51;
                                        $rango_hijos = 2;
                                        $rango_hijas = 52;
                                        $rango_hermanos = 10;
                                        $rango_hermanas = 80;
                                        $rango_padre = 16;
                                        $rango_madre = 66;
                                        $data_conyugue = '';
                                        $data_hijos = '';
                                        $data_hijas = '';
                                        $data_hermanos = '';
                                        $data_hermanas = '';
                                        $data_general = '';
                                        $cantidad_filas = 1;
                                        foreach ($item['frm_value'] as $item2) {
                                            $cantidad_filas = $cantidad_filas + 1;
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($parentesco == 'CONY') {
                                                $doc_id = $item2[1]['col_value'];
                                                $data_conyugue = $this->generateHtmlClaves($rango_conyugue, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                $rango_conyugue++;
                                            } else if ($parentesco == 'HIJ') {
                                                if ($item2[10]['col_value'] == 'M') {
                                                    $data_hijos .= $this->generateHtmlClaves($rango_hijos, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hijos++;
                                                } else {
                                                    $data_hijas .= $this->generateHtmlClaves($rango_hijas, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hijas++;
                                                }
                                            } else if ($parentesco == 'HER') {
                                                if ($item2[10]['col_value'] == 'M') {
                                                    $data_hermanos .= $this->generateHtmlClaves($rango_hermanos, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hermanos++;
                                                } else {
                                                    $data_hermanas .= $this->generateHtmlClaves($rango_hermanas, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hermanas++;
                                                }
                                            } else {
                                                $data_general .= $this->generateHtmlClaves(0, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                            }
                                        }
                                        if ($cantidad_filas < 12) {
                                            $cantidad = 12 - $cantidad_filas;
                                            $cantidad_filas = $cantidad_filas + $cantidad;
                                            for ($i = 1; $i <= $cantidad; $i++) {
                                                $data_general .= $this->generateHtmlClaves('', '', '', '', '', '', '');
                                            }
                                        }
                                        $data_filas .= $data_conyugue . $data_hijos . $data_hijas . $data_hermanos . $data_hermanas . $data_general;
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                        <td class="style85" colspan="2">' . $dia . '</td>
                                        <td class="style85" colspan="2">' . $mes . '</td>
                                        <td class="style85" colspan="2">' . $anio . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtml = '<table><tr class="row12">
                                        <td class="style85" >M</td>
                                        <td class="style85" >' . $valorM . '</td>
                                        <td class="style85" >F</td>
                                        <td class="style85" >' . $valorF . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style85" colspan="2">' . $dia . '</td>
                                        <td class="style85" colspan="2">' . $mes . '</td>
                                        <td class="style86" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                        <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                        $cuerpox = str_replace('#cantidad_filas#', $cantidad_filas, $cuerpox);
                        $cuerpox = str_replace('#data_filas#', $data_filas, $cuerpox);
                    } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE REVISION DE DICTAMEN') {
                        $data_filas = '';
                        $cantidad_filas = 0;
                        $fechaNacimiento = '';
                        $generohtml = '';
                        $generohtmlsol = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $coberturahtml = '';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                        <td class="style85" colspan="2">' . $dia . '</td>
                                        <td class="style85" colspan="2">' . $mes . '</td>
                                        <td class="style85" colspan="2">' . $anio . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }


                                        $generohtml = '<table><tr class="row12">
                                        <td class="style85" >M</td>
                                        <td class="style85" >' . $valorM . '</td>
                                        <td class="style85" >F</td>
                                        <td class="style85" >' . $valorF . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                        <td class="style84" colspan="2">' . $mes . '</td>
                                        <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                        <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }


                                        $generohtmlsol = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    } //$coberturahtml='';
                                    else if ($frmCampo == "TIENE_COBERTURA") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "SI":
                                                $valorM = "X";
                                                break;
                                            case "NO":
                                                $valorF = "X";
                                                break;
                                        }


                                        $coberturahtml = '  <td class="style85" colspan="">SI</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">NO</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    } else if ($frmCampo == "TIPO_DE_SOLICITANTE") {
                                        $valorA = "";
                                        $valorD = "";
                                        $valorE = "";
                                        switch ($frmValue) {
                                            case "A":
                                                $valorA = "X";
                                                break;
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "E":
                                                $valorE = "X";
                                                break;
                                        }


                                        $tipoSolicitantehtml = '  <table border="0" >
                                        <tr>
                                        <td  colspan="4" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt" > ASEGURADO </td>
                                        <td>&nbsp;</td>
                                        <td   style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">' . $valorA . '     </td>
                                        <td>&nbsp;</td>
                                        <td colspan="4" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;DERECHOHABIENTE</td>
                                        <td>&nbsp;</td>
                                        <td   style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">    ' . $valorD . '  </td>
                                        <td>&nbsp;</td>
                                        <td colspan="4"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;EMPLEADOR</td>
                                        <td>&nbsp;</td>
                                        <td   style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">  ' . $valorE . '    </td>
                                         </tr>
                                 </table>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#tipoSolicitantehtml#', $tipoSolicitantehtml, $cuerpox);
                        $cuerpox = str_replace('#coberturahtml#', $coberturahtml, $cuerpox);
                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                        $cuerpox = str_replace('#cantidad_filas#', $cantidad_filas, $cuerpox);
                        $cuerpox = str_replace('#data_filas#', $data_filas, $cuerpox);
                    } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE RECALIFICACION') {
                        $data_filas = '';
                        $cantidad_filas = 0;
                        $fechaNacimiento = '';
                        $generohtml = '';
                        $generohtmlsol = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $Dictamen = "";
                        $valorra = "";
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $rango_conyugue = 60;
                                        $rango_conviviente = 51;
                                        $rango_hijos = 2;
                                        $rango_hijas = 52;
                                        $rango_hermanos = 10;
                                        $rango_hermanas = 80;
                                        $rango_padre = 16;
                                        $rango_madre = 66;
                                        $data_conyugue = '';
                                        $data_hijos = '';
                                        $data_hijas = '';
                                        $data_hermanos = '';
                                        $data_hermanas = '';
                                        $data_general = '';
                                        $cantidad_filas = 1;
                                        foreach ($item['frm_value'] as $item2) {
                                            $cantidad_filas = $cantidad_filas + 1;
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($parentesco == 'CONY') {
                                                $doc_id = $item2[1]['col_value'];
                                                $data_conyugue = $this->generateHtmlClaves($rango_conyugue, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                $rango_conyugue++;
                                            } else if ($parentesco == 'HIJ') {
                                                if ($item2[10]['col_value'] == 'M') {
                                                    $data_hijos .= $this->generateHtmlClaves($rango_hijos, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hijos++;
                                                } else {
                                                    $data_hijas .= $this->generateHtmlClaves($rango_hijas, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hijas++;
                                                }
                                            } else if ($parentesco == 'HER') {
                                                if ($item2[10]['col_value'] == 'M') {
                                                    $data_hermanos .= $this->generateHtmlClaves($rango_hermanos, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hermanos++;
                                                } else {
                                                    $data_hermanas .= $this->generateHtmlClaves($rango_hermanas, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                                    $rango_hermanas++;
                                                }
                                            } else {
                                                $data_general .= $this->generateHtmlClaves(0, $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[2]['col_value'], $item2[5]['col_value']);
                                            }
                                        }
                                        if ($cantidad_filas < 12) {
                                            $cantidad = 12 - $cantidad_filas;
                                            $cantidad_filas = $cantidad_filas + $cantidad;
                                            for ($i = 1; $i <= $cantidad; $i++) {
                                                $data_general .= $this->generateHtmlClaves('', '', '', '', '', '', '');
                                            }
                                        }
                                        $data_filas .= $data_conyugue . $data_hijos . $data_hijas . $data_hermanos . $data_hermanas . $data_general;
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                        <td class="style85" colspan="2">' . $dia . '</td>
                                        <td class="style85" colspan="2">' . $mes . '</td>
                                        <td class="style85" colspan="2">' . $anio . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }


                                        $generohtml = '<table><tr class="row12">
                                        <td class="style85" >M</td>
                                        <td class="style85" >' . $valorM . '</td>
                                        <td class="style85" >F</td>
                                        <td class="style85" >' . $valorF . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                        <td class="style84" colspan="2">' . $mes . '</td>
                                        <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                        <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }


                                        $generohtmlsol = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    } else if ($frmCampo == "FECHA_DICTAMEN_REC") {
                                        $fechDic = "";
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechDic = '    <tr>
                                         <td style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;' . $dia . '</td>
                                         <td style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;' . $mes . '</td>
                                         <td style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;' . $anio . '</td>
                                     </tr>';
                                    } else if ($frmCampo == "TIPO_DIC_REC") {
                                        // print($frmValue );

                                        switch ($frmValue) {
                                            case "Dictamen":
                                                $Dictamen = "DIC";
                                                break;
                                            case "RA":
                                                $valorra = "RA";
                                                break;
                                        }
                                    } else if ($frmCampo == "NRODICTAMEN_REC") {
                                        $tipoDic = "";
                                        /*print($frmValue );
                                                print($Dictamen );
                                                print($valorra );*/
                                        if ($Dictamen == "DIC") {
                                            //print("PPP" );
                                            $tipoDic = '   <td colspan="4" rowspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;Nro. de Dictamen</td>

                                         <td  rowspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">  ' . $frmValue . '   </td>

                                         <td colspan="4" rowspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;Resolución Administrativa</td>

                                         <td rowspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">     </td>';
                                        }
                                        if ($valorra == "RA") {  //print("PPP7" );
                                            $tipoDic = '   <td colspan="4" rowspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;Nro. de Dictamen</td>

                                         <td  rowspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">     </td>

                                         <td colspan="4" rowspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">&nbsp;  &nbsp;Resolución Administrativa</td>

                                         <td rowspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt"> ' . $frmValue . '    </td>';
                                        }
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#tipoDic#', $tipoDic, $cuerpox);
                        $cuerpox = str_replace('#fechDic#', $fechDic, $cuerpox);
                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                        $cuerpox = str_replace('#cantidad_filas#', $cantidad_filas, $cuerpox);
                        $cuerpox = str_replace('#data_filas#', $data_filas, $cuerpox);
                    } else if ($nombre_doc == '2. Form Retiros') {
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '<tr class="row47"><td class="style43" colspan="28">  V. DERECHOHABIENTES </td> </tr>';
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($grado == 1) {
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('RM', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[15]['col_value']);
                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('4B', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                    $fecha_nacimiento = $item2[5]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $primer_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                } else if ($parentesco == 'HIJ') {

                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('RMS', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('3B', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                    $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                }
                                            } else {
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('RMS', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');

                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('3B', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }
                                        $grilla .= $primer_grilla . $segunda_grilla;
                                    }
                                }
                            }
                        }
                    } else if ($nombre_doc == 'Formulario pension por muerte') {
                        $generohtml = '';
                        $generohtmlsol = '';
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        $fechaNacimiento = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $bandera = 0;
                        $contar_hijo = 0;
                        $tipo_habiente = '';
                        $tipo_habiente = '  <td class="style108" colspan="4">Cnnyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';
                        $cabecera_derecho_hambientes = '
                                                        <tr class="row51">
                                                            <td class="style105" colspan="4"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="7"></td>
                                                            <td class="style106" colspan="5"></td>

                                                        </tr>
                                                        <tr class="row50">
                                                            <td class="style107" colspan="4"></td>
                                                            #CONY_CONV#

                                                            <td class="style107" colspan="2"></td>
                                                            <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                            <td class="style108" colspan="2">#VIVO#</td>
                                                            <td class="style107" colspan="5"></td>


                                                        </tr>

                                                        ';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $bandera++;
                                        $grilla .= '  <tr class="row23"><td class="style40" colspan="28"></td></tr><tr class="row47"><td class="style43" colspan="28">  V. DERECHOHABIENTES </td> </tr>';

                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($grado == 1) {
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    if ($item2[14]['col_value'] == '1-CONY') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" >X</td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';
                                                    } else if ($item2[14]['col_value'] == '1-CONV') {
                                                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td><td class="style108"  >X</td>';
                                                    }
                                                    ///<tr class="row63"><td class="style44" colspan="28"></td></tr>
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('RM', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');

                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('PMDERCON', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                    $fecha_nacimiento = $item2[5]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $primer_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento . '                                            <tr class="row63">
                                                <td class="style44" colspan="28"></td>
                                            </tr>';
                                                } else if ($parentesco == 'HIJ') {
                                                    $contar_hijo++;
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('PMH', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[13]['col_value'], '');
                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('3BPM', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                    $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                }
                                            } else {
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('RMS', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('3B', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }

                                        $cabecera_derecho_hambientes = str_replace('#VIVO#', $contar_hijo, $cabecera_derecho_hambientes);

                                        if ($bandera == 0) {
                                            $cabecera_derecho_hambientes = '';
                                        } else {
                                            $cabecera_derecho_hambientes = str_replace('#CONY_CONV#', $tipo_habiente, $cabecera_derecho_hambientes);
                                        }

                                        $grilla .= $cabecera_derecho_hambientes . $primer_grilla . $segunda_grilla;
                                    } else if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtml = '<table><tr class="row12">
                                        <td class="style17" >M</td>
                                        <td class="style85" >' . $valorM . '</td>
                                        <td class="style17" >F</td>
                                        <td class="style86" >' . $valorF . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                        <td class="style85" colspan="2">' . $dia . '</td>
                                        <td class="style85" colspan="2">' . $mes . '</td>
                                        <td class="style85" colspan="2">' . $anio . '</td>
                                    </tr></table>';
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                        <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                        <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                        <td class="style84" colspan="2">' . $mes . '</td>
                                        <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol = '  <td class="style17" colspan="">M</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style17" colspan="">F</td>
                                         <td class="style85" colspan="">' . $valorF . '</td>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                        //FORMULARIO DE SOLICITUD DE CCM
                    } else if ($nombre_doc == 'FORMULARIO DE REGISTRO DE DOCUMENTOS (FRDL)') {
                        $generohtmlherpoder = '';
                        $AS_TIPO_PRESTACIONES = '';
                        $html_documento_ben = '';
                        $BENEFICIARIO = '';

                        foreach ($impData1 as $item) {

                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DAHE") {
                                        $generohtmlherpoder .= '<tr class="row24"> <th class="style85" colspan="16"> DATOS DEL APODERADO </th> </tr> ';
                                        foreach ($item['frm_value'] as $item2) {
                                            //dd($item['frm_value']);
                                            $doc_categoria = $item2[4]['col_value'];
                                            $DH_GENERO = $item2[12]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                                            $DOC_IDENTIDAD = "CEDULA IDENTIDAD";

                                            switch ($item2[2]['col_value']) {
                                                case "I":
                                                    $DOC_IDENTIDAD = "CEDULA IDENTIDAD";
                                                    break;
                                                case "E":
                                                    $DOC_IDENTIDAD = "EXTRANJERO";
                                                    break;
                                                case "P":
                                                    $DOC_IDENTIDAD = "PASAPORTE";
                                                    break;
                                                default:
                                                    $DOC_IDENTIDAD = "TEMPORAL";
                                                    break;
                                            }

                                            $html_fila = '

                                            <tr style="font-size:8px; text-align:center;">
                                                <th colspan="2">Apoderado: </th>
                                                <th colspan="6">' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . ' ' . $item2[10]['col_value'] . ' ' . $item2[11]['col_value'] . '</th>
                                                <th colspan="2">' . $DOC_IDENTIDAD . ': </th>
                                                <th colspan="3">' . $item2[4]['col_value'] . ' ' . $item2[5]['col_value'] . '</th>
                                                <th colspan="1">Sexo: </th>
                                                <th colspan="2">' . $DH_GENERO . '</th>
                                            </tr>
                                                   ';
                                            $html_heredero_documento = $this->htmlHerederoDocumento($cas_id, $doc_categoria, 'DAHE');
                                            $generohtmlherpoder = $generohtmlherpoder . $html_fila . $html_heredero_documento;
                                        }
                                    }
                                    if ($frmCampo == "GRILLA_DAHERDERO") {

                                        $generohtmlherpoder .= '
                                                <tr class="row24">
                                                    <th class="style85" colspan="16">DATOS DEL HEREDERO</th>
                                                </tr>
                                            ';
                                        foreach ($item['frm_value'] as $item2) {

                                            //dd($item2);
                                            $DOC_IDENTIDAD = "CEDULA IDENTIDAD";
                                            $doc_categoria = $item2[4]['col_value'];
                                            $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_referencia = 'DAHERDERO'  and doc_categoria = '$doc_categoria' ");
                                            $valor_si = '';
                                            $valor_no = '';
                                            if ($gp_documentos != []) {
                                                $valor_si = 'X';
                                                $valor_no = '';
                                            } else {
                                                $valor_si = '';
                                                $valor_no = 'X';
                                            }
                                            $DH_GENERO = $item2[11]['col_value'] === 'M' ? 'Masculino' : 'Femenino';

                                            switch ($item2[1]['col_value']) {
                                                case "I":
                                                    $DOC_IDENTIDAD = "CEDULA IDENTIDAD";
                                                    break;
                                                case "E":
                                                    $DOC_IDENTIDAD = "EXTRANJERO";
                                                    break;
                                                case "P":
                                                    $DOC_IDENTIDAD = "PASAPORTE";
                                                    break;
                                                default:
                                                    $DOC_IDENTIDAD = "TEMPORAL";
                                                    break;
                                            }
                                            $html_fila = '

                                            <tr style="font-size:8px; text-align:center;">
                                                <th colspan="2">Heredero: </th>
                                                <th colspan="6">' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . ' ' . $item2[10]['col_value'] . '</th>
                                                <th colspan="2">' . $DOC_IDENTIDAD . ': </th>
                                                <th colspan="3">' . $item2[3]['col_value'] . ' ' . $item2[4]['col_value'] . '</th>
                                                <th colspan="1">Sexo: </th>
                                                <th colspan="2">' . $DH_GENERO . '</th>
                                            </tr>
                                                                        ';
                                            $html_heredero_documento = $this->htmlHerederoDocumento($cas_id, $item2[3]['col_value'], 'DAHERDERO');

                                            $generohtmlherpoder = $generohtmlherpoder . $html_fila . $html_heredero_documento;
                                        }
                                    }
                                    if ($frmCampo == "GRILLA_DACO") {

                                        $DOC_IDENTIDAD = "CEDULA IDENTIDAD";
                                        $BENEFICIARIO .= '<tr class="row24"> <th class="style85" colspan="16"> DATOS DEL BENEFICIARIO </th> </tr> ';
                                        foreach ($item['frm_value'] as $item2) {
                                            $doc_categoria = $item2[4]['col_value'];
                                            $DH_GENERO = $item2[12]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                                            switch ($item2[2]['col_value']) {
                                                case "I":
                                                    $DOC_IDENTIDAD = "CEDULA IDENTIDAD";
                                                    break;
                                                case "E":
                                                    $DOC_IDENTIDAD = "EXTRANJERO";
                                                    break;
                                                case "P":
                                                    $DOC_IDENTIDAD = "PASAPORTE";
                                                    break;
                                                default:
                                                    $DOC_IDENTIDAD = "TEMPORAL";
                                                    break;
                                            }
                                            $html_fila = '
                                            <tr style="font-size:8px; text-align:center;">
                                                <th colspan="2">Benificiario: </th>
                                                <th colspan="6">' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . ' ' . $item2[10]['col_value'] . ' ' . $item2[11]['col_value'] . '</th>
                                                <th colspan="2">' . $DOC_IDENTIDAD . ': </th>
                                                <th colspan="3">' . $item2[4]['col_value'] . ' ' . $item2[5]['col_value'] . '</th>
                                                <th colspan="1">Sexo: </th>
                                                <th colspan="2">' . $DH_GENERO . '</th>
                                            </tr>';
                                            $html_heredero_documento = $this->htmlHerederoDocumento($cas_id, $doc_categoria, 'DACO');
                                            $BENEFICIARIO = $BENEFICIARIO . $html_fila . $html_heredero_documento;
                                        }
                                    }
                                    if ($frmCampo == "AS_TIPO_EAP") {
                                        $AS_TIPO_PRESTACIONES = $frmValue;
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#BENEFICIARIO#', $BENEFICIARIO, $cuerpox);
                        $cuerpox = str_replace('#generohtmlherpoder#', $generohtmlherpoder, $cuerpox);

                        $cuerpox = str_replace('#AS_TIPO_PRESTACIONES#', $this->nombrePrestaciones($AS_TIPO_PRESTACIONES), $cuerpox);
                    } else if ($nombre_doc == 'FORMULARIO DE NOTIFICACION DE REVISION - L') {
                        $html_fila_claves = '';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DAHE") {
                                        foreach ($item['frm_value'] as $item2) {
                                            $html_fila = '<tr class="row7">

                                                <td class="style16" colspan="6">' . $item2[7]['col_value'] . '</td>
                                                <td class="style20" colspan="4">' . $item2[8]['col_value'] . '</td>
                                                <td class="style20" colspan="4">' . $item2[9]['col_value'] . '</td>
                                                <td class="style20" colspan="4">' . $item2[5]['col_value'] . '</td>
                                                <td class="style20" colspan="4">' . $item2[6]['col_value'] . '</td>
                                                <td class="style16" colspan="6">' . $item2[3]['col_value'] . '</td>

                                            </tr>';
                                            $html_fila_claves = $html_fila_claves . $html_fila;
                                        }
                                    }
                                }
                            }
                        }
                        $cuerpox = str_replace('#data_filas#', $html_fila_claves, $cuerpox);
                    } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE CCM') {
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '<tr class="row23"><td class="style40" colspan="28"></td></tr><tr class="row47"><td class="style43" colspan="28">  V. DERECHOHABIENTES </td> </tr>';
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            if ($grado == 1) {
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {

                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('RM', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('4B', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], 'NO');
                                                    $fecha_nacimiento = $item2[5]['col_value'];
                                                    $fecha_exp = explode("-", $fecha_nacimiento);
                                                    $primer_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                } else if ($parentesco == 'HIJ') {
                                                    $HtmlFilaNombre = $this->generateHtmlFilaNombre('RM', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                    $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('CCMD', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], $item2[13]['col_value']);
                                                    $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                                }
                                            } else {
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('RM', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('CCMD', $cas_id, $parentesco_value, $item2[5]['col_value'], $item2[1]['col_value'], $item2[13]['col_value']);
                                                $segunda_grilla .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }

                                        $grilla .= $primer_grilla . $segunda_grilla;
                                    }
                                }
                            }
                        }
                    } else if ($nombre_doc == 'CONTRATO DE INCLUSION') {
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        $tercera_grilla = '';
                        $primer_grado = '';
                        $segundo_grado = '';
                        $tercer_grado = '';

                        $direccion = $AS_ZONA . '&nbsp;' . $AS_DIRECCION . '&nbsp;' . $AS_NUM;

                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '    <tr class="row9">
                                                           <td class="style16" colspan="1" ></td>
                                                            <td class="style16" colspan="27" style="text-align: justify;"> <strong>B) Derechohabientes.<br>
                                                                    1. Datos Individualizados de los Derechohabientes.
                                                                </strong>
                                                            </td>
                                                        </tr>';
                                        $cantidad = count($item['frm_value']);
                                        $cantidad_hijos = 0;
                                        $bandera = 1;
                                        $bandera_hijos = 1;
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_explote = explode("-", $item2[14]['col_value']);
                                            if ($parentesco_explote[1] == 'HIJ') {
                                                $cantidad_hijos++;
                                            }
                                        }
                                        foreach ($item['frm_value'] as $item2) {
                                            $parentesco_value = $item2[14]['col_value'];
                                            $parentesco_explote = explode("-", $parentesco_value);
                                            $grado = $parentesco_explote[0];
                                            $parentesco = $parentesco_explote[1];
                                            $nombre = $item2[6]['col_value'] . ' ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'];
                                            if ($bandera == $cantidad) {
                                                $salto = '';
                                            } else {
                                                $salto = '<tr class="row9"> <td class="style94" colspan="28" style="text-align: justify;">  </td></tr>';
                                            }
                                            if ($grado == 1) {
                                                if ($parentesco == 'CONY' or $parentesco == 'CONV') {
                                                    $primer_grado = '<tr class="row9"><td class="style94" colspan="1" ></td> <td class="style94" colspan="27" style="text-align: justify;"> <strong> PRIMER GRADO</strong> </td></tr>';


                                                    $HtmlFilaIndividualizado = $this->generateHtmlDatosIndividualizado($nombre, $item2[2]['col_value'], $item2[3]['col_value'], $item2[5]['col_value'], $direccion, $item2[12]['col_value'], $item2[13]['col_value'], $item2[14]['col_value']);
                                                    $primer_grilla .= $HtmlFilaIndividualizado;
                                                } else if ($parentesco == 'HIJ') {
                                                    if ($bandera_hijos == $cantidad_hijos) {
                                                        $salto_hijo = '';
                                                    } else {
                                                        $salto_hijo = '<tr class="row9">  <td class="style94" colspan="1" ></td><td class="style94" colspan="27" style="text-align: justify;">  </td></tr>';
                                                    }
                                                    $segundo_grado = '<tr class="row9"> <td class="style94" colspan="28" style="text-align: justify;">  </td></tr>';
                                                    $HtmlFilaIndividualizado = $this->generateHtmlDatosIndividualizado($nombre, $item2[2]['col_value'], $item2[3]['col_value'], $item2[5]['col_value'], $direccion, $item2[12]['col_value'], $item2[13]['col_value'], $item2[14]['col_value']);
                                                    $segunda_grilla .= $HtmlFilaIndividualizado . $salto_hijo;
                                                    $bandera_hijos++;
                                                }
                                            } else {
                                                $tercer_grado = '<tr class="row9">  <td class="style94" colspan="1" ></td> <td class="style94" colspan="27" style="text-align: justify;"> <strong> TERCER GRADO</strong> </td></tr>';
                                                $HtmlFilaIndividualizado = $this->generateHtmlDatosIndividualizado($nombre, $item2[2]['col_value'], $item2[3]['col_value'], $item2[5]['col_value'], $direccion, $item2[12]['col_value'], $item2[13]['col_value'], $item2[14]['col_value']);
                                                $tercera_grilla .= $HtmlFilaIndividualizado . $salto;
                                            }
                                            $bandera++;
                                        }
                                        $grilla .= $primer_grado . $primer_grilla . $segundo_grado . $segunda_grilla . $tercer_grado . $tercera_grilla;
                                    }
                                }
                            }
                        }
                    }
                    //MAHER_v1
                    else if ($nombre_doc == 'FORMULARIO MASA HEREDITARIA') {
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= 'tiene grilla';
                                        $derecho_hambiente_muerto = '';
                                        $derecho_hambiente_viva = '';
                                        $cabecera_muerto = '';
                                        $cabecera_viva = '';
                                        foreach ($item['frm_value'] as $item2) {
                                            if ($item2[0]['col_value'] == 1) {

                                                $cabecera_muerto = '<tr class="row24"><td class="style43" colspan="28"> II. DATOS DEL DERECHOHABIENTE FALLECIDO</td></tr>';
                                                $parentesco_value = $item2[18]['col_value'];
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('MH', $item2[8]['col_value'], $item2[9]['col_value'], $item2[10]['col_value'], $item2[7]['col_value'], $item2[6]['col_value'], $item2[1]['col_value'], $item2[3]['col_value'], $item2[4]['col_value'], $item2[18]['col_value'], $item2[11]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], $item2[10]['col_value'], '');
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('MH', $cas_id, $parentesco_value, $item2[16]['col_value'], $item2[2]['col_value'], 'NO');
                                                $derecho_hambiente_muerto .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            } else {
                                                $cabecera_viva = '<tr class="row47"><td class="style43" colspan="28"> IV. DATOS GENERALES DE LOS HEREDEROS </td> </tr>';
                                                $cantidad_firmas++;
                                                $parentesco_value = $item2[18]['col_value'];
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre(
                                                    'MHV',
                                                    $item2[8]['col_value'],
                                                    $item2[9]['col_value'],
                                                    $item2[10]['col_value'],
                                                    $item2[7]['col_value'],
                                                    $item2[6]['col_value'],
                                                    $item2[1]['col_value'],
                                                    $item2[3]['col_value'],
                                                    $item2[4]['col_value'],
                                                    $item2[18]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[14]['col_value'],
                                                    $item2[10]['col_value'],
                                                    $item2[15]['col_value'],
                                                    $item2[12]['col_value'],
                                                    $item2[13]['col_value'],
                                                    $item2[10]['col_value'],
                                                    ''
                                                );
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('4B', $cas_id, $parentesco_value, $item2[6]['col_value'], $item2[2]['col_value'], 'NO');
                                                $derecho_hambiente_viva .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }
                                        $derecho_hambiente_viva = $cabecera_viva . $derecho_hambiente_viva;
                                        $derecho_hambiente_muerto = $cabecera_muerto . $derecho_hambiente_muerto;

                                        $bandera_par = 0;
                                        $firma_bloque = '';
                                        $firma_final = '';
                                        foreach ($item['frm_value'] as $item2) {
                                            if ($item2[0]['col_value'] == 1) {
                                            } else {
                                                $firma_inicio = '';
                                                if (($bandera_par % 2) == 0) {
                                                    if ($cantidad_firmas != ($bandera_par + 1)) {
                                                        $firma_inicio = '
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                        </tr>
                                                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                                ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . '
                                                            </td>';
                                                    }
                                                } else {
                                                    $firma_inicio = '
                                                            <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                                ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . '
                                                            </td>
                                                        </tr>';
                                                }
                                                $firma_bloque .= $firma_inicio;
                                                if (($cantidad_firmas % 2) == 0) {
                                                    $firma_final = '
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                        </tr>
                                                        <tr class="row23"><td colspan="14" class="style19" > <br><br></td></tr>
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14"> Firma de Recepción Responsable de Gestora</td>
                                                        </tr>';
                                                } else {
                                                    $firma_final = '
                                                      <tr class="row35">
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                        </tr>
                                                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                                ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . ' ' . $item2[9]['col_value'] . '
                                                            </td>
                                                            <td class="style17" colspan="14"> Firma de Recepción Responsable de Gestora</td>
                                                        </tr>';
                                                }
                                                $bandera_par++;
                                            }
                                        }
                                        $firmas_lote = $firma_bloque . $firma_final;
                                    }
                                }
                            }
                        }
                    }

                    //MAHER_v2
                    /*else if ($nombre_doc == 'FORMULARIO MASA HEREDITARIA') {
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= 'tiene grilla';
                                        $derecho_hambiente_muerto = '';
                                        $derecho_hambiente_viva = '';
                                        $cabecera_muerto = '';
                                        $cabecera_viva = '';
                                        foreach ($item['frm_value'] as $item2) {
                                            if ($item2[1]['col_value'] == 1) {

                                                $cabecera_muerto = '<tr class="row24"><td class="style43" colspan="28"> II. DATOS DEL DERECHOHABIENTE FALLECIDO</td></tr>';
                                                $parentesco_value = $item2[19]['col_value'];
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre('MH', $item2[9]['col_value'], $item2[10]['col_value'], $item2[11]['col_value'], $item2[8]['col_value'], $item2[7]['col_value'], $item2[2]['col_value'], $item2[4]['col_value'], $item2[5]['col_value'], $item2[19]['col_value'], $item2[12]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], $item2[11]['col_value'], '');
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('MH', $cas_id, $parentesco_value, $item2[7]['col_value'], $item2[3]['col_value'], 'NO');
                                                $derecho_hambiente_muerto .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            } else {
                                                $cabecera_viva = '<tr class="row47"><td class="style43" colspan="28"> IV. DATOS GENERALES DE LOS HEREDEROS </td> </tr>';

                                                $parentesco_value = $item2[19]['col_value'];
                                                $HtmlFilaNombre = $this->generateHtmlFilaNombre(
                                                    'MHV',
                                                    $item2[9]['col_value'],
                                                    $item2[10]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[8]['col_value'],
                                                    $item2[7]['col_value'],
                                                    $item2[2]['col_value'],
                                                    $item2[4]['col_value'],
                                                    $item2[5]['col_value'],
                                                    $item2[19]['col_value'],
                                                    $item2[12]['col_value'],
                                                    $item2[15]['col_value'],
                                                    $item2[11]['col_value'],
                                                    $item2[16]['col_value'],
                                                    $item2[13]['col_value'],
                                                    $item2[14]['col_value'],
                                                    $item2[11]['col_value'],
                                                    ''
                                                );
                                                $HtmlFilaDocumento = $this->generateHtmlFilaDocumento('4B', $cas_id, $parentesco_value, $item2[7]['col_value'], $item2[3]['col_value'], 'NO');
                                                $derecho_hambiente_viva .= $HtmlFilaNombre . $HtmlFilaDocumento;
                                            }
                                        }
                                        $derecho_hambiente_viva = $cabecera_viva . $derecho_hambiente_viva;
                                        $derecho_hambiente_muerto = $cabecera_muerto . $derecho_hambiente_muerto;

                                        $bandera_par = 0;
                                        $firma_bloque = '';
                                        $array_herederos = '[';
                                        foreach ($item['frm_value'] as $item2) {
                                            if ($item2[0]['col_value'] == 'Heredero') {
                                                $cantidad_firmas++;
                                                $array_herederos = $array_herederos . json_encode($item2) . ',';
                                            }
                                        }

                                        $array_herederos = substr($array_herederos, 0, -1);
                                        $array_herederos = $array_herederos . ']';
                                        $array_herederos = json_decode($array_herederos);


                                        foreach ($array_herederos as $item2) {

                                            if ($item2[1]->col_value == 1) {
                                            } else {

                                                $firma_inicio = '';
                                                if (($bandera_par % 2) == 0) {
                                                    if ($cantidad_firmas != ($bandera_par + 1)) {
                                                        $firma_inicio = '
                                                     <tr class="row35">
                                                         <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                         <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                     </tr>
                                                     <tr class="row23"><td class="style40" colspan="28"></td></tr>
                                                     <tr class="row35">
                                                         <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                             ' . $item2[8]->col_value . ' ' . $item2[9]->col_value . ' ' . $item2[10]->col_value . '
                                                         </td>';
                                                    }
                                                } else {
                                                    $firma_inicio = '
                                                         <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                             ' . $item2[8]->col_value . ' ' . $item2[9]->col_value . ' ' . $item2[10]->col_value . '
                                                         </td>
                                                     </tr>';
                                                }
                                                $firma_bloque .= $firma_inicio;
                                                if (($cantidad_firmas % 2) == 0) {
                                                    $firma_final = '
                                                     <tr class="row35">
                                                         <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                     </tr>
                                                     <tr class="row23"><td colspan="14" class="style19" > <br><br></td></tr>
                                                     <tr class="row35">
                                                         <td class="style17" colspan="14"> Firma de Recepción Responsable de Gestora</td>
                                                     </tr>';
                                                } else {
                                                    $firma_final = '
                                                   <tr class="row35">
                                                         <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                         <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                                     </tr>
                                                     <tr class="row23"><td class="style40" colspan="28"></td></tr>
                                                     <tr class="row35">
                                                         <td class="style17" colspan="14"> Firma del Heredero ' . ($bandera_par + 1) . '.<br>
                                                             ' . $item2[8]->col_value . ' ' . $item2[9]->col_value . ' ' . $item2[10]->col_value . '
                                                         </td>
                                                         <td class="style17" colspan="14"> Firma de Recepción Responsable de Gestora</td>
                                                     </tr>';
                                                }

                                                $bandera_par++;
                                            }
                                        }
                                        $firmas_lote = $firma_bloque . $firma_final;
                                    }
                                }
                            }
                        }
                    } */
                    //FIN MAHER
                    else if ($nombre_doc == 'FORMULARIO GASTOS FUNERARIOS') {
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                        $grilla .= '<tr class="row23"><td class="style40" colspan="28"></td></tr><tr class="row47"><td class="style43" colspan="28">III. TESTIGOS (Para solicitudes presentadas dentro de los primeros 6 meses y sólo en caso de presentar Recibo) </td> </tr>';
                                        $cantidad = count($item['frm_value']);
                                        $bandera = 1;
                                        foreach ($item['frm_value'] as $item2) {
                                            $HtmlFilaNombre = $this->generateHtmlFilaNombre('GF', $item2[7]['col_value'], $item2[8]['col_value'], $item2[9]['col_value'], $item2[6]['col_value'], $item2[5]['col_value'], $item2[0]['col_value'], $item2[2]['col_value'], $item2[3]['col_value'], $item2[14]['col_value'], $item2[10]['col_value'], $item2[16]['col_value'], $item2[12]['col_value'], $item2[13]['col_value'], $item2[14]['col_value'], $item2[15]['col_value'], $item2[11]['col_value'], '');
                                            $salto = '';
                                            if ($bandera == $cantidad) {
                                                $salto = '';
                                            } else {
                                                $salto = '<tr class="row63"><td class="style44" colspan="28"></td></tr>';
                                            }
                                            $grilla .= $HtmlFilaNombre . $salto;
                                            $bandera++;
                                            $cantidad_firmas++;
                                        }
                                        $bandera_par = 0;
                                        $firma_bloque = '';
                                        $firma_final = '';
                                        // foreach ($item['frm_value'] as $item2) {
                                        //     if ($item2[0]['col_value'] == 1) {
                                        //     } else {
                                        //         $firma_inicio = '';
                                        //         if (($bandera_par % 2) == 0) {
                                        //             if ($cantidad_firmas != ($bandera_par + 1)) {
                                        //                 $firma_inicio = '
                                        //                 <tr class="row35">
                                        //                     <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                        //                     <td class="style17" colspan="14" rowspan ="2"><br><br> </td>
                                        //                 </tr>
                                        //                 <tr class="row23"><td class="style40" colspan="28"></td></tr>
                                        //                 <tr class="row35">
                                        //                     <td class="style17" colspan="14"> Firma del Testigo ' . ($bandera_par + 1) . '.<br>
                                        //                         ' . $item2[6]['col_value'] . ' ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . '
                                        //                     </td>';
                                        //             }
                                        //         } else {
                                        //             $firma_inicio = '
                                        //                     <td class="style17" colspan="14"> Firma del Testigo ' . ($bandera_par + 1) . '.<br>
                                        //                         ' . $item2[6]['col_value'] . ' ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . '
                                        //                     </td>
                                        //                 </tr>';
                                        //         }
                                        //         $firma_bloque .= $firma_inicio;
                                        //         if (($cantidad_firmas % 2) == 0) {
                                        //             $firma_final = '';
                                        //         } else {
                                        //             $firma_final = '
                                        //                 <tr class="row35">
                                        //                     <td class="style17" colspan="14" rowspan ="2"> <br><br></td>
                                        //                 </tr>
                                        //                 <tr class="row23"><td class="style41" colspan="14"><br><br></td></tr>
                                        //                 <tr class="row35">
                                        //                     <td class="style17" colspan="14"> Firma del Testigo ' . ($bandera_par + 1) . '.<br>
                                        //                         ' . $item2[6]['col_value'] . ' ' . $item2[7]['col_value'] . ' ' . $item2[8]['col_value'] . '
                                        //                     </td>
                                        //                 </tr>';
                                        //         }
                                        //         $bandera_par++;
                                        //     }
                                        // }
                                        $firmas_pie = '
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                                                            <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                                                        </tr>
                                                        <tr class="row23"><td class="style41" colspan="14"></td></tr>
                                                        <tr class="row35">
                                                            <td class="style17" colspan="14"> FIRMA DEL ASEGURADO O SOLICITANTE </td>
                                                            <td class="style17" colspan="14"> FIRMA DE RECEPCIÓN RESPONSABLE DE GESTORA</td>
                                                        </tr>';
                                        $firmas_lote = $firmas_pie . $firma_bloque . $firma_final;
                                    }
                                }
                            }
                        }
                    } else if ($nombre_doc == 'FORMULARIO DE REVISION GERENCIA NACIONAL LEGAL') {
                        $generohtml = '';
                        $generohtmlsol = '';
                        $primer_grilla = '';
                        $segunda_grilla = '';
                        $fechaNacimiento = '';
                        $fechaLaboralhtml = '';
                        $dependietehtml = '';
                        $bandera = 0;
                        $contar_hijo = 0;
                        $tipo_habiente = '';

                        $tipo_habiente = '  <td class="style108" colspan="4">Conyugue</td> <td class="style108" ></td> <td class="style107"  colspan="2"></td>
                                                                            <td class="style108" colspan="4">Conviviente</td> <td class="style108"  ></td>';

                        $cabecera_derecho_hambientes = '
                                                        <tr class="row51">
                                                            <td class="style105" colspan="4"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="5"></td>
                                                            <td class="style103" colspan="2"></td>
                                                            <td class="style104" colspan="7"></td>
                                                            <td class="style106" colspan="5"></td>

                                                        </tr>
                                                        <tr class="row50">
                                                            <td class="style107" colspan="4"></td>
                                                            #CONY_CONV#

                                                            <td class="style107" colspan="2"></td>
                                                            <td class="style108" colspan="5">N° Hijos Vivos</td>
                                                            <td class="style108" colspan="2">#VIVO#</td>
                                                            <td class="style107" colspan="5"></td>
                                                        </tr>
                                                        ';

                        foreach ($impData1 as $item) {

                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];

                                if (!empty($frmValue)) {
                                    if ($frmCampo == "AS_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtml = '<table><tr class="row12">
                                                <td class="style17" >M</td>
                                                <td class="style85" >' . $valorM . '</td>
                                                <td class="style17" >F</td>
                                                <td class="style86" >' . $valorF . '</td>
                                            </tr></table>';
                                    } else if ($frmCampo == "AS_NACIMIENTO") {
                                        $fecha = $frmValue;
                                        $partes = explode("-", $fecha);

                                        $anio = $partes[0];
                                        $mes = $partes[1];
                                        $dia = $partes[2];
                                        $fechaNacimiento = '<table><tr class="row12">
                                                                <td class="style85" colspan="2">' . $dia . '</td>
                                                                <td class="style85" colspan="2">' . $mes . '</td>
                                                                <td class="style85" colspan="2">' . $anio . '</td>
                                                            </tr></table>';
                                    } else if ($frmCampo == "EM_TIPO_AS") {
                                        $valorD = "";
                                        $valorI = "";
                                        switch ($frmValue) {
                                            case "D":
                                                $valorD = "X";
                                                break;
                                            case "I":
                                                $valorI = "X";
                                                break;
                                        }
                                        $dependietehtml = '  <td class="style16" colspan="3" rowspan="3" >Dependiente</td>
                                                                <td class="style16" colspan="1" rowspan="3" >' . $valorD . '</td>
                                                                <td class="style16" colspan="3" rowspan="3" >Independiente</td>
                                                                <td class="style16" colspan="1" rowspan="3" >' . $valorI . '</td>';
                                    } else if ($frmCampo == "EM_FECHA") {
                                        $fechae = $frmValue;
                                        $partese = explode("-", $fechae);
                                        $anio = $partese[0];
                                        $mes = $partese[1];
                                        $dia = $partese[2];
                                        $fechaLaboralhtml = '<td class="style84" colspan="2">' . $dia . '</td>
                                                                <td class="style84" colspan="2">' . $mes . '</td>
                                                                <td class="style84" colspan="2">' . $anio . '</td>'; //EM_TIPO_AS
                                    } else if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";

                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol = '  <td class="style17" colspan="">M</td>
                                                                <td class="style85" colspan="">' . $valorM . '</td>
                                                                <td class="style17" colspan="">F</td>
                                                                <td class="style85" colspan="">' . $valorF . '</td>';
                                    }
                                }
                            }
                        }

                        $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
                        $cuerpox = str_replace('#generohtml#', $generohtml, $cuerpox);
                        $cuerpox = str_replace('#fechaLaboralhtml#', $fechaLaboralhtml, $cuerpox);
                        $cuerpox = str_replace('#dependietehtml#', $dependietehtml, $cuerpox);
                        $cuerpox = str_replace('#fechaNacimiento#', $fechaNacimiento, $cuerpox);
                    } else if ($nombre_doc == 'PLANTILLA FORM MUT' || $nombre_doc == 'PLANTILLA ITM MUT' || $nombre_doc == 'PLANTILLA DICT MUT') {
                        $bandera_doctor = 1;
                        $array_doctores = [];
                        $causahtml = '';
                        $origenhtml = '';
                        $origenhtml2 = '';

                        foreach ($impData1 as $item) {

                            $doc = 'DOCC' . $bandera_doctor;
                            if ($item['frm_campo'] == $doc) {
                                $bandera_doctor++;
                            }

                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];

                                if ($frmCampo == $doc & $item['frm_value'] == 'true') {
                                    $data = \DB::select("SELECT * from public.personal_imp where codigo ='$frmCampo' ");
                                    $doctor = $data[0]->nombre . '<br>' . $data[0]->matricula;
                                    array_push($array_doctores, $doctor);
                                }
                            }
                            if (!empty($item['frm_value'])) {
                                if (isset($item['frm_campo']) && $item['frm_campo'] == "ORIGEN") {
                                    $causahtml = "";
                                    switch ($item['frm_value']) {
                                        case "Riesgo Comun":
                                            $causahtml = "no";
                                            break;
                                    }
                                } else if (isset($item['frm_campo']) && $item['frm_campo'] == "CAUSA") {
                                    $origenhtml = "";
                                    $origenhtml2 = "";
                                    switch ($item['frm_value']) {
                                        case "Accidente":
                                            $origenhtml = "circunstancias del siniestro";
                                            $origenhtml2 = "evento súbito o violento";
                                            break;
                                        case "Enfermedad":
                                            $origenhtml = "patologías";
                                            $origenhtml2 = "proceso evolutivo e irreversible ";
                                            break;
                                    }
                                }
                            }
                        }
                        $cuerpox = str_replace('#origenhtml2#', $origenhtml2, $cuerpox);
                        $cuerpox = str_replace('#causahtml#', $causahtml, $cuerpox);
                        $cuerpox = str_replace('#origenhtml#', $origenhtml, $cuerpox);
                        // Variable para almacenar la salida HTML
                        $output = '<table>

                        <tbody>';

                        // Iterar sobre el array de doctores y crear filas dinámicamente
                        for ($i = 0; $i < count($array_doctores); $i += 3) {
                            $output .= '<tr>';
                            for ($j = 0; $j < 3; $j++) {
                                if (isset($array_doctores[$i + $j])) {

                                    $output .= '<td align="center" class="style16"><br><br><br><br><br><br><br><b>' . $array_doctores[$i + $j] . '</b></td>';
                                } else {
                                    $output .= '<td></td>'; // Celda vacía si no hay más doctores
                                }
                            }
                            $output .= '</tr>';
                        }

                        $output .= '</tbody></table>';

                        // Imprimir la variable que contiene la salida HTML
                        $firmas_lote = $output;
                    }
                } else {
                    echo "No se pudo decodificar cas_data_valores como un array";
                }
            } else {
                //  echo "No se tiene el valor cas data_valores";
            }
        } else {
            /// echo "No se tienen valores";
        }
        $finmja = '   </tbody>      </table>     </body>    </html>';

        $tablaContenido = $cuerpox;
        $cuerpox = "";
        $html = '';
        if ($imp_tipo == '3') {
            if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="20"> <strong>FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN </strong></td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $html_documento_asegurado = $this->generateHtmlAseguradoDocumento($cas_id, 'JUB');
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $pbs_Html = $this->generateHtmlSolicitantedoDocumentoPbsHtml($cas_id);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $tablaContenido = str_replace('#CODIGO_VER#', $pbs_Html, $tablaContenido);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY NRO 1582-2024') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="20"> <strong>FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY N° 1582/2024 </strong></td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $html_documento_asegurado = $this->generateHtmlAseguradoDocumento($cas_id, 'JUB');
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $pbs_Html = $this->generateHtmlSolicitantedoDocumentoPbsHtml($cas_id);
                $tablaContenido = str_replace('#CODIGO_VER#', $pbs_Html, $tablaContenido);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'SOLICITUD DE PENSIÓN POR INVALIDEZ') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="column3 style69" colspan="22" style="border: none;"><br><br>SOLICITUD DE PENSIÓN POR INVALIDEZ</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumento($cas_id, 'INV');
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_documento_tecnico_medico = $this->generateHtmlDocumentoTecnico($cas_id);
                $tablaContenido = str_replace('#DOC_MEDICINA#', $html_documento_tecnico_medico, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE NOTIFICACION DE REVISION - L') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE REVISÍON</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $htmlRechazados = $this->generateHtmlRechazado($GRILLA_MRCHZ);
                $tablaContenido = str_replace('#data_filas_grilla_rechazado#', $htmlRechazados, $tablaContenido);
                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == '2. Form Retiros') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">SOLICITUD DE RETIROS</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $html_retiro_solicitante = $this->generateHtmlRetirosMinimos($RMI_OPCION);
                $tablaContenido = str_replace('#SELECCION_RETIROS#', $html_retiro_solicitante, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);

                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO GASTOS FUNERARIOS') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">SOLICITUD DE GASTOS FUNERARIOS</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $pbs_Html = $this->generateHtmlSolicitantedoDocumentoPbsHtml($cas_id);
                $tablaContenido = str_replace('#CODIGO_VER_CI#', $pbs_Html, $tablaContenido);
                $html_retiro_solicitante = $this->generateHtmlRetirosMinimos($RMI_OPCION);
                $tablaContenido = str_replace('#SELECCION_RETIROS#', $html_retiro_solicitante, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_factura = $this->generateHtmlFacturaRecibo($SOL_FAC_REC, $FECHA_SUPERA_6);
                $tablaContenido = str_replace('#FACTURA_RECIBO#', $html_factura, $tablaContenido);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);

                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);

                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE DECLARACIÓN DE APORTES EN OTRO (S) ESTADOS (S) PARTE (FDAOEP) PARA ASEGURADOS') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="column3 style69" colspan="22" style="border: none;">FORMULARIO DE DECLARACIÓN DE APORTES EN OTRO (S) ESTADOS (S) PARTE (FDAOEP) PARA ASEGURADOS</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
            } else if ($nombre_doc == 'FORMULARIO DE REGISTRO DE DOCUMENTOS (FRDL)') {
                $BENEFICIARIO ='';
                $BENEFICIARIO_1 ='';
                $nro_caso = '';
                if ($CASO_HEREDARO != '') {
                    $nro_caso = '(' . $CASO_HEREDARO . ')';
                }

                $request = new Request([
                    'agrupador' => 'LEGAL-GRUPO'
                ]);
                // Obtener instancia de LegalAPIController con sus dependencias inyectadas
                $legalApiController = app()->make(LegalAPIController::class);
                // Llamar al método
                $response = $legalApiController->parametricaDeParametricas($request);
                // Obtener datos de la respuesta
                $data = $response->getData();
                // Asumimos que ya tienes $parametricasLegal como array
                $parametricasLegal = $response->getData(true)['data']; // O como sea que recibas tu array
                // Buscar el objeto donde 'pdp_parameter_name' es 'GRUPO-COBRO'
                $grupoCobro = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-COBRO');
                // Obtener los valores si existen
                $parametricasLegalGrupoCobro = $grupoCobro['pdp_parameter_value'] ?? [];
                // Verificar si está en el array
                /*if (in_array($AS_TIPO_PRESTACIONES, $parametricasLegalGrupoCobro)) {
                    // Está incluido, puedes continuar con tu lógica aquí
                    echo("El prestacion_id {$AS_TIPO_PRESTACIONES} está en GRUPO-COBRO");
                } else {
                    echo("El prestacion_id {$AS_TIPO_PRESTACIONES} NO está en GRUPO-COBRO");
                }*/


                $PERIODOS_HABILITAR = '';
                $HTML_DATOS_PODER = '';
                $HTML_DATOS_APOSTILLA = '';



                if (
                    /*$AS_TIPO_PRESTACIONES == 2 ||
                    $AS_TIPO_PRESTACIONES == 31 ||
                    $AS_TIPO_PRESTACIONES == 176 ||
                    $AS_TIPO_PRESTACIONES == 199 ||
                    $AS_TIPO_PRESTACIONES == 200 ||
                    $AS_TIPO_PRESTACIONES == 201 ||
                    $AS_TIPO_PRESTACIONES == 202 ||
                    $AS_TIPO_PRESTACIONES == 219 ||
                    $AS_TIPO_PRESTACIONES == 228*/
                    in_array($AS_TIPO_PRESTACIONES, $parametricasLegalGrupoCobro)
                ) {


                    if (
                        $AS_TIPO_PRESTACIONES == 199 ||
                        $AS_TIPO_PRESTACIONES == 200 ||
                        $AS_TIPO_PRESTACIONES == 202 ||
                        $AS_TIPO_PRESTACIONES == 201
                    ) {

                        if ($HA_PAGO_UNICO == "SI") {
                            if ($HA_PAGO_AGUINALDO == 'SI') {
                                $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                                <th colspan="4">TIPO DE PAGO:</th>
                                                <th colspan="12"> UNICO (Con Aguinaldo ' . $HA_GESTION_AGUINALDO . ')</th>
                                            </tr>';
                            } else {
                                $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                            <th colspan="4">TIPO DE PAGO:</th>
                                            <th colspan="12"> UNICO </th>
                                        </tr>';
                            }
                        } else {
                            if ($HA_PAGO_AGUINALDO == 'SI') {
                                $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                                        <th colspan="4">PERIODOS A HABILITAR:</th>
                                                        <th colspan="12">' . $FORM_JUB_MES_INI . '  A ' . $FORM_JUB_MES_FIN . ' (Con Aguinaldo ' . $HA_GESTION_AGUINALDO . ')</th>
                                                    </tr>';
                            } else {

                                $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                                        <th colspan="4">PERIODOS A HABILITAR:</th>
                                                        <th colspan="12">' . $FORM_JUB_MES_INI . '  A ' . $FORM_JUB_MES_FIN . '</th>
                                                    </tr>';
                            }
                        }
                    } else {
                        if ($HA_PAGO_AGUINALDO == 'SI') {
                            $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                                    <th colspan="4">PERIODOS A HABILITAR:</th>
                                                    <th colspan="12">' . $FORM_JUB_MES_INI . '  A ' . $FORM_JUB_MES_FIN . ' (Con Aguinaldo ' . $HA_GESTION_AGUINALDO . ')</th>
                                                </tr>';
                        } else {

                            $PERIODOS_HABILITAR = ' <tr style="font-size:8px; text-align:center; ">
                                                    <th colspan="4">PERIODOS A HABILITAR:</th>
                                                    <th colspan="12">' . $FORM_JUB_MES_INI . '  A ' . $FORM_JUB_MES_FIN . '</th>
                                                </tr>';
                        }
                    }


                    //*******TABLA DE BENIFICIARIO********** */
                    /* $html_documento_ben = $this->generateHtmlDocumentoParametrica($cas_id, '4-RES', 'CI BENIFICIARIO', 'DOCUMENTOS DEL BENIFICIARIO');
                    $BENEFICIARIO = ' <tr style="text-align:center; font-size:8px;">
                                <th colspan="16" class="style85">DATOS DEL BENIFICIARIO </th>
                                    </tr>
                                    <tr style="font-size:8px; text-align:center; ">
                                        <th colspan="3">Nombre Completo: </th>
                                        <th colspan="6">' . $BE_PRIMER_APELLIDO . ' ' . $BE_SEGUNDO_APELLIDO . ' ' . $BE_PRIMER_NOMBRE . ' ' . $BE_SEGUNDO_NOMBRE . ' ' . $BE_APELLIDO_CASADA . '</th>
                                        <th colspan="1">CI:</th>
                                        <th colspan="3">' . $BE_CI . ' ' . $BE_COMPLEMENTO . '</th>
                                        <th colspan="1">Sexo: </th>
                                        <th colspan="2">' . $BE_GENERO . '</th>
                                    </tr>
                                    <tr style="font-size:8px; text-align:center; ">
                                        <th colspan="2">DIRECCION: </th>
                                        <th colspan="7">' . $BE_DIRECCION . '</th>
                                        <th colspan="3">Celular: </th>
                                        <th colspan="4">' . $BE_CELULAR . '</th>
                                    </tr>
                                    <tr style="font-size:8px; text-align:center; ">
                                        <th colspan="3">Correo electronico: </th>
                                        <th colspan="13">' . $BE_CORREO . '</th>
                                    </tr>' . $html_documento_ben;*/
                    //generateHtmlDocumentoParametrica($cas_id,$doc_referencia,$doc_descripcion,$titulo)
                    if ($EXTRANGERO_PODER == "1") {
                        $HTML_DATOS_PODER = '  <tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                    } else {
                        $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                <th colspan="16" class="style85">DATOS DEL PODER </th>
                                            </tr>
                                             <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="8">Testimonio: </th>
                                                    <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                </tr>
                                            <tr style="font-size:8px; text-align:center;">
                                                <th colspan="3">Nro. Poder: </th>
                                                <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                <th colspan="4">Nombre del Notario: </th>
                                                <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                            </tr>
                                            <tr style="font-size:8px; text-align:center;">
                                                <th colspan="3">Fecha de Emisión: </th>
                                                <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                <th colspan="3">Lugar de emisión Departamento: </th>
                                                <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                <th colspan="4">Municipio: </th>
                                                <th colspan="2">' . $MUNICIPIO_1 . ' </th>
                                            </tr>';
                    }
                } else {
                    $grupo3 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-3');
                    $grupo3Values = $grupo3['pdp_parameter_value'] ?? [];
                    $grupo1 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-1');
                    $grupo1Values = $grupo1['pdp_parameter_value'] ?? [];
                    $grupo4 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-4');
                    $grupo4Values = $grupo4['pdp_parameter_value'] ?? [];
                    $grupo2 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-2');
                    $grupo2Values = $grupo2['pdp_parameter_value'] ?? [];
                    $grupo5 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-5');
                    $grupo5Values = $grupo5['pdp_parameter_value'] ?? [];
                    $grupo6 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-6');
                    $grupo6Values = $grupo6['pdp_parameter_value'] ?? [];
                    if (in_array($AS_TIPO_PRESTACIONES, $grupo3Values)) {
                        /* $html_documento_ben = $this->generateHtmlDocumentoParametrica($cas_id, '4-RES', 'CI BENIFICIARIO', 'DOCUMENTOS DEL BENIFICIARIO');
                        $BENEFICIARIO = ' <tr style="text-align:center; font-size:8px;">
                        <th colspan="16" class="style85">DATOS DEL BENIFICIARIO </th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="3">Nombre Completo: </th>
                                <th colspan="6">' . $BE_PRIMER_APELLIDO . ' ' . $BE_SEGUNDO_APELLIDO . ' ' . $BE_PRIMER_NOMBRE . ' ' . $BE_SEGUNDO_NOMBRE . ' ' . $BE_APELLIDO_CASADA . '</th>
                                <th colspan="1">CI:</th>
                                <th colspan="3">' . $BE_CI . ' '. $BE_COMPLEMENTO .'</th>
                                <th colspan="1">Sexo: </th>
                                <th colspan="2">' . $BE_GENERO . '</th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="2">DIRECCION: </th>
                                <th colspan="7">' . $BE_DIRECCION . '</th>
                                <th colspan="3">Celular: </th>
                                <th colspan="4">' . $BE_CELULAR . '</th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="3">Correo electronico: </th>
                                <th colspan="13">' . $BE_CORREO . '</th>
                            </tr>' . $html_documento_ben;*/

                        if ($EXTRANGERO_PODER == "1") {
                            $HTML_DATOS_PODER = ' <tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                        } else {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="8">Testimonio: </th>
                                                    <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Nro. Poder: </th>
                                                    <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                    <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                    <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                    <th colspan="3">Lugar de emisión Departamento: </th>
                                                    <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                    <th colspan="4">Municipio: </th>
                                                    <th colspan="2">' . $MUNICIPIO_1 . ' </th>
                                                </tr>';
                        }
                    }
                    if (in_array($AS_TIPO_PRESTACIONES, $grupo5Values)) {
                        if ($EXTRANGERO_PODER == "1") {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                        } else {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                 <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="8">Testimonio: </th>
                                                    <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Nro. Poder: </th>
                                                    <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                    <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                    <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                    <th colspan="3">Lugar de emisión Departamento: </th>
                                                    <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                    <th colspan="4">Municipio: </th>
                                                    <th colspan="2">' . $MUNICIPIO_1 . ' </th>
                                                </tr>';
                        }
                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo4Values)) {
                        $HTML_DATOS_PODER = '';
                        $HTML_ACEPTACION_DE_HERENCIA = "";
                        if ($ACEPTACION_DE_HERENCIA == "2") {
                            $HTML_ACEPTACION_DE_HERENCIA = '<tr style="text-align:center; font-size:8px;">
                                                        <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                                <th colspan="3">Aceptación de Herencia: </th>
                                                                <th colspan="2">' . $ACEPTACION_DE_HERENCIA_TEXTO . ' </th>
                                                                <th colspan="3">Nro. de Sentencia/Resolución: </th>
                                                                <th colspan="2">' . $NRO_SENTENCIA_RESOLUCION . ' </th>
                                                                <th colspan="4">Juzgado: </th>
                                                                <th colspan="2">' . $JUZGADO . ' </th>
                                                           </tr>';
                        }
                        else {
                            if ($EXTRANGERO_PODER == "1") {
                                $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                        <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                            <th colspan="8">Testimonio: </th>
                                                            <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">

                                                        <th colspan="8">Nro. Poder: </th>
                                                        <th colspan="8">' . $NRO_PODER_SOL_1 . ' </th>

                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="4">Nombre del Notario: </th>
                                                        <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                        <th colspan="2">Fecha de Emisión: </th>
                                                        <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                        <th colspan="2">País: </th>
                                                        <th colspan="2">' . $PAIS . '</th>
                                                    </tr>';
                            } else {
                                $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                        <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                    </tr>
                                                     <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="8">Testimonio: </th>
                                                        <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="3">Nro. Poder: </th>
                                                        <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                        <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                        <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                        <th colspan="4">Nombre del Notario: </th>
                                                        <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="3">Fecha de Emisión: </th>
                                                        <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                        <th colspan="3">Lugar de emisión Departamento: </th>
                                                        <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                        <th colspan="4">Municipio: </th>
                                                        <th colspan="2">' . $MUNICIPIO_1 . ' </th>
                                                    </tr>';
                            }
                        }


                        $HTML_DATOS_PODER =  $HTML_DATOS_PODER . $HTML_ACEPTACION_DE_HERENCIA;
                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo6Values)) {
                        if ($EXTRANGERO_PODER == "1") {
                            $HTML_DATOS_PODER = '  <tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                        } else {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                 <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="8">Testimonio: </th>
                                                    <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="2">Nro. Poder: </th>
                                                    <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                    <th colspan="2">Nro. Notaría de la Fe Pública: </th>
                                                    <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                    <th colspan="2">Nro. Poder revocado: </th>
                                                    <th colspan="2">' . $NRO_PODER_REVOCATORIO . ' </th>
                                                    <th colspan="2">Nombre del Notario: </th>
                                                    <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                    <th colspan="3">Lugar de emisión Departamento: </th>
                                                    <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                    <th colspan="4">Municipio: </th>
                                                    <th colspan="2">' . $MUNICIPIO_1 . ' </th>

                                                </tr>';
                        }
                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo1Values)) {
                        if ($EXTRANGERO_PODER == "1") {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                        } else {
                            $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="8">Testimonio: </th>
                                                    <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Nro. Poder: </th>
                                                    <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                    <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                    <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                </tr> <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="3">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                    <th colspan="3">Lugar de emisión Departamento: </th>
                                                    <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                    <th colspan="4">Municipio: </th>
                                                    <th colspan="2">' . $MUNICIPIO_1 . ' </th>

                                                </tr>';
                        }
                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo2Values)) //apostilla
                    {

                        $HTML_DATOS_APOSTILLA = '  <tr style="text-align:center; font-size:8px;">
                                                        <th colspan="16" class="style85">DATOS DE APOSTILLA </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="8">Nro. Apostilla: </th>
                                                        <th colspan="8">' . $NUMERO_APOSTILLA . ' </th>
                                                    </tr>';
                        $html_documento_apostilla = $this->generateHtmlDocumentoParametrica($cas_id, '4-RES', 'Carta', 'DOCUMENTOS PARA APOSTILLA');
                        if ($TIENE_PODER == "1") {
                            if ($EXTRANGERO_PODER == "1") {
                                $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                    <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Testimonio:  </th>
                                                    <th colspan="4">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    <th colspan="4">Nro. Poder: </th>
                                                    <th colspan="4">' . $NRO_PODER_SOL_1 . ' </th>

                                                </tr>
                                                <tr style="font-size:8px; text-align:center;">
                                                    <th colspan="4">Nombre del Notario: </th>
                                                    <th colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>
                                                    <th colspan="2">Fecha de Emisión: </th>
                                                    <th colspan="2">' . $FECHA_DE_EMISION . '</th>
                                                    <th colspan="2">País: </th>
                                                    <th colspan="2">' . $PAIS . '</th>
                                                </tr>';
                            } else {
                                $HTML_DATOS_PODER = '<tr style="text-align:center; font-size:8px;">
                                                        <th colspan="16" class="style85">DATOS DEL PODER </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="8">Testimonio: </th>
                                                        <th colspan="8">' . $EXTRANGERO_PODER_TEXTO . ' </th>
                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="3">Nro. Poder: </th>
                                                        <th colspan="2">' . $NRO_PODER_SOL_1 . ' </th>
                                                        <th colspan="3">Nro. Notaría de la Fe Pública: </th>
                                                        <th colspan="2">' . $NRO_NOTARIA_SOL_1 . ' </th>
                                                        <th colspan="4">Nombre del Notario: </th>
                                                        <th colspan="2">' . $NOMBRE_NOTARIO_SOL_1 . ' </th>

                                                    </tr>
                                                    <tr style="font-size:8px; text-align:center;">
                                                        <th colspan="3">Fecha de Emisión: </th>
                                                        <th colspan="2">' . $FECHA_DE_EMISION . ' </th>
                                                        <th colspan="3">Lugar de emisión Departamento: </th>
                                                        <th colspan="2">' . $DEPARTAMENTO . ' </th>
                                                        <th colspan="4">Municipio: </th>
                                                        <th colspan="2">' . $MUNICIPIO_1 . ' </th>
                                                    </tr>';
                            }
                        }
                    } else {


                        $html_documento_ben = $this->generateHtmlDocumentoParametrica($cas_id, '4-RES', 'CI BENIFICIARIO', 'DOCUMENTOS DEL BENIFICIARIO');
                        $BENEFICIARIO_1 = ' <tr style="text-align:center; font-size:8px;">
                        <th colspan="16" class="style85">DATOS DEL BENIFICIARIO </th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="2">Nombre Completo: </th>
                                <th colspan="6">' . $BE_PRIMER_APELLIDO . ' ' . $BE_SEGUNDO_APELLIDO . ' ' . $BE_PRIMER_NOMBRE . ' ' . $BE_SEGUNDO_NOMBRE . ' ' . $BE_APELLIDO_CASADA . '</th>
                                <th colspan="2">' . $BE_TIPO_DOCUMENTO . ':</th>
                                <th colspan="3">' . $BE_CI . ' ' . $BE_COMPLEMENTO . '</th>
                                <th colspan="1">Sexo: </th>
                                <th colspan="2">' . $BE_GENERO . '</th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="2">DIRECCION: </th>
                                <th colspan="7">' . $BE_DIRECCION . '</th>
                                <th colspan="3">Celular: </th>
                                <th colspan="4">' . $BE_CELULAR . '</th>
                            </tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="3">Correo electronico: </th>
                                <th colspan="13">' . $BE_CORREO . '</th>
                            </tr>' . $html_documento_ben;

                        //dd($html_documento_ben);
                    }

                }
                //dd($tablaContenido);
                $tablaContenido = str_replace('#BENEFICIARIO_1#', $BENEFICIARIO_1, $tablaContenido);

                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                $tablaContenido = str_replace('#BENEFICIARIO#', $BENEFICIARIO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_DATOS_APOSTILLA#', $HTML_DATOS_APOSTILLA, $tablaContenido);
                $tablaContenido = str_replace('#html_documento_apostilla#', $html_documento_apostilla, $tablaContenido);
                $tablaContenido = str_replace('#HTML_DATOS_PODER#', $HTML_DATOS_PODER, $tablaContenido);

                $tablaContenido = str_replace('#PERIODOS_HABILITAR#', $PERIODOS_HABILITAR, $tablaContenido);
                $html_documento_sol = $this->generateHtmlDocumentoParametrica($cas_id, '4-RES', 'CI SOLICITANTE', 'DOCUMENTOS DEL SOLICITANTE');
                $tablaContenido = str_replace('#DOCUMENTOS_ENTREGADOS_SOL#', $html_documento_sol, $tablaContenido);
                $tablaContenido = str_replace('#NRO_CASO#', $nro_caso, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlDocumentoConstancia($cas_id, $ESTADO_PODER_PRESENTADO, $ESTADO_PODER_PRESENTADO_TEXT, $OBSERVACION_PODER);
                $tablaContenido = str_replace('#DOCUMENTOS_ENTREGADOS#', $html_documento_solicitante, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlDocumentoConstanciaOtrosAdjuntosLegal($cas_id);
                $tablaContenido = str_replace('#DOCUMENTOS_ENTREGADOS_ADJUNTOS#', $html_documento_solicitante, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE CCM') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;"><strong>FORMULARIO DE SOLICITUD DE CCM</strong></td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'Formulario pension por muerte') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">SOLICITUD DE PENSIÓN POR MUERTE</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN POR MUERTE') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">SOLICITUD DE PENSIÓN POR MUERTE</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id); // GESTORA
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);

                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY N° 1582/2024</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id); // GESTORA
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            }
            if ($nombre_doc == 'ADENDA LEY 1582/2024') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE SOLICITUD DE JUBILACION LEY N° 1582/2024</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id); // GESTORA
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $FECHA_HOY_ADENDA = date('d/m/Y');
                $tablaContenido = str_replace('#FECHA_HOY_ADENDA#', $FECHA_HOY_ADENDA, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
                $tablaContenido = str_replace('#DETALLE_ADENDA#', $fila_detalles, $tablaContenido);

                if (empty($sol_primer_nombre)) {
                    $error = array("message" => "sol_primer_nombre está vacío", "code" => 300);
                    return array("data" => '', "codigoRespuesta" => $error);
                }

                $tablaContenido = str_replace('#SOL_PRIMER_NOMBRE#', $sol_primer_nombre, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_NOMBRE#', $sol_segundo_nombre, $tablaContenido);
                $tablaContenido = str_replace('#SOL_PRIMER_APELLIDO#', $sol_primer_apellido, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_APELLIDO#', $sol_segundo_apellido, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO MASA HEREDITARIA' || $nombre_doc == 'FORMULARIO DE SOLICITUD MAHER') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;"> <strong> FORMULARIO DE SOLICITUD DE MASA HEREDITARIA </strong></td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $pbs_Html = $this->generateHtmlSolicitantedoDocumentoPbsHtml($cas_id);
                $tablaContenido = str_replace('#CODIGO_VER_CI#', $pbs_Html, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $html_clasificaion = $this->generateHtmlClasificacion($AS_TIPO_EAP, $PENS_NO_COBRADAS);
                $tablaContenido = str_replace('#CLASIFICAION_MASA_HEREDIATRIA#', $html_clasificaion, $tablaContenido);

                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);

                $html_heredero = $this->generateHtmlHerederos($DECLARATORIA_HEREDEROS);
                $tablaContenido = str_replace('#VALIDACION_HEREDERO#', $html_heredero, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE RENUNCIA DE DERECHO DE SOLICITAR REVISIÓN DE DICTAMEN') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE RENUNCIA DE DERECHO DE SOLICITAR REVISIÓN DE DICTAMEN</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $tablaContenido = str_replace('#generohtmlsol#', $generohtmlsol2, $tablaContenido);
                $tablaContenido = str_replace('#coverturahtml#', $coverturahtmlsol, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumentoAsegurado($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);

                $pbs_Html = $this->generateHtmlSolicitantedoDocumentoPbsHtml($cas_id);
                $tablaContenido = str_replace('#CODIGO_VER#', $pbs_Html, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE RECALIFICACION') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="column3 style69" colspan="22" style="border: none;"><br><br>FORMULARIO DE SOLICITUD DE RECALIFICACION</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRecalificacion($cas_id);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_documento_tecnico_medico = $this->generateHtmlDocumentoTecnico($cas_id);

                $tablaContenido = str_replace('#DOC_MEDICINA#', $html_documento_tecnico_medico, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE REVISION DE DICTAMEN') {

                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE SOLICITUD DE REVISIÓN DE DICTAMEN</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';
                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);
                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_tecnico_medico = $this->generateHtmlDocumentoTecnicoRev($cas_id);
                $tablaContenido = str_replace('#DOC_MEDICINA#', $html_documento_tecnico_medico, $tablaContenido);
                $htmlcadenaAdj = '';
                if ($this->generateHtmlDocumentoTecnicoRevCantidad($cas_id) > 0) {
                    $htmlcadenaAdj = '<td colspan="10" class="style23" >&nbsp;  &nbsp;Se adjunta documentación  SI</td>
                    <td colspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">    X  </td>
                    <td colspan="2" class="style23" >&nbsp;  &nbsp;NO</td>
                    <td colspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">      </td>';
                } else {
                    $htmlcadenaAdj = '<td colspan="10" class="style23" >&nbsp;  &nbsp;Se adjunta documentación  SI</td>
                    <td colspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">      </td>
                    <td colspan="2" class="style23" >&nbsp;  &nbsp;NO</td>
                    <td colspan="2"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">  X    </td>';
                }
                $tablaContenido = str_replace('#htmlcadenaAdj#', $htmlcadenaAdj, $tablaContenido);
            } else if ($nombre_doc == 'FORMULARIO DE REVISION GERENCIA NACIONAL LEGAL') {
                // FORMULARIO PARA LEGALSIP


                $PERIODO_INICIO = '';
                $PERIODO_FIN = '';
                $NRO_TRAMITE = '';
                $FECHA_DE_VALIDACION_LEGAL = '';
                $ESTADO_DERIVACION = '';
                $PROCESO_BASE = '';
                $DATO_PRESTACION = '';
                $TESTIMONIO_JUDICIAL = '';
                $NUMERO_SENTENCIA_AUTO = '';
                $DESCRIPCION_FUNDAMENTACION = '';
                $DATA_MRCHZ = '';
                $VER_FUNDAMENTOS = '';
                $GRILLA_DAHE_LEGAL = '';
                $HA_PAGO_AGUINALDO_LEGAL = '';
                $AS_TIPO_PRESTACIONES = '';
                $AS_TIPO_PRESTACIONES_TI = '';
                $AS_TIPO_EAP_LEGAL = '';
                $EXTRANGERO_PODER = '';

                $HTML_APOSTILLA = '';
                $HTML_EFICIARIO = '';
                $HTML_TITULO = '';
                $HTML_RECHAZO_VALIDADO = '';
                $HTML_RECHAZO_VALIDADO_TITULO = '';
                $HTML_PODER_DATO = '';
                $HTML_PODER_APODERADO = '';
                $HTML_PODER_TITULO = '';
                $HTML_FECHA_VALIDACION = '';
                $tit_testimonio_judicial = '';
                $LIST_DATA_MRCHZ_60 = '';
                $NRO_PODER_SOL_1 = '';
                $DEPARTAMENTO = '';
                $MUNICIPIO_1 = '';
                $APODERADOS_HEREDADOS_LEGAL = '';
                $TESTIMONIO_JUDICIAL_LEGAL = '';
                $BE_COMPLEMENTO = '';
                $BE_PARENTESCO = '';
                $BE_TIPO_DOCUMENTO = '';
                $HTML_COBRO_TITULO = '';
                $HA_PAGOS_SUPENDIDOS_VAL = '';
                $HTML_HA_PAGOS_SUPENDIDOS_VAL = '';
                $HA_PAGO_AGUINALDO_VA = '';
                $HTML_HA_PAGO_AGUINALDO_VA = '';
                $HTML_RAGO_FECHA = '';
                $HA_PAGO_UNICO = '';
                $GRILLA_DAHERDERO = '';
                $HTML_TITLE_PODER = '';
                $GRILLA_DACO = '';



                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == '_CASO_NOMBRE') {
                            $NRO_TRAMITE = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == "FORM_JUB_MES_INI") {
                            $PERIODO_INICIO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == "FORM_JUB_MES_FIN") {
                            $PERIODO_FIN = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == "FECHA_DE_VALIDACION_LEGAL") {
                            $FECHA_DE_VALIDACION_LEGAL = $valores['frm_value'];
                            $fecha_parts = explode("-", $FECHA_DE_VALIDACION_LEGAL);
                            $dia = $fecha_parts[2];
                            $mes = $fecha_parts[1];
                            $anio = $fecha_parts[0];
                            $FECHA_DE_VALIDACION_LEGAL = $dia . '/' . $mes . '/' . $anio;
                        } elseif ($valores['frm_campo'] == "AS_TIPO_EAP_LEGAL") {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                            $PROCESO_BASE = $this->nombreProceso($valores['frm_value']);
                        } elseif ($valores['frm_campo'] == "AS_TIPO_EAP") {
                            $AS_TIPO_PRESTACIONES_TI = $valores['frm_value_label'];
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $DATO_PRESTACION = $this->nombrePrestaciones($valores['frm_value']);
                        } elseif ($valores['frm_campo'] == 'TESTIMONIO_JUDICIAL') {
                            $TESTIMONIO_JUDICIAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'ESTADO_DERIVACION') {
                            $ESTADO_DERIVACION = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'VER_FUNDAMENTOS') {
                            $VER_FUNDAMENTOS = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_AGUINALDO') {
                            $HA_PAGO_AGUINALDO_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DACO') {
                            $GRILLA_DACO = $valores['frm_value'];
                        }
                        //*********************** */
                        elseif ($valores['frm_campo'] == 'BE_TIPO_DOCUMENTO') {
                            $BE_TIPO_DOCUMENTO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_CI') {
                            $BE_CI = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_COMPLEMENTO') {
                            $BE_COMPLEMENTO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == "BE_NACIMIENTO") {
                            $BE_NACIMIENTO = $valores['frm_value'];
                            $fecha_parts = explode("-", $BE_NACIMIENTO);
                            $dia = $fecha_parts[2];
                            $mes = $fecha_parts[1];
                            $anio = $fecha_parts[0];
                            $BE_NACIMIENTO = $dia . '/' . $mes . '/' . $anio;
                        } elseif ($valores['frm_campo'] == 'BE_CUA') {
                            $BE_CUA = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_PRIMER_APELLIDO') {
                            $BE_PRIMER_APELLIDO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_SEGUNDO_APELLIDO') {
                            $BE_SEGUNDO_APELLIDO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_APELLIDO_CASADA') {
                            $BE_APELLIDO_CASADA = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_PRIMER_NOMBRE') {
                            $BE_PRIMER_NOMBRE = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_SEGUNDO_NOMBRE') {
                            $BE_SEGUNDO_NOMBRE = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_PARENTESCO') {
                            $BE_PARENTESCO = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'BE_ESTADO_CIVIL') {
                            $BE_ESTADO_CIVIL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_GENERO') {
                            $BE_GENERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_CELULAR') {
                            $BE_CELULAR = $valores['frm_value'];
                        } //APOSTILLA
                        elseif ($valores['frm_campo'] == 'NUMERO_APOSTILLA') {
                            $NUMERO_APOSTILLA = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'TIENE_PODER') {
                            $TIENE_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                            $EXTRANGERO_PODER_TEXTO = $valores['frm_value_label'];
                        }
                        //****DATOS DEL PODER */
                        elseif ($valores['frm_campo'] == 'NRO_NOTARIA_SOL_1') {
                            $NRO_NOTARIA_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NOMBRE_NOTARIO_SOL_1') {
                            $NOMBRE_NOTARIO_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'FECHA_DE_EMISION') {
                            $FECHA_DE_EMISION = $valores['frm_value'];
                            $fecha_parts = explode("-", $FECHA_DE_EMISION);
                            $dia = $fecha_parts[2];
                            $mes = $fecha_parts[1];
                            $anio = $fecha_parts[0];
                            $FECHA_DE_EMISION = $dia . '/' . $mes . '/' . $anio;
                        } elseif ($valores['frm_campo'] == 'PAIS') {
                            $PAIS = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'MUNICIPIO_1') {
                            $MUNICIPIO_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DEPARTAMENTO') {
                            $DEPARTAMENTO = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGOS_SUPENDIDOS_VAL') {
                            $HA_PAGOS_SUPENDIDOS_VAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_AGUINALDO_VA') {
                            $HA_PAGO_AGUINALDO_VA = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'FORM_JUB_MES_INI') {
                            $FORM_JUB_MES_INI = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'FORM_JUB_MES_FIN') {
                            $FORM_JUB_MES_FIN = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'BE_COMPLEMENTO') {
                            $BE_COMPLEMENTO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_REVOCATORIO') {
                            $NRO_PODER_REVOCATORIO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'ACEPTACION_DE_HERENCIA') {
                            $ACEPTACION_DE_HERENCIA = $valores['frm_value'];
                            $ACEPTACION_DE_HERENCIA_TEXTO = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'NRO_SENTENCIA_RESOLUCION') {
                            $NRO_SENTENCIA_RESOLUCION = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'JUZGADO') {
                            $JUZGADO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_GESTION_AGUINALDO') {
                            $HA_GESTION_AGUINALDO = $valores['frm_value'];
                        }
                    }
                }

                $request = new Request([
                    'agrupador' => 'LEGAL-GRUPO'
                ]);
                // Obtener instancia de LegalAPIController con sus dependencias inyectadas
                $legalApiController = app()->make(LegalAPIController::class);
                // Llamar al método
                $response = $legalApiController->parametricaDeParametricas($request);
                // Obtener datos de la respuesta
                $data = $response->getData();
                // Asumimos que ya tienes $parametricasLegal como array
                $parametricasLegal = $response->getData(true)['data']; // O como sea que recibas tu array
                // Buscar el objeto donde 'pdp_parameter_name' es 'GRUPO-COBRO'
                $grupoCobro = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-COBRO');
                // Obtener los valores si existen
                $parametricasLegalGrupoCobro = $grupoCobro['pdp_parameter_value'] ?? [];
                // dd($AS_TIPO_PRESTACIONES);
                if (in_array($AS_TIPO_PRESTACIONES, $parametricasLegalGrupoCobro)) {


                    //**************************BEBIFICIARIO************************** */
                    /* $HTML_EFICIARIO .= '
                                            <tr class="row33">
                                                 <td class="style43" colspan="28"> DATOS DEL BENIFICIARIO </td>
                                             </tr>
                                             <tr class="row35">
                                                 <td class="style16" colspan="6">Primer Apellido</td>
                                                 <td class="style17" colspan="6">Segundo Apellido</td>
                                                 <td class="style17" colspan="6">Apellido Casada</td>
                                                 <td class="style17" colspan="5">Primer Nombre</td>
                                                 <td class="style18" colspan="5">Segundo Nombre</td>
                                             </tr>
                                             <tr class="row36">
                                                 <td class="style84" colspan="6">' . $BE_PRIMER_APELLIDO . '</td>
                                                 <td class="style85" colspan="6">' . $BE_SEGUNDO_APELLIDO . '</td>
                                                 <td class="style85" colspan="6">' . $BE_APELLIDO_CASADA . '</td>
                                                 <td class="style85" colspan="5">' . $BE_PRIMER_NOMBRE . '</td>
                                                 <td class="style86" colspan="5">' . $BE_SEGUNDO_NOMBRE . '</td>
                                             </tr>
                                             <tr class="row37">
                                                 <td class="style16" colspan="6">Tipo Doc. Ident.</td>
                                                 <td class="style17" colspan="5">N° Doc. Identidad</td>
                                                 <td class="style17" colspan="3">Compl. CI</td>
                                                 <td class="style17" colspan="4">Parentesco</td>
                                                 <td class="style17" colspan="5">Sexo</td>
                                                 <td class="style18" colspan="7">Teléfono/Celular</td>
                                             </tr>
                                             <tr class="row38">
                                                 <td class="style84" colspan="6">' . $BE_TIPO_DOCUMENTO . '</td>
                                                 <td class="style85" colspan="5">' . $BE_CI . '</td>
                                                 <td class="style85" colspan="3">' . $BE_COMPLEMENTO . '</td>
                                                 <td class="style85" colspan="4">' . $BE_PARENTESCO . '</td>
                                                 <td class="style86" colspan="5">' . $BE_GENERO . '</td>
                                                 <td class="style86" colspan="7">' . $BE_CELULAR . '</td>
                                             </tr>

                                            ';*/

                    $HTML_FECHA_VALIDACION = '<tr class="row33">
                                                 <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                                             </tr><tr class="row35">
                                                 <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                                             </tr>';
                    //****************************PODER LEGAL********************** */



                    $HTML_PODER_TITULO = '<tr class="row33">
                                                                     <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                                                 </tr>
                                                                  <tr class="row35">
                                                    <td class="style16"  colspan="14">Testimonio: </td>
                                                    <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                    if ($EXTRANGERO_PODER == '1') {

                        $HTML_PODER_DATO = '
                                                                 <tr class="row35">
                                                                    <td class="style16"  colspan="5">Nro. Poder:</td>
                                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                                     <td class="style16" colspan="3">Nombre del Notario: </td>
                                                                     <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                                     <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                                     <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                                     <td class="style16" colspan="4">País: </td>
                                                                     <td class="style85" colspan="4">' . $PAIS . '</td>
                                                                 </tr>';
                    } else {

                        $HTML_PODER_DATO = '
                                                <tr class="row35">
                                                    <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                                    <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                                    <td class="style16" colspan="5">Nombre del Notario: </td>
                                                    <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                    <td class="style16" colspan="4">Nro. Poder: </td>
                                                    <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                                </tr>
                                                <tr class="row35">
                                                    <td class="style16" colspan="5">Fecha de Emisión: </td>
                                                    <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                                    <td class="style16"  colspan="5">Departamento: </td>
                                                    <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                                    <td class="style16" colspan="4">Municipio: </td>
                                                    <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                                </tr>';
                    }
                    if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {
                        $title_dahe_apoderados_herederos = ' APODERADOS';
                        $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlApoderadosHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHE_LEGAL), $tablaContenido);
                    } else {
                        $title_dahe_apoderados_herederos = '';
                        $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                    }

                    if (!empty($GRILLA_DACO) && $GRILLA_DACO != '[]') {
                        $title_dahe_apoderados_herederos = ' BENIFICIARIO';
                        $tablaContenido = str_replace('#HTML_EFICIARIO#', $this->generateHtmlBenificiarioLegal($title_dahe_apoderados_herederos, $GRILLA_DACO), $tablaContenido);
                    } else {
                        $title_dahe_apoderados_herederos = '';
                        $tablaContenido = str_replace('#HTML_EFICIARIO#', '', $tablaContenido);
                    }




                    //***************FIN*************PODER LEGAL********************** */
                    //**************AREA DE TESTIMONIO**************************** */

                    if ($TESTIMONIO_JUDICIAL == 'SI') {
                        $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                    } else {
                        $tit_testimonio_judicial = '';
                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                    }
                    //**************FIN DE AREA DE TESTIMONIO**************************** */
                    //**************MOTIVO DE RECHAZO**************************** */
                    $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                                             <td class="style43" colspan="28">VALIDACIÓN O RECHAZO</td>
                                             </tr>
                                             ';
                    if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                        if ($VER_FUNDAMENTOS == '1') {
                            $DATA_MRCHZ_60 = '';
                            $DESCRIPCION_FUNDAMENTACION_60 = '';

                            foreach ($impData1 as $valores) {
                                if (isset($valores['frm_value'])) {
                                    if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                        $DATA_MRCHZ_60 = $valores['frm_value'];
                                    } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                        $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                    }
                                }
                            }

                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                                 <td class="style16" colspan="28">RECHAZADO</td>
                                                 </tr>
                                                 <tr class="row33">
                                                     <td class="style43" colspan="28"> OBSERVACIONES </td>
                                                 </tr><tr class="row35">
                                                     <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                                                 </tr>';
                        } elseif ($VER_FUNDAMENTOS == '2') {

                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                                 <td class="style16" colspan="28">RECHAZADO</td>
                                             </tr>
                                             <tr class="row33">
                                                 <td class="style43" colspan="28"> OBSERVACIONES </td>
                                             </tr><tr class="row35">
                                                 <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                                             </tr>';
                        } elseif ($VER_FUNDAMENTOS == '3') {

                            $DATA_MRCHZ_60 = '';
                            $DESCRIPCION_FUNDAMENTACION_60 = '';

                            foreach ($impData1 as $valores) {
                                if (isset($valores['frm_value'])) {
                                    if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                        $DATA_MRCHZ_60 = $valores['frm_value'];
                                    } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                        $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                    }
                                }
                            }

                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                                 <td class="style16" colspan="28">RECHAZADO</td>
                                                 </tr>
                                                 <tr class="row33">
                                                     <td class="style43" colspan="28"> OBSERVACIONES </td>
                                                 </tr><tr class="row35">
                                                     <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                                                 </tr>';
                        }
                    } else {
                        $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                                 <td class="style16" colspan="28">VALIDADO</td>
                                             </tr>
                                             <tr class="row33">
                                                 <td class="style43" colspan="28"> OBSERVACIONES </td>
                                             </tr><tr class="row35">
                                                 <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                                             </tr>';
                    }
                    //**************FIN DF MOTIVO DE RECHAZO**************************** */

                    //**************AREA DE COBRO****************************  $HTML_COBRO_TITULO = '';
                    $HTML_COBRO_TITULO = '  <tr class="row33">
                            <td class="style43" colspan="28"> DATOS PARA EL PAGO</td>
                            </tr>
                            ';
                    if ($HA_PAGOS_SUPENDIDOS_VAL == '1') {
                        $HTML_HA_PAGOS_SUPENDIDOS_VAL = '<tr class="row35">
                                                     <td class="style16" colspan="14">PAGOS SUSPENDIDOS Y REVERTIDOS</td>
                                                     <td class="style85" colspan="14">SI</td>
                                                 </tr>';
                    }
                    if ($HA_PAGO_AGUINALDO_VA == '1') {
                        $HTML_HA_PAGO_AGUINALDO_VA = '<tr class="row35">
                                                     <td class="style16" colspan="14">PAGO CON AGUINALDO</td>
                                                     <td class="style85" colspan="14">SI (Con Aguinaldo ' . $HA_GESTION_AGUINALDO . ')</td>
                                                 </tr>';
                    }
                    if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201, 202])) {
                        if ($HA_PAGO_UNICO == "1") {
                            $HTML_RAGO_FECHA = '<tr class="row35">
                                        <td class="style16" colspan="14">PERIODOS DE PAGOS</td>
                                        <td class="style85" colspan="14">UNICO</td>
                                    </tr>';
                        } else {
                            $HTML_RAGO_FECHA = '<tr class="row35">
                                    <td class="style16" colspan="14">PERIODOS DE PAGOS</td>
                                    <td class="style85" colspan="14">' . $FORM_JUB_MES_INI . ' A ' . $FORM_JUB_MES_FIN . '</td>
                                </tr>';
                        }
                    } else {
                        $HTML_RAGO_FECHA = '<tr class="row35">
                                    <td class="style16" colspan="14">PERIODOS DE PAGOS</td>
                                    <td class="style85" colspan="14">' . $FORM_JUB_MES_INI . ' A ' . $FORM_JUB_MES_FIN . '</td>
                                </tr>';
                    }




                    //**************FIN AREA DE COBRO**************************** */





                } else {
                    $grupo3 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-3');
                    $grupo3Values = $grupo3['pdp_parameter_value'] ?? [];
                    $grupo1 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-1');
                    $grupo1Values = $grupo1['pdp_parameter_value'] ?? [];
                    $grupo4 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-4');
                    $grupo4Values = $grupo4['pdp_parameter_value'] ?? [];
                    $grupo2 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-2');
                    $grupo2Values = $grupo2['pdp_parameter_value'] ?? [];
                    $grupo5 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-5');
                    $grupo5Values = $grupo5['pdp_parameter_value'] ?? [];
                    $grupo6 = collect($parametricasLegal)->firstWhere('pdp_parameter_name', 'GRUPO-6');
                    $grupo6Values = $grupo6['pdp_parameter_value'] ?? [];
                    //dd($AS_TIPO_PRESTACIONES);
                    if (in_array($AS_TIPO_PRESTACIONES, $grupo3Values)) //b) Validación de Poderes de inicio, seguimiento conclusión de trámite
                    {

                        //dd(1);
                        //**************************BEBIFICIARIO************************** */
                        /* $HTML_EFICIARIO .= '
                        <tr class="row33">
                             <td class="style43" colspan="28"> DATOS DEL BENIFICIARIO </td>
                         </tr>
                         <tr class="row35">
                             <td class="style16" colspan="6">Primer Apellido</td>
                             <td class="style17" colspan="6">Segundo Apellido</td>
                             <td class="style17" colspan="6">Apellido Casada</td>
                             <td class="style17" colspan="5">Primer Nombre</td>
                             <td class="style18" colspan="5">Segundo Nombre</td>
                         </tr>
                         <tr class="row36">
                             <td class="style84" colspan="6">' . $BE_PRIMER_APELLIDO . '</td>
                             <td class="style85" colspan="6">' . $BE_SEGUNDO_APELLIDO . '</td>
                             <td class="style85" colspan="6">' . $BE_APELLIDO_CASADA . '</td>
                             <td class="style85" colspan="5">' . $BE_PRIMER_NOMBRE . '</td>
                             <td class="style86" colspan="5">' . $BE_SEGUNDO_NOMBRE . '</td>
                         </tr>
                         <tr class="row37">
                             <td class="style16" colspan="6">Tipo Doc. Ident.</td>
                             <td class="style17" colspan="5">N° Doc. Identidad</td>
                             <td class="style17" colspan="3">Compl. CI</td>
                             <td class="style17" colspan="4">Parentesco</td>
                             <td class="style17" colspan="5">Sexo</td>
                             <td class="style18" colspan="7">Teléfono/Celular</td>
                         </tr>
                         <tr class="row38">
                             <td class="style84" colspan="6">' . $BE_TIPO_DOCUMENTO . '</td>
                             <td class="style85" colspan="5">' . $BE_CI . '</td>
                             <td class="style85" colspan="3">' . $BE_COMPLEMENTO . '</td>
                             <td class="style85" colspan="4">' . $BE_PARENTESCO . '</td>
                             <td class="style86" colspan="5">' . $BE_GENERO . '</td>
                             <td class="style86" colspan="7">' . $BE_CELULAR . '</td>
                         </tr>

                         <tr class="row23">
                             <td class="style40" colspan="28"></td>
                         </tr>';*/



                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                             <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                         </tr><tr class="row35">
                             <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                         </tr>';
                        //****************************PODER LEGAL********************** */



                        $HTML_PODER_TITULO = '<tr class="row33">
                                                 <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                             </tr>
                                              <tr class="row35">
                                                <td class="style16"  colspan="14">Testimonio: </td>
                                                <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                        if ($EXTRANGERO_PODER == '1') {

                            $HTML_PODER_DATO = '
                                             <tr class="row35">
                                                  <td class="style16"  colspan="5">Nro. Poder:</td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                 <td class="style16" colspan="3">Nombre del Notario: </td>
                                                 <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                 <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                 <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                 <td class="style16" colspan="4">País: </td>
                                                 <td class="style85" colspan="4">' . $PAIS . '</td>
                                             </tr>';
                        } else {

                            $HTML_PODER_DATO = '
                                            <tr class="row35">
                                                <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                                <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                                <td class="style16" colspan="5">Nombre del Notario: </td>
                                                <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                <td class="style16" colspan="4">Nro. Poder: </td>
                                                <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                            </tr>
                                            <tr class="row35">
                                                <td class="style16" colspan="5">Fecha de Emisión: </td>
                                                <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                                <td class="style16"  colspan="5">Departamento: </td>
                                                <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                                <td class="style16" colspan="4">Municipio: </td>
                                                <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                            </tr>';
                        }
                        if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {
                            $title_dahe_apoderados_herederos = ' APODERADOS';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlApoderadosHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHE_LEGAL), $tablaContenido);
                        } else {
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                        }
                        if (!empty($GRILLA_DACO) && $GRILLA_DACO != '[]') {
                            $title_dahe_apoderados_herederos = ' BENIFICIARIO';
                            $tablaContenido = str_replace('#HTML_EFICIARIO#', $this->generateHtmlBenificiarioLegal($title_dahe_apoderados_herederos, $GRILLA_DACO), $tablaContenido);
                        } else {
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#HTML_EFICIARIO#', '', $tablaContenido);
                        }




                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                         <td class="style43" colspan="28">VALIDACIÓN O RECHAZO</td>
                         </tr>
                         ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                             <td class="style16" colspan="28">RECHAZADO</td>
                             </tr>
                             <tr class="row33">
                                 <td class="style43" colspan="28"> OBSERVACIONES </td>
                             </tr><tr class="row35">
                                 <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                             </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                             <td class="style16" colspan="28">RECHAZADO</td>
                         </tr>
                         <tr class="row33">
                             <td class="style43" colspan="28"> OBSERVACIONES </td>
                         </tr><tr class="row35">
                             <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                         </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                             <td class="style16" colspan="28">RECHAZADO</td>
                             </tr>
                             <tr class="row33">
                                 <td class="style43" colspan="28"> OBSERVACIONES </td>
                             </tr><tr class="row35">
                                 <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                             </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                             <td class="style16" colspan="28">VALIDADO</td>
                         </tr>
                         <tr class="row33">
                             <td class="style43" colspan="28"> OBSERVACIONES </td>
                         </tr><tr class="row35">
                             <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                         </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */





                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo4Values)) {
                        // dd(2);

                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                            <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                        </tr>';
                        //****************************PODER LEGAL********************** */





                        $HTML_ACEPTACION_DE_HERENCIA = "";
                        if ($ACEPTACION_DE_HERENCIA == "2") {
                            $HTML_PODER_TITULO = '<tr class="row33">
                            <td class="style43" colspan="28"> DATOS DEL PODER </td>
                        </tr>';
                            $HTML_ACEPTACION_DE_HERENCIA = '<tr class="row35">
                                                                <td class="style16" colspan="5">Aceptación de Herencia: </td>
                                                                <td class="style85" colspan="5">' . $ACEPTACION_DE_HERENCIA_TEXTO . ' </td>
                                                                <td class="style16" colspan="5">Nro. de Sentencia/Resolución: </td>
                                                                <td class="style85" colspan="5">' . $NRO_SENTENCIA_RESOLUCION . ' </td>
                                                                <td class="style16" colspan="4">Juzgado: </td>
                                                                <td class="style85" colspan="4">' . $JUZGADO . ' </td>
                                                           </tr>';
                        }
                        else {
                            $HTML_PODER_TITULO = '<tr class="row33">
                            <td class="style43" colspan="28"> DATOS DEL PODER </td>
                        </tr>
                        <tr class="row35">
                            <td class="style16"  colspan="14">Testimonio: </td>
                            <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                        </tr>';
                            if ($EXTRANGERO_PODER == '1') {
                                $HTML_PODER_DATO = '
                                                <tr class="row35">
                                                    <td class="style16"  colspan="5">Nro. Poder: </td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Nombre del Notario: </td>
                                                    <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                    <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                    <td class="style16" colspan="4">País: </td>
                                                    <td class="style85" colspan="4">' . $PAIS . '</td>
                                                </tr>';
                            } else {
                                $HTML_PODER_DATO = '
                                                <tr class="row35">
                                                    <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                                    <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                                    <td class="style16" colspan="5">Nombre del Notario: </td>
                                                    <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                    <td class="style16" colspan="4">Nro. Poder: </td>
                                                    <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                                </tr>
                                                <tr class="row35">
                                                    <td class="style16" colspan="5">Fecha de Emisión: </td>
                                                    <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                                    <td class="style16"  colspan="5">Departamento: </td>
                                                    <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                                    <td class="style16" colspan="4">Municipio: </td>
                                                    <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                                </tr>';
                            }
                        }
                        $HTML_PODER_DATO =  $HTML_PODER_DATO . $HTML_ACEPTACION_DE_HERENCIA;

                        if ($GRILLA_DAHERDERO != '') {

                            $title_dahe_apoderados_herederos = ' HEREDEROS';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHERDERO), $tablaContenido);
                        } else {
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                        }




                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                        <td class="style43" colspan="28">VALIDACIÓN O RECHAZO</td>
                        </tr>
                        ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                        </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">VALIDADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                        </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */






                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo6Values)) {


                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                            <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                        </tr>';
                        //****************************PODER LEGAL********************** */



                        $HTML_PODER_TITULO = '<tr class="row33">
                                                <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                            </tr>
                                             <tr class="row35">
                                                <td class="style16"  colspan="14">Testimonio: </td>
                                                <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                        if ($EXTRANGERO_PODER == '1') {

                            $HTML_PODER_DATO = '
                                            <tr class="row35">
                                                <td class="style16"  colspan="5">Nro. Poder:</td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                <td class="style16" colspan="5">Nro. Poder revocado: </td>
                                                <td class="style85" colspan="5">' . $NRO_PODER_REVOCATORIO . ' </td>
                                                <td class="style16" colspan="5">Nombre del Notario: </td>
                                                <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . '</td>

                                            </tr> <tr class="row35">

                                                <td class="style16" colspan="7">Fecha de Emisión: </td>
                                                <td class="style85" colspan="7">' . $FECHA_DE_EMISION . '</td>
                                                <td class="style16" colspan="7">País: </td>
                                                <td class="style85" colspan="7">' . $PAIS . '</td>
                                            </tr>';
                        } else {

                            $HTML_PODER_DATO = '
                                            <tr class="row35">
                                                <td class="style16"  colspan="4">Nro. Notaría de la Fe Pública: </td>
                                                <td class="style85" colspan="4">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                                <td class="style16" colspan="3">Nro. Poder revocado: </td>
                                                <td class="style85" colspan="3">' . $NRO_PODER_REVOCATORIO . ' </td>
                                                <td class="style16" colspan="4">Nombre del Notario: </td>
                                                <td class="style85" colspan="4">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                <td class="style16" colspan="3">Nro. Poder: </td>
                                                <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . '</td>
                                            </tr>
                                            <tr class="row35">
                                                <td class="style16" colspan="5">Fecha de Emisión: </td>
                                                <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                                <td class="style16"  colspan="5">Departamento: </td>
                                                <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                                <td class="style16" colspan="4">Municipio: </td>
                                                <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                            </tr>';
                        }


                        $title_dahe_apoderados_herederos = '';
                        $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);




                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                        <td class="style43" colspan="28">VALIDACIÓN O RECHAZO</td>
                        </tr>
                        ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                        </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">VALIDADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                        </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */






                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo1Values)) {

                        // dd(4);



                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                            <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                        </tr>';
                        //****************************PODER LEGAL********************** */



                        $HTML_PODER_TITULO = '<tr class="row33">
                                                <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                            </tr>
                                             <tr class="row35">
                                                <td class="style16"  colspan="14">Testimonio: </td>
                                                <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                        if ($EXTRANGERO_PODER == '1') {

                            $HTML_PODER_DATO = '
                                            <tr class="row35">
                                                 <td class="style16"  colspan="5">Nro. Poder:</td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                <td class="style16" colspan="3">Nombre del Notario: </td>
                                                <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                <td class="style16" colspan="4">País: </td>
                                                <td class="style85" colspan="4">' . $PAIS . '</td>
                                            </tr>';
                        } else {

                            $HTML_PODER_DATO = '
                                            <tr class="row35">
                                                <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                                <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                                <td class="style16" colspan="5">Nombre del Notario: </td>
                                                <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                <td class="style16" colspan="4">Nro. Poder: </td>
                                                <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                            </tr>
                                            <tr class="row35">
                                                <td class="style16" colspan="5">Fecha de Emisión: </td>
                                                <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                                <td class="style16"  colspan="5">Departamento: </td>
                                                <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                                <td class="style16" colspan="4">Municipio: </td>
                                                <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                            </tr>';
                        }


                        if ($GRILLA_DAHERDERO != '') {

                            $title_dahe_apoderados_herederos = ' HEREDEROS';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHERDERO), $tablaContenido);
                        } else {
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                        }
                        if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                            $HTML_TITLE_PODER = $this->generateHtmlApoderadosHeredadosLegal(' APODERADOS', $GRILLA_DAHE_LEGAL);
                            // $tablaContenido = str_replace('#HTML_TITLE_PODER#', $this->generateHtmlApoderadosHeredadosLegal($HTML_TITLE_PODER, $GRILLA_DAHE_LEGAL), $tablaContenido);

                        } else {
                            $HTML_TITLE_PODER = '';
                            //$tablaContenido = str_replace('#HTML_TITLE_PODER#', '', $tablaContenido);
                        }






                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                        <td class="style43" colspan="28">VALIDACIÓN O RECHAZO</td>
                        </tr>
                        ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                        </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">VALIDADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                        </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */





                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo2Values)) //apostilla  APODERADOS_HEREDADOS_LEGAL
                    {
                        // dd(5);

                        $HTML_APOSTILLA = '  <tr class="row33">
                        <td class="style43" colspan="28"> DATOS DE LA APOSTILLA </td>
                            </tr>
                            <tr class="row35">
                                <td class="style16" colspan="14">Nro. Apostilla</td>
                                <td class="style85" colspan="14">' . $NUMERO_APOSTILLA . '</td>
                            </tr>';
                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                                                    <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                                                </tr><tr class="row35">
                                <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                            </tr>';

                        //****************************PODER LEGAL********************** */

                        if ($TIENE_PODER == '1') //
                        {
                            $HTML_PODER_TITULO = '<tr class="row33">
                                                    <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                                </tr>
                                                 <tr class="row35">
                                                <td class="style16"  colspan="14">Testimonio: </td>
                                                <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                            if ($EXTRANGERO_PODER == '1') {

                                $HTML_PODER_DATO = '
                                                <tr class="row35">
                                                    <td class="style16"  colspan="5">Nro. Poder:</td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Nombre del Notario: </td>
                                                    <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                    <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                    <td class="style16" colspan="4">País: </td>
                                                    <td class="style85" colspan="4">' . $PAIS . '</td>
                                                </tr>';
                            } else {

                                $HTML_PODER_DATO = '
                                <tr class="row35">
                                    <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                    <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                    <td class="style16" colspan="5">Nombre del Notario: </td>
                                    <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                    <td class="style16" colspan="4">Nro. Poder: </td>
                                    <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                </tr>
                                <tr class="row35">
                                    <td class="style16" colspan="5">Fecha de Emisión: </td>
                                    <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                    <td class="style16"  colspan="5">Departamento: </td>
                                    <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                    <td class="style16" colspan="4">Municipio: </td>
                                    <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                </tr>';
                            }
                            if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {
                                $title_dahe_apoderados_herederos = ' APODERADOS';
                                $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlApoderadosHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHE_LEGAL), $tablaContenido);
                            } else {
                                $title_dahe_apoderados_herederos = '';
                                $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                            }
                        } else {
                            $HTML_PODER_TITULO = '';
                            $HTML_PODER_DATO = '';
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                        }
                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                            <td class="style43" colspan="28"> MOTIVO DE RECHAZO </td>
                            </tr>
                            ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                                </tr><tr class="row35">
                                    <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                                </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                                </tr><tr class="row35">
                                    <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                                </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">NINGUNO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28"><BR><BR>APROBADO Y FIRMADO PARA PROSEGUIR APOSTILLADO ANTE EL Ministerio de RR.EE.<BR></td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                            </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */
                    } else if (in_array($AS_TIPO_PRESTACIONES, $grupo5Values)) {
                        // dd(6);


                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                                                    <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                                                </tr><tr class="row35">
                                <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                            </tr>';

                        //****************************PODER LEGAL********************** */

                        $HTML_PODER_TITULO = '<tr class="row33">
                                                    <td class="style43" colspan="28"> DATOS DEL PODER </td>
                                                </tr>
                                                 <tr class="row35">
                                                <td class="style16"  colspan="14">Testimonio: </td>
                                                <td class="style85" colspan="14">' . $EXTRANGERO_PODER_TEXTO . ' </td>
                                            </tr>';
                        if ($EXTRANGERO_PODER == '1') {

                            $HTML_PODER_DATO = '
                                                <tr class="row35">
                                                    <td class="style16"  colspan="5">Nro. Poder: </td>
                                                    <td class="style85" colspan="3">' . $NRO_PODER_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Nombre del Notario: </td>
                                                    <td class="style85" colspan="3">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                                    <td class="style16" colspan="3">Fecha de Emisión: </td>
                                                    <td class="style85" colspan="3">' . $FECHA_DE_EMISION . '</td>
                                                    <td class="style16" colspan="4">País: </td>
                                                    <td class="style85" colspan="4">' . $PAIS . '</td>
                                                </tr>';
                        } else {

                            $HTML_PODER_DATO = '
                                <tr class="row35">
                                    <td class="style16"  colspan="5">Nro. Notaría de la Fe Pública: </td>
                                    <td class="style85" colspan="5">' . $NRO_NOTARIA_SOL_1 . ' </td>
                                    <td class="style16" colspan="5">Nombre del Notario: </td>
                                    <td class="style85" colspan="5">' . $NOMBRE_NOTARIO_SOL_1 . ' </td>
                                    <td class="style16" colspan="4">Nro. Poder: </td>
                                    <td class="style85" colspan="4">' . $NRO_PODER_SOL_1 . '</td>
                                </tr>
                                <tr class="row35">
                                    <td class="style16" colspan="5">Fecha de Emisión: </td>
                                    <td class="style85" colspan="5">' . $FECHA_DE_EMISION . '</td>
                                    <td class="style16"  colspan="5">Departamento: </td>
                                    <td class="style85" colspan="5">' . $DEPARTAMENTO . ' </td>
                                    <td class="style16" colspan="4">Municipio: </td>
                                    <td class="style85" colspan="4">' . $MUNICIPIO_1 . ' </td>

                                </tr>';
                        }

                        //  dd($GRILLA_DAHE_LEGAL);
                        if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {
                            $title_dahe_apoderados_herederos = ' APODERADOS';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlApoderadosHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHE_LEGAL), $tablaContenido);
                        } else {
                            $title_dahe_apoderados_herederos = '';
                            $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                        }

                        //***************FIN*************PODER LEGAL********************** */
                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                            <td class="style43" colspan="28"> MOTIVO DE RECHAZO </td>
                            </tr>
                            ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                                </tr><tr class="row35">
                                    <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                                </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                                </tr><tr class="row35">
                                    <td class="style16" colspan="28">“<B>Las revisiones y validaciones que realiza la gerencia nacional legal, son de orden completamente jurídica en observancia de los requisitos de forma y fondo establecida en la Ley del Notariado (ley 483) y su reglamento D.S 2189.</B> la recepción hasta su conclusión en una solicitud de prestación u otro tramite, es de entera responsabilidad de las plataformas de atención al cliente ya sea en oficina regional o nacional.”</td>
                                </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                            <td class="style16" colspan="28">VALIDADO</td>
                        </tr>
                        <tr class="row33">
                            <td class="style43" colspan="28"> OBSERVACIONES </td>
                        </tr><tr class="row35">
                            <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                        </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */
                    } else {
                        // dd(7);

                        //**************************BEBIFICIARIO************************** */
                        $HTML_EFICIARIO .= '
                        <tr class="row33">
                             <td class="style43" colspan="28"> DATOS DEL BENIFICIARIO </td>
                         </tr>
                         <tr class="row35">
                             <td class="style16" colspan="6">Primer Apellido</td>
                             <td class="style17" colspan="6">Segundo Apellido</td>
                             <td class="style17" colspan="6">Apellido Casada</td>
                             <td class="style17" colspan="5">Primer Nombre</td>
                             <td class="style18" colspan="5">Segundo Nombre</td>
                         </tr>
                         <tr class="row36">
                             <td class="style84" colspan="6">' . $BE_PRIMER_APELLIDO . '</td>
                             <td class="style85" colspan="6">' . $BE_SEGUNDO_APELLIDO . '</td>
                             <td class="style85" colspan="6">' . $BE_APELLIDO_CASADA . '</td>
                             <td class="style85" colspan="5">' . $BE_PRIMER_NOMBRE . '</td>
                             <td class="style86" colspan="5">' . $BE_SEGUNDO_NOMBRE . '</td>
                         </tr>
                         <tr class="row37">
                             <td class="style16" colspan="6">Tipo Doc. Ident.</td>
                             <td class="style17" colspan="5">N° Doc. Identidad</td>
                             <td class="style17" colspan="3">Compl. CI</td>
                             <td class="style17" colspan="4">Parentesco</td>
                             <td class="style17" colspan="5">Sexo</td>
                             <td class="style18" colspan="7">Teléfono/Celular</td>
                         </tr>
                         <tr class="row38">
                             <td class="style84" colspan="6">' . $BE_TIPO_DOCUMENTO . '</td>
                             <td class="style85" colspan="5">' . $BE_CI . '</td>
                             <td class="style85" colspan="3">' . $BE_COMPLEMENTO . '</td>
                             <td class="style85" colspan="4">' . $BE_PARENTESCO . '</td>
                             <td class="style86" colspan="5">' . $BE_GENERO . '</td>
                             <td class="style86" colspan="7">' . $BE_CELULAR . '</td>
                         </tr>

                         <tr class="row23">
                             <td class="style40" colspan="28"></td>
                         </tr>';

                        $HTML_FECHA_VALIDACION = '<tr class="row33">
                             <td class="style43" colspan="28"> FECHA DE VALIDACIÓN </td>
                         </tr><tr class="row35">
                             <td class="style16" colspan="28"><BR>' . $FECHA_DE_VALIDACION_LEGAL . '</td>
                         </tr>';
                        //****************************FIN DEL BENIFICIARIO********************** */



                        //**************AREA DE TESTIMONIO**************************** */

                        if ($TESTIMONIO_JUDICIAL == 'SI') {
                            $tit_testimonio_judicial = ' TESTIMONIO JUDICIAL';

                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        } else {
                            $tit_testimonio_judicial = '';
                            $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        }
                        //**************FIN DE AREA DE TESTIMONIO**************************** */
                        //**************MOTIVO DE RECHAZO**************************** */
                        $HTML_RECHAZO_VALIDADO_TITULO = '  <tr class="row33">
                            <td class="style43" colspan="28"> VALIDACIÓN O RECHAZO</td>
                            </tr>
                            ';
                        if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                            if ($VER_FUNDAMENTOS == '1') {
                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '</td>
                                </tr>';
                            } elseif ($VER_FUNDAMENTOS == '2') {

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28">' . $DESCRIPCION_FUNDAMENTACION . '</td>
                            </tr>';
                            } elseif ($VER_FUNDAMENTOS == '3') {

                                $DATA_MRCHZ_60 = '';
                                $DESCRIPCION_FUNDAMENTACION_60 = '';

                                foreach ($impData1 as $valores) {
                                    if (isset($valores['frm_value'])) {
                                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                                            $DATA_MRCHZ_60 = $valores['frm_value'];
                                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                                        }
                                    }
                                }

                                $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">RECHAZADO</td>
                                </tr>
                                <tr class="row33">
                                    <td class="style43" colspan="28"> OBSERVACIONES </td>
                                </tr><tr class="row35">
                                    <td class="style1689" colspan="28">' . $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60) . '<BR>' . $DESCRIPCION_FUNDAMENTACION . '</td>
                                </tr>';
                            }
                        } else {
                            $HTML_RECHAZO_VALIDADO = '<tr class="row35">
                                <td class="style16" colspan="28">VALIDADO</td>
                            </tr>
                            <tr class="row33">
                                <td class="style43" colspan="28"> OBSERVACIONES </td>
                            </tr><tr class="row35">
                                <td class="style16" colspan="28"><BR><BR>NINGUNO<BR></td>
                            </tr>';
                        }
                        //**************FIN DF MOTIVO DE RECHAZO**************************** */

                        $title_dahe_apoderados_herederos = '';
                        $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);
                    }
                }
                // dd($AS_TIPO_PRESTACIONES );

                $html .= '
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE REVISIÓN TRÁMITE DE  ' . $NRO_TRAMITE . '</td>
                    <td class="column23" colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $tablaContenido = str_replace('#HTML_EFICIARIO#', $HTML_EFICIARIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $HTML_TITULO = ' <tr class="row33">
                <td class="style43" colspan="28"> TRAMITE SOLICITADO </td>
                    </tr>
                    <tr class="row35">
                        <td class="style85" colspan="28">' . $HTML_FRASE_TITULO_CASO  . '</td>
                    </tr>';

                $tablaContenido = str_replace('#HTML_TITULO#', $HTML_TITULO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_APOSTILLA#', $HTML_APOSTILLA, $tablaContenido);
                $tablaContenido = str_replace('#HTML_FECHA_VALIDACION#', $HTML_FECHA_VALIDACION, $tablaContenido);
                $tablaContenido = str_replace('#HTML_PODER_TITULO#', $HTML_PODER_TITULO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_PODER_DATO#', $HTML_PODER_DATO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_RECHAZO_VALIDADO_TITULO#', $HTML_RECHAZO_VALIDADO_TITULO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_RECHAZO_VALIDADO#', $HTML_RECHAZO_VALIDADO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_COBRO_TITULO#', $HTML_COBRO_TITULO, $tablaContenido);
                $tablaContenido = str_replace('#HTML_HA_PAGOS_SUPENDIDOS_VAL#', $HTML_HA_PAGOS_SUPENDIDOS_VAL, $tablaContenido);
                $tablaContenido = str_replace('#HTML_HA_PAGO_AGUINALDO_VA#', $HTML_HA_PAGO_AGUINALDO_VA, $tablaContenido);
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#', $HTML_RAGO_FECHA, $tablaContenido);
                $tablaContenido = str_replace('#HTML_TITLE_PODER#', $HTML_TITLE_PODER, $tablaContenido);

                //  $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id);
                // $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                // $tablaContenido = str_replace('#PERIODO_INICIO#', $PERIODO_INICIO, $tablaContenido);
                // $tablaContenido = str_replace('#PERIODO_FIN#', $PERIODO_FIN, $tablaContenido);

                //$tablaContenido = str_replace('#DIA#', $dia, $tablaContenido);
                //$tablaContenido = str_replace('#MES#', $mes, $tablaContenido);
                //$tablaContenido = str_replace('#ANIO#', $anio, $tablaContenido);

                //$tablaContenido = str_replace('#PROCESO_BASE#', $PROCESO_BASE, $tablaContenido);
                //$tablaContenido = str_replace('#DATO_PRESTACION#', $DATO_PRESTACION, $tablaContenido);

                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);

                //$tablaContenido = str_replace('#ESTADO_VALICACION#', $this->generateHtmlDatosPoderLegal($ESTADO_DERIVACION), $tablaContenido);

                /*if ($GRILLA_DAHE_LEGAL != '') {
                    $title_dahe_apoderados_herederos = 'III. APODERADOS/HEREDEROS';
                    $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', $this->generateHtmlApoderadosHeredadosLegal($title_dahe_apoderados_herederos, $GRILLA_DAHE_LEGAL), $tablaContenido);

                    $title_datos_del_poder = 'IV. DATOS DEL PODER';
                    $tablaContenido = str_replace('#TITLE_DATOS_PODER#', $title_datos_del_poder, $tablaContenido);

                    $title_procesos_de_base = 'V. DATOS DEL PROCESO BASE';
                    $tablaContenido = str_replace('#TITLE_PROCESOS_BASE#', $title_procesos_de_base, $tablaContenido);

                    $title_datos_prestacion = 'VI. DATOS DE LA PRESTACIÓN';
                    $tablaContenido = str_replace('#TITLE_DATOS_PRESTACION#', $title_datos_prestacion, $tablaContenido);

                    if ($TESTIMONIO_JUDICIAL == 'SI') {
                        $tit_testimonio_judicial = 'VII. TESTIMONIO JUDICIAL';
                        $tit_periodos_solicitados = 'VIII. PERIODOS SOLICITADOS';

                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        $tablaContenido = str_replace('#PERIODOS_SOLICITADOS_LEGAL#', $this->generateHtmlPeriodosSolicitados($tit_periodos_solicitados, $PERIODO_INICIO, $PERIODO_FIN, $HA_PAGO_AGUINALDO_LEGAL), $tablaContenido);
                    } else {
                        $tit_periodos_solicitados = 'VII. PERIODOS SOLICITADOS';
                        $tit_validacion_rechazo = 'VII. VALIDACIÓN DE RECHAZO';

                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        $tablaContenido = str_replace('#PERIODOS_SOLICITADOS_LEGAL#', $this->generateHtmlPeriodosSolicitados($tit_periodos_solicitados, $PERIODO_INICIO, $PERIODO_FIN, $HA_PAGO_AGUINALDO_LEGAL), $tablaContenido);
                    }

                    if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                        $tit_validacion_rechazo = 'IX. VALIDACIÓN DE RECHAZO';
                        $tablaContenido = str_replace('#VALIDACION_RECHAZO_LEGAL#', $this->generateHtmlValidacionRechazado($tit_validacion_rechazo, $DATA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS), $tablaContenido);
                    } else {
                        $tablaContenido = str_replace('#VALIDACION_RECHAZO_LEGAL#', '', $tablaContenido);
                    }
                } else {
                    $tablaContenido = str_replace('#APODERADOS_HEREDADOS_LEGAL#', '', $tablaContenido);

                    $title_datos_del_poder = 'III. DATOS DEL PODER';
                    $tablaContenido = str_replace('#TITLE_DATOS_PODER#', $title_datos_del_poder, $tablaContenido);

                    $title_procesos_de_base = 'IV. DATOS DEL PROCESO BASE';
                    $tablaContenido = str_replace('#TITLE_PROCESOS_BASE#', $title_procesos_de_base, $tablaContenido);

                    $title_datos_prestacion = 'V. DATOS DE LA PRESTACIÓN';
                    $tablaContenido = str_replace('#TITLE_DATOS_PRESTACION#', $title_datos_prestacion, $tablaContenido);

                    if ($TESTIMONIO_JUDICIAL == 'SI') {
                        $tit_testimonio_judicial = 'VI. TESTIMONIO JUDICIAL';
                        $tit_periodos_solicitados = 'VII. PERIODOS SOLICITADOS';

                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', $this->generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO), $tablaContenido);
                        $tablaContenido = str_replace('#PERIODOS_SOLICITADOS_LEGAL#', $this->generateHtmlPeriodosSolicitados($tit_periodos_solicitados, $PERIODO_INICIO, $PERIODO_FIN), $tablaContenido);
                    } else {
                        $tit_periodos_solicitados = 'VII. PERIODOS SOLICITADOS';
                        $tit_validacion_rechazo = 'VII. VALIDACIÓN DE RECHAZO';

                        $tablaContenido = str_replace('#TESTIMONIO_JUDICIAL_LEGAL#', '', $tablaContenido);
                        $tablaContenido = str_replace('#PERIODOS_SOLICITADOS_LEGAL#', $this->generateHtmlPeriodosSolicitados($tit_periodos_solicitados, $PERIODO_INICIO, $PERIODO_FIN), $tablaContenido);
                    }

                    if ($ESTADO_DERIVACION == 'RCHZ_LEGAL') {
                        $tit_validacion_rechazo = 'VIII. VALIDACIÓN DE RECHAZO';
                        $tablaContenido = str_replace('#VALIDACION_RECHAZO_LEGAL#', $this->generateHtmlValidacionRechazado($tit_validacion_rechazo, $DATA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS), $tablaContenido);
                    } else {
                        $tablaContenido = str_replace('#VALIDACION_RECHAZO_LEGAL#', '', $tablaContenido);
                    }
                }*/
            }
            //**************************************LEGAL INICIO******************************* */
            else if ($nombre_doc == 'ACEPTACIÓN DE PODER DE INICIO, SEGUIMIENTO Y CONCLUSIÓN')  //ACEPTACION PODER DE INICIO
            {


                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';

                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);

                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
            } else if ($nombre_doc == 'ACEPTACION  DE HERENCIA') { //Ref.: NOTIFICA ACEPTACIÓN DE HERENCIA

                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        }
                    }
                }


                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;
                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
            } else if ($nombre_doc == 'RECHAZO DE HERENCIA') { //Ref.: NOTIFICA ACEPTACIÓN DE HERENCIA RECHAZADO

                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        }
                    }
                }


                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'PODER DE COBRO APROBADO') { //NOTIFICA ACEPTACIÓN PODER DE COBRO HTML_FRASE_TITULO_CASO

                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                $HTML_HA_PAGO_AGUINALDO_VA = '';
                foreach ($impData1 as $valores) {

                    if (isset($valores['frm_value'])) {

                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {

                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];

                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value'];
                        }elseif ($valores['frm_campo'] == 'HA_PAGO_AGUINALDO_VA') {
                            $HA_PAGO_AGUINALDO_VA = $valores['frm_value'];
                        }elseif ($valores['frm_campo'] == 'HA_GESTION_AGUINALDO') {
                            $HA_GESTION_AGUINALDO = $valores['frm_value'];
                        }
                    }
                }



                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if($HA_PAGO_AGUINALDO_VA == '1'){
                    $HTML_HA_PAGO_AGUINALDO_VA = ', (Con Aguinaldo ' . $HA_GESTION_AGUINALDO . ')';
                }


                if (in_array($AS_TIPO_EAP_ID, [199, 200, 201,202])) {

                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO'.$HTML_HA_PAGO_AGUINALDO_VA ;
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN.$HTML_HA_PAGO_AGUINALDO_VA;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN.$HTML_HA_PAGO_AGUINALDO_VA;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'PODER DE COBRO RECHAZADO') { //PODER DE COBRO RECHAZADO

                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'PODER DE INICIO DE TRAMITE RECHAZADO') { //PODER DE INICIO DE TRAMITE RECHAZADO

                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);

                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA VALIDACIÓN DE ACEPTACIÓN DE DOCUMENTACIÓN EXTRANJERA') { //NOTIFICA VALIDACIÓN DE ACEPTACIÓN DE DOCUMENTACIÓN EXTRANJERA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO  DE DOCUMENTACIÓN EXTRANJERA') { //NOTIFICA RECHAZO  DE DOCUMENTACIÓN EXTRANJERA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA VALIDACIÓN DE TESTIMONIO DE INTERDICCIÓN') { //
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                $NUMERO_SENTENCIA_AUTO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        }  elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                           // dd(55555);
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }
               // dd($NUMERO_SENTENCIA_AUTO);



                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO DE TESTIMONIO DE INTERDICCIÓN') { //
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' .  $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            }else if ($nombre_doc == 'NOTIFICA VALIDACIÓN DE TESTIMONIO DE TUTORIA') { //NOTIFICA VALIDACIÓN DE TESTIMONIO DE TUTORIA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                $NUMERO_SENTENCIA_AUTO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        }  elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                           // dd(55555);
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }
               // dd($NUMERO_SENTENCIA_AUTO);



                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO  DE TESTIMONIO DE TUTORIA') { //NOTIFICA VALIDACIÓN DE TESTIMONIO DE TUTORIA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' .  $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA VALIDACIÓN DE TESTIMONIO DE UNIÓN LIBRE') { //NOTIFICA VALIDACIÓN DE TESTIMONIO DE UNIÓN LIBRE
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                $NUMERO_SENTENCIA_AUTO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO DE TESTIMONIO DE UNIÓN LIBRE') { //NOTIFICA RECHAZO  DE TESTIMONIO DE UNIÓN LIBRE
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                $NUMERO_SENTENCIA_AUTO = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NUMERO_SENTENCIA_AUTO') {
                            $NUMERO_SENTENCIA_AUTO = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NUMERO_SENTENCIA_AUTO;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO  DE DECLARACIÓN JURADA DEL SERVICIO MILITAR') { //NOTIFICA RECHAZO  DE TESTIMONIO DE UNIÓN LIBRE
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'ACEPTACIÓN DE PODER  PARA ABONO EN CUENTA') { //ACEPTACIÓN DE PODER  PARA ABONO EN CUENTA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'PODER  RECHAZADO PARA ABONO EN CUENTA') {
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'ACEPTACIÓN DE PODER  PARA HABILITACIÓN DE PAGOS SUSPENDIDOS Y/O REVERTIDOS') { //ACEPTACIÓN DE PODER  PARA HABILITACIÓN DE PAGOS SUSPENDIDOS Y/O REVERTIDOS
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'RECHAZO DE PODER PARA HABILITACIÓN DE PAGOS SUSPENDIDOS Y/O REVERTIDOS') { //RECHAZO DE PODER PARA HABILITACIÓN DE PAGOS SUSPENDIDOS Y/O REVERTIDOS
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'ACEPTACIÓN DE PODER  PARA HABILITACIÓN DE FRACCIÓN SOLIDARIA') { //ACEPTACIÓN DE PODER  PARA HABILITACIÓN DE FRACCIÓN SOLIDARIA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'RECHAZO DE PODER  PARA HABILITACIÓN DE FRACCIÓN SOLIDARIA') { //RECHAZO DE PODER  PARA HABILITACIÓN DE FRACCIÓN SOLIDARIA
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'ACEPTACIÓN DE PODER  PARA ACTUALIZACIÓN DE DATOS DEL ASEGURADO') { //ACEPTACIÓN DE PODER  PARA ACTUALIZACIÓN DE DATOS DEL ASEGURADO
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'RECHAZO DE PODER  PARA ACTUALIZACIÓN DE DATOS DEL ASEGURADO') { //RECHAZO DE PODER  PARA ACTUALIZACIÓN DE DATOS DEL ASEGURADO
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            } else if ($nombre_doc == 'NOTIFICA RECHAZO DE REVOCATORIA DE PODER') {
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NRO_PODER_REVOCATORIO') {
                            $NRO_PODER_REVOCATORIO = $valores['frm_value'];
                        }
                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $tablaContenido = str_replace('#NRO_PODER_REVOCATORIO#', $NRO_PODER_REVOCATORIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            }else if ($nombre_doc == 'NOTIFICA ACEPTACIÓN DE REVOCATORIA DE PODER') {
                $DATA_MRCHZ_60 = '';
                $DESCRIPCION_FUNDAMENTACION_60 = '';
                $HTML_FRASE_TITULO_CASO = '';
                $HTML_NUMERO_TESTIMONIO = '';
                $HTML_RAGO_FECHA = '';
                foreach ($impData1 as $valores) {
                    if (isset($valores['frm_value'])) {
                        if ($valores['frm_campo'] == 'GRILLA_MRCHZ') {
                            $DATA_MRCHZ_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'DESCRIPCION_FUNDAMENTACION') {
                            $DESCRIPCION_FUNDAMENTACION_60 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP_LEGAL') {
                            $AS_TIPO_EAP_LEGAL = $valores['frm_value_label'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_EAP') {
                            $AS_TIPO_EAP = $valores['frm_value_label'];
                            $AS_TIPO_EAP_ID = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHE') {
                            $GRILLA_DAHE_LEGAL = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'GRILLA_DAHERDERO') {
                            $GRILLA_DAHERDERO = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'EXTRANGERO_PODER') {
                            $EXTRANGERO_PODER = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'NRO_PODER_SOL_1') {
                            $NRO_PODER_SOL_1 = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'AS_TIPO_PRESTACIONES') {
                            $AS_TIPO_PRESTACIONES = $valores['frm_value'];
                        } elseif ($valores['frm_campo'] == 'HA_PAGO_UNICO') {
                            $HA_PAGO_UNICO = $valores['frm_value_label'];
                        }elseif ($valores['frm_campo'] == 'NRO_PODER_REVOCATORIO') {

                            $NRO_PODER_REVOCATORIO = $valores['frm_value'];
                        }


                    }
                }




                $HTML_NUMERO_TESTIMONIO = 'N° ' . $NRO_PODER_SOL_1;

                $tablaContenido = str_replace('#HTML_NUMERO_TESTIMONIO#', $HTML_NUMERO_TESTIMONIO, $tablaContenido);
                $tablaContenido = str_replace('#NRO_PODER_REVOCATORIO#', $NRO_PODER_REVOCATORIO, $tablaContenido);
                $HTML_FRASE_TITULO_CASO = $this->generateHtmlNombreCaso($AS_TIPO_EAP_ID);
                $tablaContenido = str_replace('#HTML_FRASE_TITULO_CASO#', $HTML_FRASE_TITULO_CASO, $tablaContenido);
                if ($GRILLA_DAHERDERO != '') {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', $this->generateHtmlHerederoNombresLegal($GRILLA_DAHERDERO), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_HEREDERO#', '', $tablaContenido);
                }
                if ($GRILLA_DAHE_LEGAL != ''  && $GRILLA_DAHE_LEGAL != '[]') {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', $this->generateHtmlApoderantesNombresLegal($GRILLA_DAHE_LEGAL), $tablaContenido);
                } else {

                    $tablaContenido = str_replace('#HTML_PODERDANTE#', '', $tablaContenido);
                }
                //******COBRO ******** */
                if (in_array($AS_TIPO_PRESTACIONES, [199, 200, 201])) {
                    if ($HA_PAGO_UNICO == "1") {
                        $HTML_RAGO_FECHA = 'correspondiente a pago UNICO';
                    } else {
                        $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;
                    }
                } else {
                    $HTML_RAGO_FECHA = 'correspondiente a los periodos de ' . $FORM_JUB_MES_INI . ' a ' . $FORM_JUB_MES_FIN;;
                }
                $tablaContenido = str_replace('#HTML_RAGO_FECHA#',  $HTML_RAGO_FECHA, $tablaContenido);
                //******************** */
                $tablaContenido = str_replace('#LIST_DATA_MRCHZ_60#', $this->generatehtmlRechazoLegal60($DATA_MRCHZ_60), $tablaContenido);
                $tablaContenido = str_replace('#DESCRIPCION_FUNDAMENTACION_60#', $DESCRIPCION_FUNDAMENTACION_60, $tablaContenido);
            }


            //*********************************************LEGAL FIN******************************* */
        } else {
            $html .= '
            <table border="1" style="height:10%; width:100%">
                <tbody>
                    <tr>
                        <td style="border-color:rgb(0, 0, 0); text-align:center; vertical-align:middle; width:20%">
                        <p><span style="font-size:11px"><img alt="" > <img src="img/logo_gestora.jpg" style="height:90px; width:90px" /></span></p>
                        </td>
                        <td style="border-color:rgb(0, 0, 0); width:60%" align="center">
                        <p style="text-align:center">
                        <span style="font-size:14px">FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN</span>
                        </p>
                        </td>
                    </tr>
                </tbody>
            </table>';
        }
        $html = '';
        if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY NRO 1582-2024') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE NOTIFICACION DE REVISION - L') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'GNL', $firmaSolicitante, '', '');
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'SOLICITUD DE PENSIÓN POR INVALIDEZ') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'RMIN', $firmaSolicitante, $FECHA_DE_SOLICITUD, '');
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == '2. Form Retiros') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'RMIN20', $firmaSolicitante, '', '');
            if ($AS_TIPO_EAP == 'CVEAP-B8' || $AS_TIPO_EAP == 'CVEAP-A9' || $AS_TIPO_EAP == 'CVEAP-A15') {
                $tipo_cuenta_personal = $this->tipoCuentaPersonal($AS_TIPO_EAP, $AS_TIENE_CC, $AS_CC, $AS_FECHA_INICIO_COTIZACION, $AS_NUM_CUOTAS, $AS_VALOR_CUOTA, $AS_SALDO_ACUMULADO);
            } else {
                $tipo_cuenta_personal = '';
            }
            $html = $tablaContenido . $grilla . $tipo_cuenta_personal . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO GASTOS FUNERARIOS') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'GF', $firmaSolicitante, '', '');
            $html = $tablaContenido . $grilla . $data_pie . $firmas_lote . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE CCM') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'PAGCC', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE RENUNCIA DE DERECHO DE SOLICITAR REVISIÓN DE DICTAMEN') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'DC2', $firmaSolicitante, '', '');
            $html = $tablaContenido . $data_pie . $finmja;
        } else if ($nombre_doc == 'Formulario pension por muerte') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN', $firmaSolicitante, '', '');
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE PENSIÓN POR MUERTE') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            // p               ublic function generatePiePagina($cas_id, $cantidad_firmas, $tipo, $firmaSolicitante, $fecha,$AS_FECHA_APERSONAMIENTO)
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            // p               ublic function generatePiePagina($cas_id, $cantidad_firmas, $tipo, $firmaSolicitante, $fecha,$AS_FECHA_APERSONAMIENTO)
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN_1582', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO MASA HEREDITARIA' || $nombre_doc == 'FORMULARIO DE SOLICITUD MAHER') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MAGER', $firmaSolicitante, '', '');

            $tablaContenido = str_replace('#DERECHOHABIENTE_FALLECIDO#', $derecho_hambiente_muerto, $tablaContenido);
            $html = $tablaContenido . $derecho_hambiente_viva . $data_pie . $firmas_lote . $finmja;
        } else if ($nombre_doc == 'FORM - PLANTILLA - INV') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $tablaContenido = str_replace('#FIRMAS_LOTE#', $firmas_lote, $tablaContenido);
            $fecha_personalizada = Carbon::parse($FECHA_SINIESTRO_A);
            $fecha_literal = $fecha_personalizada->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_SINIESTRO_A_LITERAL#', $fecha_literal, $tablaContenido);
            $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == 'DOCUMENTACION OBSERVADA') {
            $tablaContenidoMarca = $this->documentacionObservada($tablaContenido, $tipo_dibujo, $cas_id);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == 'DIFERENCIA REGISTRO') {
            $tablaContenidoMarca = $this->registroDiferencia($tablaContenido, $tipo_dibujo, $AS_CUA, $cas_data, $cas_id);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE RECALIFICACION') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'REC', $firmaSolicitante, '', '');
            $html = $tablaContenido . $data_pie . $finmja;
        } else if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE REVISION DE DICTAMEN') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'DC', $firmaSolicitante, '', '');
            $html = $tablaContenido . $finmja;
        } else if ($nombre_doc == 'PLANTILLA FORM MUT') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $tablaContenido = str_replace('#FIRMAS_LOTE#', $firmas_lote, $tablaContenido);
            $fecha_personalizada = Carbon::parse($FECHA_SINIESTRO_A);
            $fecha_literal = $fecha_personalizada->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_SINIESTRO_A_LITERAL#', $fecha_literal, $tablaContenido);
            $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == 'PLANTILLA ITM MUT') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $tablaContenido = str_replace('#FIRMAS_LOTE#', $firmas_lote, $tablaContenido);
            $fecha_personalizada = Carbon::parse($FECHA_SINIESTRO_A);
            $fecha_literal = $fecha_personalizada->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_SINIESTRO_A_LITERAL#', $fecha_literal, $tablaContenido);
            $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == 'PLANTILLA DICT MUT') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $tablaContenido = str_replace('#FIRMAS_LOTE#', $firmas_lote, $tablaContenido);
            $fecha_personalizada = Carbon::parse($FECHA_SINIESTRO_A);
            $fecha_literal = $fecha_personalizada->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_SINIESTRO_A_LITERAL#', $fecha_literal, $tablaContenido);
            $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            return $tablaContenidoMarca;
        } else if ($nombre_doc == '3. Sol Verif EAP' || $nombre_doc == 'FORMULARIO DE SOLICITUD DE CVEAP') {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            if ($AS_CI == $SOL_CI) {

                $APS_CORRELATIVO_v2 = $this->generateActualCorrelativo($cas_id);
                $tablaContenido = str_replace('#_APS_CORRELATIVO_V2#', $APS_CORRELATIVO_v2, $tablaContenido);

                $firma = $this->generateHtmlFirma($firmaSolicitante);
                $tablaContenido = str_replace('#FIRMA_SOLICITANTE#', $firma, $tablaContenido);
                $data_tipo_asugurado = $this->generateHtmlTipo($AS_TIPO_DOCUMENTO);
                $tablaContenido = str_replace('#TIPO_TITULAR#', $data_tipo_asugurado, $tablaContenido);
                $fecha_literal = strftime('%d de %B del %Y', time());
                $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);
                $derecho_hab = $this->derechoHab('igual');
                $tablaContenido = str_replace('#DERECHO_HAMBIENTE#', $derecho_hab, $tablaContenido);
                $data_tipo_solicitante = $this->generateHtmlTipo($SOL_TIPO_DOCUMENTO);
                $data_grado_solicitante = $this->generateHtmlGrado($SOL_PARENTESCO);
                $tablaContenido = str_replace('#TIPO_SOLICITANTE#', $data_tipo_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#GRADO_SOLICITANTE#', $data_grado_solicitante, $tablaContenido);
                $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            } else {
                $firma = $this->generateHtmlFirma($firmaSolicitante);
                $tablaContenido = str_replace('#FIRMA_SOLICITANTE#', $firma, $tablaContenido);
                $data_tipo_asugurado = $this->generateHtmlTipo($AS_TIPO_DOCUMENTO);
                $tablaContenido = str_replace('#TIPO_TITULAR#', $data_tipo_asugurado, $tablaContenido);
                date_default_timezone_set('America/La_Paz');
                setlocale(LC_TIME, 'es_ES.UTF-8');
                setlocale(LC_TIME, 'spanish');
                $fecha_literal = strftime('%d de %B del %Y', time());
                $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);
                $derecho_hab = $this->derechoHab('no igual');
                $tablaContenido = str_replace('#DERECHO_HAMBIENTE#', $derecho_hab, $tablaContenido);
                $data_tipo_solicitante = $this->generateHtmlTipo($SOL_TIPO_DOCUMENTO);
                $data_grado_solicitante = $this->generateHtmlGrado($SOL_PARENTESCO);
                $tablaContenido = str_replace('#TIPO_SOLICITANTE#', $data_tipo_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#GRADO_SOLICITANTE#', $data_grado_solicitante, $tablaContenido);
                $tablaContenido = str_replace('#SOL_PRIMER_APELLIDO#', $SOL_PRIMER_APELLIDO, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_APELLIDO#', $SOL_SEGUNDO_APELLIDO, $tablaContenido);
                $tablaContenido = str_replace('#SOL_APELLIDO_CASADA#', $SOL_APELLIDO_CASADA, $tablaContenido);
                $tablaContenido = str_replace('#SOL_PRIMER_NOMBRE#', $SOL_PRIMER_NOMBRE, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_NOMBRE#', $SOL_SEGUNDO_NOMBRE, $tablaContenido);
                $tablaContenido = str_replace('#SOL_CELULAR#', $SOL_CELULAR, $tablaContenido);
                $tablaContenido = str_replace('#SOL_TELEFONO#', $SOL_TELEFONO, $tablaContenido);
                $tablaContenido = str_replace('#SOL_CORREO#', $SOL_CORREO, $tablaContenido);
                $tablaContenido = str_replace('#SOL_ZONA#', $SOL_ZONA, $tablaContenido);
                $tablaContenido = str_replace('#SOL_DIRECCION#', $SOL_DIRECCION, $tablaContenido);
                $tablaContenido = str_replace('#AS_NUM#', $AS_NUM, $tablaContenido);


                if (isset($data1['data']) && isset($data1['data'][0]['cas_data_valores'])) {
                    $impData1 = $data1['data'][0]['cas_data_valores'];
                    if (is_string($impData1)) {
                        $impData1 = json_decode($impData1, true);
                    }
                    if (is_array($impData1)) {
                        foreach ($impData1 as $item) {
                            if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                                $frmCampo = $item['frm_campo'];
                                $frmValue = $item['frm_value'];
                                if (!empty($frmValue)) {
                                    if ($frmCampo != "GRILLA_DERECHOHABIENTES" & $frmCampo != "GRILLA_DAHE" & $frmCampo != "GRILLA_MRCHZ") {
                                        if (!empty($item['frm_value_label'])) {
                                            if ($frmCampo == "AS_ENTE_GESTOR") {
                                                $tablaContenido = str_replace('#' . $frmCampo . '#', $frmValue, $tablaContenido);
                                                $AS_ENTE_GESTOR = $item['frm_value_label'];
                                                continue;
                                            } else {
                                                $frm_label = $item['frm_value_label'];
                                                $tablaContenido = str_replace('#' . $frmCampo . '#', $frm_label, $tablaContenido);
                                                continue;
                                            }
                                        } else {

                                            $tablaContenido = str_replace('#' . $frmCampo . '#', $frmValue, $tablaContenido);
                                            continue;
                                        }
                                    }
                                }
                            } else {
                                $frmCampo = $item['frm_campo'];
                                $tablaContenido = str_replace('#' . $frmCampo . '#', '', $tablaContenido);
                            }
                        }
                    }
                }
                $tablaContenidoMarca = $this->cartaMarcaAgua($tablaContenido, $tipo_dibujo);
            }
            return $tablaContenidoMarca;
        }
        // else if ($nombre_doc == 'FORMULARIO DE REVISION GERENCIA NACIONAL LEGAL') {
        //     dump("LA TERCERA ENTRADA >saaaa>> ");
        //     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //     $pdf->AddPage();
        //     $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
        //     $html = $tablaContenido . $grilla . $data_pie . $finmja;
        // }
        else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->setFontSubsetting(true);
            $pdf->AddPage();
            $rutaMarcaAgua = public_path('img/pp.jpg');
            $pageWidth = $pdf->getPageWidth();
            $pageHeight = $pdf->getPageHeight();
            $posicionX = 0;
            $posicionY = 0;

            $pdf->SetTopMargin(35); // Margen superior 27 PDF_MARGIN_TOP
            $pdf->SetRightMargin(PDF_MARGIN_RIGHT); // Margen derecho
            $pdf->setPrintFooter(false);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(20);
            $bMargin = $pdf->getBreakMargin();
            $auto_page_break = $pdf->getAutoPageBreak();
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->Image('img/pp.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $pdf->SetAutoPageBreak($auto_page_break, 30);
            $pdf->setPageMark();
            $pdf->writeHTML($html, true, false, true, false, '');
            date_default_timezone_set('America/La_Paz');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            setlocale(LC_TIME, 'spanish');
            //   $data_tipo = $this->generateHtmlTipo();
            $recepcionDocumentos = $this->generateHtmlRecepcionDocumentos($cas_id);
            $tablaContenido = str_replace('#data_filas_recepcion_documentos#', $recepcionDocumentos, $tablaContenido);

            $data_tipo_asugurado = $this->generateHtmlTipo($AS_TIPO_DOCUMENTO);
            $data_tipo_solicitante = $this->generateHtmlTipo($SOL_TIPO_DOCUMENTO);
            $data_grado_solicitante = $this->generateHtmlGrado($SOL_PARENTESCO);

            $firma = $this->generateHtmlFirma($firmaSolicitante); //$this->generateHtmlFirma($cas_id);

            $fecha_personalizada1 = Carbon::parse($FECHA_DE_SOLICITUD_PENSION);
            $fecha_literal1 = $fecha_personalizada1->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_DE_SOLICITUD_A_LITERAL#', $fecha_literal1, $tablaContenido);

            $aportes_otros_estados = $this->generateHtmlOtrosEstados($AS_APORTES_EXTRANJERO);
            $fecha_literal = strftime('%d de %B del %Y', time());
            $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);
            $tablaContenido = str_replace('#DESCRIPCION_ENTE_GESTOR#', $AS_ENTE_GESTOR, $tablaContenido);
            $tablaContenido = str_replace('#TIPO_TITULAR#', $data_tipo_asugurado, $tablaContenido);
            $tablaContenido = str_replace('#TIPO_SOLICITANTE#', $data_tipo_solicitante, $tablaContenido);
            $tablaContenido = str_replace('#GRADO_SOLICITANTE#', $data_grado_solicitante, $tablaContenido);
            $tablaContenido = str_replace('#FIRMA_SOLICITANTE#', $firma, $tablaContenido);
            $tablaContenido = str_replace('#APORTES_SEGURIDAD_SOCIAL#', $aportes_otros_estados, $tablaContenido);
            $agencia = $this->generateAgencia($cas_id);

            $tablaContenido = str_replace('#CAST_AGENCIA#', $agencia, $tablaContenido);
            $tablaContenido = str_replace('#GRILLA_INVALIDEZ#', $grilla, $tablaContenido);

            $htmlRechazadosv2 = $this->generateHtmlRechazadoV2($GRILLA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS);
            $tablaContenido = str_replace('#data_filas_grilla_rechazado_#', $htmlRechazadosv2, $tablaContenido);
            $html = $tablaContenido;
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $nombreArchivo = 'ejemplo_con_marca_agua.pdf';
        $rutaDirectorio = public_path('archivos_pdf/');
        $rutaCompleta = $rutaDirectorio . $nombreArchivo;
        if (!file_exists($rutaDirectorio)) {
            mkdir($rutaDirectorio, 0777, true);
        }
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        //return response()->json($base64Content);

        if ($tipo_dibujo == 'Dibujar') {

            return array("data" => $base64Content, "codigoRespuesta" => $success);
        } else {

            return array("mensaje" => $pdfAsBase64);
        }
    }

    public function generarPDF1582(Request $request)
    {
        $nroTramite = $request->input('nroTramite');

        $casos = \DB::table('rmx_vys_casos')->where('cas_cod_id', $nroTramite)->get();

        $dataConsulta = \DB::select("
            SELECT htc_cas_usr_id, htc_cas_estado,
                act_data->>'act_orden' AS orden
                FROM rmx_vys_historico_casos h
                INNER JOIN rmx_vys_casos c ON c.cas_id = h.htc_cas_id
                INNER JOIN rmx_vys_actividades a ON c.cas_act_id = a.act_id
            WHERE htc_cas_cod_id = ?
            ORDER BY h.htc_cas_modificado DESC
            LIMIT 1
        ", [$nroTramite]);

        if ($casos->isEmpty()) {
            $error = array("code" => 400, "mensaje" => 'Trámite no encontrado');
            return response()->json(["codigoRespuesta" => $error]);
        }

        if ($dataConsulta[0]->orden != 20) {
            $error = array("code" => 400, "mensaje" => 'El trámite no está en estado inicial [20], no se puede visualizar el PDF. Estado actual del Trámite: ' . $dataConsulta[0]->orden);
            return response()->json(["codigoRespuesta" => $error]);
        }

        $_cas_id = $casos[0]->cas_id;
        $_cas_act_id = $casos[0]->cas_act_id;

        $tipo_dibujo = "Dibujar"; //$request["tipo"];
        $cuerpox = "";
        $cuerpofinal = "";
        $actId = $_cas_act_id;  //$request["act_id"];
        $usr_id = 87; // $request["act_usr_id"];
        $cas_id = $_cas_id; //$request["cas_id"]; //35;
        $impid = 249; //$request["impid"]; //35;
        $nombre_doc = 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024'; //$request["nombre_doc"]; //35;
        $firmaSolicitante = "";
        $request = new Request(['act_id' => $actId, 'imp_id' => $impid]);

        $response = $this->listarImpresionId($request);
        $content = $response->getContent();
        $data = json_decode($content, true);
        $grilla = '';
        $AS_ENTE_GESTOR = '';
        $AS_TIPO_DOCUMENTO = '';
        $AS_FECHA_APERSONAMIENTO = '';
        $SOL_TIPO_DOCUMENTO = '';
        $SOL_PARENTESCO = '';
        $AS_FECHA_FALLECIMIENTO = '';
        $AS_CERT_DEFUNCION = '';
        $RMI_OPCION = '';
        $AS_APORTES_EXTRANJERO = '';
        $derecho_hambiente_muerto = '';
        $derecho_hambiente_viva = '';
        $cantidad_firmas = 0;
        $firmas_lote = '';
        $AS_TIPO_EAP = '';
        $AS_TIENE_CC = '';
        $AS_CC = '';
        $AS_FECHA_INICIO_COTIZACION = '';
        $AS_NUM_CUOTAS = '';
        $AS_VALOR_CUOTA = '';
        $AS_SALDO_ACUMULADO = '';
        $FECHA_SINIESTRO_A = '';
        $AS_CI = '';
        $SOL_CI = '';
        $SOL_FAC_REC = '';
        $FECHA_SUPERA_6 = '';

        $PENS_NO_COBRADAS = '';
        $doc_id = '';
        $VALIDADOR = 0;

        $FECHA_DE_SOLICITUD = '';
        $FECHA_DE_SOLICITUD_PENSION = '';

        $VALIDAR_PODER = '';
        $FECHA_REVISION = '';
        $DECLARATORIA_HEREDEROS = '';
        $AS_ZONA = '';

        $AS_DIRECCION = '';
        $AS_NUM = '';
        $generohtmlsol2 = '';

        $AS_CUA = '';
        $GRILLA_MRCHZ = '';
        $DESCRIPCION_FUNDAMENTACION = '';
        $VER_FUNDAMENTOS = '';

        /* ------------------------------------------------------------------------------------------ */
        $SOL_PRIMER_APELLIDO = '';
        $SOL_SEGUNDO_APELLIDO = '';
        $SOL_APELLIDO_CASADA = '';
        $SOL_PRIMER_NOMBRE = '';
        $SOL_SEGUNDO_NOMBRE = '';
        $SOL_CELULAR = '';
        $SOL_TELEFONO = '';
        $SOL_CORREO = '';
        $SOL_ZONA = '';
        $SOL_DIRECCION = '';
        $AS_NUM = '';
        /* ------------------------------------------------------------------------------------------ */
        $imp_tipo = '';
        $imp_tipo_control = '';

        $success = array("code" => 200, "mensaje" => 'OK',);
        if (isset($data['data'])) {
            $responseData = $data['data'];
            if (isset($data['data'][0]['imp_data'])) {
                $imp_tipo_control = $data['data'][0]['imp_tipo'];
                $impData = $data['data'][0]['imp_data'];
                $imp_tipo = 3;
                $cuerpox = $impData;
            } else {
                //echo "No se tiene el valor imp_data";
            }
        } else {
            echo "No se tienen valores";
        }
        $request1 = new Request(['usr_id' => $usr_id, 'cas_id' => $cas_id, 'cas_act_id' => $actId]);
        $response1 = $this->listarCasosXImpresion($request1);

        $content1 = $response1->getContent();

        $data1 = json_decode($content1, true);

        $cas_data = $data1['data'][0]['cas_data'];
        if (isset($data1['data'])) {
            if (isset($data1['data'][0]['cas_data_valores'])) {
                $impData1 = $data1['data'][0]['cas_data_valores'];
                if (is_string($impData1)) {
                    $impData1 = json_decode($impData1, true);
                }
                if (is_array($impData1)) {
                    foreach ($impData1 as $item) {
                        if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                            $frmCampo = $item['frm_campo'];
                            $frmValue = $item['frm_value'];
                            if ($frmCampo != "GRILLA_DERECHOHABIENTES") {
                                if ($frmCampo == "AS_CI") {
                                    $AS_CI = $item['frm_value'];
                                    continue;
                                }
                                if ($frmCampo == "SOL_CI") {
                                    $SOL_CI = $item['frm_value'];
                                    continue;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($impid == 4 || $impid == 17 || $impid == 64 || $impid == 32 || $impid == 68 || $impid == 54 || $impid == 49 || $impid == 132) {
            if ($AS_CI == $SOL_CI) {
                $cuerpox = $cuerpox = str_replace('#SOL_PRIMER_APELLIDO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_SEGUNDO_APELLIDO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_APELLIDO_CASADA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PRIMER_NOMBRE#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_SEGUNDO_NOMBRE#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_TIPO_DOCUMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CI#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_COMPLEMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PARENTESCO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_GENERO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CELULAR#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_TELEFONO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_DEPARTAMENTO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ZONA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_NUM#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CORREO#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_POSTAL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NRO_PODER_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NRO_NOTARIA_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#NOMBRE_NOTARIO_SOL_1#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_PROVINCIA#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_CIUDAD#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_DIRECCION#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $cuerpox = $cuerpox = str_replace('#SOL_ESTADO_CIVIL#', '', $cuerpox);
                $html_documento_solicitante2 = '<td class="style84" colspan="2"></td>   <td class="style85" colspan="2"> </td>';
                $cuerpox = str_replace('#documento_solicitante#', $html_documento_solicitante2, $cuerpox);

                $generohtmlsol = '<td class="style85" colspan="">M</td>
                                    <td class="style85" colspan=""></td>
                                    <td class="style85" colspan="">F</td>
                                    <td class="style85" colspan=""></td>';
                $cuerpox = str_replace('#generohtmlsol#', $generohtmlsol, $cuerpox);
            }
        }

        if (isset($data1['data'])) {
            if (isset($data1['data'][0]['cas_data_valores'])) {
                $impData1 = $data1['data'][0]['cas_data_valores'];

                if (is_string($impData1)) {
                    $impData1 = json_decode($impData1, true);
                }
                if (is_array($impData1)) {
                    foreach ($impData1 as $item) {
                        if (isset($item['frm_campo']) && isset($item['frm_value'])) {
                            $frmCampo = $item['frm_campo'];
                            $frmValue = $item['frm_value'];
                            $act_data = json_decode($data1['data'][0]['act_data'], true);

                            if ($act_data['act_orden'] == '20') {
                                if ($frmCampo == "GRILLA_DERECHOHABIENTES") {
                                    if ($item['frm_value'] != []) {
                                        $VALIDADOR = $this->validacionDocumentos($item, $cas_id);
                                        if ($VALIDADOR != 0) {
                                            continue;
                                        } else {
                                            if ($this->getCasoOrdenActividad($cas_id) > 30) {
                                                continue;
                                            } else {
                                                $error = array("message" => "error de instancia", "code" => 500);
                                                return array("data" => '', "codigoRespuesta" => $error);
                                            }
                                        }
                                    } else {
                                        continue;
                                    }
                                }
                            }

                            if (!empty($frmValue)) {
                                if (isset($item['frm_tipo']) && $item['frm_tipo'] == 'DATE') {
                                    $fecha_exp = explode("-", $frmValue);
                                    $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                    $cuerpox = str_replace('#' . $frmCampo . '#', $fecha_espa, $cuerpox);
                                } else {
                                }
                                if ($frmCampo == "GRILLA_MRCHZ") {
                                    $GRILLA_MRCHZ = $item;
                                }

                                if ($frmCampo != "GRILLA_DERECHOHABIENTES" & $frmCampo != "GRILLA_DAHE" & $frmCampo != "GRILLA_MRCHZ" & $frmCampo != "DATA_DETALLES") {

                                    if ($frmCampo == "AS_TIPO_DOCUMENTO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIPO_DOCUMENTO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FORM_JUB_FECHA_APERSONAMIENTO") {
                                        $AS_FECHA_APERSONAMIENTO = $item['frm_value'];
                                        $fecha_exp = explode("-", $AS_FECHA_APERSONAMIENTO);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                        $AS_FECHA_APERSONAMIENTO = $fecha_espa;
                                        continue;
                                    }

                                    if ($frmCampo == "FORM_PAGCC_FECHA_APERSONAMIENTO") {
                                        $AS_FECHA_APERSONAMIENTO = $item['frm_value'];
                                        $fecha_exp = explode("-", $AS_FECHA_APERSONAMIENTO);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                                        $AS_FECHA_APERSONAMIENTO = $fecha_espa;
                                        continue;
                                    }

                                    if ($frmCampo == "_FECHA") {
                                        $frm_value = $item['frm_value'];

                                        $fecha_exp = explode("-", $frm_value);
                                        $fecha_espa = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];

                                        $cuerpox = str_replace('#' . $frmCampo . '#', $fecha_espa, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "AS_CUA") {

                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_CUA = $item['frm_value'];
                                    }
                                    if ($frmCampo == "DESCRIPCION_FUNDAMENTACION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $DESCRIPCION_FUNDAMENTACION = $item['frm_value'];
                                    }
                                    if ($frmCampo == "VER_FUNDAMENTOS") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $VER_FUNDAMENTOS = $item['frm_value'];
                                    }

                                    if ($frmCampo == "SOL_GENERO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "MASCULINO":
                                                $valorM = "X";
                                                break;
                                            case "FEMENINO":
                                                $valorF = "X";
                                                break;
                                        }

                                        $generohtmlsol2 = '  <td class="style85" colspan="">M</td>
                                         <td class="style85" colspan="2">' . $valorM . '</td>
                                         <td class="style85" colspan="">F</td>
                                         <td class="style85" colspan="1">' . $valorF . '</td>';
                                    }

                                    if ($frmCampo == "ESTADO_RESPUESTA_CALCULO") {
                                        $valorF = "";
                                        $valorM = "";
                                        switch ($frmValue) {
                                            case "EJEC":
                                                $valorM = "X";
                                                break;
                                            case "VAE":
                                                $valorF = "X";
                                                break;
                                        }
                                        $coverturahtmlsol = '  <td class="style85" colspan="">SI</td>
                                         <td class="style85" colspan="">' . $valorM . '</td>
                                         <td class="style85" colspan="">NO</td>
                                         <td class="style85" colspan="1">' . $valorF . '</td>';
                                    }
                                    if ($frmCampo == "AS_ZONA") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_ZONA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_DIRECCION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_DIRECCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_NUM") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $AS_NUM = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_FAC_REC") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $SOL_FAC_REC = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FECHA_SUPERA_6") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $FECHA_SUPERA_6 = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "VALIDAR_PODER") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $VALIDAR_PODER = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_REVISION") {
                                        $frm_value = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value, $cuerpox);
                                        $FECHA_REVISION = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "DECLARATORIA_HEREDEROS") {
                                        $DECLARATORIA_HEREDEROS = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "FECHA_DE_SOLICITUD") {
                                        $FECHA_DE_SOLICITUD = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_DE_SOLICITUD_PENSION") {
                                        $FECHA_DE_SOLICITUD_PENSION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CI") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CI = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "SOL_CI") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CI = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "PENS_NO_COBRADAS") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $PENS_NO_COBRADAS = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "SOL_TIPO_DOCUMENTO") {
                                        $SOL_TIPO_DOCUMENTO = $item['frm_value'];
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_PARENTESCO") {
                                        $frm_label = $item['frm_value_label'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PARENTESCO = $item['frm_value_label'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_FECHA_FALLECIMIENTO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_FECHA_FALLECIMIENTO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CERT_DEFUNCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CERT_DEFUNCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "RMI_OPCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $RMI_OPCION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_APORTES_EXTRANJERO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_APORTES_EXTRANJERO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_TIPO_EAP") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIPO_EAP = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_TIENE_CC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_TIENE_CC = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_CC") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_CC = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_FECHA_INICIO_COTIZACION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_FECHA_INICIO_COTIZACION = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_NUM_CUOTAS") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_NUM_CUOTAS = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_VALOR_CUOTA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_VALOR_CUOTA = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_SALDO_ACUMULADO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_SALDO_ACUMULADO = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "FECHA_SINIESTRO_A") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $FECHA_SINIESTRO_A = $item['frm_value'];
                                        continue;
                                    }
                                    if ($frmCampo == "AS_CIUDAD" || $frmCampo == "EM_CIUDAD" || $frmCampo == "SOL_CIUDAD") {
                                        $frm_value_label = $item['frm_value_label'];
                                        $frm_value_label_explote = explode("-", $frm_value_label);
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_value_label_explote[0], $cuerpox);
                                        $FECHA_SINIESTRO_A = $item['frm_value'];
                                        continue;
                                    }

                                    /*  */

                                    if ($frmCampo == "SOL_PRIMER_APELLIDO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PRIMER_APELLIDO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_SEGUNDO_APELLIDO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_SEGUNDO_APELLIDO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_APELLIDO_CASADA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_APELLIDO_CASADA = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_PRIMER_NOMBRE") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_PRIMER_NOMBRE = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_SEGUNDO_NOMBRE") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_SEGUNDO_NOMBRE = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_CELULAR") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CELULAR = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_TELEFONO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_TELEFONO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_CORREO") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_CORREO = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_ZONA") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_ZONA = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "SOL_DIRECCION") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $SOL_DIRECCION = $item['frm_value'];
                                        continue;
                                    }

                                    if ($frmCampo == "AS_NUM") {
                                        $frm_label = $item['frm_value'];
                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                        $AS_NUM = $item['frm_value'];
                                        continue;
                                    }

                                    if (!empty($item['frm_value_label'])) {
                                        if ($frmCampo == "AS_ENTE_GESTOR") {
                                            $AS_ENTE_GESTOR = $item['frm_value_label'];
                                            $cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);
                                            $AS_ENTE_GESTOR = $item['frm_value_label'];
                                            continue;
                                        } else {
                                            $frm_label = $item['frm_value_label'];
                                            $cuerpox = str_replace('#' . $frmCampo . '#', $frm_label, $cuerpox);
                                            continue;
                                        }
                                    } else {

                                        $cuerpox = str_replace('#' . $frmCampo . '#', $frmValue, $cuerpox);
                                        continue;
                                    }
                                }
                            }
                            if ($frmValue == 0) {
                                $AS_TELEFONO = $item['frm_campo'];
                                $frm_label = $item['frm_value'];

                                $cuerpox = str_replace('#' . $item['frm_campo'] . '#', $frm_label, $cuerpox);
                                continue;
                            }
                        } else {
                            $frmCampo = $item['frm_campo'];
                            $cuerpox = str_replace('#' . $frmCampo . '#', '', $cuerpox);
                        }
                    }
                } else {
                    echo "No se pudo decodificar cas_data_valores como un array";
                }
            } else {
                //  echo "No se tiene el valor cas data_valores";
            }
        } else {
            /// echo "No se tienen valores";
        }
        $finmja = '   </tbody>      </table>     </body>    </html>';

        $tablaContenido = $cuerpox;
        $cuerpox = "";
        $html = '';
        if ($imp_tipo == '3') {
            if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE SOLICITUD DE JUBILACIÓN LEY N° 1582/2024</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id); // GESTORA
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);

                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
            } else if ($nombre_doc == 'ADENDA LEY 1582/2024') {
                $html .= '
                <tr class="row2">
                    <td class="style69" colspan="8" rowspan="3"> <img src="img/logo_gestora2.jpg" style="height:50x; width:150px" /></td>
                    <td class="style69" colspan="20"></td>
                </tr>
                <tr class="row3">
                    <td class="style69" colspan="22" style="border: none;">FORMULARIO DE SOLICITUD DE JUBILACION LEY N° 1582/2024</td>
                    <td class="column23  " colspan="5" style="border: none;"></td>
                </tr>
                <tr class="row4"><td  colspan="25" style="border: none;"></td></tr>';

                $solHTMLOBS = $this->generateHtmlSolicitantedoDocumentoinv($cas_id); // GESTORA
                $tablaContenido = str_replace('#solHTMLOBS#', $solHTMLOBS, $tablaContenido);

                $html_documento_solicitante = $this->generateHtmlSolicitantedoDocumento($cas_id);

                $tablaContenido = str_replace('#documento_solicitante#', $html_documento_solicitante, $tablaContenido);
                $html_documento_asegurado = $this->generateHtmlAseguradoDocumentoRetiros($cas_id, $AS_CERT_DEFUNCION, $AS_FECHA_FALLECIMIENTO);
                $tablaContenido = str_replace('#documento_asegurado#', $html_documento_asegurado, $tablaContenido);
                $tablaContenido = str_replace('#ENCABEZADO#', $html, $tablaContenido);
                $html_poder = $this->generateHtmlPoder($VALIDAR_PODER, $FECHA_REVISION);
                $tablaContenido = str_replace('#VALIDACION_PODER#', $html_poder, $tablaContenido);
                $tablaContenido = str_replace('#DETALLE_ADENDA#', $fila_detalles, $tablaContenido);

                $tablaContenido = str_replace('#SOL_PRIMER_NOMBRE#', $sol_primer_nombre, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_NOMBRE#', $sol_segundo_nombre, $tablaContenido);
                $tablaContenido = str_replace('#SOL_PRIMER_APELLIDO#', $sol_primer_apellido, $tablaContenido);
                $tablaContenido = str_replace('#SOL_SEGUNDO_APELLIDO#', $sol_segundo_apellido, $tablaContenido);
            }
        } else {
            $html .= '
            <table border="1" style="height:10%; width:100%">
                <tbody>
                    <tr>
                        <td style="border-color:rgb(0, 0, 0); text-align:center; vertical-align:middle; width:20%">
                        <p><span style="font-size:11px"><img alt="" > <img src="img/logo_gestora.jpg" style="height:90px; width:90px" /></span></p>
                        </td>
                        <td style="border-color:rgb(0, 0, 0); width:60%" align="center">
                        <p style="text-align:center">
                        <span style="font-size:14px">FORMULARIO DE SOLICITUD DE PENSIÓN DE JUBILACIÓN</span>
                        </p>
                        </td>
                    </tr>
                </tbody>
            </table>';
        }
        $html = '';
        if ($nombre_doc == 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024') {
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            // p               ublic function generatePiePagina($cas_id, $cantidad_firmas, $tipo, $firmaSolicitante, $fecha,$AS_FECHA_APERSONAMIENTO)
            $data_pie = $this->generatePiePagina($cas_id, $cantidad_firmas, 'MN_1582', $firmaSolicitante, $AS_TIPO_EAP, $AS_FECHA_APERSONAMIENTO);
            $html = $tablaContenido . $grilla . $data_pie . $finmja;
        } else {
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->setFontSubsetting(true);
            $pdf->AddPage();
            $rutaMarcaAgua = public_path('img/pp.jpg');
            $pageWidth = $pdf->getPageWidth();
            $pageHeight = $pdf->getPageHeight();
            $posicionX = 0;
            $posicionY = 0;

            $pdf->SetTopMargin(35); // Margen superior 27 PDF_MARGIN_TOP
            $pdf->SetRightMargin(PDF_MARGIN_RIGHT); // Margen derecho
            $pdf->setPrintFooter(false);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(20);
            $bMargin = $pdf->getBreakMargin();
            $auto_page_break = $pdf->getAutoPageBreak();
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->Image('img/pp.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
            $pdf->SetAutoPageBreak($auto_page_break, 30);
            $pdf->setPageMark();
            $pdf->writeHTML($html, true, false, true, false, '');
            date_default_timezone_set('America/La_Paz');
            setlocale(LC_TIME, 'es_ES.UTF-8');
            setlocale(LC_TIME, 'spanish');
            //   $data_tipo = $this->generateHtmlTipo();
            $recepcionDocumentos = $this->generateHtmlRecepcionDocumentos($cas_id);
            $tablaContenido = str_replace('#data_filas_recepcion_documentos#', $recepcionDocumentos, $tablaContenido);

            $data_tipo_asugurado = $this->generateHtmlTipo($AS_TIPO_DOCUMENTO);
            $data_tipo_solicitante = $this->generateHtmlTipo($SOL_TIPO_DOCUMENTO);
            $data_grado_solicitante = $this->generateHtmlGrado($SOL_PARENTESCO);

            $firma = $this->generateHtmlFirma($firmaSolicitante); //$this->generateHtmlFirma($cas_id);

            $fecha_personalizada1 = Carbon::parse($FECHA_DE_SOLICITUD_PENSION);
            $fecha_literal1 = $fecha_personalizada1->locale('es_ES')->isoFormat('D [de] MMMM [de] YYYY');
            $tablaContenido = str_replace('#FECHA_DE_SOLICITUD_A_LITERAL#', $fecha_literal1, $tablaContenido);

            $aportes_otros_estados = $this->generateHtmlOtrosEstados($AS_APORTES_EXTRANJERO);
            $fecha_literal = strftime('%d de %B del %Y', time());
            $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);

            $tablaContenido = str_replace('#DESCRIPCION_ENTE_GESTOR#', $AS_ENTE_GESTOR, $tablaContenido);
            $tablaContenido = str_replace('#TIPO_TITULAR#', $data_tipo_asugurado, $tablaContenido);
            $tablaContenido = str_replace('#TIPO_SOLICITANTE#', $data_tipo_solicitante, $tablaContenido);
            $tablaContenido = str_replace('#GRADO_SOLICITANTE#', $data_grado_solicitante, $tablaContenido);
            $tablaContenido = str_replace('#FIRMA_SOLICITANTE#', $firma, $tablaContenido);
            $tablaContenido = str_replace('#APORTES_SEGURIDAD_SOCIAL#', $aportes_otros_estados, $tablaContenido);
            $agencia = $this->generateAgencia($cas_id);

            $tablaContenido = str_replace('#CAST_AGENCIA#', $agencia, $tablaContenido);
            $tablaContenido = str_replace('#GRILLA_INVALIDEZ#', $grilla, $tablaContenido);

            $htmlRechazadosv2 = $this->generateHtmlRechazadoV2($GRILLA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS);
            $tablaContenido = str_replace('#data_filas_grilla_rechazado_#', $htmlRechazadosv2, $tablaContenido);
            $html = $tablaContenido;
        }

        $pdf->writeHTML($html, true, false, true, false, '');
        $nombreArchivo = 'ejemplo_con_marca_agua.pdf';
        $rutaDirectorio = public_path('archivos_pdf/');
        $rutaCompleta = $rutaDirectorio . $nombreArchivo;
        if (!file_exists($rutaDirectorio)) {
            mkdir($rutaDirectorio, 0777, true);
        }
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        //return response()->json($base64Content);

        if ($tipo_dibujo == 'Dibujar') {
            return array("data" => $base64Content, "codigoRespuesta" => $success);
        } else {
            return array("mensaje" => $pdfAsBase64);
        }
    }

    public function generateHtmlRechazado($GRILLA_MRCHZ)
    {
        $count = 1;
        $grilla_rec = '';
        foreach ($GRILLA_MRCHZ['frm_value'] as $grilla) {
            $html_fila = '';
            $cumple = $grilla[0]['col_value'] == 'false' ? 'NO' : 'SI';
            $html_fila = '<tr class="row35">
                                                <td class="style16" colspan="1">' . $count . '</td>
                                                <td class="style18" colspan="2">' . $cumple . '</td>
                                                <td class="style19" colspan="25">' . $grilla[1]['col_value'] . '</td>
                                            </tr>';
            $grilla_rec = $grilla_rec . $html_fila;
            $count++;
        }
        return $grilla_rec;
    }

    public function generateHtmlRechazadoV2($GRILLA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS)
    {
        if ($GRILLA_MRCHZ == '') {
            $descripcion = '<tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
                            <tr class="row28">
                                <td class="style16" colspan="28"  style="text-align: justify;">' . $DESCRIPCION_FUNDAMENTACION . '
                                </td>
                            </tr>';
            return $descripcion;
        } else {
            $count = 1;
            $grilla_rec = '';
            if ($VER_FUNDAMENTOS == 1) {
                foreach ($GRILLA_MRCHZ['frm_value'] as $grilla) {
                    $html_fila = '';
                    $cumple = $grilla[0]['col_value'] == 'false' ? 'NO' : 'SI';
                    if ($cumple == 'NO') {
                        $html_fila = '
                                <tr  class="row4">
                                    <td  class="style16" colspan="28" ><input type="checkbox" value="1" name="check_' . $count . '" checked="false">' . $grilla[1]['col_value'] . '</td>
                                </tr>';
                    } else {
                        $html_fila = '
                                <tr  class="row4">
                                    <td  class="style16" colspan="28" ><input type="checkbox" value="1" name="check_' . $count . '" checked="true" >' . $grilla[1]['col_value'] . '</td>
                                </tr>';
                    }
                    $grilla_rec = $grilla_rec . $html_fila;
                    $count++;
                }
                return $grilla_rec;
            } else {
                foreach ($GRILLA_MRCHZ['frm_value'] as $grilla) {
                    $html_fila = '';
                    $cumple = $grilla[0]['col_value'] == 'false' ? 'NO' : 'SI';
                    if ($cumple == 'NO') {
                        $html_fila = '
                                <tr  class="row4">
                                    <td  class="style16" colspan="28" ><input type="checkbox" value="1" name="check_' . $count . '" checked="false">' . $grilla[1]['col_value'] . '</td>
                                </tr>';
                    } else {
                        $html_fila = '
                                <tr  class="row4">
                                    <td  class="style16" colspan="28" ><input type="checkbox" value="1" name="check_' . $count . '" checked="true" >' . $grilla[1]['col_value'] . '</td>
                                </tr>';
                    }
                    $grilla_rec = $grilla_rec . $html_fila;
                    $count++;
                }
                $descripcion = '<tr class="row28"> <td class="style85" colspan="28"> </td> </tr>
                                <tr class="row28">
                                    <td class="style16" colspan="28"  style="text-align: justify;">' . $DESCRIPCION_FUNDAMENTACION . '
                                    </td>
                                </tr>';
                return $grilla_rec . $descripcion;
            }
        }
    }
    public function htmlHerederoDocumento($cas_id, $doc_categoria, $caso)
    {

        //$sql = "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_referencia = '$caso'  and doc_categoria = '$doc_categoria'";
        //  dd($sql);
        $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_referencia = '$caso'  and doc_categoria = '$doc_categoria' ");
        $titulo = 'HEREDERO';
        if ($caso == 'DAHE') {
            $titulo = 'APODERADO';
        } else if ($caso == 'DACO') {
            $titulo = 'BENIFICIARIO';
        }

        $html = '<tr class="row9" style="text-align:center; font-size:8px;">
                <td class="style49" colspan="6">DOCUMENTOS  ' . $titulo . '</td>
                <td class="style49" colspan="2">Original</td>
                <td class="style49" colspan="3">Fotocopia/Legalizada</td>
                <td class="style49" colspan="7">Observaciones</td>
            </tr>';

        $html_documento = '';
        $nro = 1;
        foreach ($gp_documentos as $documento) {

            $true = '';
            $true2 = '';
            $true = $documento->doc_copia_original === 'true' ? '' : 'X';
            $true2 = $documento->doc_copia_original === 'true' ? 'X' : '';
            if ($true == '' && $true2 == '') {
                $obs = 'Sin Documento';
            } else {
                $obs = $documento->doc_detalle_documento;
            }
            $html2 = '<tr class="row35" style="text-align:center; font-size:8px;">
                        <td  colspan="6">' . $documento->doc_descripcion . '</td>
                        <td  colspan="2">' . $true . '</td>
                        <td  colspan="3">' . $true2 . '</td>
                        <td  colspan="7">' . $obs . '</td>
                    </tr>';
            $html_documento .= $html2;
            $nro++;
        }
        return $html . $html_documento;
    }
    public function generateHtmlDocumentoConstancia($cas_id, $ESTADO_PODER_PRESENTADO, $ESTADO_PODER_PRESENTADO_TEXT, $OBSERVACION_PODER)
    {
        $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_url != '' and doc_descripcion in('CI ASEGURADO','Certificado de unión libre debidamente registrado en el SERECI (original).', 'Poder Presentado', 'Certificado de defunción del asegurado.', 'Certificado de matrimonio o de unión libre y Certificado de Estado Civil.', 'Certificado de descendencia de asegurado.') order by doc_id asc");

        $html_documento = '';
        $nro = 1;

        foreach ($gp_documentos as $documento) {
            $true = '';
            $true2 = '';
            $true = $documento->doc_copia_original === 'false' ? '' : 'X';
            $true2 = $documento->doc_copia_original === 'false' ? 'X' : '';
            if ($documento->doc_descripcion == 'Poder Presentado') {
                if ($ESTADO_PODER_PRESENTADO == "1") {
                    $true = 'X';
                    $true2 = '';
                }
                $obs = $OBSERVACION_PODER;
            } else {
                if ($true == '' && $true2 == '') {
                    $obs = 'Sin Documento';
                } else {
                    $obs = $documento->doc_detalle_documento;
                }
            }

            $html = '<tr class="row35" style="text-align:center; font-size:8px;">
                        <td  colspan="6">' . $documento->doc_descripcion . '</td>
                        <td  colspan="2">' . $true . '</td>
                        <td  colspan="3">' . $true2 . '</td>
                        <td  colspan="7">' . $obs . '</td>
                    </tr>';
            $html_documento .= $html;
            $nro++;
        }
        return $html_documento;
    }

    public function generateHtmlDocumentoConstanciaOtrosAdjuntosLegal($cas_id)
    {
        $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_url != '' and doc_referencia='ADJUNTOS' order by doc_id asc");

        $html_documento = '';
        $nro = 1;

        if (count($gp_documentos) > 0) {
            $html_documento = '       <tr style="text-align:center; font-size:8px;">
                <th colspan="16" class="style85">ADJUNTOS</th>
                    </tr><tr class="row9">
                        <td class="style49" colspan="6">DOCUMENTOS</td>
                        <td class="style49" colspan="2">Original</td>
                        <td class="style49" colspan="3">Fotocopia/Legalizada</td>
                        <td class="style49" colspan="7">Observaciones</td>
                </tr>';
            // Hay documentos
            foreach ($gp_documentos as $documento) {

                $true = '';
                $true2 = '';
                $true = $documento->doc_copia_original === 'true' ? '' : 'X';
                $true2 = $documento->doc_copia_original === 'true' ? 'X' : '';
                if ($true == '' && $true2 == '') {
                    $obs = 'Sin Documento';
                } else {
                    $obs = $documento->doc_detalle_documento;
                }
                $html = '<tr class="row35" style="text-align:center; font-size:8px;">
                            <td colspan="6">' . $documento->doc_descripcion . '</td>
                            <td colspan="2">' . $true . '</td>
                            <td colspan="3">' . $true2 . '</td>
                            <td colspan="7">' . $obs . '</td>
                        </tr>';
                $html_documento .= $html;
                $nro++;
            }
        }

        return $html_documento;
    }
    public function generateHtmlDocumentoParametrica($cas_id, $doc_referencia, $doc_descripcion, $titulo)
    {
        $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_url != '' and doc_referencia ilike '$doc_referencia' and doc_descripcion  ilike '$doc_descripcion' order by doc_id asc");

        $html_documento = '';
        $nro = 1;

        if (count($gp_documentos) > 0) {
            $html_documento = '       <tr style="text-align:center; font-size:8px;">
                <th colspan="16" class="style85">' . $titulo . '</th>
                    </tr><tr class="row9">
                        <td class="style49" colspan="6">DOCUMENTOS</td>
                        <td class="style49" colspan="2">Original</td>
                        <td class="style49" colspan="3">Fotocopia/Legalizada</td>
                        <td class="style49" colspan="7">Observaciones</td>
                </tr>';
            // Hay documentos
            foreach ($gp_documentos as $documento) {

                $true = '';
                $true2 = '';
                $true = $documento->doc_copia_original === 'true' ? 'X' : '';
                $true2 = $documento->doc_copia_original === 'true' ? '' : 'X';
                if ($true == '' && $true2 == '') {
                    $obs = 'Sin Documento';
                } else {
                    $obs = $documento->doc_detalle_documento;
                }
                $html = '<tr class="row35" style="text-align:center; font-size:8px;">
                            <td colspan="6">' . $documento->doc_descripcion . '</td>
                            <td colspan="2">' . $true . '</td>
                            <td colspan="3">' . $true2 . '</td>
                            <td colspan="7">' . $obs . '</td>
                        </tr>';
                $html_documento .= $html;
                $nro++;
            }
        }

        return $html_documento;
    }

    public function generateHtmlRecepcionDocumentos($cas_id)
    {
        $gp_documentos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id  and doc_estado ='A' and doc_url != '' order by doc_id asc");
        $data_valores = \DB::select("SELECT cas_data_valores from  public.rmx_vys_casos c  where cas_id =$cas_id ");

        $data_valores = json_decode($data_valores[0]->cas_data_valores, true);
        $AS_PRIMER_APELLIDO = '';
        $AS_PRIMER_NOMBRE = '';
        $AS_SEGUNDO_APELLIDO = '';
        $AS_APELLIDO_CASADA = '';
        $AS_SEGUNDO_NOMBRE = '';
        $SOL_PRIMER_APELLIDO = '';
        $SOL_PRIMER_NOMBRE = '';
        $SOL_SEGUNDO_APELLIDO = '';
        $SOL_APELLIDO_CASADA = '';
        $SOL_SEGUNDO_NOMBRE = '';
        $GRILLA_DERECHOHABIENTES = '';

        foreach ($data_valores as $valores) {
            if (isset($valores['frm_value'])) {
                if ($valores['frm_campo'] == 'AS_PRIMER_APELLIDO') {
                    $AS_PRIMER_APELLIDO = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'AS_PRIMER_NOMBRE') {
                    $AS_PRIMER_NOMBRE = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'AS_SEGUNDO_APELLIDO') {
                    $AS_SEGUNDO_APELLIDO = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'AS_APELLIDO_CASADA') {
                    $AS_APELLIDO_CASADA = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'AS_SEGUNDO_NOMBRE') {
                    $AS_SEGUNDO_NOMBRE = $valores['frm_value'];
                }
            }
        }

        foreach ($data_valores as $valores) {
            if (isset($valores['frm_value'])) {
                if ($valores['frm_campo'] == 'SOL_PRIMER_APELLIDO') {
                    $SOL_PRIMER_APELLIDO = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'SOL_PRIMER_NOMBRE') {
                    $SOL_PRIMER_NOMBRE = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'SOL_SEGUNDO_APELLIDO') {
                    $SOL_SEGUNDO_APELLIDO = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'SOL_APELLIDO_CASADA') {
                    $SOL_APELLIDO_CASADA = $valores['frm_value'];
                }
                if ($valores['frm_campo'] == 'SOL_SEGUNDO_NOMBRE') {
                    $SOL_SEGUNDO_NOMBRE = $valores['frm_value'];
                }
            }
        }
        foreach ($data_valores as $valores) {
            if ($valores['frm_campo'] == 'GRILLA_DERECHOHABIENTES') {
                $GRILLA_DERECHOHABIENTES = $valores['frm_value'];
            }
        }
        $nobre_completo_as = $AS_PRIMER_NOMBRE . ' ' . $AS_SEGUNDO_NOMBRE . ' ' . $AS_PRIMER_APELLIDO . ' ' . $AS_SEGUNDO_APELLIDO . ' ' . $AS_APELLIDO_CASADA;
        $nobre_completo_sol = $SOL_PRIMER_NOMBRE . ' ' . $SOL_SEGUNDO_NOMBRE . ' ' . $SOL_PRIMER_APELLIDO . ' ' . $SOL_SEGUNDO_APELLIDO . ' ' . $SOL_APELLIDO_CASADA;
        $html_doc = '';
        if (isset($gp_documentos)) {
            foreach ($gp_documentos as $documentos) {
                $doc_copia_original = $documentos->doc_copia_original;
                $doc_copia_original = $documentos->doc_copia_original == 'false' ? 'Presentado en Fotocopia' : 'Presentado en Original';
                $nombre_completo = '';
                if ($documentos->doc_referencia == '0-TIT') {
                    $nombre_completo = $nobre_completo_as;
                } else if ($documentos->doc_referencia == '0-SOL') {
                    $nombre_completo = $nobre_completo_sol;
                } else if ($documentos->doc_referencia == '0-SOL-LEGAL') {
                    $nombre_completo = $nobre_completo_sol;
                } else {
                    $nombre_grilla = '';
                    if (!empty($GRILLA_DERECHOHABIENTES)) {
                        foreach ($GRILLA_DERECHOHABIENTES as $ambientes) {
                            foreach ($ambientes as $ambientes2) {
                                if ($ambientes2['col_campo'] == 'DH_CI_GRILLA_PROP') {
                                    if ($ambientes2['col_value'] == $documentos->doc_categoria) {
                                        $nombre_grilla = $ambientes[6]['col_value'] . ' ' . $ambientes[7]['col_value'] . ' ' . $ambientes[8]['col_value'] . ' ' . $ambientes[9]['col_value'];
                                    }
                                }
                            }
                        }
                    }
                    $nombre_completo = $nombre_grilla;
                }
                $html_documentos = '';
                $html_documentos = '    <tr class="row7">
                                            <td class="style16" colspan="2"></td>
                                            <td class="style20" colspan="7">' . $documentos->doc_descripcion . '</td>
                                            <td class="style11" colspan="6"> ' . $doc_copia_original . '</td>
                                            <td class="style11" colspan="11"> ' . $nombre_completo . '</td>
                                            <td class="style16" colspan="2"></td>
                                        </tr>';
                $html_doc = $html_doc . $html_documentos;
            }
        }
        $html_documentos_2 = '<tr class="row7">
                                <td class="style16" colspan="2"></td>
                                <td class="style20" colspan="7">FORMULARIO DE REGISTRO DE DOCUMENTOS (FRDL)</td>
                                <td class="style11" colspan="6"> Presentado en Original </td>
                                <td class="style11" colspan="11"> ' . $nobre_completo_as . '</td>
                                <td class="style16" colspan="2"></td>
                            </tr>';
        return $html_documentos_2 . $html_doc;
    }
    public function generateHtmlDatosIndividualizado($nombre, $ci, $complemento, $fecha_nacimiento, $direccion, $correo, $invalidez, $parentesco)
    {

        $tipo_paren = '';
        if ($parentesco == '1-CONY') {
            $tipo_paren = 'CONYUGUE';
        } else if ($parentesco == '1-HIJ') {
            $tipo_paren = 'HIJA O HIJO';
        } else if ($parentesco == '3-SOB') {
            $tipo_paren = 'SOBRINO O SOBRINA';
        } else if ($parentesco == '2-HER') {
            $tipo_paren = 'HERMANO O HERMANA';
        } else if ($parentesco == '1-CONV') {
            $tipo_paren = 'CONVIVIENTE';
        } else if ($parentesco == '3-TIO') {
            $tipo_paren = 'TIA O TIO';
        } else if ($parentesco == '3-ABU') {
            $tipo_paren = 'ABUELO O ABUELA';
        } else if ($parentesco == '3-OTR') {
            $tipo_paren = 'OTROS';
        } else if ($parentesco == '2-MAD') {
            $tipo_paren = 'MADRE';
        } else if ($parentesco == '2-PAD') {
            $tipo_paren = 'PADRE';
        }
        $html_si_no = '';

        if ($invalidez) {
            $html_si_no = 'INVALIDO';
        } else {
            $html_si_no = 'NO';
        }

        $html = '
            <tr class="row9">
                <td class="style91" colspan="6"><strong> NOMBRES Y APELLIDOS</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $nombre . '</td>
            </tr>
            <tr class="row9">
                <td class="style91" colspan="6"><strong> DOC. DE IDENTIDAD</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $ci . ' ' . $complemento . '</td>
            </tr>
              <tr class="row9">
                <td class="style91" colspan="6"><strong> PARENTESCO</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $tipo_paren . '</td>
            </tr>
            <tr class="row9">
                <td class="style91" colspan="6"><strong> FECHA DE NACIMIENTO</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $fecha_nacimiento . ' </td>
            </tr>
            <tr class="row9">
                <td class="style91" colspan="6"><strong> ESTADO PSICOFISICO</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $html_si_no . ' </td>
            </tr>
            <tr class="row9">
                <td class="style91" colspan="6"><strong> DIRECCIÓN</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $direccion . ' </td>
            </tr>
            <tr class="row9">
                <td class="style91" colspan="6"><strong> CORREO ELECTRONICO</strong> </td>
                <td class="style92" colspan="1"><strong> :</strong> </td>
                <td class="style93" colspan="21">' . $correo . '</td>
            </tr>';

        return $html;
    }

    public function generateHtmlFacturaRecibo($tipo, $fecha_control)
    {
        if ($fecha_control == 'false') {
            if ($tipo == 'FACTURA') {
                $html = '
                       <tr class="row44">
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2">E0</td>
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2"></td>
                    </tr>
                    <tr class="row44">
                        <td class="style84" colspan="2">X</td>
                        <td class="style85" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                    </tr>';
            } else if ($tipo == '') {
                $html = '
                    <tr class="row44">
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2"></td>
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2"></td>
                    </tr>
                    <tr class="row44">
                        <td class="style84" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                    </tr>';
            } else {
                $html = '
                    <tr class="row44">
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2"></td>
                        <td class="style16" colspan="2">SI</td>
                        <td class="style17" colspan="2">NO</td>
                        <td class="style85" colspan="2" rowspan="2">E0</td>
                    </tr>
                    <tr class="row44">
                        <td class="style84" colspan="2"></td>
                        <td class="style85" colspan="2"></td>
                        <td class="style85" colspan="2">X</td>
                        <td class="style85" colspan="2"></td>
                    </tr>';
            }
        } else {

            $html = '
            <tr class="row44">
                <td class="style16" colspan="2">SI</td>
                <td class="style17" colspan="2">NO</td>
                <td class="style85" colspan="2" rowspan="2"></td>
                <td class="style16" colspan="2">SI</td>
                <td class="style17" colspan="2">NO</td>
                <td class="style85" colspan="2" rowspan="2"></td>
            </tr>
            <tr class="row44">
                <td class="style84" colspan="2"></td>
                <td class="style85" colspan="2">X</td>
                <td class="style85" colspan="2"></td>
                <td class="style85" colspan="2">X</td>

            </tr>';
        }

        return $html;
    }
    public function generateAgencia($cas_id)
    {
        $dataHistorico = \DB::select("SELECT * from  public.rmx_vys_casos where cas_id= $cas_id");
        $cast_data = json_decode($dataHistorico[0]->cas_data);
        $agencia = $cast_data->cas_agencia;
        return $agencia;
    }

    public function validacionDocumentos($item, $cas_id)
    {
        $id_persona = '';
        $parentesco = '';
        $ci = '';
        $validador = 0;
        $DT_NUMERO = '';
        foreach ($item['frm_value'] as $item2) {
            $tamano = count($item2) - 1;
            for ($i = 0; $i <= $tamano; $i++) {
                if ($item2[$i]['col_campo'] == 'DH_IDPERSONA_GRILLA_PROP') {
                    $id_persona = $item2[$i]['col_value'];
                }
                if ($item2[$i]['col_campo'] == 'DH_PARENTESCO') {
                    $parentesco = $item2[$i]['col_value'];
                }
                if ($item2[$i]['col_campo'] == 'DH_CI_GRILLA_PROP') {
                    $ci = $item2[$i]['col_value'];
                }
                if ($item2[$i]['col_campo'] == 'DT_NUMERO') {
                    $DT_NUMERO = $item2[$i]['col_value'];
                }
            }
            if ($parentesco == '') {

                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = '$DT_NUMERO' and doc_id_persona_sip = '$id_persona' order by doc_id desc");
            } else {
                $sql = "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = '$DT_NUMERO' and doc_id_persona_sip = '$id_persona' order by doc_id desc";

                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = '$parentesco' and doc_id_persona_sip = '$id_persona' order by doc_id desc");
            }
            if ($dataHistorico != []) {

                $validador++;
            } else {
                $validador = 0;
            }
        }
        return $validador;
    }
    public function getCasoOrdenActividad($cas_id)
    {
        $data = \DB::select("select (act_data->>'act_orden')::integer as orden,a.act_data --,*
        FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                      INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                      INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                      INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                      WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                      and cas_id=" . $cas_id);
        return $data[0]->orden;
    }

    public function derechoHab($tipo)
    {
        if ($tipo == 'igual') {
            $html =
                '
            <tr class="row9">
                <td class="style16" colspan="12"> Nombres y Apellidos:</td>
                <td class="style17" colspan="16"> Relación de Parentesco:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="12"></td>
                        <td class="style15" colspan="4">1er. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">2do. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">3er. Grado</td>
                        <td class="style20" colspan="1"></td>
                <td class="style31" colspan="1"></td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="17"> Número de Documento de Identidad:</td>
                <td class="style17" colspan="11"> Tipo:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="17"></td>
                <td class="style29" colspan="2"></td>


                  <td class="style30" colspan="1">CI</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="1">CE</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="3">PAS</td>
                <td class="style20" colspan="1"></td>

                <td class="style31" colspan="1"></td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="6"> Teléfono o Celular:</td>
                <td class="style18" colspan="9"> Correo Electrónico:</td>
                <td class="style17" colspan="13"> Domicilio:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="6"></td>
                <td class="style19" colspan="9"></td>
                <td class="style15" colspan="13"></td>
            </tr>';
        } else if ($tipo == 'no igual') {
            $html =
                '
            <tr class="row9">
                <td class="style16" colspan="12"> Nombres y Apellidos:</td>
                <td class="style17" colspan="16"> Relación de Parentesco:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="12"> #SOL_PRIMER_NOMBRE# #SOL_SEGUNDO_NOMBRE# #SOL_PRIMER_APELLIDO# #SOL_SEGUNDO_APELLIDO# #SOL_APELLIDO_CASADA#  </td>
                            #GRADO_SOLICITANTE#
                <td class="style31" colspan="1"></td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="17"> Número de Documento de Identidad:</td>
                <td class="style17" colspan="11"> Tipo:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="17">#SOL_CI# &nbsp; &nbsp; #SOL_COMPLEMENTO# </td>
                <td class="style29" colspan="2"></td>


                        #TIPO_SOLICITANTE#

                <td class="style31" colspan="1"></td>
            </tr>
            <tr class="row9">
                <td class="style16" colspan="6"> Teléfono o Celular:</td>
                <td class="style18" colspan="9"> Correo Electrónico:</td>
                <td class="style17" colspan="13"> Domicilio:</td>
            </tr>
            <tr class="row9">
                <td class="style14" colspan="6"> #SOL_TELEFONO# &nbsp; &nbsp;  #SOL_CELULAR#</td>
            <td class="style19" colspan="9">#SOL_CORREO#</td>
            <td class="style15" colspan="13">#SOL_ZONA# #SOL_DIRECCION# #AS_NUM#</td>
        </tr>';
        }



        return $html;
    }
    public function generateHtmlClasificacion($tipo, $pens)
    {
        if ($tipo == 'CVEAP-A10') {
            $html =
                '<tr class="row5">
                <td class=" style72" colspan="28">Tipo de Masa Hereditaria:</td>
            </tr>
            <tr class="row5">
                <td class=" style70" colspan="6">Cuenta Personal Previsional</td>
                <td class=" style7" colspan="1">X</td>
                <td class=" style70" colspan="10"> Pensiones no cobradas Asegurado Fallecido</td>
                <td class=" style7" colspan="1"></td>
                <td class=" style70" colspan="9"> Pensiones no cobradas Derechohabiente Fallecido</td>
                <td class=" style7" colspan="1"></td>
            </tr>';
        } else {
            if ($pens == '1') {
                $html =
                    '<tr class="row5">
                        <td class=" style72" colspan="28">Tipo de Masa Hereditaria:</td>
                    </tr>
                    <tr class="row5">
                        <td class=" style70" colspan="6">Cuenta Personal Previsional</td>
                        <td class=" style7" colspan="1"></td>
                        <td class=" style70" colspan="10"> Pensiones no cobradas Asegurado Fallecido</td>
                        <td class=" style7" colspan="1">X</td>
                        <td class=" style70" colspan="9"> Pensiones no cobradas Derechohabiente Fallecido</td>
                        <td class=" style7" colspan="1"></td>
                    </tr>';
            } else if ($pens == '') {
                $html =
                    '<tr class="row5">
                        <td class=" style72" colspan="28">Tipo de Masa Hereditaria:</td>
                    </tr>
                    <tr class="row5">
                        <td class=" style70" colspan="6">Cuenta Personal Previsional</td>
                        <td class=" style7" colspan="1"></td>
                        <td class=" style70" colspan="10"> Pensiones no cobradas Asegurado Fallecido</td>
                        <td class=" style7" colspan="1"></td>
                        <td class=" style70" colspan="9"> Pensiones no cobradas Derechohabiente Fallecido</td>
                        <td class=" style7" colspan="1"></td>
                    </tr>';
            } else {
                $html =
                    '<tr class="row5">
                        <td class=" style72" colspan="28">Tipo de Masa Hereditaria:</td>
                    </tr>
                    <tr class="row5">
                        <td class=" style70" colspan="6">Cuenta Personal Previsional</td>
                        <td class=" style7" colspan="1"></td>
                        <td class=" style70" colspan="10"> Pensiones no cobradas Asegurado Fallecido</td>
                        <td class=" style7" colspan="1"></td>
                        <td class=" style70" colspan="9"> Pensiones no cobradas Derechohabiente Fallecido</td>
                        <td class=" style7" colspan="1">X</td>
                    </tr>';
            }
        }
        return $html;
    }
    public function cartaMarcaAgua($htmlSinMarcaAgua, $tipo_dibujo)
    {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->AddPage();
        $pdf->SetY(22);

        $pdf->SetTopMargin(25); // Margen superior 27 PDF_MARGIN_TOP
        $pdf->SetRightMargin(PDF_MARGIN_RIGHT); // Margen derecho
        $pdf->setPrintFooter(false);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(15);

        $rutaMarcaAgua = public_path('img/pp.jpg');
        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();
        $posicionX = 0;
        $posicionY = 0;
        $bMargin = $pdf->getBreakMargin();
        $auto_page_break = $pdf->getAutoPageBreak();
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->Image('img/pp.jpg', 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        $pdf->SetAutoPageBreak($auto_page_break, 50);
        $pdf->setPageMark();
        $pdf->writeHTML($htmlSinMarcaAgua, true, false, true, false, '');
        date_default_timezone_set('America/La_Paz');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        setlocale(LC_TIME, 'spanish');

        $nombreArchivo = 'ejemplo_con_marca_agua.pdf';
        $rutaDirectorio = public_path('archivos_pdf/');
        $rutaCompleta = $rutaDirectorio . $nombreArchivo;
        if (!file_exists($rutaDirectorio)) {
            mkdir($rutaDirectorio, 0777, true);
        }
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        $success = array('code' => 200, 'mensaje' => 'OK');

        if ($tipo_dibujo == 'Dibujar') {

            return array("data" => $base64Content, "codigoRespuesta" => $success);
        } else {

            return array("mensaje" => $pdfAsBase64);
        }
    }

    public function generateHtmlHerederos($declara_herederos)
    {
        $si = $declara_herederos == '1' ? 'X' : '';
        $no = $declara_herederos == '2' ? 'X' : '';
        $html = '
                    <td class="style85" colspan="2">' . $si . '</td>
                    <td class="style86" colspan="1">' . $no . '</td>';
        return $html;
    }
    public function generateHtmlPoder($valida_poder, $fecha_poder)
    {
        if ($fecha_poder == '' || $valida_poder == '') {
            $fecha_poder = ' - - ';
            $aprobado = '';
            $rechazado = '';
        } else {
            $aprobado = $valida_poder == 'APR' ? 'X' : '';
            $rechazado = $valida_poder == 'REC' ? 'X' : '';
        }
        $fecha_exp = explode("-", $fecha_poder);
        $html = '   <td class="style85" colspan="2">' . $fecha_exp[2] . '</td>
                    <td class="style85" colspan="2">' . $fecha_exp[1] . '</td>
                    <td class="style85" colspan="2">' . $fecha_exp[0] . '</td>
                    <td class="style85" colspan="2">' . $aprobado . '</td>
                    <td class="style86" colspan="2">' . $rechazado . '</td>';
        return $html;
    }

    public function tipoCuentaPersonal($AS_TIPO_EAP, $AS_TIENE_CC, $AS_CC, $AS_FECHA_INICIO_COTIZACION, $AS_NUM_CUOTAS, $AS_VALOR_CUOTA, $AS_SALDO_ACUMULADO)
    {
        $retiro = '';
        if ($AS_TIENE_CC == 'SI') {
            $retiro = '   <td colspan="1" class="style91" >SI</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">X</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" class="style91" >NO</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"></td>
';
        } else {
            $retiro = '   <td colspan="1" class="style91" >SI</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt"></td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" class="style91" >NO</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt">X</td>';
        }
        if ($AS_FECHA_INICIO_COTIZACION == '') {
            $fecha = '<td colspan="1" class="style91" >Día</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt"></td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" class="style91" >Mes</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"></td>
                        <td colspan="1" class="style91" >Año</td>
                        <td colspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"></td>
                        <td colspan="10" class="style92" ></td>';
        } else {
            $fecha_exp = explode("-", $AS_FECHA_INICIO_COTIZACION);
            $fecha = '<td colspan="1" class="style91" >Día</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">' . $fecha_exp[2] . '</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" class="style91" >Mes</td>
                        <td colspan="1" class="style91" ></td>
                        <td colspan="1" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt">' . $fecha_exp[1] . '</td>
                        <td colspan="1" class="style91" >Año</td>
                        <td colspan="2" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"> ' . $fecha_exp[0] . '</td>
                        <td colspan="10" class="style92" ></td>';
        }

        if ($AS_NUM_CUOTAS == "") {
            $AS_NUM_CUOTAS_redondeado = $AS_NUM_CUOTAS;
            $AS_VALOR_CUOTA_redondeado = $AS_VALOR_CUOTA;
            $AS_SALDO_ACUMULADO_redondeado =  $AS_SALDO_ACUMULADO;
        } else {
            $AS_NUM_CUOTAS_redondeado = round($AS_NUM_CUOTAS, 2);
            $AS_VALOR_CUOTA_redondeado = round($AS_VALOR_CUOTA, 2);
            $AS_SALDO_ACUMULADO_redondeado =  round($AS_SALDO_ACUMULADO, 2);
        }

        $html = ' <tr class="row47"><td class="style43" colspan="28"> VI. CUENTA PERSONAL PREVISIONAL  </td> </tr>
            <tr class="row47">
                        <td colspan="10" class="style90"> Tiene Compensación de Cotizaciones (CC)</td>
                       ' . $retiro . '
                        <td colspan="5" class="style91"> Monto CC</td>
                        <td colspan="3" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"> ' . $AS_CC . '</td>
                        <td colspan="7"  class="style92" ></td>

            </tr>
            <tr class="row47">
                        <td colspan="10" class="style90"> Fecha de inicio de Cotización al SSO/SIP</td>
                      ' . $fecha . '
            </tr>
            <tr class="row47">
                        <td colspan="14" class="style90"> - N° de Cuotas correspondientes al Saldo Acumulado:</td>
                        <td colspan="3"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">' . $AS_NUM_CUOTAS_redondeado . '</td>
                        <td colspan="1" class="style91" >a</td>
                        <td colspan="13" class="style92" ></td>
            </tr>
            <tr class="row47">
                        <td colspan="14" class="style90"> - Valor Cuota a la fecha del día anterior a la recepción de la Solicitud:</td>
                        <td colspan="3"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">' . $AS_VALOR_CUOTA_redondeado . '</td>
                        <td colspan="1" class="style91" >b</td>
                        <td colspan="13"  class="style92" ></td>
            </tr>
            <tr class="row47">
                        <td colspan="14" class="style90"> - Saldo Acumulado en la Cuenta Personal Previsional en Bolivianos:</td>
                        <td colspan="4"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">' . $AS_SALDO_ACUMULADO_redondeado . '</td>
                        <td colspan="2" class="style91" > a x b</td>
                        <td colspan="11"  class="style92" ></td>
            </tr>
        ';

        return $html;
    }
    public function generateHtmlOtrosEstados($estado)
    {
        $html = '';
        if ($estado != 'SI') {
            $html = '<tr class="row9">
                    <td class="style16" colspan="2">   </td>
                    <td class="style16" colspan="28">
                        1) No tengo aportes efectuados a la Seguridad Social de Largo Plazo de otro(s) país(es) diferente(s) a Bolivia:
                    </td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="8">
                    </td>
                    <td class=" style5 " colspan="1">X</td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="2">
                    </td>
                    <td class="style16" colspan="28">
                        2) Tengo aportes efectuados a la Seguridad Social de Largo Plazo de otro(s) país(es) diferente(s) a Bolivia:
                    </td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="8">
                    </td>
                    <td class=" style5 " colspan="1"></td>
                </tr>';
        } else {
            $html = '<tr class="row9">
                    <td class="style16" colspan="2">   </td>
                    <td class="style16" colspan="28">
                        1) No tengo aportes efectuados a la Seguridad Social de Largo Plazo de otro(s) país(es) diferente(s) a Bolivia.
                    </td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="8">
                    </td>
                    <td class=" style5 " colspan="1"></td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="2">
                    </td>
                    <td class="style16" colspan="28">
                        2) Tengo aportes efectuados a la Seguridad Social de Largo Plazo de otro(s) país(es) diferente(s) a Bolivia:
                    </td>
                </tr>
                <tr class="row9">
                    <td class="style16" colspan="8">
                    </td>
                    <td class=" style5 " colspan="1">X</td>
                </tr>';
        }

        return $html;
    }
    public function generateHtmlGrado($grado)
    {
        $tipo = '';
        if ($grado == 'Hija o Hijo' or $grado == 'Conyugue' or $grado == 'Conviviente') {
            $tipo = '   <td class="style15" colspan="4">1er. Grado</td>
                        <td class="style20" colspan="1">X</td>
                        <td class="style31" colspan="4">2do. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">3er. Grado</td>
                        <td class="style20" colspan="1"></td>';
        } else if ($grado == 'Hermano o Hermana' or $grado == 'Padre' or $grado == 'Madre') {
            $tipo = '   <td class="style15" colspan="4">1er. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">2do. Grado</td>
                        <td class="style20" colspan="1">X</td>
                        <td class="style31" colspan="4">3er. Grado</td>
                        <td class="style20" colspan="1"></td>';
        } else if ($grado == 'Otros') {
            $tipo = '   <td class="style15" colspan="4">1er. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">2do. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">3er. Grado</td>
                        <td class="style20" colspan="1">X</td>';
        } else {
            $tipo = '   <td class="style15" colspan="4">1er. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">2do. Grado</td>
                        <td class="style20" colspan="1"></td>
                        <td class="style31" colspan="4">3er. Grado</td>
                        <td class="style20" colspan="1"></td>';
        }
        return $tipo;
    }
    /*public function generateHtmlFirma($cas_id)
    {
        $data = \DB::select("select  doc_url from  public._gp_documentos where doc_descripcion = 'Firma del Solicitante' and doc_cas_id = $cas_id");
        if ($data != []) {
            $imagen = $data[0]->doc_url;
            $firma = ' <img src="' . $imagen . '" style="height:50px; width:150px border: none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }
        return $firma;
    }*/
    public function generateHtmlFirma($firmaSolicitante)
    {
        if ($firmaSolicitante !== null && $firmaSolicitante !== '') {
            $firma = '<img src="data:image/png;base64,' . $firmaSolicitante . '" style="height:50px; width:150px; border:none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }
        return $firma;
    }
    public function generateHtmlRetirosMinimos($opcion)
    {
        $tipo = '';
        if ($opcion == 'RM') {
            $tipo = '   <td colspan="6" class="style90"  > Elija una opción:</td>
                        <td colspan="6" class="style91" > Retiros Mínimos</td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="3"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">X</td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="4" class="style91" >Retiro Final</td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="3" style="background-color: #faf9f6; border: 1px solid black; text-align: center;font-size: 8pt"></td>
                        <td colspan="7" rowspan="3" class="style92" ></td> ';
        } else {
            $tipo = '   <td colspan="6" class="style90"  > Elija una opción:</td>
                        <td colspan="6" class="style91" > Retiros Mínimos</td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="3"  style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt"></td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="4" class="style91" >Retiro Final</td>
                        <td colspan="2" class="style91" ></td>
                        <td colspan="3" style="background-color: #faf9f6; border: 1px solid black; text-align: center; font-size: 8pt">X</td>
                        <td colspan="7" rowspan="3" class="style92" ></td> ';
        }
        return $tipo;
    }
    public function generateHtmlTipo($tipo)
    {
        if ($tipo == 'I') {
            $tipo = ' <td class="style30" colspan="1">CI</td>
                <td class="style20" colspan="1">X</td>
                <td class="style31" colspan="1">CE</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="3">PAS</td>
                <td class="style20" colspan="1"></td>';
        } else if ($tipo == 'E') {
            $tipo = ' <td class="style30" colspan="1">CI</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="1">CE</td>
                <td class="style20" colspan="1">X</td>
                <td class="style31" colspan="3">PAS</td>
                <td class="style20" colspan="1"></td>';
        } else {
            $tipo = ' <td class="style30" colspan="1">CI</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="1">CE</td>
                <td class="style20" colspan="1"></td>
                <td class="style31" colspan="3">PAS</td>
                <td class="style20" colspan="1">X</td>';
        }
        return $tipo;
    }

    public function listarImpresionId(Request $request)
    {
        $_id = $request['act_id'];
        $imp_id = $request['imp_id'];
        if (!empty($_id)) {
            $where = 'AND impact_act_id = ' . $_id . 'AND imp_id = ' . $imp_id;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_estado,imp_tipo
                FROM rmx_vys_impresiones
                inner join rmx_vys_impresiones_actividades on impact_imp_id = imp_id
                WHERE imp_estado = 'A' and impact_estado='A' $where
                ORDER BY imp_nombre");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosXImpresion(Request $request)
    {
        $usr_id = $request["usr_id"];
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {

            $data = \DB::select("SELECT c.cas_data_valores , a.act_data, c.cas_data
                FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
                    join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                    join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND n.usn_estado <> 'X'
                    AND n.usn_user_id = $usr_id
                    and c.cas_id = $cas_id
                    and c.cas_act_id = $cas_act_id
                ORDER BY c.cas_modificado desc");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function generatePiePagina($cas_id, $cantidad_firmas, $tipo, $firmaSolicitante, $fecha, $AS_FECHA_APERSONAMIENTO)
    {

        $firma = '';
        /*if ($data != []) {
            $imagen = $data[0]->doc_url;
            $firma = ' <img src="' . $imagen . '" style="height:50px; width:150px border: none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }*/
        if ($firmaSolicitante !== null && $firmaSolicitante !== '') {
            $firma = '<img src="data:image/png;base64,' . $firmaSolicitante . '" style="height:50px; width:150px; border:none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }
        $fechaActual = new DateTime();
        $fechaSumada = $fechaActual->format('d-m-Y');
        $data = \DB::select("SELECT to_char(public.calcular_dias_habiles('$fechaSumada'), 'yyyy-MM-dd') AS fecha_formateada");
        $dias = $data[0]->fecha_formateada;
        $dias = explode("-", $dias);
        $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];
        if ($tipo == 'MN') {

            if ($fecha == 'CVEAP-B6' || $fecha == 'CVEAP-B7') {
                $dias_habiles = '';
            } else if ($fecha == 'CVEAP-B8') {
                $dias_habiles = $AS_FECHA_APERSONAMIENTO;
            }

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1.  Si el Asegurado no cumple con los requisitos para acceder a Prestación de Vejez o Solidaria de Vejez y de Riesgos, la Gestora rechazara el presente trámite. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un plazo de DOCE (12) meses de la fecha de solicitud consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Asegurado presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28" > 3. En el evento, que el Poder de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                                                      <tr class="row35">
                            <td class="style27" colspan="14"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="10" rowspan = "2" > Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>

                        <tr class="row23"><td class="style40" colspan="28"></td></tr>';
        } else if ($tipo == 'REC') {
            $html_pie = '';
        } else if ($tipo == 'MN_1582') {
            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1.  Si el Asegurado no cumple con los requisitos para acceder a Prestación de Vejez o Solidaria de Vejez y de Riesgos, la Gestora rechazara el presente trámite. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un plazo de DOCE (12) meses de la fecha de solicitud consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Asegurado presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28" > 3. En el evento, que el Poder de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado. </td></tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        ';
        } else if ($tipo == 'RMIN') {
            if ($fecha = '') {
            } else {
                $fechaActual = new DateTime();
                $fecha = $fechaActual->format('d-m-Y');
            }

            $data = \DB::select("SELECT to_char(public.calcular_n_habiles('$fecha',10), 'yyyy-MM-dd') AS fecha_formateada");
            $dias = $data[0]->fecha_formateada;
            $dias = explode("-", $dias);
            $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];
            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1. Si el Asegurado no cumple con los requisitos para acceder a Retiros Mínimos / Retiro Final, la Gestora rechazará el presente trámite.</td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un plazo de DOCE (12) meses de la fecha de solicitud consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original. En caso de no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Asegurado presentar una nueva solicitud en cualquier momento </td></tr>
                         <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="12"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="12" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else if ($tipo == 'DC') {

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> Habiendo revisado el Dictamen señalado en el numeral III. anterior, y toda vez que me encuentro de acuerdo con lo establecido en el mismo y con la fecha de siniestro (invalidez o fallecimiento) determinada, renuncio a mi derecho a pedir Revision de Dictamen, por lo que de no contar la Gestora con una solicitud de revision anterior y no ser parte del control concurrente, en el marco del artículo 154 del Reglamento del Decreto Supremo N° 0822/2011, solicito que el citado dictamen quede ejecutoriado a partir de la fecha de entrega y suscripcion del presente formulario.</td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> Renuncio a mi derecho a pedir revisión de Dictámen:</td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="14"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="10" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else if ($tipo == 'GNL') {

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style23" colspan="28">LAS REVISIONES Y VALIDACIONES QUE REALIZA LA GERENCIA NACIONAL LEGAL, SON DE ORDEN COMPLETAMENTE JURIDICA EN OBSERVANCIA DE LOS REQUISITOS DE FORMA Y FONDO ESTABLECIDA EN LA LEY DEL NOTARIADO (LEY 483) Y SU REGLAMENTO. LA RECEPCION HASTA SU CONCLUSIÓN EN UNA SOLICITUD DE PRESTACIÓN U OTRO TRAMITE, ES DE ENTERA RESPONSABILIDAD DE LAS PLATAFORMAS DE ATENCION AL CLIENTE YA SEA EN OFICINA REGIONAL O NACIONAL.</td></tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else if ($tipo == 'DC2') {

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                          <tr class="row7"> <td class="column0 style43 s" colspan="28"> IV. RENUCIA AL DERECHO DE SOLICITAR REVISÍON DE DICTAMEN </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> Habiendo revisado el Dictamen señalado en el numeral III. anterior, y toda vez que me encuentro de acuerdo con lo establecido en el mismo y con la fecha de siniestro (invalidez o fallecimiento) determinada, renuncio a mi derecho a pedir Revision de Dictamen, por lo que de no contar la Gestora con una solicitud de revision anterior y no ser parte del control concurrente, en el marco del artículo 154 del Reglamento del Decreto Supremo N° 0822/2011, solicito que el citado dictamen quede ejecutoriado a partir de la fecha de entrega y suscripcion del presente formulario.</td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28">  Renuncio a mi derecho a pedir revisión de Dictámen:  <strong> SI </strong> </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                       <tr class="row35"><td class="style23" colspan="28"> En el evento que el Poder o Test. de Tutoria de inicio de tramite no cumpla las condiciones legales y fuera observado por la gestora en suproceso de validacion, este tramite sera anulado.</td></tr>

                       ';
        } else if ($tipo == 'RMIN20') {
            $fecha_actual = $fechaActual->format('d-m-Y');
            $data = \DB::select("SELECT to_char(public.calcular_n_habiles('$fecha_actual',20), 'yyyy-MM-dd') AS fecha_formateada");
            $dias = $data[0]->fecha_formateada;
            $dias = explode("-", $dias);
            $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1.  Si el Asegurado no cumple con los requisitos para acceder a Prestación de Vejez o Solidaria de Vejez y de Riesgos, la Gestora rechazara el presente trámite. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un plazo de DOCE (12) meses de la fecha de solicitud consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Asegurado presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35"><td class="style23" colspan="28" > 3. En el evento, que el Poder de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado. </td></tr>
                        <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="14"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="10" rowspan = "2" > Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>

                       ';
        } else if ($tipo == 'PAGCC') {

            $fecha_actual = $fechaActual->format('d-m-Y');
            $data = \DB::select("SELECT to_char(public.calcular_n_habiles('$fecha_actual',10), 'yyyy-MM-dd') AS fecha_formateada");
            $dias = $data[0]->fecha_formateada;
            $dias = explode("-", $dias);
            $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];

            if ($fecha == 'CVEAP-B4') {
                $dias_habiles = $AS_FECHA_APERSONAMIENTO;
            }

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL</td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un <strong>  plazo de DOCE (12) meses de la fecha de solicitud  </strong>  consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Solicitante presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. En el evento, que el Poder o Test. de Tutoría de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado.</td></tr>
                         <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="12"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="12" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else if ($tipo == 'LEGAL') {  //LEGAL
            $fecha_actual = $fechaActual->format('d-m-Y');
            $data = \DB::select("SELECT to_char(public.calcular_n_habiles('$fecha_actual',20), 'yyyy-MM-dd') AS fecha_formateada");
            $dias = $data[0]->fecha_formateada;
            $dias = explode("-", $dias);
            $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];

            if ($fecha == 'CVEAP-B4') {
                $dias_habiles = $AS_FECHA_APERSONAMIENTO;
            }

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL</td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un <strong>  plazo de DOCE (12) meses de la fecha de solicitud  </strong>  consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Solicitante presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. En el evento, que el Poder o Test. de Tutoría de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado.</td></tr>
                         <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="12"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="12" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else if ($tipo == 'MAGER') {
            $fecha_actual = $fechaActual->format('d-m-Y');
            $data = \DB::select("SELECT to_char(public.calcular_n_habiles('$fecha_actual',20), 'yyyy-MM-dd') AS fecha_formateada");
            $dias = $data[0]->fecha_formateada;
            $dias = explode("-", $dias);
            $dias_habiles = $dias[2] . '-' . $dias[1] . '-' . $dias[0];

            if ($fecha == 'CVEAP-B4') {
                $dias_habiles = $AS_FECHA_APERSONAMIENTO;
            }

            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL</td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un <strong>  plazo de DOCE (12) meses de la fecha de solicitud  </strong>  consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Solicitante presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. En el evento, que el Poder o Test. de Tutoría de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado.</td></tr>
                         <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="12"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="12" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        } else {
            $html_pie = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35"><td class="style22" colspan="28"> AVISO LEGAL </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 1. El solicitante que hubiera entregado los documentos de acreditación en fotocopia, será notificado por escrito cuando existan observaciones a los mismos, para que en un <strong>  plazo de DOCE (12) meses de la fecha de solicitud  </strong>  consignada en el presente formulario, rectifique las mismas y/o presente los documentos en original.  De no regularizar las observaciones y/o presentar los documentos requeridos en el plazo señalado, la Gestora anulará el presente trámite, pudiendo el Solicitante presentar una nueva solicitud en cualquier momento. </td></tr>
                        <tr class="row35"><td class="style23" colspan="28"> 2. En el evento, que el Poder o Test. de Tutoría de inicio de trámite no cumpla las condiciones legales y fuera observado por la Gestora en su proceso de validación, este trámite será anulado.</td></tr>
                         <tr class="row35"><td class="style21" colspan="28"> </td></tr>
                        <tr class="row35">
                            <td class="style27" colspan="12"  rowspan = "2" > EL SOLICITANTE DEBE RETORNAR A LA GESTORA A PARTIR DE FECHA: </td>
                            <td class="style28" colspan="4"  rowspan = "2" > ' . $dias_habiles . '</td>
                            <td class="style26" colspan="12" rowspan = "2"> Entre tanto el Solicitante no se presente en la Gestora a partir de la fecha indicada, para notificarse con los resultados de la verificación de los documentos y Estado de Ahorro Previsional, su trámite no podrá ser completado.  </td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                       ';
        }

        if ($cantidad_firmas != 0) {
            $espacio_firma = '';
        } else {
            if ($tipo == 'MN_1582') {
                $espacio_firma = '
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35">
                        <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                        <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                        </tr>
                        <tr class="row23"><td class="style40" colspan="28"></td></tr>
                        <tr class="row35">
                        <td class="style17" colspan="14"> FIRMA DEL ASEGURADO/SOLICITANTE </td>
                        <td class="style17" colspan="14"> FIRMA DE RECEPCIÓN RESPONSABLE DE GESTORA</td>
                        </tr>';
            } else {
                $espacio_firma = '
                    <tr class="row23"><td class="style40" colspan="28"></td></tr>
                    <tr class="row35">
                        <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                        <td class="style17" colspan="14" rowspan ="2"> <br><br><br></td>
                    </tr>
                    <tr class="row23"><td class="style40" colspan="28"></td></tr>
                    <tr class="row35">
                        <td class="style17" colspan="14"> FIRMA DEL ASEGURADO O SOLICITANTE </td>
                        <td class="style17" colspan="14"> FIRMA DE RECEPCIÓN RESPONSABLE DE GESTORA</td>
                    </tr>';
            }
        }
        return $html_pie . $espacio_firma;
    }
    public function generateHtmlClaves($clave, $primer_apellido, $segundo_apellido, $apellido_casado, $nombre, $identidad, $fecha_nacimiento)
    {
        $html_fila_claves = '<tr class="row35">
                                <td class="style17" colspan="2"> ' . $clave . '</td>
                                <td class="style17" colspan="5">' . $primer_apellido . '</td>
                                <td class="style17" colspan="4">' . $segundo_apellido . '</td>
                                <td class="style17" colspan="4">' . $apellido_casado . '</td>
                                <td class="style17" colspan="4">' . $nombre . '</td>
                                <td class="style17" colspan="3">' . $identidad . '</td>
                                <td class="style18" colspan="4">' . $fecha_nacimiento . '</td>
                            </tr>';
        return $html_fila_claves;
    }

    public function generateHtmlDocumentoTecnico($cas_id)
    {
        // ADJUNTO_MEDICOS
        $dataDocumetos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = 'ADJUNTO_MEDICOS' order by doc_id desc"); //doc_id
        $dataDocumetoMedico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_descripcion = 'Certificado Médico' order by doc_id desc"); //doc_id
        $nro = 1;
        $html_documento = '';
        if ($dataDocumetoMedico[0]->doc_url != '') {
            $original = $dataDocumetoMedico[0]->doc_copia_original == 'false' ? 'X' : '';
            $fotocopia = $dataDocumetoMedico[0]->doc_copia_original == 'true' ? 'X' : '';
            $html_documento = '<tr class="row35">
                <td class="column6 style14 s style16" colspan="3">' . $nro . '</td>
                <td class="column6 style14 s style16" colspan="19">' . $dataDocumetoMedico[0]->doc_descripcion . '</td>
                <td class="column6 style14 s style16" colspan="3"> ' . $original . '  </td>
                <td class="column6 style14 s style16" colspan="3"> ' . $fotocopia . ' </td>
            </tr>';
            $nro++;
        }
        foreach ($dataDocumetos as $documento) {
            $original = $documento->doc_copia_original == 'false' ? 'X' : '';
            $fotocopia = $documento->doc_copia_original == 'true' ? 'X' : '';
            $html = '   <tr class="row35">
                <td class="column6 style14 s style16" colspan="3">' . $nro . '</td>
                <td class="column6 style14 s style16" colspan="19">' . $documento->doc_descripcion . '</td>
                <td class="column6 style14 s style16" colspan="3"> ' . $original . '  </td>
                <td class="column6 style14 s style16" colspan="3"> ' . $fotocopia . ' </td>
            </tr>';
            $html_documento .= $html;
            $nro++;
        }
        return $html_documento;
    }
    public function generateHtmlSolicitantedoDocumentoPbsHtml($cas_id)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $tipoobsci = '';
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-SOL' order by doc_id desc");
        foreach ($dataHistorico as $historico) {
            if ($historico->doc_descripcion == 'Cedula de Identidad') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_carnet .= '  <td class="style84" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_carnet .= '  <td class="style84" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    $tipoobsci = $this->tipoPbsHtml($historico->doc_id_observacion);
                }
            }
            if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_nacimiento .= '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento .= '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
                if ($historico->doc_copia_original == 'true') {
                    $copia_nacimiento_o_c .= '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento_o_c .= '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
            }
        }
        $html_documento_solicitante = $copia_carnet;
        return $tipoobsci;
    }

    public function generateHtmlSolicitantedoDocumento($cas_id)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $tipoobsci = '';
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-SOL' order by doc_id desc");
        foreach ($dataHistorico as $historico) {
            if ($historico->doc_descripcion == 'Cedula de Identidad') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_carnet = '  <td class="style84" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_carnet = '  <td class="style84" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    $tipoobsci = $this->tipoPbsHtml($historico->doc_id_observacion);
                }
            }
            if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_nacimiento = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
                if ($historico->doc_copia_original == 'true') {
                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
            }
        }
        $html_documento_solicitante = $copia_carnet;
        return $html_documento_solicitante;
    }

    public function generateHtmlSolicitantedoDocumentoAsegurado($cas_id)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $tipoobsci = '';
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-TIT' order by doc_id desc");
        foreach ($dataHistorico as $historico) {
            if ($historico->doc_descripcion == 'Cedula de Identidad') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_carnet = '  <td class="style84" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_carnet = '  <td class="style84" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    $tipoobsci = $this->tipoPbsHtml($historico->doc_id_observacion);
                }
            }
            if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_nacimiento = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
                if ($historico->doc_copia_original == 'true') {
                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
            }
        }
        $html_documento_solicitante = $copia_carnet;
        return $html_documento_solicitante;
    }

    public function generateHtmlPeriodosSolicitados($tit_periodos_solicitados, $PERIODO_INICIO, $PERIODO_FIN, $HA_PAGO_AGUINALDO_LEGAL)
    {
        $html_periodos_solicitados = '
            <tr class="row33">
            <td class="style43" colspan="28">' . $tit_periodos_solicitados . '</td>
            </tr>
            <tr class="row43">
            <td class="style19" colspan="14">' . $PERIODO_INICIO . '</td>
            <td class="style19" colspan="14">' . $PERIODO_FIN . '</td>
            </tr>';

        if ($HA_PAGO_AGUINALDO_LEGAL == 1) {
            $html_periodos_solicitados .= '
            <tr class="row43">
                <td class="style19" colspan="14">PAGO DE AGUINALDO</td>
                <td class="style19" colspan="14">SI</td>
            </tr>';
        }

        $html_periodos_solicitados .= '
            <tr class="row23">
            <td class="style40" colspan="28"></td>
            </tr>';

        return $html_periodos_solicitados;
    }

    public function generateHtmlDatosPoderLegal($estado_derivacion)
    {
        $ESTADO_DERIVACION_MARCA_VAL = '';
        $ESTADO_DERIVACION_MARCA_RCHZ = '';

        if ($estado_derivacion == 'VAL') {
            $ESTADO_DERIVACION_MARCA_VAL = 'X';
        } else if ($estado_derivacion == 'RCHZ_LEGAL') {
            $ESTADO_DERIVACION_MARCA_RCHZ = 'X';
        }

        $html_documento_solicitante = '
            <td class="style85" colspan="2"> ' . $ESTADO_DERIVACION_MARCA_VAL . ' </td>
            <td class="style86" colspan="2"> ' . $ESTADO_DERIVACION_MARCA_RCHZ . ' </td>
        ';

        return $html_documento_solicitante;
    }

    public function generateHtmlHeredadosLegal($tit_dahe_apoderados_herederos, $grilla_apod_heredados)
    {
        $html_apoderados_heredados = '
            <tr class="row33">
                <td class="style43" colspan="28">' . $tit_dahe_apoderados_herederos . '</td>
            </tr>';

        foreach ($grilla_apod_heredados as $item) {
            $DAHE_NOMBRES = '';
            $DAHE_CI_GRILLA_PROP = '';
            $DAHE_FECHA_NAC = '';
            $DAHE_PRIMER_APELLIDO = '';
            $DAHE_SEGUNDO_APELLIDO = '';
            $DAHE_GENERO = '';
            $DAHE_FACULTAD = false;
            $DAHE_TIPO_DOCUMENTO = '';
            $DAHE_APELLIDO_CASADA = '';

            foreach ($item as $field) {
                if ($field['col_campo'] == 'DAHERDERO_NOMBRES') {
                    $DAHE_NOMBRES = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_CI_GRILLA_PROP') {
                    $DAHE_CI_GRILLA_PROP = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_FECHA_NAC') {
                    $fecha_exp = explode("-", $field['col_value']);
                    $DAHE_FECHA_NAC = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                } elseif ($field['col_campo'] == 'DAHERDERO_PRIMER_APELLIDO') {
                    $DAHE_PRIMER_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_SEGUNDO_APELLIDO') {
                    $DAHE_SEGUNDO_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_APELLIDO_CASADA') {
                    $DAHE_APELLIDO_CASADA = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_GENERO') {
                    $DAHE_GENERO = $field['col_value'] == 'F' ? 'FEMENINO' : 'MASCULINO';
                } elseif ($field['col_campo'] == 'DAHERDERO_FACULTAD') {
                    $DAHE_FACULTAD = $field['col_value'] == 'true';
                } elseif ($field['col_campo'] == 'DAHERDERO_TIPO_DOCUMENTO') {
                    //$DAHE_TIPO_DOCUMENTO = $field['col_value'] == 'I' ? 'CEDULA DE IDENTIDAD' : $field['col_value'];
                    $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                    switch ($field['col_value']) {
                        case "I":
                            $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                            break;
                        case "E":
                            $DAHE_TIPO_DOCUMENTO = "EXTRANJERO";
                            break;
                        case "P":
                            $DAHE_TIPO_DOCUMENTO = "PASAPORTE";
                            break;
                        default:
                            $DAHE_TIPO_DOCUMENTO = "TEMPORAL";
                            break;
                    }
                }
            }

            if ($DAHE_FACULTAD) {
                $html_apoderados_heredados .= '
                <tr class="row35">
                    <td class="style16" colspan="7">Primer Apellido</td>
                    <td class="style17" colspan="7">Segundo Apellido</td>
                    <td class="style17" colspan="7">Nombres</td>
                    <td class="style17" colspan="6">Tipo. Doc. Ident.</td>
                </tr>
                <tr class="row36">
                    <td class="style84" colspan="7">' . $DAHE_PRIMER_APELLIDO . '</td>
                    <td class="style85" colspan="7">' . $DAHE_SEGUNDO_APELLIDO . ' ' . $DAHE_APELLIDO_CASADA . '</td>
                    <td class="style85" colspan="7">' . $DAHE_NOMBRES . '</td>
                    <td class="style85" colspan="6">' . $DAHE_TIPO_DOCUMENTO . '</td>
                </tr>
                <tr class="row39">
                    <td class="style16" colspan="9">N° de Doc. de Identidad</td>
                    <td class="style17" colspan="8">Fecha de Nacimiento</td>
                    <td class="style18" colspan="10">Sexo</td>
                </tr>
                <tr class="row40">
                    <td class="style84" colspan="9">' . $DAHE_CI_GRILLA_PROP . '</td>
                    <td class="style85" colspan="8">' . $DAHE_FECHA_NAC . '</td>
                    <td class="style86" colspan="10">' . $DAHE_GENERO . '</td>
                </tr>';
            }
        }

        return $html_apoderados_heredados;
    }

    public function generateHtmlApoderantesNombresLegal($grilla_apod_heredados)
    {
        $fila_detalles = '';
        $fila_detalles_false = '';
        foreach ($grilla_apod_heredados as $item) {
            $DAHE_NOMBRES = '';
            $DAHE_CI_GRILLA_PROP = '';
            $DAHE_FECHA_NAC = '';
            $DAHE_PRIMER_APELLIDO = '';
            $DAHE_SEGUNDO_APELLIDO = '';
            $DAHE_APELLIDO_CASADA = '';
            $DAHE_GENERO = '';
            $DAHE_FACULTAD = false;
            $DAHE_TIPO_DOCUMENTO = '';


            foreach ($item as $field) {
                if ($field['col_campo'] == 'DAHE_NOMBRES') {
                    $DAHE_NOMBRES = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_CI_GRILLA_PROP') {
                    $DAHE_CI_GRILLA_PROP = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_FECHA_NAC') {
                    $fecha_exp = explode("-", $field['col_value']);
                    $DAHE_FECHA_NAC = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                } elseif ($field['col_campo'] == 'DAHE_PRIMER_APELLIDO') {
                    $DAHE_PRIMER_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_SEGUNDO_APELLIDO') {
                    $DAHE_SEGUNDO_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_APELLIDO_CASADA') {
                    $DAHE_APELLIDO_CASADA = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_GENERO') {
                    $DAHE_GENERO = $field['col_value'] == 'F' ? 'FEMENINO' : 'MASCULINO';
                } elseif ($field['col_campo'] == 'DAHE_FACULTAD') {
                    $DAHE_FACULTAD = $field['col_value'] == 'true';
                } elseif ($field['col_campo'] == 'DAHE_TIPO_DOCUMENTO') {
                    //$DAHE_TIPO_DOCUMENTO = $field['col_value'] == 'I' ? 'CEDULA DE IDENTIDAD' : $field['col_value'];
                    $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                    switch ($field['col_value']) {
                        case "I":
                            $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                            break;
                        case "E":
                            $DAHE_TIPO_DOCUMENTO = "EXTRANJERO";
                            break;
                        case "P":
                            $DAHE_TIPO_DOCUMENTO = "PASAPORTE";
                            break;
                        default:
                            $DAHE_TIPO_DOCUMENTO = "TEMPORAL";
                            break;
                    }
                }
            }

            if ($DAHE_FACULTAD) {

                $fila =  $DAHE_NOMBRES . ' ' . $DAHE_PRIMER_APELLIDO . ' ' . $DAHE_SEGUNDO_APELLIDO . ' ' . $DAHE_APELLIDO_CASADA . '<br>';
                $fila_detalles .= $fila;
            }
        }

        return $fila_detalles;
    }

    public function generateHtmlNombreCaso($idPrestacion)
    {

        $resultados = DB::select("
            SELECT
                prc_id,
                (prc_data->>'prc_descripcion') AS AS_TIPO_EAP_LEGAL,
                lgpsub_id,
                lgpsub_nombre AS AS_SUB_SOLICITUD,
                lgp_id,
                lgp_nombre AS AS_TIPO_EAP,
                columnas_involucradas,
                CASE
                    WHEN columnas_involucradas = ARRAY['AS_TIPO_EAP_LEGAL'] THEN
                        (prc_data->>'prc_descripcion')
                    WHEN columnas_involucradas = ARRAY['AS_TIPO_EAP_LEGAL', 'AS_TIPO_EAP'] THEN
                        (prc_data->>'prc_descripcion') || '/' || lgp_nombre
                    WHEN columnas_involucradas = ARRAY['AS_SUB_SOLICITUD', 'AS_TIPO_EAP'] THEN
                        lgpsub_nombre || '/' || lgp_nombre
                    WHEN columnas_involucradas = ARRAY['AS_TIPO_EAP_LEGAL', 'AS_SUB_SOLICITUD', 'AS_TIPO_EAP'] THEN
                        (prc_data->>'prc_descripcion') || '/' || lgpsub_nombre || '/' || lgp_nombre
                   WHEN columnas_involucradas = ARRAY['AS_TIPO_EAP_LEGAL', 'AS_SUB_SOLICITUD'] THEN
                       (prc_data->>'prc_descripcion')  || '/' || lgpsub_nombre
                    ELSE NULL
                END AS valor_concatenado
            FROM public.rmx_vys_procesos
            INNER JOIN public.lgprestaciones_subclf ON prc_id = lgpsub_prs_id
            INNER JOIN public.lgprestaciones ON lgpsub_id = lgp_lgpsub_id
            WHERE prc_proceso = 'P' AND prc_estado = 'A'
              AND lgp_estado = 'A'
              AND lgpsub_estado = 'A'
              AND lgp_id = ?
            ORDER BY (prc_data->>'prc_descripcion') ASC
        ", [$idPrestacion]);

        return $resultados[0]->valor_concatenado ?? null; // o genera HTML si lo necesitas
    }


    public function generateHtmlHerederoNombresLegal($grilla_apod_heredados)
    {
        $fila_detalles = '';
        $fila_detalles_false = '';
        foreach ($grilla_apod_heredados as $item) {
            $DAHE_NOMBRES = '';
            $DAHE_CI_GRILLA_PROP = '';
            $DAHE_FECHA_NAC = '';
            $DAHE_PRIMER_APELLIDO = '';
            $DAHE_SEGUNDO_APELLIDO = '';
            $DAHE_APELLIDO_CASADA = '';
            $DAHE_GENERO = '';
            $DAHE_FACULTAD = false;
            $DAHE_TIPO_DOCUMENTO = '';

            foreach ($item as $field) {
                if ($field['col_campo'] == 'DAHERDERO_NOMBRES') {
                    $DAHE_NOMBRES = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_CI_GRILLA_PROP') {
                    $DAHE_CI_GRILLA_PROP = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_FECHA_NAC') {
                    $fecha_exp = explode("-", $field['col_value']);
                    $DAHE_FECHA_NAC = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                } elseif ($field['col_campo'] == 'DAHERDERO_PRIMER_APELLIDO') {
                    $DAHE_PRIMER_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_SEGUNDO_APELLIDO') {
                    $DAHE_SEGUNDO_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_APELLIDO_CASADA') {
                    $DAHE_APELLIDO_CASADA = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHERDERO_GENERO') {
                    $DAHE_GENERO = $field['col_value'] == 'F' ? 'FEMENINO' : 'MASCULINO';
                } elseif ($field['col_campo'] == 'DAHERDERO_FACULTAD') {
                    $DAHE_FACULTAD = $field['col_value'] == 'true';
                } elseif ($field['col_campo'] == 'DAHERDERO_TIPO_DOCUMENTO') {
                    // $DAHE_TIPO_DOCUMENTO = $field['col_value'] == 'I' ? 'CEDULA DE IDENTIDAD' : $field['col_value'];

                    $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                    switch ($field['col_value']) {
                        case "I":
                            $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                            break;
                        case "E":
                            $DAHE_TIPO_DOCUMENTO = "EXTRANJERO";
                            break;
                        case "P":
                            $DAHE_TIPO_DOCUMENTO = "PASAPORTE";
                            break;
                        default:
                            $DAHE_TIPO_DOCUMENTO = "TEMPORAL";
                            break;
                    }
                }
            }

            if ($DAHE_FACULTAD) {

                $fila =  $DAHE_NOMBRES . ' ' . $DAHE_PRIMER_APELLIDO . ' ' . $DAHE_SEGUNDO_APELLIDO . ' ' . $DAHE_APELLIDO_CASADA . '<br>';
                $fila_detalles .= $fila;
            }
        }

        return $fila_detalles;
    }

    public function generateHtmlApoderadosHeredadosLegal($tit_dahe_apoderados_herederos, $grilla_apod_heredados)
    {
        $html_apoderados_heredados = '
            <tr class="row33">
                <td class="style43" colspan="28">' . $tit_dahe_apoderados_herederos . '</td>
            </tr>';

        foreach ($grilla_apod_heredados as $item) {
            $DAHE_NOMBRES = '';
            $DAHE_CI_GRILLA_PROP = '';
            $DAHE_FECHA_NAC = '';
            $DAHE_PRIMER_APELLIDO = '';
            $DAHE_SEGUNDO_APELLIDO = '';
            $DAHE_APELLIDO_CASADA = '';
            $DAHE_GENERO = '';
            $DAHE_FACULTAD = false;
            $DAHE_TIPO_DOCUMENTO = '';

            foreach ($item as $field) {
                if ($field['col_campo'] == 'DAHE_NOMBRES') {
                    $DAHE_NOMBRES = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_CI_GRILLA_PROP') {
                    $DAHE_CI_GRILLA_PROP = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_FECHA_NAC') {
                    $fecha_exp = explode("-", $field['col_value']);
                    $DAHE_FECHA_NAC = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                } elseif ($field['col_campo'] == 'DAHE_PRIMER_APELLIDO') {
                    $DAHE_PRIMER_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_SEGUNDO_APELLIDO') {
                    $DAHE_SEGUNDO_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_APELLIDO_CASADA') {
                    $DAHE_APELLIDO_CASADA = $field['col_value'];
                } elseif ($field['col_campo'] == 'DAHE_GENERO') {
                    $DAHE_GENERO = $field['col_value'] == 'F' ? 'FEMENINO' : 'MASCULINO';
                } elseif ($field['col_campo'] == 'DAHE_FACULTAD') {
                    $DAHE_FACULTAD = $field['col_value'] == 'true';
                } elseif ($field['col_campo'] == 'DAHE_TIPO_DOCUMENTO') {
                    // $DAHE_TIPO_DOCUMENTO = $field['col_value'] == 'I' ? 'CEDULA DE IDENTIDAD' : $field['col_value'];
                    $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                    switch ($field['col_value']) {
                        case "I":
                            $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                            break;
                        case "E":
                            $DAHE_TIPO_DOCUMENTO = "EXTRANJERO";
                            break;
                        case "P":
                            $DAHE_TIPO_DOCUMENTO = "PASAPORTE";
                            break;
                        default:
                            $DAHE_TIPO_DOCUMENTO = "TEMPORAL";
                            break;
                    }
                }
            }

            if ($DAHE_FACULTAD) {
                $html_apoderados_heredados .= '
                <tr class="row35">
                    <td class="style16" colspan="7">Primer Apellido</td>
                    <td class="style17" colspan="7">Segundo Apellido</td>
                    <td class="style17" colspan="7">Nombres</td>
                    <td class="style17" colspan="6">Tipo. Doc. Ident.</td>
                </tr>
                <tr class="row36">
                    <td class="style84" colspan="7">' . $DAHE_PRIMER_APELLIDO . '</td>
                    <td class="style85" colspan="7">' . $DAHE_SEGUNDO_APELLIDO . ' ' . $DAHE_APELLIDO_CASADA . '</td>
                    <td class="style85" colspan="7">' . $DAHE_NOMBRES . '</td>
                    <td class="style85" colspan="6">' . $DAHE_TIPO_DOCUMENTO . '</td>
                </tr>
                <tr class="row39">
                    <td class="style16" colspan="9">N° de Doc. de Identidad</td>
                    <td class="style17" colspan="8">Fecha de Nacimiento</td>
                    <td class="style18" colspan="10">Sexo</td>
                </tr>
                <tr class="row40">
                    <td class="style84" colspan="9">' . $DAHE_CI_GRILLA_PROP . '</td>
                    <td class="style85" colspan="8">' . $DAHE_FECHA_NAC . '</td>
                    <td class="style86" colspan="10">' . $DAHE_GENERO . '</td>
                </tr>';
            }
        }

        return $html_apoderados_heredados;
    }
    public function generateHtmlBenificiarioLegal($tit_dahe_apoderados_herederos, $grilla_apod_heredados)
    {
        $html_apoderados_heredados = '
            <tr class="row33">
                <td class="style43" colspan="28">' . $tit_dahe_apoderados_herederos . '</td>
            </tr>';

        foreach ($grilla_apod_heredados as $item) {
            $DAHE_NOMBRES = '';
            $DAHE_CI_GRILLA_PROP = '';
            $DAHE_FECHA_NAC = '';
            $DAHE_PRIMER_APELLIDO = '';
            $DAHE_SEGUNDO_APELLIDO = '';
            $DAHE_GENERO = '';
            $DAHE_FACULTAD = false;
            $DAHE_TIPO_DOCUMENTO = '';
            $DAHE_APELLIDO_CASADA = '';

            foreach ($item as $field) {
                if ($field['col_campo'] == 'DACO_NOMBRES') {
                    $DAHE_NOMBRES = $field['col_value'];
                } elseif ($field['col_campo'] == 'DACO_CI_GRILLA_PROP') {
                    $DAHE_CI_GRILLA_PROP = $field['col_value'];
                } elseif ($field['col_campo'] == 'DACO_FECHA_NAC') {
                    $fecha_exp = explode("-", $field['col_value']);
                    $DAHE_FECHA_NAC = $fecha_exp[2] . '-' . $fecha_exp[1] . '-' . $fecha_exp[0];
                } elseif ($field['col_campo'] == 'DACO_PRIMER_APELLIDO') {
                    $DAHE_PRIMER_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DACO_SEGUNDO_APELLIDO') {
                    $DAHE_SEGUNDO_APELLIDO = $field['col_value'];
                } elseif ($field['col_campo'] == 'DACO_APELLIDO_CASADA') {
                    $DAHE_APELLIDO_CASADA = $field['col_value'];
                } elseif ($field['col_campo'] == 'DACO_GENERO') {
                    $DAHE_GENERO = $field['col_value'] == 'F' ? 'FEMENINO' : 'MASCULINO';
                } elseif ($field['col_campo'] == 'DACO_FACULTAD') {
                    $DAHE_FACULTAD = $field['col_value'] == 'true';
                } elseif ($field['col_campo'] == 'DACO_TIPO_DOCUMENTO') {
                    $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                    switch ($field['col_value']) {
                        case "I":
                            $DAHE_TIPO_DOCUMENTO = "CEDULA IDENTIDAD";
                            break;
                        case "E":
                            $DAHE_TIPO_DOCUMENTO = "EXTRANJERO";
                            break;
                        case "P":
                            $DAHE_TIPO_DOCUMENTO = "PASAPORTE";
                            break;
                        default:
                            $DAHE_TIPO_DOCUMENTO = "TEMPORAL";
                            break;
                    }
                }
            }

            if ($DAHE_FACULTAD) {
                $html_apoderados_heredados .= '
                <tr class="row35">
                    <td class="style16" colspan="7">Primer Apellido</td>
                    <td class="style17" colspan="7">Segundo Apellido</td>
                    <td class="style17" colspan="7">Nombres</td>
                    <td class="style17" colspan="6">Tipo. Doc. Ident.</td>
                </tr>
                <tr class="row36">
                    <td class="style84" colspan="7">' . $DAHE_PRIMER_APELLIDO . '</td>
                    <td class="style85" colspan="7">' . $DAHE_SEGUNDO_APELLIDO . ' ' . $DAHE_APELLIDO_CASADA . '</td>
                    <td class="style85" colspan="7">' . $DAHE_NOMBRES . '</td>
                    <td class="style85" colspan="6">' . $DAHE_TIPO_DOCUMENTO . '</td>
                </tr>
                <tr class="row39">
                    <td class="style16" colspan="9">N° de Doc. de Identidad</td>
                    <td class="style17" colspan="8">Fecha de Nacimiento</td>
                    <td class="style18" colspan="10">Sexo</td>
                </tr>
                <tr class="row40">
                    <td class="style84" colspan="9">' . $DAHE_CI_GRILLA_PROP . '</td>
                    <td class="style85" colspan="8">' . $DAHE_FECHA_NAC . '</td>
                    <td class="style86" colspan="10">' . $DAHE_GENERO . '</td>
                </tr>';
            }
        }

        return $html_apoderados_heredados;
    }
    public function generateHtmlTestimonioJudicial($tit_testimonio_judicial, $NUMERO_SENTENCIA_AUTO)
    {
        $html_bloque_testimonio_judicial = '

            <tr class="row33">
                <td class="style43" colspan="28">' . $tit_testimonio_judicial . '</td>
            </tr>
            <tr class="row43">
                <td class="style19" colspan="14">N° de Sentencia o Auto</td>
                <td class="style19" colspan="14">' . $NUMERO_SENTENCIA_AUTO . '</td>
            </tr>

            ';

        return $html_bloque_testimonio_judicial;
    }

    public function generateHtmlValidacionRechazado($tit_validacion_rechazo, $DATA_MRCHZ, $DESCRIPCION_FUNDAMENTACION, $VER_FUNDAMENTOS)
    {
        $fila_detalles = '';
        $fila_detalles_false = '';

        if (isset($DATA_MRCHZ) && is_array($DATA_MRCHZ)) {
            foreach ($DATA_MRCHZ as $detalle) {
                $MRCHZ_CUMPLE = '';
                foreach ($detalle as $col) {
                    if ($col['col_campo'] == 'MRCHZ_CUMPLE') {
                        $MRCHZ_CUMPLE = $col['col_value'];
                        if ($MRCHZ_CUMPLE == 'true') {
                            foreach ($detalle as $col) {
                                if ($col['col_campo'] == 'MRCHZ_DESCRIPCION') {
                                    $fila = '<p> - ' . $col['col_value'] . '</p>';
                                    $fila_detalles .= $fila;
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }

        $html_bloque_validacion_rechazo = '
            <tr class="row33">
                <td class="style43" colspan="28">' . $tit_validacion_rechazo . '</td>
            </tr>
            <tr class="row43">
                <td class="style19R" colspan="28">' . $fila_detalles . '
                    <br>';
        if ($VER_FUNDAMENTOS != 1) {
            $html_bloque_validacion_rechazo .= '<p>' . $DESCRIPCION_FUNDAMENTACION . '</p>';
        }
        $html_bloque_validacion_rechazo .= '
                </td>
            </tr>';

        return $html_bloque_validacion_rechazo;
    }

    public function generatehtmlRechazoLegal60($DATA_MRCHZ)
    {
        $fila_detalles = '';

        if (isset($DATA_MRCHZ) && is_array($DATA_MRCHZ)) {
            foreach ($DATA_MRCHZ as $detalle) {
                $MRCHZ_CUMPLE = '';
                foreach ($detalle as $col) {
                    if ($col['col_campo'] == 'MRCHZ_CUMPLE') {
                        $MRCHZ_CUMPLE = $col['col_value'];
                        if ($MRCHZ_CUMPLE == 'true') {
                            foreach ($detalle as $col) {
                                if ($col['col_campo'] == 'MRCHZ_DESCRIPCION') {
                                    $fila = '<p> - ' . $col['col_value'] . '</p>';
                                    $fila_detalles .= $fila;
                                }
                            }
                        }
                        break;
                    }
                }
            }
        }

        return $fila_detalles;
    }

    public function generateHtmlFilaDocumento($tipo_documento, $cas_id, $refrencia, $fecha_nacimiento, $id_persona, $valido)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $copia_matrimonio = '';
        $copia_matrimonio_o_c = '';
        $copia_defuncion = '';
        $copia_defuncion_o_c = '';
        $sql = "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = '$refrencia' and doc_id_persona_sip = '$id_persona' order by doc_id desc";

        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '$refrencia' and doc_id_persona_sip = '$id_persona' order by doc_id desc");

        $html_documento_asegurado = '';
        $matrimonio_ver = '';
        $carnet_ver = '';
        $nacimiento_ver = '';
        $copia_testimonio = '';
        if ($tipo_documento == 'PMDERCON') {

            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet = '<td class="style84" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_carnet = '<td class="style84" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento = ' <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_nacimiento = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {

                        if ($historico->doc_copia_original == 'true') {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Matrimonio') {
                    $copia_testimonio = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="3"></td>';
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_matrimonio = '<td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_matrimonio = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $matrimonio_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_matrimonio_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {
                        if ($historico->doc_copia_original == 'true') {
                            $copia_matrimonio_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_matrimonio_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                } else if ($historico->doc_descripcion == 'Certificado de Convivencia') {
                    $copia_matrimonio = ' <td class="style85" colspan="1"></td> <td class="style85" colspan="1"></td>';
                    $copia_matrimonio_o_c = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_testimonio = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="3">X</td>';
                    } else {
                        $copia_testimonio = '  <td class="style84" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                    }
                }
            }

            $html_documento_asegurado = '   <tr class="row20">
                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                <td class="style17" colspan="4">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="4">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Matrimonio</td>
                                                <td class="style18" colspan="4">Cód. Ver.</td>

                                            </tr>
                                            <tr class="row21">
                                                <td class="style16" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style85" rowspan="2" colspan="4">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" colspan="4" rowspan="2">' . $nacimiento_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style86" rowspan="2" colspan="4">' . $matrimonio_ver . '</td>

                                            </tr>
                                            <tr class="row22">
                                            ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '' . $copia_matrimonio . '' . $copia_matrimonio_o_c . '
                                            </tr>
                                            <tr class="row20">
                                                <td class="style16" colspan="6">Testimonio de Convivencia</td>
                                                <td class="style18" colspan="22" rowspan="3" ></td>
                                            </tr>
                                            <tr class="row20">
                                                <td class="style16" colspan="3">SI</td>
                                                <td class="style18" colspan="3">NO</td>
                                            </tr>
                                           <tr class="row20">
                                          ' . $copia_testimonio . '
                                            </tr>


                                            ';
        } else if ($tipo_documento == 'CCMD') {
            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet = '<td class="style84" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_carnet = '<td class="style84" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_nacimiento = ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {

                        if ($historico->doc_copia_original == 'true') {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                }
            }

            $html_invalidez = '';
            if ($valido == false) {
                $html_invalidez = '    <td class="style17">SI</td><td class="style85" colspan="2"></td><td class="style17">NO</td><td class="style86"  colspan="2" >X</td>';
            } else {
                $html_invalidez = '    <td class="style17">SI</td><td class="style85" colspan="2">X</td><td class="style17">NO</td><td class="style86"  colspan="2" ></td>';
            }

            $html_documento_asegurado = '   <tr class="row20">
                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                <td class="style17" colspan="5">Cód. Ver.</td>
                                                <td class="style17" colspan="8">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="5">Cód. Ver.</td>
                                                <td class="style18" colspan="6" rowspan="2">Inválido</td>

                                            </tr>
                                            <tr class="row21">
                                                <td class="style16" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style85" rowspan="2" colspan="5">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" colspan="5" rowspan="2">' . $nacimiento_ver . '</td>


                                            </tr>
                                            <tr class="row22">
                                            ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '' . $html_invalidez . '
                                            </tr> <tr class="row63"><td class="style44" colspan="28"></td></tr>
                                            ';
        } else if ($tipo_documento == '4B') {
            $carnet_ver = '';
            $nacimiento_ver = '';
            $matrimonio_ver = '';
            $copia_testimonio = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="3"></td>';
            $copia_matrimonio = '<td class="style85" colspan="1"></td> <td class="style85" colspan="1"></td>';
            $copia_matrimonio_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet = '<td class="style84" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_carnet = '<td class="style84" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento = ' <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_nacimiento = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {

                        if ($historico->doc_copia_original == 'true') {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                }

                if ($historico->doc_descripcion == 'Certificado de Matrimonio') {
                    $copia_testimonio = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="3"></td>';
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_matrimonio = '<td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_matrimonio = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $matrimonio_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_matrimonio_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {
                        if ($historico->doc_copia_original == 'true') {
                            $copia_matrimonio_o_c = '  <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_matrimonio_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                } else if ($historico->doc_descripcion == 'Certificado de Convivencia') {
                    $copia_matrimonio = ' <td class="style85" colspan="1"></td> <td class="style85" colspan="1"></td>';
                    $copia_matrimonio_o_c = ' <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_testimonio = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="3">X</td>';
                    } else {
                        $copia_testimonio = '  <td class="style84" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                    }
                }
            }

            $html_documento_asegurado = '   <tr class="row20">
                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Matrimonio</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style18" colspan="6">Testimonio de Convivencia</td>
                                            </tr>
                                            <tr class="row21">
                                                <td class="style16" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style85" rowspan="2" colspan="2">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" colspan="2" rowspan="2">' . $nacimiento_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" rowspan="2" colspan="2">' . $matrimonio_ver . '</td>
                                                <td class="style17" colspan="3">SI</td>
                                                <td class="style18" colspan="3">NO</td>
                                            </tr>
                                            <tr class="row22">
                                            ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '' . $copia_matrimonio . '' . $copia_matrimonio_o_c . '
                                             ' . $copia_testimonio . '

                                            </tr>
                                            <tr class="row63"><td class="style44" colspan="28"></td></tr>';
        } else if ($tipo_documento == 'MH') {
            $carnet_ver = '';
            $nacimiento_ver = '';
            $matrimonio_ver = '';
            $fecha_exp = explode("-", $fecha_nacimiento);
            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet = '      <td class="style84" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_carnet = '       <td class="style84" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento = '     <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_nacimiento = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                    if ($historico->doc_copia_original == 'true') {
                        $copia_nacimiento_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Defunción') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_defuncion = '     <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                    } else {
                        $copia_defuncion = ' <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                        $matrimonio_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }

                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                    } else {

                        if ($historico->doc_copia_original == 'true') {
                            $copia_defuncion_o_c = '        <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                        } else {
                            $copia_defuncion_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                        }
                    }
                }
            }
            $html_documento_asegurado = '   <tr class="row69">
                                                <td class="style16" colspan="4">Copia Doc. Ident. </td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Defunción</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style18" colspan="6">Fecha de fallecimiento</td>
                                            </tr>
                                            <tr class="row70">
                                                <td class="style16" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style85" rowspan="2"  colspan="2">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" rowspan="2"  colspan="2">' . $nacimiento_ver . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" rowspan="2" colspan="2">' . $matrimonio_ver . '</td>
                                                <td class="style17" colspan="2">Día</td>
                                                <td class="style17" colspan="2">Mes</td>
                                                <td class="style18" colspan="2">Año</td>
                                            </tr>
                                            <tr class="row22">
                                             ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '' . $copia_defuncion . '' . $copia_defuncion_o_c . '
                                                <td class="style85" colspan="2">' . $fecha_exp[2] . '</td>
                                                <td class="style85" colspan="2">' . $fecha_exp[1] . '</td>
                                                <td class="style86" colspan="2">' . $fecha_exp[0] . '</td>
                                            </tr>
                                            <tr class="row63">
                                                <td class="style44" colspan="28"></td>
                                            </tr>';
        } else if ($tipo_documento == '3BPM') {
            $carnet_ver = '';
            $nacimiento_ver = '';
            $fecha_exp = explode("-", $fecha_nacimiento);
            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet = '      <td class="style84" colspan="3"></td> <td class="style85" colspan="3">X</td>';
                    } else {
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                        $copia_carnet = '       <td class="style84" colspan="3">X</td> <td class="style85" colspan="3"></td>';
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento = '     <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_nacimiento = ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }


                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento_o_c = '        <td class="style85" colspan="3"></td> <td class="style85" colspan="3"></td>';
                    } else {
                        if ($historico->doc_copia_original == 'true') {
                            $copia_nacimiento_o_c = '        <td class="style85" colspan="3"></td> <td class="style85" colspan="3">X</td>';
                        } else {
                            $copia_nacimiento_o_c = '  <td class="style85" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                        }
                    }
                }
            }
            $html_documento_asegurado = '  <tr class="row69">
                                                <td class="style16" colspan="6">Copia Doc. Ident. </td>
                                                <td class="style17" colspan="3">Cód. Ver.</td>
                                                <td class="style17" colspan="10">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="3">Cód. Ver.</td>
                                                <td class="style18" colspan="6"  rowspan="3" ></td>
                                            </tr>
                                            <tr class="row70">
                                                <td class="style16" colspan="3">SI</td>
                                                <td class="style17" colspan="3">NO</td>
                                                <td class="style85" rowspan="2"  colspan="3">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style17" colspan="3">Original</td>
                                                <td class="style17" colspan="3">Copia</td>
                                                <td class="style85" rowspan="2" colspan="3">            ' . $nacimiento_ver . '</td>

                                            </tr>
                                            <tr class="row22">
                                             ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '

                                            </tr>
                                            <tr class="row63">
                                                <td class="style44" colspan="28"></td>
                                            </tr>';
        } else {
            $carnet_ver = '';
            $nacimiento_ver = '';
            $fecha_exp = explode("-", $fecha_nacimiento);
            foreach ($dataHistorico as $historico) {
                if ($historico->doc_descripcion == 'Cedula de Identidad') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_carnet .= '      <td class="style84" colspan="3"></td> <td class="style85" colspan="3">X</td>';
                    } else {
                        $copia_carnet .= '       <td class="style84" colspan="3">X</td> <td class="style85" colspan="3"></td>';
                        $carnet_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }
                }
                if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento .= '     <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_nacimiento .= ' <td class="style85" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                        $nacimiento_ver = $this->tipoPbsHtml($historico->doc_id_observacion);
                    }


                    if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                        $copia_nacimiento_o_c .= '        <td class="style85" colspan="3"></td> <td class="style85" colspan="3"></td>';
                    } else {
                        if ($historico->doc_copia_original == 'true') {
                            $copia_nacimiento_o_c .= '        <td class="style85" colspan="3"></td> <td class="style85" colspan="3">X</td>';
                        } else {
                            $copia_nacimiento_o_c .= '  <td class="style85" colspan="3">X</td>  <td class="style85" colspan="3"></td>';
                        }
                    }
                }
            }
            $html_documento_asegurado = '  <tr class="row69">
                                                <td class="style16" colspan="6">Copia Doc. Ident.</td>
                                                <td class="style17" colspan="3">Cód. Ver.</td>
                                                <td class="style17" colspan="10">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="3">Cód. Ver.</td>
                                                <td class="style18" colspan="6">Fecha de Nacimiento</td>
                                            </tr>
                                            <tr class="row70">
                                                <td class="style16" colspan="3">SI</td>
                                                <td class="style17" colspan="3">NO</td>
                                                <td class="style85" rowspan="2"  colspan="3">' . $carnet_ver . '</td>
                                                <td class="style17" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style17" colspan="3">Original</td>
                                                <td class="style17" colspan="3">Copia</td>
                                                <td class="style85" rowspan="2" colspan="3">' . $nacimiento_ver . '</td>
                                                <td class="style17" colspan="2">Día</td>
                                                <td class="style17" colspan="2">Mes</td>
                                                <td class="style18" colspan="2">Año</td>
                                            </tr>
                                            <tr class="row22">
                                             ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '
                                                <td class="style85" colspan="2">' . $fecha_exp[2] . '</td>
                                                <td class="style85" colspan="2">' . $fecha_exp[1] . '</td>
                                                <td class="style86" colspan="2">' . $fecha_exp[0] . '</td>
                                            </tr>
                                            <tr class="row63">
                                                <td class="style44" colspan="28"></td>
                                            </tr>';
        }
        return $html_documento_asegurado;
    }
    public function generateHtmlAseguradoDocumentoRetiros($cas_id, $dertificado, $fecha)
    {
        $fec = '';
        $fehca = '';
        if ($fecha != '') {
            $fecha_se = explode("-", $fecha);
            $fec = $fecha_se[2] . '-' . $fecha_se[1] . '-' . $fecha_se[0];
            $fehca = '    <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
            <td class="style85" colspan="2">' . $fecha_se[1] . '</td>
            <td class="style86" colspan="2">' . $fecha_se[0] . '</td>';
        } else {
            $fec = '';
            $fehca = '<td class="style85" colspan="2"></td>
            <td class="style85" colspan="2"></td>
            <td class="style86" colspan="2"></td>';
        }
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $certificado_difuncion = '';
        $copia_difuncion = '';
        $copia_difuncion_o_c = '';
        $ci = '';
        $cc = '';
        $cf = '';
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-TIT' order by doc_id desc");
        $bandera = 0;
        $certificado_difuncion = '          <td class="style85" colspan="1"></td>
                                                <td class="style85" colspan="1"></td>
                                                <td class="style85" colspan="2"></td>
                                                <td class="style85" colspan="2"></td>';
        foreach ($dataHistorico as $historico) {
            //print($historico->doc_descripcion);
            //echo "<br>";
            if ($historico->doc_descripcion == 'Cedula de Identidad') {

                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_carnet = '          <td class="style84" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                } else {
                    $copia_carnet = '          <td class="style84" colspan="2">X</td> <td class="style85" colspan="2"></td>';
                    $ci = $this->tipoPbsHtml($historico->doc_id_observacion);
                }
            }
            if ($historico->doc_descripcion == 'Certificado de Nacimiento') {

                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_nacimiento = '      <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                } else {
                    $copia_nacimiento = '      <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                    $cc = $this->tipoPbsHtml($historico->doc_id_observacion);
                }

                if ($historico->doc_url != '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    if ($historico->doc_copia_original == 'true') {
                        $copia_nacimiento_o_c = '      <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_nacimiento_o_c = '      <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    }
                } else {
                    $copia_nacimiento_o_c = '      <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                }
            }
            // if ($bandera == 0) {
            if ($historico->doc_descripcion == 'Certificado de Defunción') {

                //print("si--------------");
                // echo "<br>";
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_difuncion = '      <td class="style85" colspan="1"></td> <td class="style85" colspan="1">X</td>';
                } else {
                    $copia_difuncion = '      <td class="style85" colspan="1">X</td> <td class="style85" colspan="1"></td>';
                    $cf = $this->tipoPbsHtml($historico->doc_id_observacion);
                }
                if ($historico->doc_url != '') {
                    if ($historico->doc_copia_original == 'true') {
                        $copia_difuncion_o_c = '      <td class="style85" colspan="2"></td> <td class="style85" colspan="2">X</td>';
                    } else {
                        $copia_difuncion_o_c = '      <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    }
                } else {
                    $copia_difuncion_o_c = '      <td class="style85" colspan="2"></td> <td class="style85" colspan="2"></td>';
                }
                $certificado_difuncion = $copia_difuncion . $copia_difuncion_o_c;
            }
            $bandera++;
        }
        if ($dertificado == 'SO') {
            $certificado_difuncion = '          <td class="style85" colspan="1">X</td>
                                                <td class="style85" colspan="1"></td>
                                                <td class="style85" colspan="2">X</td>
                                                <td class="style85" colspan="2"></td>';
        } else if ($dertificado == 'SC') {
            $certificado_difuncion = '          <td class="style85" colspan="1">X</td>
                                                <td class="style85" colspan="1"></td>
                                                <td class="style85" colspan="2"></td>
                                                <td class="style85" colspan="2">X</td>';
        } else {
        }
        $html_documento_asegurado = '       <tr class="row20">
                                                <td class="style16" colspan="4">Copia Doc. Ident.</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Nacimiento</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style17" colspan="6">Certificado de Defuncion</td>
                                                <td class="style17" colspan="2">Cód. Ver.</td>
                                                <td class="style18" colspan="6">Fecha de Fallecimiento</td>
                                            </tr>
                                            <tr class="row21">
                                                <td class="style16" colspan="2">SI</td>
                                                <td class="style17" colspan="2">NO</td>
                                                <td class="style85" rowspan="2" colspan="2">' . $ci . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" colspan="2" rowspan="2">' . $cc . '</td>
                                                <td class="style17" colspan="1">SI</td>
                                                <td class="style17" colspan="1">NO</td>
                                                <td class="style17" colspan="2">Original</td>
                                                <td class="style17" colspan="2">Copia</td>
                                                <td class="style85" rowspan="2" colspan="2">' . $cf . '</td>
                                                <td class="style17" colspan="2">Día</td>
                                                <td class="style17" colspan="2">Mes</td>
                                                <td class="style18" colspan="2">Año</td>
                                            </tr>
                                            <tr class="row22">
                                            ' . $copia_carnet . ' ' . $copia_nacimiento . ' ' . $copia_nacimiento_o_c . '
                                              ' . $certificado_difuncion . $fehca . '

                                            </tr>';
        return $html_documento_asegurado;
    }
    public function generateHtmlAseguradoDocumento($cas_id, $tipo)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $tipoobsci = '';


        $tipoobnac = '';
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = '0-TIT' order by doc_id desc");

        foreach ($dataHistorico as $historico) {
            if ($historico->doc_descripcion == 'Cedula de Identidad') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $tipoobsci = '';
                    $copia_carnet = '  <td class="style84" colspan="3"></td>   <td class="style85" colspan="2" >X</td>';
                } else {
                    $tipoobsci = $this->tipoPbsHtml($historico->doc_id_observacion);
                    $copia_carnet = '  <td class="style84" colspan="3">X</td>  <td class="style85"  colspan="2"></td>';
                }
            }
            if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $tipoobnac = '';
                    $copia_nacimiento = '  <td class="style85" colspan="2"></td>   <td class="style85"  colspan="2">X</td>';
                } else {
                    $tipoobnac = $this->tipoPbsHtml($historico->doc_id_observacion);
                    $copia_nacimiento = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                }
                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $copia_nacimiento_o_c = '  <td class="style85" colspan="2" ></td>   <td class="style85" colspan="2" ></td>';
                } else {
                    if ($historico->doc_copia_original == 'true') {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2"></td>   <td class="style85" colspan="2" >X</td>';
                    } else {
                        $copia_nacimiento_o_c = '  <td class="style85" colspan="2">X</td>  <td class="style85" colspan="2"></td>';
                    }
                }
            }
        }

        if ($tipo == 'JUB') {
            $html_documento_asegurado = '       <tr class="row20">
                                                    <td class="style16" colspan="5">Copia Doc. Ident. </td>
                                                    <td class="style17" colspan="3" >Cód. Ver.</td>
                                                    <td class="style17" colspan="8">Certificado de Nacimiento</td>
                                                    <td class="style17" colspan="3" >Cód. Ver.</td>
                                                    <td class="style18" colspan="10" rowspan="3"></td>

                                                </tr>
                                                <tr class="row21">
                                                    <td class="style16"  colspan="3">SI</td>
                                                    <td class="style17" colspan="2" >NO</td>
                                                    <td class="style85" rowspan="2"  colspan="3" >' . $tipoobsci . '</td>
                                                    <td class="style17"  colspan="2">SI</td>
                                                    <td class="style17"  colspan="2" >NO</td>
                                                    <td class="style17"  colspan="2">Original</td>
                                                    <td class="style17"  colspan="2">Copia</td>
                                                    <td class="style85"  colspan="3" rowspan="2">' . $tipoobnac . '</td>
                                                </tr>
                                                <tr class="row22">
                                                    ' . $copia_carnet . '
                                                    ' . $copia_nacimiento . '
                                                    ' . $copia_nacimiento_o_c . '


                                                </tr>
                                               ';
        } else {
            $html_documento_asegurado = '           <tr class="row20">
                                                    <td class="style16" colspan="5">Copia Doc. Ident. </td>
                                                    <td class="style17" colspan="3" >Cód. Ver.</td>
                                                    <td class="style17" colspan="8">Certificado de Nacimiento </td>
                                                    <td class="style17" colspan="3" >Cód. Ver.</td>


                                                </tr>
                                                <tr class="row21">
                                                    <td class="style16"  colspan="3">SI</td>
                                                    <td class="style17" colspan="2" >NO</td>
                                                    <td class="style85" rowspan="2"  colspan="3" >' . $tipoobsci . '</td>
                                                    <td class="style17"  colspan="2">SI</td>
                                                    <td class="style17"  colspan="2" >NO</td>
                                                    <td class="style17"  colspan="2">Original</td>
                                                    <td class="style17"  colspan="2">Copia</td>
                                                    <td class="style85"  colspan="3" rowspan="2">' . $tipoobnac . '</td>
                                                </tr>
                                                <tr class="row22">
                                                    ' . $copia_carnet . '
                                                    ' . $copia_nacimiento . '
                                                    ' . $copia_nacimiento_o_c . '


                                                </tr>
                                               ';
        }


        return $html_documento_asegurado;
    }
    public function generateHtmlFilaNombre($tipo_tramite, $primer_apellido, $segundo_apellido, $apellido_casado, $nombre, $fecha_nacimiento, $tipo_doc, $identidad, $complemento, $parentesco, $genero, $telefono, $ciudad, $zona, $direccion, $nro, $fac_rec, $estado_civil)
    {
        $nombres_data = explode(" ", $nombre);
        $nombre_1 = '';
        $nombre_2 = '';
        if (count($nombres_data) == 2) {
            $nombre_1 = $nombres_data[0];
            $nombre_2 = $nombres_data[1];
        } else if (count($nombres_data) == 1) {
            $nombre_1 = $nombres_data[0];
        } else if (count($nombres_data) > 2) {
            $tam = count($nombres_data) - 1;
            $nombre_1 = $nombres_data[0];
            for ($i = 1; $i <= $tam; $i++) {
                $nombre_2 .= $nombres_data[$i] . ' ';
            }
        }
        $fecha_se = explode("-", $fecha_nacimiento);
        $tipo = '';
        if ($tipo_doc == 'I') {
            $tipo = 'CEDULA IDENTIDAD';
        } else if ($tipo_doc == 'E') {
            $tipo = 'EXTRANJERO';
        } else if ($tipo_doc == 'P') {
            $tipo = 'PASAPORTE';
        }

        $tipo_paren = '';
        if ($parentesco == '1-CONY') {
            $tipo_paren = 'CONYUGUE';
        } else if ($parentesco == '1-HIJ') {
            $tipo_paren = 'HIJO(A)';
        } else if ($parentesco == '3-SOB') {
            $tipo_paren = 'SOBRINO(A)';
        } else if ($parentesco == '2-HER') {
            $tipo_paren = 'HERMANO(A)';
        } else if ($parentesco == '1-CONV') {
            $tipo_paren = 'CONVIVIENTE';
        } else if ($parentesco == '3-TIO') {
            $tipo_paren = 'TIO(A)';
        } else if ($parentesco == '3-ABU') {
            $tipo_paren = 'ABUELO(A)';
        } else if ($parentesco == '3-OTR') {
            $tipo_paren = 'OTROS';
        } else if ($parentesco == '2-MAD') {
            $tipo_paren = 'MADRE';
        } else if ($parentesco == '2-PAD') {
            $tipo_paren = 'PADRE';
        }

        $sexo = '';
        if ($genero == 'M') {
            $sexo = '  <td class="style17">M</td> <td class="style85">X</td> <td class="style17">F</td> <td class="style85"></td>';
        } else {
            $sexo = '  <td class="style17">M</td> <td class="style85"></td> <td class="style17">F</td> <td class="style85">X</td>';
        }

        if ($tipo_tramite == 'JB') {
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="6">Primer Apellido</td>
                <td class="style17" colspan="6">Segundo Apellido</td>
                <td class="style17" colspan="6">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="6">' . $primer_apellido . '</td>
                <td class="style85" colspan="6">' . $segundo_apellido . '</td>
                <td class="style85" colspan="6">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="6" rowspan="2">Tipo Doc. Ident.</td>
                <td class="style17" colspan="4" rowspan="2">N° Doc. Identidad</td>
                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                <td class="style17" colspan="5" rowspan="2">Parentesco</td>
                <td class="style17" colspan="4" rowspan="2">Sexo</td>
                <td class="style18" colspan="7">Fecha de Nacimiento</td>
            </tr>
            <tr class="row159">
                <td class="style17" colspan="2">Día</td>
                <td class="style17" colspan="3">Mes</td>
                <td class="style18" colspan="2">Año</td>
            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="4">' . $identidad . '</td>
                <td class="style85" colspan="2">' . $complemento . '</td>
                <td class="style85" colspan="5">' . $tipo_paren . ' </td>' . $sexo . '
                <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
                <td class="style85" colspan="3">' . $fecha_se[1] . '</td>
                <td class="style86" colspan="2">' . $fecha_se[0] . '</td>
            </tr>';
        } else if ($tipo_tramite == 'MH') {
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="3">CUA</td>
                <td class="style17" colspan="5">Primer Apellido</td>
                <td class="style17" colspan="5">Segundo Apellido</td>
                <td class="style17" colspan="5">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre</td>
            </tr>
            <tr class="row52">
                <td class="style84" colspan="3">NO HAY</td>
                <td class="style85" colspan="5">' . $primer_apellido . '</td>
                <td class="style85" colspan="5">' . $segundo_apellido . '</td>
                <td class="style85" colspan="5">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>

            <tr class="row158">
                <td class="style16" colspan="6" rowspan="2">Tipo Doc. Ident.</td>
                <td class="style17" colspan="3" rowspan="2">N° Doc. Identidad</td>
                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                <td class="style17" colspan="7" >Fecha de Nacimiento</td>
                <td class="style17" colspan="3" rowspan="2">Parentesco</td>
                <td class="style17" colspan="3" rowspan="2">estado civil</td>
                <td class="style18" colspan="4" rowspan="2">Sexo</td>

            </tr>
            <tr class="row159">
                <td class="style17" colspan="2">Día</td>
                <td class="style17" colspan="3">Mes</td>
                <td class="style18" colspan="2">Año</td>
            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="3">' . $identidad . '</td>
                <td class="style85" colspan="2">' . $complemento . '</td>
                <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
                <td class="style85" colspan="3">' . $fecha_se[1] . '</td>
                <td class="style85" colspan="2">' . $fecha_se[0] . '</td>
                <td class="style85" colspan="3">' . $tipo_paren . ' </td>
                <td class="style85" colspan="3">HO HAY</td>' . $sexo . '

            </tr>';
        } else if ($tipo_tramite == 'PMH') {
            $fac_rec_ = '';
            if ($fac_rec == false) {
                $fac_rec_ = '
                <td class="style85" colspan="2"></td>
                <td class="style86" colspan="2">X</td>';
            } else {
                $fac_rec_ = '
                <td class="style85" colspan="2">X</td>
                <td class="style86" colspan="2"></td>';
            }

            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="6">Primer Apellido</td>
                <td class="style17" colspan="6">Segundo Apellido</td>
                <td class="style17" colspan="6">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre  </td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="6">' . $primer_apellido . '</td>
                <td class="style85" colspan="6">' . $segundo_apellido . '</td>
                <td class="style85" colspan="6">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="6" rowspan="2">Tipo Doc. Ident.</td>
                <td class="style17" colspan="3" rowspan="2">N° Doc. Identidad</td>
                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                <td class="style17" colspan="3" rowspan="2">Parentesco</td>
                 <td class="style17" colspan="4" rowspan="2">Sexo</td>
                <td class="style17" colspan="6" >Fecha de Nacimiento</td>
                <td class="style18" colspan="4">Inválido</td>

            </tr>
             <tr class="row159">
                <td class="style17" colspan="2">Día</td>
                <td class="style17" colspan="2">Mes</td>
                <td class="style17" colspan="2">Año</td>
                <td class="style17" colspan="2">SI</td>
                <td class="style18" colspan="2">NO</td>

            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="3">' . $identidad . '</td>
                <td class="style85" colspan="2">' . $complemento . '</td>
                <td class="style85" colspan="3">' . $tipo_paren . ' </td>' . $sexo . '
                 <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
                <td class="style85" colspan="2">' . $fecha_se[1] . '</td>
                <td class="style85" colspan="2">' . $fecha_se[0] . '</td>
                ' . $fac_rec_ . '

            </tr>';
        } else if ($tipo_tramite == 'RMS') {
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="6">Primer Apellido</td>
                <td class="style17" colspan="6">Segundo Apellido</td>
                <td class="style17" colspan="6">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="6">' . $primer_apellido . '</td>
                <td class="style85" colspan="6">' . $segundo_apellido . '</td>
                <td class="style85" colspan="6">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="6" >Tipo Doc. Ident.</td>
                <td class="style17" colspan="6" >N° Doc. Identidad</td>
                <td class="style17" colspan="4" >Compl. CI</td>
                <td class="style17" colspan="8" >Parentesco</td>
                <td class="style18" colspan="4" >Sexo</td>
            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="6">' . $identidad . '</td>
                <td class="style85" colspan="4">' . $complemento . '</td>
                <td class="style85" colspan="8">' . $tipo_paren . ' </td>' . $sexo . '
            </tr>';
        } else if ($tipo_tramite == 'MHV') {
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="6">Primer Apellido</td>
                <td class="style17" colspan="6">Segundo Apellido</td>
                <td class="style17" colspan="6">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="6">' . $primer_apellido . '</td>
                <td class="style85" colspan="6">' . $segundo_apellido . '</td>
                <td class="style85" colspan="6">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="6" >Tipo Doc. Ident.</td>
                <td class="style17" colspan="6" >N° Doc. Identidad</td>
                <td class="style17" colspan="4" >Compl. CI</td>
                <td class="style17" colspan="4" >Parentesco</td>
                <td class="style17" colspan="4" >Sexo</td>
                <td class="style18" colspan="4" >Telefono/Celular</td>

            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="6">' . $identidad . '</td>
                <td class="style85" colspan="4">' . $complemento . '</td>
                <td class="style85" colspan="4">' . $tipo_paren . ' </td>' . $sexo . '
                <td class="style86" colspan="4">' . $telefono . '</td>
            </tr>

            <tr class="row52">
                <td class="style16" colspan="7">Departamento</td>
                <td class="style17" colspan="7">Provincia</td>
                <td class="style17" colspan="7">Ciudad </td>
                <td class="style17" colspan="7"> Zona/Villa/Barrio/Sector</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="7"></td>
                <td class="style85" colspan="7"></td>
                <td class="style85" colspan="7"></td>
                <td class="style85" colspan="7"></td>
            </tr>
              <tr class="row52">
                <td class="style16" colspan="13"> Dirección</td>
                <td class="style17" colspan="2"> N°</td>
                <td class="style17" colspan="9">  Correo Electrónico </td>
                <td class="style17" colspan="4"> Casilla Postal</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="13">' . $direccion . '</td>
                <td class="style85" colspan="2">' . $nro . '</td>
                <td class="style85" colspan="9">' . $zona . '</td>
                <td class="style85" colspan="4"></td>
            </tr>
            ';
        } else if ($tipo_tramite == 'GF') {
            $fac_rec_ = '';
            if ($fac_rec == '1') {
                $fac_rec_ = '<td class="style85" colspan="2">X</td>
                <td class="style85" colspan="2"></td>
                <td class="style85" colspan="2"></td>
                <td class="style85" colspan="2"></td>';
            } else {
                $fac_rec_ = '<td class="style85" colspan="2"></td>
                <td class="style85" colspan="2"></td>
                <td class="style85" colspan="2">X</td>
                <td class="style85" colspan="2"></td>';
            }
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="5" rowspan="2">Primer Apellido</td>
                <td class="style17" colspan="4" rowspan="2">Segundo Apellido</td>
                <td class="style17" colspan="4" rowspan="2">Apellido Casada</td>
                <td class="style17" colspan="4" rowspan="2">Primer Nombre</td>
                <td class="style18" colspan="4" rowspan="2">Segundo Nombre</td>
                <td class="style18" colspan="7" >Fecha de Nacimiento</td>
            </tr>
            <tr class="row159">
                <td class="style17" colspan="2">Día</td>
                <td class="style17" colspan="3">Mes</td>
                <td class="style18" colspan="2">Año</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="5">' . $primer_apellido . '</td>
                <td class="style85" colspan="4">' . $segundo_apellido . '</td>
                <td class="style85" colspan="4">' . $apellido_casado . '</td>
                <td class="style85" colspan="4">' . $nombre_1 . '</td>
                <td class="style86" colspan="4">' . $nombre_2 . '</td>
                <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
                <td class="style85" colspan="3">' . $fecha_se[1] . '</td>
                <td class="style86" colspan="2">' . $fecha_se[0] . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="5"  rowspan="2">Tipo Doc. Ident.</td>
                <td class="style17" colspan="4"  rowspan="2">N° Doc. Identidad</td>
                <td class="style17" colspan="3"  rowspan="2">Compl. CI</td>
                <td class="style17" colspan="4">Factura</td>
                <td class="style17" colspan="4">Recibo</td>
                <td class="style17" colspan="4"  rowspan="2">Telefono/Celular</td>
                <td class="style18" colspan="4"  rowspan="2">Sexo</td>
            </tr>
            <tr class="row21">
                <td class="style17" colspan="2">SI</td>
                <td class="style17" colspan="2">NO</td>
                <td class="style17" colspan="2">SI</td>
                <td class="style17" colspan="2">NO</td>
            </tr>
            <tr class="row160">
                <td class="style84" colspan="5">' . $tipo . '</td>
                <td class="style85" colspan="4">' . $identidad . '</td>
                <td class="style85" colspan="3">' . $complemento . '</td>
               ' . $fac_rec_ . '
                <td class="style85" colspan="4"> ' . $telefono . '</td>' . $sexo . '
            </tr>
               <tr class="row52">
                <td class="style16" colspan="7"> Ciudad</td>
                <td class="style17" colspan="7"> Zona/Villa/Barrio/Sector</td>
                <td class="style17" colspan="7"> Dirección </td>
                <td class="style17" colspan="7"> N°</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="7">' . $ciudad . '</td>
                <td class="style85" colspan="7">' . $zona . '</td>
                <td class="style85" colspan="7">' . $direccion . '</td>
                <td class="style85" colspan="7">' . $nro . '</td>
            </tr>
            ';
        } else {
            $html_fila_nombre = '
            <tr class="row52">
                <td class="style16" colspan="6">Primer Apellido</td>
                <td class="style17" colspan="6">Segundo Apellido</td>
                <td class="style17" colspan="6">Apellido Casada</td>
                <td class="style17" colspan="5">Primer Nombre</td>
                <td class="style18" colspan="5">Segundo Nombre</td>
            </tr>
            <tr class="row53">
                <td class="style84" colspan="6">' . $primer_apellido . '</td>
                <td class="style85" colspan="6">' . $segundo_apellido . '</td>
                <td class="style85" colspan="6">' . $apellido_casado . '</td>
                <td class="style85" colspan="5">' . $nombre_1 . '</td>
                <td class="style86" colspan="5">' . $nombre_2 . '</td>
            </tr>
            <tr class="row158">
                <td class="style16" colspan="6" rowspan="2">Tipo Doc. Ident.</td>
                <td class="style17" colspan="3" rowspan="2">N° Doc. Identidad</td>
                <td class="style17" colspan="2" rowspan="2">Compl. CI</td>
                <td class="style17" colspan="3" rowspan="2">Parentesco</td>
                <td class="style17" colspan="3" rowspan="2">estado civil</td>
                <td class="style17" colspan="4" rowspan="2">Sexo</td>
                <td class="style18" colspan="7">Fecha de Nacimiento</td>
            </tr>
            <tr class="row159">
                <td class="style17" colspan="2">Día</td>
                <td class="style17" colspan="3">Mes</td>
                <td class="style18" colspan="2">Año</td>
            </tr>
            <tr class="row160">
                <td class="style84" colspan="6">' . $tipo . '</td>
                <td class="style85" colspan="3">' . $identidad . '</td>
                <td class="style85" colspan="2">' . $complemento . '</td>
                <td class="style85" colspan="3">' . $tipo_paren . ' </td>
                <td class="style85" colspan="3">   ' . $estado_civil . '</td>' . $sexo . '
                <td class="style85" colspan="2">' . $fecha_se[2] . '</td>
                <td class="style85" colspan="3">' . $fecha_se[1] . '</td>
                <td class="style86" colspan="2">' . $fecha_se[0] . '</td>
            </tr>';
        }
        return $html_fila_nombre;
    }
    public function generateHtmlSolicitantedoDocumentoinv($cas_id)
    {
        $tipoobs = "";
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-SOL'  and  doc_descripcion='Cedula de Identidad' order by doc_id desc");
        foreach ($dataHistorico as $historico) {
            $tipoobs = "";
            switch ($historico->doc_id_observacion) {
                case 1:
                    $tipoobs = "E0";
                    break;
                case 2:
                    $tipoobs = "E1";
                    break;
                case 3:
                    $tipoobs = "E2";
                    break;
                case 4:
                    $tipoobs = "E3";
                    break;
                case 5:
                    $tipoobs = "E4";
                    break;
                case 6:
                    $tipoobs = "E5";
                    break;
                case 7:
                    $tipoobs = "E6";
                    break;
                case 8:
                    $tipoobs = "E7";
                    break;
                default:
                    $tipoobs = "";
            }
        }

        return $tipoobs;
    }
    public function tipoPbsHtml($obs)
    {
        $tipoobs = "";
        switch ($obs) {
            case 1:
                $tipoobs = "E0";
                break;
            case 2:
                $tipoobs = "E1";
                break;
            case 3:
                $tipoobs = "E2";
                break;
            case 4:
                $tipoobs = "E3";
                break;
            case 5:
                $tipoobs = "E4";
                break;
            case 6:
                $tipoobs = "E5";
                break;
            case 7:
                $tipoobs = "E6";
                break;
            case 8:
                $tipoobs = "E7";
                break;
            default:
                $tipoobs = "";
        }




        return $tipoobs;
    }

    public function generateHtmlDocumentoTecnicoRev($cas_id)
    {
        // ADJUNTO_MEDICOS
        $dataDocumetos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = 'ADJUNTO_MEDICOS' order by doc_id desc"); //doc_id
        $nro = 1;
        $html_documento = '';
        foreach ($dataDocumetos as $documento) {
            $html = '<tr class="row35">
                <td class="column6 style14 s style16" colspan="3">' . $nro . '</td>
                <td class="column6 style14 s style16" colspan="25">' . $documento->doc_descripcion . '</td>
            </tr>';
            $html_documento .= $html;
            $nro++;
        }
        return '<table><tr class="row35">
        <td class="column6 style14 s style16" colspan="3">Cantidad</td>
        <td class="column6 style14 s style16" colspan="25">Descripción del Documento</td>
        </tr>' . $html_documento . '</table>';
    }
    public function generateHtmlDocumentoTecnicoRevCantidad($cas_id)
    {
        // ADJUNTO_MEDICOS
        $dataDocumetos = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and   doc_referencia = 'ADJUNTO_MEDICOS' order by doc_id desc"); //doc_id
        $cantidadDocumentos = count($dataDocumetos);
        return $dataDocumetos;
    }

    public function generateHtmlAseguradoDocumentoRecalificacion($cas_id)
    {
        $copia_carnet = '';
        $copia_nacimiento = '';
        $copia_nacimiento_o_c = '';
        $tipoobsci = '';
        $tipoobnac = '';
        $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
        $id_caso_real = $data_caso_real[0]->cas_padre_id;
        $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and   doc_referencia = '0-TIT' order by doc_id desc");
        foreach ($dataHistorico as $historico) {


            if ($historico->doc_descripcion == 'Cedula de Identidad') {

                if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                    $tipoobsci = '';
                    $copia_carnet .= '  <td class="style84" colspan="3" ></td>   <td class="style85" colspan="3" >X</td>';
                } else {
                    $tipoobsci = $this->tipoPbsHtml($historico->doc_id_observacion);
                    $copia_carnet .= '  <td class="style84" colspan="3">X</td>  <td class="style85"  colspan="3"></td>';
                }
            }
        }



        $html_documento_asegurado = '           <tr class="row20">
                                                    <td class="style16" colspan="6">Copia Doc. Ident. </td>
                                                    <td class="style16" colspan="2" >Cód. Ver.</td>


                                                </tr>
                                                <tr class="row20">
                                                <td class="style16" colspan="3">SI </td>
                                                <td class="style16" colspan="3">NO </td>
                                                <td class="style84" colspan="2" rowspan="2">' . $tipoobsci . '</td>



                                            </tr>
                                                <tr class="row22">
                                                    ' . $copia_carnet . '



                                                </tr>
                                               ';

        return $html_documento_asegurado;
    }

    public function registroDiferencia($tablaContenido, $tipo_dibujo, $cua, $cas_data, $cas_id)
    {
        $cas_data = json_decode($cas_data, true);
        $usuario = $cas_data['USUARIO_REGISTRO'];
        $response = Http::get('https://sgg.gestora.bo/compensacion-cotizacion/api/cargaFile/consulta', [
            'cua' => $cua,
        ]);
        $data = $response->json();
        if ($data['codigoRespuesta'] == 200) {
            $dataOriginal = $data['data']['original'];
            $dataSenac = $data['data']['historico'][0];
            $bandera = 0;

            $primer_apellido = '';
            $segundo_apellido = '';
            $primer_nombre = '';
            $segundo_nombre = '';
            $cua_ = '';
            $num_identificacion = '';
            $fec_nacimiento = '';

            if ($dataOriginal['primer_apellido'] == $dataSenac['primer_apellido']) {
                $bandera++;
            } else {
                $primer_apellido = $dataSenac['primer_apellido'];
            }
            if ($dataOriginal['segundo_apellido'] == $dataSenac['segundo_apellido']) {
                $bandera++;
            } else {
                $segundo_apellido = $dataSenac['segundo_apellido'];
            }
            if ($dataOriginal['primer_nombre'] == $dataSenac['primer_nombre']) {
                $bandera++;
            } else {
                $primer_nombre = $dataSenac['primer_nombre'];
            }
            if ($dataOriginal['segundo_nombre'] == $dataSenac['segundo_nombre']) {
                $bandera++;
            } else {
                $segundo_nombre = $dataSenac['segundo_nombre'];
            }
            if ($dataOriginal['cua'] == $dataSenac['cua']) {
                $bandera++;
            } else {
                $cua_ = $dataSenac['cua_'];
            }
            if ($dataOriginal['num_identificacion'] == $dataSenac['num_identificacion']) {
                $bandera++;
            } else {
                $num_identificacion = $dataSenac['num_identificacion'];
            }
            if ($dataOriginal['fec_nacimiento'] == $dataSenac['fec_nacimiento']) {
                $bandera++;
            } else {
                $fec_nacimiento = $dataSenac['fec_nacimiento'];
            }

            if ($bandera == 7) {
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Author');
                $pdf->SetTitle('Hello PDF');
                $pdf->SetSubject('Simple PDF');
                $pdf->SetKeywords('TCPDF, PDF, hello, world');
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', '', 12);
                $html = '';
                $html .= '
                <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
                    </head>
                    <body>
                       No hay Direfencia
                    </body>
                </html>';
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdfAsBase64 = $pdf->Output('', 'S');
                $base64Content = base64_encode($pdfAsBase64);
                $success = array('code' => 200, 'mensaje' => 'OK');
                if ($tipo_dibujo == 'Dibujar') {
                    return array("data" => $base64Content, "codigoRespuesta" => $success);
                } else {
                    return array("mensaje" => $pdfAsBase64);
                }
            } else {
                $pdf = new MYPDF3(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Author');
                $pdf->SetTitle('Hello PDF');
                $pdf->SetSubject('Simple PDF');
                $pdf->SetKeywords('TCPDF, PDF, hello, world');
                $pdf->AddPage();
                $pdf->SetFont('dejavusans', '', 12);

                $html = '<tr>
                    <td colspan="4" class="style47"><strong> PRIMER APELLIDO</strong></td>
                     <td colspan="6" class="style48"> ' . $dataOriginal['primer_apellido'] . '</td>
                    <td colspan="6" class="style49"> ' . $primer_apellido . '</td>

                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> SEGUNDO APELLIDO</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['segundo_apellido'] . '</td>
                    <td colspan="6" class="style49"> ' . $segundo_apellido . '</td>
                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> PRIMER NOMBRE</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['primer_nombre'] . '</td>
                    <td colspan="6" class="style49"> ' . $primer_nombre . '</td>
                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> SEGUNDO NOMBRE</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['segundo_nombre'] . '</td>
                    <td colspan="6" class="style49"> ' . $segundo_nombre . '</td>
                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> N° CUA</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['cua'] . '</td>
                    <td colspan="6" class="style49"> ' . $cua_ . '</td>
                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> N° DOCUMENTO</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['num_identificacion'] . '</td>
                    <td colspan="6" class="style49"> ' . $num_identificacion . '</td>
                </tr>
                <tr>
                    <td colspan="4" class="style47"><strong> FECHA DE NACIMIENTO</strong></td>
                    <td colspan="6" class="style48"> ' . $dataOriginal['fec_nacimiento'] . '</td>
                    <td colspan="6" class="style49"> ' . $fec_nacimiento . '</td>
                </tr>';
                date_default_timezone_set('America/La_Paz');
                setlocale(LC_TIME, 'es_ES.UTF-8');
                setlocale(LC_TIME, 'spanish');
                $fecha_literal = strftime('%d de %B del %Y', time());
                $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);
                $tablaContenido = str_replace('#TABLA_COMPARACION#', $html, $tablaContenido);
                $tablaContenido = str_replace('#FUNCIONARIO#', $usuario, $tablaContenido);
                $data_caso_real = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id =  $cas_id");
                $doc_1 = '';
                $doc_2 = '';
                $doc_3 = '';
                $id_caso_real = $data_caso_real[0]->cas_padre_id;
                $dataHistorico = \DB::select("SELECT * from  public._gp_documentos where doc_cas_id = $id_caso_real and doc_referencia = '0-TIT'      order by doc_id desc");
                foreach ($dataHistorico as $historico) {
                    if ($historico->doc_descripcion == 'Cedula de Identidad') {
                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                            $doc_2 = ' ';
                        } else {
                            //  echo 'entro aqui Cedula de Identidad--------';
                            $doc_2 = 'X';
                        }
                    }
                    if ($historico->doc_descripcion == 'Certificado de Nacimiento') {
                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                            //  echo 'entro aqui Certificado de Nacimiento falso-----------------';
                            $doc_1 = ' ';
                        } else {
                            //  echo 'entro aqui Certificado de Nacimiento-----------';
                            $doc_1 = 'X';
                        }
                    }
                    if ($historico->doc_descripcion == 'Certificado de Defunción') {
                        if ($historico->doc_url == '' || $historico->doc_url == 'Hubo un error al mover el archivo.' || $historico->doc_url == 'Hubo un error al crear el archivo temporal.' || $historico->doc_url == 'Hubo un error al decodificar la cadena base64.') {
                            //  echo 'entro aqui Certificado de Nacimiento falso-----------------';
                            $doc_3 = ' ';
                        } else {
                            //  echo 'entro aqui Certificado de Nacimiento-----------';
                            $doc_3 = 'X';
                        }
                    }
                }
                $tablaContenido = str_replace('#DOC_1#', $doc_1, $tablaContenido);
                $tablaContenido = str_replace('#DOC_2#', $doc_2, $tablaContenido);
                $tablaContenido = str_replace('#DOC_3#', $doc_3, $tablaContenido);
                $pdf->writeHTML($tablaContenido, true, false, true, false, '');
                $pdfAsBase64 = $pdf->Output('', 'S');
                $base64Content = base64_encode($pdfAsBase64);
                $success = array('code' => 200, 'mensaje' => 'OK');
                if ($tipo_dibujo == 'Dibujar') {
                    return array("data" => $base64Content, "codigoRespuesta" => $success);
                } else {
                    return array("mensaje" => $pdfAsBase64);
                }
            }
        }
    }

    public function documentacionObservada($tablaContenido, $tipo_dibujo, $cas_id)
    {
        $dataDocumentos = \DB::select("select doc_cas_id,doc_codigo, doc_descripcion,codigo ,doc_referencia
                from _gp_documentos gd
                inner join  gp_observacion go2 on go2.id_observacion = gd.doc_id_observacion
                where  doc_id_observacion != 1 and doc_cas_id = $cas_id  ");
        $html_list_documento = '';
        $count = 1;
        foreach ($dataDocumentos as $documento) {
            $html_docuemnto = '     <tr class="row9">
                <td class="style16" colspan="2"></td>
                <td class="style40" colspan="2">' . $count . '</td>
                <td class="style41" colspan="17"> (' . $documento->doc_referencia . ') ' . $documento->doc_descripcion . '</td>
                <td class="style42" colspan="5"> ' . $documento->codigo . ' </td>
                <td class="style16" colspan="2"></td>
            </tr>';
            $html_list_documento .= $html_docuemnto;
            $count++;
        }

        date_default_timezone_set('America/La_Paz');
        setlocale(LC_TIME, 'es_ES.UTF-8');
        setlocale(LC_TIME, 'spanish');
        $fecha_literal = strftime('%d de %B del %Y', time());
        $tablaContenido = str_replace('#FECHA_HOY#', $fecha_literal, $tablaContenido);
        $tablaContenido = str_replace('#HTML_LIST_DOCUMENTO#', $html_list_documento, $tablaContenido);
        $pdf = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);     // Crear una instancia de TCPDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Author');
        $pdf->SetTitle('Hello PDF');
        $pdf->SetSubject('Simple PDF');
        $pdf->SetKeywords('TCPDF, PDF, hello, world');
        $pdf->AddPage('P');
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetLeftMargin(PDF_MARGIN_LEFT); // Margen izquierdo
        $pdf->SetTopMargin(35); // Margen superior 27 PDF_MARGIN_TOP
        $pdf->SetRightMargin(PDF_MARGIN_RIGHT); // Margen derecho
        $pdf->setPrintFooter(false);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(35);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);
        $pdf->setPageMark();
        $pdf->writeHTML($tablaContenido, true, false, true, false, '');
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        $success = array('code' => 200, 'mensaje' => 'OK');

        if ($tipo_dibujo == 'Dibujar') {
            return array("data" => $base64Content, "codigoRespuesta" => $success);
        } else {
            return array("mensaje" => $pdfAsBase64);
        }
    }

    public function generateActualCorrelativo($cas_id)
    {
        $dataCorr = \DB::select("select cas_correlativo from public.rmx_vys_casos where cas_id=$cas_id ");
        return $dataCorr[0]->cas_correlativo;
    }

    public function nombreProceso($prc_id)
    {
        $dataProceso = \DB::select("select prc_data->>'prc_descripcion' as pro_nombre from public.rmx_vys_procesos where prc_id = $prc_id");
        return $dataProceso[0]->pro_nombre;
    }

    public function nombrePrestaciones($pre_id)
    {
        $dataPrestacion = \DB::select("select lgp_nombre as prestacion_nombre from public.lgprestaciones where lgp_id = $pre_id");
        return $dataPrestacion[0]->prestacion_nombre;
    }
}
class MYPDF2 extends TCPDF
{
    public function Header()
    {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 36);
        $img_file = 'img/pp.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        $this->SetAutoPageBreak(42, 40);
        $this->setPageMark();
    }
}

class MYPDF3 extends TCPDF
{
    public function Header()
    {
        $bMargin = $this->getBreakMargin();
        $auto_page_break = $this->AutoPageBreak;
        $this->SetAutoPageBreak(false, 36);

        $this->SetAutoPageBreak(42, 40);
        $this->setPageMark();
    }
    public function Footer()
    {
        // No hacer nada aquí para eliminar el pie de página
    }
}



class MYPDF extends TCPDF
{
    //Page header
    public function Header()
    {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = 'img/pp.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

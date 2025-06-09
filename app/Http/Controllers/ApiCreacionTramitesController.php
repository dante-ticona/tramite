<?php
namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use TCPDF;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\servicioGestora\fallecidosSslpController;
use App\Http\Controllers\ApiVySController;
use App\Http\Controllers\documentos\documentosPrevisualizacionController;
/* hola */
class ApiCreacionTramitesController extends Controller
{
    public function grabarCasos(Request $request)
    {
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_gestion = $request["cas_data.cas_gestion"];
        $primer_act_id = $request["primer_act_id"];
        $primer_act_nodo_id = $request["primer_act_nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        try {
            $tramite = \DB::table('rmx_vys_casos')
                ->select(\DB::raw('coalesce(max(cast(split_part(cas_cod_id, \'/\', 2) as integer)), 0) as nro_tramite'))
                ->where('cas_cod_id', 'like', $request["prc_codigo"] . '/%/' . $cas_gestion)
                ->get();
            $nro_tramite = $tramite[0]->nro_tramite;
            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);
            $codigo = $request["prc_codigo"];
            $codigo2 = $request["prc_codigo"] . '/' . ($nro_tramite + 1) . '/' . $cas_gestion;
            $sql = "select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id ,$cas_gestion) ";
            //dd($sql);
            $data = \DB::select("select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id,$cas_gestion) ");
            return response()->json(["data" => $data[0]->sp_grabar_casos, "codigoRespuesta" => $success, "codigo" => $codigo2]);
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error, "codigo" => $codigo2], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }
    public function grabarCasosAqui($request)
    {
        $registro=array(
            "cas_gestion" => date("Y"),
        );

        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_gestion = $request["cas_gestion"];
        $primer_act_id = $request["primer_act_id"];
        $primer_act_nodo_id = $request["primer_act_nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        try {
            $tramite = \DB::table('rmx_vys_casos')
                ->select(\DB::raw('coalesce(max(cast(split_part(cas_cod_id, \'/\', 2) as integer)), 0) as nro_tramite'))
                ->where('cas_cod_id', 'like', $request["prc_codigo"] . '/%/' . $cas_gestion)
                ->get();
            $nro_tramite = $tramite[0]->nro_tramite;
            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);
            $codigo = $request["prc_codigo"];
            $codigo2 = $request["prc_codigo"] . '/' . ($nro_tramite + 1) . '/' . $cas_gestion;
            $sql = "select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id ,$cas_gestion) ";
            //dd($sql);
            $data = \DB::select("select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id,$cas_gestion) ");
            $respuesta = array("data" => $data[0]->sp_grabar_casos, "codigoRespuesta" => $success,"codigo"=>$codigo2 );
            return $respuesta;
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error, "codigo" => $codigo2], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }

    public function validarBoleta(Request $request)
    {
        $cas_cua = $request->input("cas_cua");
        $url = "https://pruebas.gestora.bo/seguridad-apis/api/autenticar";
        $data1 = [
            'username' => 'wsprestaciones',
            'password' => 'Prestaciones2023'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $token = $response["token"];
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token, // Aquí agregas el token en el header
            ])->get('https://pruebas.gestora.bo/reporte-boletas-pago/api/v1/boletasJubilacionTitularByCua?cuaAsegurado=' . $cas_cua);
            return $response2;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function buscarBeneficiario1582(Request $request)
    {
        $tipoDoc = $request->input("tipo_documento");
        $numDoc = $request->input("numero_documento");
        $complemento = $request->input("complemento");
        $fecNac = $request->input("fecha_nacimiento");
        $error = array("message" => "error de instancia", "code" => 500);
        $url =  urlGestora()."/spr-nls-rest/api/consulta/prestaciones?tipoDoc=". $tipoDoc ."&numDoc=". $numDoc ."&complemento=". $complemento ."&fecNac=". $fecNac;
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->get($url);
            $response = $response->json();
           // echo($response);
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function verificarAsegurado1582($cua)
    {
        $cua = $cua;
        $url = "https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip";
        $data1 = [
            'cua' => $cua,
            'tipoBusqueda' => 'T'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $codigoRespuesta = $response["codigoRespuesta"];
            $mensaje = $response["mensaje"];
            $data = $response["data"];
            return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function buscarAsegurado1582($cua)
    {
        $cua = $cua;
        $url = "https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip";
        $data1 = [
            'cua' => $cua,
            'tipoBusqueda' => 'T'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $codigoRespuesta = $response["codigoRespuesta"];
            $mensaje = $response["mensaje"];
            $data = $response["data"];
            $respuesta = array("codigoRespuesta" => $codigoRespuesta, "mensaje" => $mensaje,"data"=>$data );
            return $respuesta;
            //return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function buscarDerechohabiente1582($datos)
    {
        //$cua = $cua;
        $url = "https://cenpersona.gestora.bo/api/v1/personasip/buscaPersonaRegistroDirectoSip";
        $data1 = [
            'cua' => 'cua',
            'tipoBusqueda' => 'T'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $datos);
            $response = $response->json();
            $codigoRespuesta = $response["codigoRespuesta"];
            $mensaje = $response["mensaje"];
            $data = $response["data"];
            $respuesta = array("codigoRespuesta" => $codigoRespuesta, "mensaje" => $mensaje,"data"=>$data );
            return $respuesta;
            //return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function obtenerDepto($depto)
    {
        $respuesta='';
        if($depto=='LA PAZ'){
            $respuesta = array("ciudad" => "2010101", "ciudadValue" => "LA PAZ - MURILLO - LA PAZ",
                                "provincia"=>"01","provinciaValue"=>"MURILLO",
                                "departamento"=>"2","departamentoValue"=>"LA PAZ" );
        } else if($depto=='SANTA CRUZ'){
            $respuesta = array("ciudad" => "7010101", "ciudadValue" => "SANTA CRUZ DE LA SIERRA - ANDRES IBAÑEZ - SANTA CRUZ",
                                "provincia"=>"01","provinciaValue"=>"ANDRES IBAÑEZ",
                                "departamento"=>"7","departamentoValue"=>"SANTA CRUZ" );
        }else if($depto=='TARIJA'){
            $respuesta = array("ciudad" => "6010101", "ciudadValue" => "TARIJA - CERCADO - TARIJA",
                                "provincia"=>"01","provinciaValue"=>"CERCADO",
                                "departamento"=>"6","departamentoValue"=>"TARIJA" );
        }else if($depto=='BENI'){
            $respuesta = array("ciudad" => "8010101", "ciudadValue" => "TRINIDAD - CERCADO - BENI",
                                "provincia"=>"01","provinciaValue"=>"CERCADO",
                                "departamento"=>"8","departamentoValue"=>"BENI" );
        }else if($depto=='PANDO'){
            $respuesta = array("ciudad" => "9010101", "ciudadValue" => "SANTA CRUZ (c. COBIJA) - NICOLAS SUAREZ - PANDO",
                                "provincia"=>"01","provinciaValue"=>"NICOLAS SUAREZ",
                                "departamento"=>"9","departamentoValue"=>"PANDO" );
        }else if($depto=='CHUQUISACA'){
            $respuesta = array("ciudad" => "1010101", "ciudadValue" => "SUCRE - OROPEZA - CHUQUISACA",
                                "provincia"=>"01","provinciaValue"=>"OROPEZA",
                                "departamento"=>"1","departamentoValue"=>"CHUQUISACA" );
        }else if($depto=='COCHABAMBA'){
            $respuesta = array("ciudad" => "3010101", "ciudadValue" => "COCHABAMBA - CERCADO - COCHABAMBA",
                                "provincia"=>"01","provinciaValue"=>"CERCADO",
                                "departamento"=>"3","departamentoValue"=>"COCHABAMBA" );
        }else if($depto=='ORURO'){
            $respuesta = array("ciudad" => "4010101", "ciudadValue" => "ORURO - CERCADO - ORURO",
                                "provincia"=>"01","provinciaValue"=>"CERCADO",
                                "departamento"=>"4","departamentoValue"=>"ORURO" );
        }else if($depto=='POTOSI'){
            $respuesta = array("ciudad" => "5010101", "ciudadValue" => "POTOSI - TOMAS FRIAS - POTOSI",
                                "provincia"=>"01","provinciaValue"=>"TOMAS FRIAS",
                                "departamento"=>"5","departamentoValue"=>"POTOSI" );
        }
        return $respuesta;
    }
    public function cargar1582($request)
    {
        DB::beginTransaction(); // Iniciar la transacción
        try{
            $departamento = $request["departamento"];
        // dd($departamento);
            $datosDepartamento = $this->obtenerDepto($departamento);
            $cua = $request["cua"];
            $tipoSol = $request["tipoSol"];
            $datoSol = $request["datoSol"];
            $cas_act_id = $request["cas_act_id"];
            $uid = $request["uid"];
            $cas_usr_id = $request["cas_usr_id"];
            $datosBusqueda = $this->buscarAsegurado1582($cua);
            $datoSolicitante ='';

            $fecha = date("Y-m-d");
            $hora = date("h:m"); 
            $gestion = $request["cas_gestion"];
            /////ciudad-provincia-departamento
            $ciudad = $datosDepartamento["ciudad"];
            $ciudadValue = $datosDepartamento["ciudadValue"];
            $provincia = $datosDepartamento["provincia"];
            $provinciaValue = $datosDepartamento["provinciaValue"];
            $departamentoId = $datosDepartamento["departamento"];
            $departamentoValue = $datosDepartamento["departamentoValue"];
            $ciudadValue = str_replace("'", "''", $ciudadValue);
            $provinciaValue = str_replace("'", "''", $provinciaValue);
            $departamentoValue = str_replace("'", "''", $departamentoValue);
            $solDifAs = 'true';
            if($datosBusqueda["codigoRespuesta"]== 0){
                if( $tipoSol =='T'){
                    $datoSolicitanteI = $datosBusqueda;
                    $solDifAs = 'true';
                } else if( $tipoSol =='D'){
                    $datoSolicitanteI = $this->buscarDerechohabiente1582($datoSol);
                    $solDifAs = 'false';
                }
                $caso = $this->grabarCasosAqui($request);
                $datosAsegurado = $datosBusqueda["data"];
                $datoSolicitante = $datoSolicitanteI["data"];
                $cas_id = $caso["data"];
                $codigo = $caso["codigo"];
                $tipo = $request["tipo"];
                $dataCorr = \DB::select("select cas_correlativo from public.rmx_vys_casos where cas_id=$cas_id ");
                $correlativo = $dataCorr[0]->cas_correlativo;
                $detalles = json_encode($request["detalles"]);
                $valor='';
                $valorLabel='';
                if($tipo=='AUTOMATICO'){
                    $valor='CVEAP-B1';
                    $valorLabel='1. SOLICITUD DE JUBILACIÓN (LEY 1582) - AUTOMATICO';
                } else {
                    $valor='CVEAP-B2';
                    $valorLabel='2. SOLICITUD DE JUBILACIÓN (LEY 1582) - MANUAL';
                }
                try {
                    ///TITULAR
                    //$cas_data_valores_con = str_replace('\'', '\'\'', $cas_data_valores);
                    $tipoIdentidad=$datosAsegurado["tipoIdentidad"]=== null ? ' ' : $datosAsegurado["tipoIdentidad"];
                // dd($complemento);
                    $documentoIdentidad=$datosAsegurado["documentoIdentidad"]=== null ? ' ' : $datosAsegurado["documentoIdentidad"];
                    $complemento=$datosAsegurado["complemento"]=== null ?  ' ' : $datosAsegurado["tipoIdentidad"];
                    $cua=$datosAsegurado["cua"]=== null ? ' ' : $datosAsegurado["cua"];
                    $primerNombre=$datosAsegurado["primerNombre"]=== null ? ' ' : $datosAsegurado["primerNombre"];
                    $primerNombre = str_replace("'", "''", $primerNombre);
                    $segundoNombre=$datosAsegurado["segundoNombre"]=== null ? ' ' : $datosAsegurado["segundoNombre"];
                    $segundoNombre = str_replace("'", "''", $segundoNombre);
                    $primerApellidoo=$datosAsegurado["primerApellido"]=== null ? ' ' : $datosAsegurado["primerApellido"];
                    $primerApellido = str_replace("'", "''", $primerApellidoo);
                    //dd($primerApellido);
                    $segundoApellido=$datosAsegurado["segundoApellido"]=== null ? ' ' : $datosAsegurado["segundoApellido"];
                    $segundoApellido = str_replace("'", "''", $segundoApellido);

                    $apellidoCasada=$datosAsegurado["apellidoCasada"]=== null ? ' ' : $datosAsegurado["apellidoCasada"];
                    $fechaNacimiento=$datosAsegurado["fechaNacimiento"]=== null ? ' ' : $datosAsegurado["fechaNacimiento"];
                    $idGenero=$datosAsegurado["idGenero"]=== null ? ' ' : $datosAsegurado["idGenero"];
                    $idEstadoCivil=$datosAsegurado["idEstadoCivil"]=== null ? ' ' : $datosAsegurado["idEstadoCivil"];
                    $telefonoCelular=$datosAsegurado["telefonoCelular"]=== null ? ' ' : $datosAsegurado["telefonoCelular"];
                    $telefonoFijo=$datosAsegurado["telefonoFijo"]=== null ? ' ' : $datosAsegurado["telefonoFijo"];
                    $idPersonaSip=$datosAsegurado["idPersonaSip"]=== null ? ' ' : $datosAsegurado["idPersonaSip"];
                    if($tipoIdentidad =='I') $tipoIdentidadValue = 'CEDULA IDENTIDAD';
                    if($tipoIdentidad =='E') $tipoIdentidadValue = 'EXTRANJERO';
                    if($tipoIdentidad =='P') $tipoIdentidadValue = 'PASAPORTE';

                    if($idEstadoCivil =='C') $idEstadoCivilValue = 'CASADO(A)';
                    if($idEstadoCivil =='S') $idEstadoCivilValue = 'SOLTERO(A)';
                    if($idEstadoCivil =='D') $idEstadoCivilValue = 'DIVORCIADO(A)';
                    if($idEstadoCivil =='V') $idEstadoCivilValue = 'VIUDO(A)';

                    if($idGenero =='M') $idGeneroValue = 'MASCULINO';
                    if($idGenero =='F') $idGeneroValue = 'FEMENINO';

                    ///SOLICITANTE
                    $tipoIdentidadSol=$datoSolicitante["tipoIdentidad"]=== null ? ' ' : $datoSolicitante["tipoIdentidad"];
                    $documentoIdentidadSol=$datoSolicitante["documentoIdentidad"]=== null ? ' ' : $datoSolicitante["documentoIdentidad"];
                    $complementoSol=$datoSolicitante["complemento"]=== null ? ' ' : $datoSolicitante["complemento"];
                    $cuaSol=$datoSolicitante["cua"]=== null ? ' ' : $datoSolicitante["cua"];
                    $primerNombreSol=$datoSolicitante["primerNombre"]=== null ? ' ' : $datoSolicitante["primerNombre"];
                    $segundoNombreSol=$datoSolicitante["segundoNombre"]=== null ? ' ' : $datoSolicitante["segundoNombre"];
                    $primerApellidoSol=$datoSolicitante["primerApellido"]=== null ? ' ' : $datoSolicitante["primerApellido"];
                    $segundoApellidoSol=$datoSolicitante["segundoApellido"]=== null ? ' ' : $datoSolicitante["segundoApellido"];
                    $apellidoCasadaSol=$datoSolicitante["apellidoCasada"]=== null ? ' ' : $datoSolicitante["apellidoCasada"];
                    $fechaNacimientoSol=$datoSolicitante["fechaNacimiento"]=== null ? ' ' : $datoSolicitante["fechaNacimiento"];
                    $idGeneroSol=$datoSolicitante["idGenero"]=== null ? ' ' : $datoSolicitante["idGenero"];
                    $idEstadoCivilSol=$datoSolicitante["idEstadoCivil"]=== null ? ' ' : $datoSolicitante["idEstadoCivil"];
                    $telefonoCelularSol=$datoSolicitante["telefonoCelular"]=== null ? ' ' : $datoSolicitante["telefonoCelular"];
                    $telefonoFijoSol=$datoSolicitante["telefonoFijo"]=== null ? ' ' : $datoSolicitante["telefonoFijo"];
                    $idPersonaSipSol=$datoSolicitante["idPersonaSip"]=== null ? ' ' : $datoSolicitante["idPersonaSip"];
                    if($tipoIdentidadSol =='I') $tipoIdentidadValueSol = 'CEDULA IDENTIDAD';
                    if($tipoIdentidadSol =='E') $tipoIdentidadValueSol = 'EXTRANJERO';
                    if($tipoIdentidadSol =='P') $tipoIdentidadValueSol = 'PASAPORTE';

                    if($idEstadoCivilSol =='C') $idEstadoCivilValueSol = 'CASADO(A)';
                    if($idEstadoCivilSol =='S') $idEstadoCivilValueSol = 'SOLTERO(A)';
                    if($idEstadoCivilSol =='D') $idEstadoCivilValueSol = 'DIVORCIADO(A)';
                    if($idEstadoCivilSol =='V') $idEstadoCivilValueSol = 'VIUDO(A)';

                    if($idGeneroSol =='M') $idGeneroValueSol = 'MASCULINO';
                    if($idGeneroSol =='F') $idGeneroValueSol = 'FEMENINO';

                    DB::table('rmx_vys_casos')
                    ->where('cas_id', $cas_id)
                    ->update([
                        'cas_data_valores' => DB::raw("
                        cas_data_valores ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_TIPO_EAP\", \"frm_value\": \"$valor\", \"frm_value_label\": \"$valorLabel\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DATE\", \"frm_campo\": \"FORM_JUB_FECHA\", \"frm_value\": \"2024-10-01\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_TIPO_DOCUMENTO\", \"frm_value\": \"$tipoIdentidad\", \"frm_value_label\": \"$tipoIdentidadValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_CI\", \"frm_value\": \"$documentoIdentidad\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_COMPLEMENTO\", \"frm_value\": \"$complemento\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DATE\", \"frm_campo\": \"AS_NACIMIENTO\", \"frm_value\": \"$fechaNacimiento\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_CUA\", \"frm_value\": \"$cua\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_PRIMER_APELLIDO\", \"frm_value\": \"$primerApellido\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_SEGUNDO_APELLIDO\", \"frm_value\": \"$segundoApellido\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_APELLIDO_CASADA\", \"frm_value\": \"$apellidoCasada\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_PRIMER_NOMBRE\", \"frm_value\": \"$primerNombre\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_SEGUNDO_NOMBRE\", \"frm_value\": \"$segundoNombre\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_ESTADO_CIVIL\", \"frm_value\": \"$idEstadoCivilValue\", \"frm_value_label\": \"$idEstadoCivilValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_GENERO\", \"frm_value\": \"$idGeneroValue\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"AS_WHATSAPP\", \"frm_value\": \"$telefonoCelular\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"AS_TELEFONO\", \"frm_value\": \"$telefonoFijo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"AS_CELULAR\", \"frm_value\": \"$telefonoCelular\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_CIUDAD\", \"frm_value\": \"$ciudad\", \"frm_value_label\": \"$ciudadValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"PROVINCIA\", \"frm_value\": \"$provincia\", \"frm_value_label\": \"$provinciaValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_DEPARTAMENTO\", \"frm_value\": \"$departamentoId\", \"frm_value_label\": \"$departamentoValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AGENCIA\", \"frm_value\": \"OFICINA VIRTUAL\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"USR_NOMBRE\", \"frm_value\": \"\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_CORREO\", \"frm_value\": \"--\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"AS_IDPERSONA\", \"frm_value\": \"$idPersonaSip\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TABLA\", \"frm_campo\": \"DATA_DETALLES\", \"frm_value\": $detalles}'::jsonb ||
                        
                        '{\"frm_tipo\": \"CHECKBOX\", \"frm_campo\": \"SOL_DIFERENTE_AS\", \"frm_value\": $solDifAs, \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_TIPO_DOCUMENTO\", \"frm_value\": \"$tipoIdentidadSol\", \"frm_value_label\": \"$tipoIdentidadValueSol\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_CI\", \"frm_value\": \"$documentoIdentidadSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_COMPLEMENTO\", \"frm_value\": \"$complementoSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DATE\", \"frm_campo\": \"SOL_NACIMIENTO\", \"frm_value\": \"$fechaNacimientoSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_CUA\", \"frm_value\": \"$cuaSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_PRIMER_APELLIDO\", \"frm_value\": \"$primerApellidoSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_SEGUNDO_APELLIDO\", \"frm_value\": \"$segundoApellidoSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_APELLIDO_CASADA\", \"frm_value\": \"$apellidoCasadaSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_PRIMER_NOMBRE\", \"frm_value\": \"$primerNombreSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_SEGUNDO_NOMBRE\", \"frm_value\": \"$segundoNombreSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_ESTADO_CIVIL\", \"frm_value\": \"$idEstadoCivilValueSol\", \"frm_value_label\": \"$idEstadoCivilValueSol\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_PARENTESCO\", \"frm_value\": \"Solicitante\", \"frm_value_label\": \"Solicitante\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_GENERO\", \"frm_value\": \"$idGeneroValueSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"SOL_WHATSAPP\", \"frm_value\": \"$telefonoCelularSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"SOL_TELEFONO\", \"frm_value\": \"$telefonoFijoSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"SOL_CELULAR\", \"frm_value\": \"$telefonoCelularSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_CIUDAD\", \"frm_value\": \"$ciudad\", \"frm_value_label\": \"$ciudadValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_PROVINCIA\", \"frm_value\": \"$provincia\", \"frm_value_label\": \"$provinciaValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"SOL_DEPARTAMENTO\", \"frm_value\": \"$departamentoId\", \"frm_value_label\": \"$departamentoValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AGENCIA\", \"frm_value\": \"OFICINA VIRTUAL\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"USR_NOMBRE\", \"frm_value\": \"\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"SOL_CORREO\", \"frm_value\": \"--\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"SOL_IDPERSONA\", \"frm_value\": \"$idPersonaSipSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_FECHA\", \"frm_value\": \"$fecha\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_HORA\", \"frm_value\": \"$hora\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_CASO_GESTION\", \"frm_value\": \"$gestion\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_CASO_PERIODO\", \"frm_value\": \"00\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_CASO_NRO\", \"frm_value\": \"$idPersonaSipSol\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_USR_ID\", \"frm_value\": \"0\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_USR_NOMBRE\", \"frm_value\": \"OFICINA VIRTUAL\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_COD_PROCESO\", \"frm_value\": \"$codigo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_DEPARTAMENTO\", \"frm_value\": \"$departamento\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_REGIONAL\", \"frm_value\": \"\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_AGENCIA\", \"frm_value\": \"OFICINA VIRTUAL\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_APS_CORRELATIVO\", \"frm_value\": \"$correlativo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_NOMBRE_PROCESO\", \"frm_value\": \"JUBILACIÓN LEY 1582\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_ID_DEPARTAMENTO\", \"frm_value\": \"0\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_ID_REGIONAL\", \"frm_value\": \"0\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"_ID_AGENCIA\", \"frm_value\": \"0\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb  ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"UID\", \"frm_value\": \"$uid\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb 
                        ")]);
                    DB::table('rmx_vys_casos')
                    ->where('cas_id', $cas_id)
                    ->update([
                        'cas_data' => DB::raw("
                            cas_data || jsonb_build_object('AS_CI', '$documentoIdentidad')
                            || jsonb_build_object('UID', '$uid')
                            || jsonb_build_object('cas_nro_caso', '$cas_id')
                            || jsonb_build_object('cas_cod_id', '$codigo')
                            || jsonb_build_object('AS_CUA', '$cua')
                            || jsonb_build_object('AS_CORREO', '')
                            || jsonb_build_object('AS_CELULAR', '$telefonoCelular')
                            || jsonb_build_object('AS_TIPO_EAP', '$valorLabel')
                            || jsonb_build_object('AS_COMPLEMENTO', '$complemento')
                            || jsonb_build_object('AS_PRIMER_NOMBRE', '$primerNombre')
                            || jsonb_build_object('AS_SEGUNDO_NOMBRE', '$segundoNombre')
                            || jsonb_build_object('AS_TIPO_DOCUMENTO', '$tipoIdentidadValue')
                            || jsonb_build_object('AS_PRIMER_APELLIDO', '$primerApellido')
                            || jsonb_build_object('AS_SEGUNDO_APELLIDO', '$segundoApellido')
                            || jsonb_build_object('cas_nombre_caso', '$documentoIdentidad|$primerNombre|$segundoNombre|$primerApellido|$segundoApellido')
                        ")
                        ]);
                    ///GENERAR DOCUMENTO
                    $datosDoc = array(
                        'act_id' => $cas_act_id,
                        'act_usr_id' => 87,
                        'cas_id' => $cas_id,
                        'impid' => 249,
                        'nombre_doc' => 'FORMULARIO DE SOLICITUD DE JUBILACION LEY 1582/2024',
                        "tipo"=> "Dibujar",
                        "firma"=> ""
                    );
                    //dd($datosDoc);
                    $newPrevisualizacion = new documentosPrevisualizacionController;
                    $requestEnvio = new Request($datosDoc);
                    $resultadoDoc = $newPrevisualizacion->generatePdf($requestEnvio);
                    //dd($resultado["data"]);
                    /// enviar respuesta a cesar
                    $datosTram = array(
                        '_id' => $uid,
                        '_tipoActualizacion' => 'G',
                        '_rutaDocumento' => 'dddd',
                        '_nroTramite' => $codigo,
                        '_usuario' => 'OFICINA VIRTUAL'
                    );
                    $apiVySController = new ApiVySController;
                    $requestEnvio = new Request($datosTram);
                    $resultadoPres = $apiVySController->prestaciones1582($requestEnvio);
                    //dd($resultadoPres);
                    DB::commit(); // Confirmar transacción si todo es exitoso
                    return response()->json(["cas_id" => $cas_id, "codigoRespuesta" => 200, "codigo" => $codigo, "documento"=>$resultadoDoc["data"]]);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => $e->getMessage()], 500);
                }
            } else {
                return response()->json(["data" => 0, "codigoRespuesta" => 201, "mensaje" => $datosBusqueda["mensaje"]]);
            }
        }
        catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function crearOV1582(Request $request)
    {
        $tipo = $request->input("tipo");//AUTOMATICO-MANUAL
        $cua = $request->input("cua");
        $departamento = $request->input("departamento");
        $detalles = $request->input("detalles");
        $tipoSol = $request->input("tipoSol");
        $datoSol = $request->input("datoSol");
        $uid = $request->input("uid");
// dd($cua);
       // $tipoSol = 'T'; //T: titular, D: Derechohabiente
       // $datoSol = null;
        $cas_data=[
            "cas_gestion" => date("Y"),
            "cas_departamento" => $departamento,//--------
            "cas_regional" => '',
            "cas_agencia" => '',
            "id_cas_departamento" => 4,
            "id_cas_agencia" => 0,
            "id_cas_regional" => 0,
            "cas_nro_caso" => '',
            "cas_nombre_caso" => '',
            "cas_cod_id" => '',
            "NOMBRE_PROCESO" => 'PENSIÓN POR JUBILACIÓN',
            "TIPO_PROCESO" => 'JUB1582',
            "USUARIO_REGISTRO" => 0,
            "ESTADO_DERIVACION" => 'INICIADO',
            "DESCRIPCION_DERIVACION" => '',
            "cas_registrado" => date("d/m/y"),
            "de_usuario" => '',
            "a_usuario" => 'sin_registro',
            "ORIGEN" => 'OV'
        ];
        // $detalles = [ ///__________________-----------
        //     "ccm" => 662.9, 
        //     "fs" => 854.27,
        //     "fsa" => 389.5,
        //     "pension" => 1906.67
        // ];
        $registro = [
            "cas_act_id" => 247,
            "cas_nodo_id" => 97,
            "cas_data" => $cas_data,
            "cas_data_valores" => [],
            "cas_data_campos_clave" => [],
            "cas_usr_id" => 0,
            "prc_codigo" => 'JUB1582',
            "primer_act_id" => 246,
            "primer_act_nodo_id" => 97,
            "cua" => $cua, //------------------------------
            "tipo" => $tipo,  //------------------------------
            "uid" => $uid,  //------------------------------
            "cas_gestion" => date("Y"),
            "departamento" => $departamento,  //------------------------------
            "detalles" => $detalles,  //------------------------------
            "tipoSol" => $tipoSol,  //------------------------------datoSol
            "datoSol" => $datoSol
        ];
        $caso = $this->cargar1582($registro);
        return ($caso);
    }
}

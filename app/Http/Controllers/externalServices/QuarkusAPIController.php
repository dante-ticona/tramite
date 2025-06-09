<?php

namespace App\Http\Controllers\externalServices;

use App\Http\Controllers\Controller;
use App\Http\Models\UtilConstant;
use App\Http\Services\DocumentoService;
use App\Http\Services\ParticipantsTypeCheckboxEnum;
use App\Http\Services\ParticipantsTypeEnum;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QuarkusAPIController extends Controller
{
    protected $documentoService;

    public function __construct(DocumentoService $documentoService){
        $this->documentoService = $documentoService;
    }
    /**
     * System: Quarkus-gtalento.
     *
     * This method retrieves a {"SI","NO"} of tramite-poder using the provided dniNro-string-parameter.
     */
    public function quarkusVerificarFuncionario(Request $request){
        $request = $request->all();
        $client = new Client();
        $request = array_filter($request, function ($value) {
            return !is_null($value);
        });
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            //url:url: urlApiRestfulBase + "/api/v1/agencia?idDepartamento=" + onlyId,
            // $url = gtalentoQuarkusUrlBase() . "/sgg-it-rest/api/v1/legalVerificarFuncionario";
            // $response = $client->post($url, [
            //     'headers' => [
            //         'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.HwqufMy7lXgsSdBYzsr3D2UyveP12TWdQ4Pa_iNJ_Eg',
            //         'Content-Type' => 'application/json',
            //     ],
            //     'json' => $request
            // ]);

            $jsonString = '{
                "codigoRespuesta": 0,
                "fechaHora": "2025-04-11T20:32:57.641+00:00",
                "mensaje": "La operación se realizo Existosamente",
                "respuesta": "NO"
            }';

            // $response = json_decode($jsonString, true); // <-- true makes it return an associative array
            $data = json_decode($jsonString, true);
            return response()->json($data,200);
        } catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            return response()->json($error,500);
        }
    }
    /**
     * System: Quarkus-service provided by Heidy Soliz.
     *
     * This method retrieves a ID of tramite-poder using the provided nested-json-asegurado-and-derechoHabientes.
     */
    public function quarkusTramitesPoder(Request $request){
        // $client = new Client();
        $error = array("message" => "error de instancia", "code" => 500);
        $success = array("message" => "Exito ejecutando el servicio equipo plantillas", "code" => 200);
        $request= $request->all();
        $casId = $request["casId"];

        try {
            $solicitudePayload = $this->assembleJson($casId);
            // $jsonString = json_encode($solicitudePayload);
            // Log::info($jsonString);
            //1) get the a valid-jwt 
            $arrayAuthenticatePoder = authenticateUrlRegistrarPoder();
            $payload = json_decode(json_encode($arrayAuthenticatePoder["credenciales"]), true);
            
            $responseToken = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->withoutVerifying() // Disables SSL certificate validation
            ->post($arrayAuthenticatePoder["url"], $payload);
            
            $responseToken = $responseToken->json();
            $token = $responseToken["accessToken"];
            // $registrarPoderUrl = urlsggTest() . '/prestaciones-novedades/api/v1/novedades/registro?siglaBoletaJubilacionTramite=PDRES';
            $registrarPoderUrl = urlsggTest() . '/prestaciones-novedades/api/v1/novedades/save/novedad?' . http_build_query([
                'siglaBoletaJubilacionTramite' => 'PDRES'
            ]);
            // Verify JSON encoding
            // if (json_last_error() !== JSON_ERROR_NONE) {
            //     throw new Exception('Invalid JSON payload: ' . json_last_error_msg());
            // }
            //2) trigger the "registrar-poder" web-service
            Log::info(json_encode($solicitudePayload,JSON_UNESCAPED_UNICODE)) ;
            $serviceResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => 'Laravel/10.0', // Mimic Postman's behavior
                'Accept-Encoding' => 'gzip, deflate, br'
            ])->withoutVerifying() // Disables SSL certificate validation
            ->post($registrarPoderUrl, $solicitudePayload);
            //3) return response as is.
            $data = json_decode($serviceResponse->getBody(), true);
            if($data["codigoRespuesta"]!== "0"){
                $message = $data["mensajeRespuesta"]??"Error: Servicio Quarkus";    
                throw new Exception($message, 400);
            }
            $success["data"] = $data;
            return response()->json($success,200);
        
        }catch(ContinueException $e) {
            $success["message"] = $e->getMessage()??"This request is OK.";
            $success["code"] = $e->getCode()??200;
            return response()->json($success,$success["code"]);
        }catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            $error["code"] = $e->getCode() && $e->getCode() !== 0?$e->getCode():500;
            return response()->json($error,$error["code"]);
        }
    }

    private function assembleJson($casId){
        /*
        {
    "idRegional":4,//_ID_REGIONAL //??????
    "idPersonaSipAsegurado": 1017604,// AS_IDPERSONA //???????
    "idBeneficiarioCondicionAsegurado": 1748579,// BE_IDPERSONA //??????
    "tiposBoleta": [
        {
            "id":1,// id-caso = f(cas_dependiente_id = cas_id_activo_derivacion) //??????
            "tipo":"JUB"//cod-proceso
        },
        {
            "id":2, //id-caso = f(cas_dependiente_id = cas_id_activo_derivacion) //????????
            "tipo":"RIS"//cod-proceso
        }
    ],
    "preguntasCheckBox": [
        {
            "pregunta": "¿Poder se encuentra validado por el Area legal?",// pasamos la actividad 40 (VERIFICACIÓN DE PODERES), then "true" //??????? estado_derivacion
            "respuesta": true
        },
        {
            "pregunta": "¿Cuenta con periodos pendientes de cobro o habilitacion?",// ????????
            "respuesta": true
        }
    ],
    "respaldos": [//all documents upload to table "_gp_documentos" //????????
        {
            "codigoDescripcionRespaldo": "CI",
            "nombreArchivo": "CIANV.jpg",
            "datosContenido": null,
            "base64": "/9j/4AAQSkZJRgABAQ"
        },
        {
            "codigoDescripcionRespaldo": "PODER",// formulario-51-data
            "nombreArchivo": "cest.png",
            "datosContenido": {
                "sector": "Nacional",//?????????
                "fechaEmision": "2023-09-12",//?????????
                "fechaInicio": "2023-10-01",//?????????
                "fechaFin": "2023-10-31",//?????????
                "fechaInicioVigencia": "2023-10-01", //?????????
                "fechaFinVigencia" : "2024-03-31"//?????????
            },
            "base64": "iVBOggg=="
        },
        {
            "codigoDescripcionRespaldo": "FIRMA-PAD",
            "nombreArchivo": "firma.png",
            "datosContenido": null,
            "base64": "ggg=="
        }
    ],
    "participantes": [ //DATOS DEL BENIFICIARIO,DATOS DEL SOLICITANTE, DATOS DEL APODERADO(GRID), DATOS DE HEREDERO(GRID)//????????
        {
            "codigoTipoRolNovedad": "DH/TIT",//????????? beneficiario/TITULAR/ASEGURADO
            "idPersonaSip": 2150343,//
            "parentesco": null,//????????? 
            //"idBeneficiarioCondicion": 1899198,// SOL_IDPERSONA, DAHE_IDPERSONA_GRILLA_PROP_XX,  DAHERDERO_IDPERSONA_GRILLA_PROP_XX,  //????????????
            "datosReferenciales": null
        },
        {
            "codigoTipoRolNovedad": "COB",//????????????? apoderado/SOLICITANTE-SUBFORM (SOL_IDPERSONA)
            "idPersonaSip": 2191573,
            //"idBeneficiarioCondicion": 189934377,
            "parentesco": 1,
            "datosReferenciales": [
                {
                    "codigoTipoReferencia": "TELEF-CEL",//DAHE_NRO_CELULAR_XX, DAHERDERO_NRO_CELULAR_X1, BE_CELULAR, SOL_CELULAR,//?????????????
                    "datoReferencia": "77777"
                }
            ]
        }
    ],
    "informacionExtraNovedad": null
}
        */
        // $casId = $request["casId"];
        $dataResponse = DB::select("select rvc.cas_data_valores, rvc.cas_data
                            from rmx_vys_casos rvc 
                            where rvc.cas_id = :casId", array("casId"=>$casId));
        if(empty($dataResponse)){
            throw new Exception("No record was found for the given casId.");
        }
        $arrayCasDataValores = json_decode($dataResponse[0]->cas_data_valores, true);
        $arrayCasData = json_decode($dataResponse[0]->cas_data, true);
        //1) validations. When estado-derivacion = 'APR' then pursue with trigger the service.
        $responsePursueDerivation = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','ESTADO_DERIVACION');
        //2) validations. When parametrica-de-parametricas config 'LEGAL-GRUPO && GRUPO-COBRO' are meeted;
        $formDerivationCondition = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_TIPO_EAP');
        $formDerivationCondition = $formDerivationCondition === UtilConstant::NOT_FOUND? null: (int)$formDerivationCondition;
        //2.1) 
        $dataDerivacion = $this->documentoService->getParametricaDeParametricas('LEGAL-GRUPO', 'GRUPO-COBRO');
        if(!isset($dataDerivacion[0])){
            throw new Exception("Error, no existe la configuracion de la parametrica-de-parametricas 'LEGAL-GRUPO' && 'GRUPO-COBRO'.");
        }
        $dataDerivacion = $dataDerivacion[0]->pdp_parameter_value;
        if(collect($dataDerivacion)->contains($formDerivationCondition) ===false){
            throw new ContinueException("El caso no cumple con la configuracion de la parametrica-de-parametricas 'LEGAL-GRUPO' && 'GRUPO-COBRO'. En consecuencia no se dispara el servicio-registro-poder equipo-heidy.", 200);
        }

        if($responsePursueDerivation !== UtilConstant::LEGAL_APROBADO_DERIVATION_STATUS){
            throw new ContinueException("El caso no se encuentra en estado de derivacion APROBADO (derivacion tipo observacion). En consecuencia no se dispara el servicio-registro-poder equipo-heidy.", 200);
        }
        $titularIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_IDPERSONA');
        $titularIdPersona = $titularIdPersona ===UtilConstant::NOT_FOUND? throw new Exception("Error, no existe el IdPersona-titular"): (int)$titularIdPersona;
        $firstLevelBeneficiarioIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','BE_IDPERSONA');
        $firstLevelBeneficiarioIdPersona = $firstLevelBeneficiarioIdPersona ===UtilConstant::NOT_FOUND? null: (int)$firstLevelBeneficiarioIdPersona;
        $homologadoIdRegional = $this->homologadoIdRegional($arrayCasData, $casId);
        $firstLevel = [
            "idPersonaSipAsegurado"=>$titularIdPersona,
            "idBeneficiarioCondicionAsegurado"=> $firstLevelBeneficiarioIdPersona,
            "idRegional"=>$homologadoIdRegional,
            "tiposBoleta"=> [],
            "preguntasCheckBox"=>[],
            "respaldos"=>[],
            "participantes"=>[],
            "informacionExtraNovedad"=>null,
        ];
        
        $firstLevel["tiposBoleta"]= $this->getTiposBoleta($arrayCasDataValores, $casId);
        $firstLevel["preguntasCheckBox"]= $this->getPreguntasCheckBox($arrayCasDataValores, $casId);
        $firstLevel["respaldos"]=$this->getRespaldos($arrayCasDataValores, $casId);
        $firstLevel["participantes"]=$this->getParticipantes_v1($arrayCasDataValores, $casId);
        $firstLevel["informacionExtraNovedad"]=[
            "idTramite"=> $casId /// acordado, enviamos el cas_id.
        ];
        return $firstLevel;
    }

    private function getParticipantes($arrayCasDataValores, $casId){
        //BE_DIFERENTE_AS
        $codigoTipoRolNovedad = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','BE_DIFERENTE_AS');
        $codigoTipoRolNovedad = $codigoTipoRolNovedad === UtilConstant::NOT_FOUND || $codigoTipoRolNovedad === false?'DH':'TIT';
        $participanteTitular = null;
        if($codigoTipoRolNovedad === 'DH'){
            //- Tenemos que generar el participante TIT = f(subformulario-BENEFICIARIO)
            //BE_IDPERSONA
            $beneficiarioIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_IDPERSONA');
            $beneficiarioIdPersona  = $beneficiarioIdPersona === UtilConstant::NOT_FOUND? null: (int)$beneficiarioIdPersona;
            
            //BE_CELULAR
            $beneficiarioCelular = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_CELULAR');
            $beneficiarioDatosReferenciales=[[
                "codigoTipoReferencia"=>"TELEF-CEL",
                "datoReferencia"=>$beneficiarioCelular
            ]];
            if($beneficiarioCelular===UtilConstant::NOT_FOUND){
                $beneficiarioDatosReferenciales= null;
            }
            $participanteTitular = [
                "codigoTipoRolNovedad"=> "TIT",
                "idPersonaSip"=> $beneficiarioIdPersona,// 2150343,
                "parentesco"=> null,// null,
                "datosReferenciales"=>$beneficiarioDatosReferenciales
            ];

        }
        //BE_PARENTESCO = f(BE_DIFERENTE_AS)
        $beneficiarioParentesco = $this->homologadoExternoParentesco($arrayCasDataValores,'id','BE_PARENTESCO','frm_value');
        $beneficiarioParentesco = $beneficiarioParentesco["id"]??UtilConstant::NOT_FOUND;
        $beneficiarioParentesco = $beneficiarioParentesco === UtilConstant::NOT_FOUND? null: (int)$beneficiarioParentesco;
        if($codigoTipoRolNovedad === 'TIT'){
            $beneficiarioParentesco = null;//TIT by default
        } 
        //BE_IDPERSONA
        $beneficiarioIdPersona = (int)$this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','BE_IDPERSONA');
        $beneficiarioIdPersona = $beneficiarioIdPersona === UtilConstant::NOT_FOUND?null:(int)$beneficiarioIdPersona;
        //BE_CELULAR
        $beneficiarioCelular = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','BE_CELULAR');
        $beneficiarioDatosReferenciales=[[
            "codigoTipoReferencia"=>"TELEF-CEL",
            "datoReferencia"=>$beneficiarioCelular
        ]];
        if($beneficiarioCelular===UtilConstant::NOT_FOUND){
            $beneficiarioDatosReferenciales= null;
        }

        $participanteBeneficiario = [
            "codigoTipoRolNovedad"=> $codigoTipoRolNovedad, //"DH",//TIT
            "idPersonaSip"=> $beneficiarioIdPersona,// 2150343,
            "parentesco"=> $beneficiarioParentesco,// null,
            "datosReferenciales"=>$beneficiarioDatosReferenciales
        ];
        //================================ datos-apoderado-cobrador ultimo json-heidy.
        //GRID-COMPONENT-ID: GRILLA_DAHE
        //RECORDS:
        //DAHE_FACULTAD_CO2 ---> Apoderado-Cobrador-SI --> de la lista de apoderados que podria tener  en la grilla
        //{ "col_campo": "DAHE_FACULTAD_CO", "col_value": true } --> only must exist one with the value TRUE
        $gridApoderadosCobrador = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','GRILLA_DAHE');
        if($gridApoderadosCobrador === UtilConstant::NOT_FOUND){
            throw new Exception("No existe ningun apoderado-cobrador registrado");
        }
        // $gridApoderadosCobrador = json_decode($gridApoderadosCobrador,true);
        //step-1) find  
        $found = collect($gridApoderadosCobrador)->first(function ($item) {
            // Convert the sub-array into a collection for easy search
            return collect($item)->contains(function ($element) {
                return $element['col_campo'] === 'DAHE_FACULTAD_CO' && $element['col_value'] === true;
            });
        });
        if(!$found){
            throw new Exception("No se encontro a un apoderado-cobrador definido dentro de los cobradores registrados!");
        }
        $cobradorIdPersonaSip = (int) $this->documentoService->findArrayCasDataValores($found,'col_campo','DAHE_IDPERSONA_GRILLA_PROP','col_value');
        $cobradorIdPersonaSip = $cobradorIdPersonaSip === UtilConstant::NOT_FOUND? null: (int)$cobradorIdPersonaSip;
        if($cobradorIdPersonaSip === null){
            throw new Exception("IdPersona-cobrador no registrado!");
        } 
        $cobradorCelular = $this->documentoService->findArrayCasDataValores($found,'col_campo','DAHE_NRO_CELULAR','col_value');
        $cobradorDatosReferenciales = [[
            "codigoTipoReferencia"=>"TELEF-CEL",
            "datoReferencia"=>$cobradorCelular
        ]];
        if($cobradorCelular===UtilConstant::NOT_FOUND){
            $cobradorDatosReferenciales= null;
        }

        $participanteCobrador =[
            "codigoTipoRolNovedad"=> 'APOD', //"COB"
            "idPersonaSip"=> $cobradorIdPersonaSip,// 2150343,
            "parentesco"=> null,// null,
            "datosReferenciales"=>$cobradorDatosReferenciales
        ];

        $participantes = [
            $participanteBeneficiario,
            $participanteCobrador
        ];
        if($participanteTitular !== null){
            array_push($participantes, $participanteTitular);
        }
        return $participantes;
    }

    private function formatDatosReferenciales($beneficiarioCelular){
        $beneficiarioCelular = $beneficiarioCelular === UtilConstant::NOT_FOUND || $beneficiarioCelular === false || trim($beneficiarioCelular) === '' ?null:$beneficiarioCelular;
        $beneficiarioDatosReferenciales=[[
            "codigoTipoReferencia"=>"TELEF-CEL",
            "datoReferencia"=>$beneficiarioCelular
        ]];
        if($beneficiarioCelular === null){
            $beneficiarioDatosReferenciales = null;
        }
        return $beneficiarioDatosReferenciales;
    }
    private function getParticipantes_v1($arrayCasDataValores, $casId){
        //0)========================== SUBFORMULARIO TITULAR --> here retrieve the asegurado-data (TIT) (sub-formulario).
        $beneficiarioIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_IDPERSONA');
        $beneficiarioIdPersona  = $beneficiarioIdPersona === UtilConstant::NOT_FOUND? null: (int)$beneficiarioIdPersona;
        $beneficiarioCelular = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_CELULAR');
        $beneficiarioDatosReferenciales= $this->formatDatosReferenciales($beneficiarioCelular);
        $participanteTitular = [
            "codigoTipoRolNovedad"=> ParticipantsTypeEnum::TITULAR->value,
            "idPersonaSip"=> $beneficiarioIdPersona,// 2150343,
            "parentesco"=> null,// null,
            "datosReferenciales"=>$beneficiarioDatosReferenciales
        ];

        //1)============================ SUBFORMULARIO SOLICITANTE 
        // 1.1) ya no existe el subformulario BENEFICIOARIO => implica que no existe BE_DIFERENTE_AS(check)
        // 1.2) ahora procesamos el subformulario del solicitante. El cual contiene un checkbox (SOL_DIFERENTE_AS) para copiar info del titular.
        $codigoTipoRolNovedad = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','SOL_DIFERENTE_AS');
        //TODO: CHECK: si el solicitante se considera como DH???? VERIFICAR
        $beneficiarioIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','SOL_IDPERSONA');
        $beneficiarioIdPersona  = $beneficiarioIdPersona === UtilConstant::NOT_FOUND? null: (int)$beneficiarioIdPersona;
        $codigoTipoRolNovedad = $codigoTipoRolNovedad === UtilConstant::NOT_FOUND || $codigoTipoRolNovedad === false?ParticipantsTypeEnum::SOLICITANTE->value:ParticipantsTypeEnum::TITULAR->value; // TODO: coordinate with heidy
        $beneficiarioCelular = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','SOL_CELULAR');
        $beneficiarioDatosReferenciales=$this->formatDatosReferenciales($beneficiarioCelular);
        if($codigoTipoRolNovedad !== ParticipantsTypeCheckboxEnum::DERECHO_HABIENTE->value){
            //override the id-persona-sip value with the i-persona-sip-titular because the 20.js does't fill this fieldl
            $beneficiarioIdPersona = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_IDPERSONA');
            $beneficiarioIdPersona  = $beneficiarioIdPersona === UtilConstant::NOT_FOUND? null: (int)$beneficiarioIdPersona;
        }
        //BE_PARENTESCO = f(BE_DIFERENTE_AS)
        $participanteSolicitante = [
            "codigoTipoRolNovedad"=> $codigoTipoRolNovedad,
            "idPersonaSip"=> $beneficiarioIdPersona,// 2150343,
            "parentesco"=> null,// null,
            "datosReferenciales"=>$beneficiarioDatosReferenciales
        ];
    
        // 2)================================ SUBFORMULARIO-APODERADO, datos-apoderado-cobrador ultimo json-heidy.
        //GRID-COMPONENT-ID: GRILLA_DAHE
        //RECORDS:
        //DAHE_FACULTAD_CO2 ---> Apoderado-Cobrador-SI --> de la lista de apoderados que podria tener  en la grilla
        //{ "col_campo": "DAHE_FACULTAD_CO", "col_value": true } --> only must exist one with the value TRUE
        $gridApoderadosCobrador = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','GRILLA_DAHE');
        if($gridApoderadosCobrador === UtilConstant::NOT_FOUND){
            throw new Exception("No existe ningun apoderado-cobrador registrado");
        }
        // $gridApoderadosCobrador = json_decode($gridApoderadosCobrador,true);
        //step-1) find  
        $found = collect($gridApoderadosCobrador)->first(function ($item) {
            // Convert the sub-array into a collection for easy search
            return collect($item)->contains(function ($element) {
                return $element['col_campo'] === 'DAHE_FACULTAD_CO' && $element['col_value'] === true;
            });
        });
        if(!$found){
            throw new Exception("No se encontro a un apoderado-cobrador definido dentro de los cobradores registrados!");
        }
        $cobradorIdPersonaSip = (int) $this->documentoService->findArrayCasDataValores($found,'col_campo','DAHE_IDPERSONA_GRILLA_PROP','col_value');
        $cobradorIdPersonaSip = $cobradorIdPersonaSip === UtilConstant::NOT_FOUND? null: (int)$cobradorIdPersonaSip;
        if($cobradorIdPersonaSip === null){
            throw new Exception("IdPersona-cobrador no registrado!");
        } 
        $cobradorCelular = $this->documentoService->findArrayCasDataValores($found,'col_campo','DAHE_NRO_CELULAR','col_value');

        $cobradorDatosReferenciales = $this->formatDatosReferenciales($cobradorCelular);
        
        $participanteCobrador =[
            "codigoTipoRolNovedad"=> ParticipantsTypeEnum::APODERADO->value,  //'APOD', //"COB"
            "idPersonaSip"=> $cobradorIdPersonaSip,// 2150343,
            "parentesco"=> null,// null,
            "datosReferenciales"=>$cobradorDatosReferenciales
        ];
        //3)================================ SUBFORMULARIO-GRILLA BENEFICIARIOS
        $participantesBeneficiariosGrid = $this->getParticipantesGrilla_v1($arrayCasDataValores, $casId);
        //4)================================ MERGE ALL PARTICIPANTS
        $participantes = [
            $participanteTitular,
            $participanteSolicitante,
            $participanteCobrador
        ];
        $participantes = [...$participantes, ...$participantesBeneficiariosGrid];
        return $participantes;
    }

    private function getParticipantesGrilla_v1($arrayCasDataValores, $casId){
        $arrayParticipantes = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','GRILLA_DACO');
        $messageWithoutElements = "No existe ningun beneficiario registrado";
        if($arrayParticipantes === UtilConstant::NOT_FOUND || $arrayParticipantes === false){
            throw new Exception($messageWithoutElements);
        }
        // $arrayParticipantes = json_decode($participantesGrilla, true);
        if(count($arrayParticipantes) === 0){
            throw new Exception($messageWithoutElements);
        }
        $participantesGrillaFormateado=[];
        foreach($arrayParticipantes as $participante){
            $idPersonaSip = $this->documentoService->findArrayCasDataValores($participante,'col_campo','DACO_IDPERSONA_GRILLA_PROP','col_value');
            $idPersonaSip = $idPersonaSip === UtilConstant::NOT_FOUND || $idPersonaSip === false? null: (int)$idPersonaSip;
            if($idPersonaSip === null){
                throw new Exception("IdPersona-beneficioario no registrado!");
            } 
            $homologationParentesco = $this->homologadoExternoParentesco_grilla_v1($participante,"col_campo","DACO_PARENTESCO","col_value");
            $homologationParentesco = $homologationParentesco["id"]?$homologationParentesco["id"]:null;
            $participanteCelular = $this->documentoService->findArrayCasDataValores($participante,'col_campo','DACO_NRO_CELULAR','col_value');
            $participanteCelular = $participanteCelular === UtilConstant::NOT_FOUND || $participanteCelular === false? null: (string)$participanteCelular;
            $cobradorDatosReferenciales = $this->formatDatosReferenciales($participanteCelular);
            $participante = [
                "codigoTipoRolNovedad"=> ParticipantsTypeEnum::DERECHO_HABIENTE->value, //"DH",
                "idPersonaSip"=>$idPersonaSip,
                "idBeneficiarioCondicion"=>null,
                "parentesco"=>$homologationParentesco,
                "datosReferenciales"=>$cobradorDatosReferenciales
            ];
            array_push($participantesGrillaFormateado, $participante);
        }
        return $participantesGrillaFormateado;
    }

    private function homologadoIdRegional($arrayCasData, $casId){
        $idCasDepartamento = (string)$this->documentoService->findArrayCasData($arrayCasData,'id_cas_departamento');
        if($idCasDepartamento === UtilConstant::NOT_FOUND){
            throw new Exception("No existe un valor definido en idRegional.");
        }
        $idCasDepartamento = (int)$idCasDepartamento;
        $jsonIdDepartamento = json_encode([['id_sip_departamento' => $idCasDepartamento]]);
        $homologation = DB::select("SELECT *
            FROM homologation
            WHERE hml_internal_code @> :jsonIdDepartamento", ["jsonIdDepartamento"=>$jsonIdDepartamento]);
        
        if(empty($homologation)){
            throw new Exception("No existe un valor homologado al id-regional:  $idCasDepartamento ");
        }
        $homologadoIdRegional = json_decode($homologation[0]->hml_external_code, true)["id"];
        return $homologadoIdRegional;
    }   

    private function homologadoExternoParentesco($arrayCasDataValores,$searchHomologadoInternoId, $frm_campo, $frm_campo_value = 'frm_value'){
        $procesoId = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo',$frm_campo,$frm_campo_value);
        if($procesoId === UtilConstant::NOT_FOUND){
            return UtilConstant::NOT_FOUND;
        }
        $procesoId = (int) $procesoId;
        $jsonProcesoId = json_encode([[$searchHomologadoInternoId => $procesoId]]);
        $homologation = DB::select("SELECT *
            FROM homologation h
            WHERE h.hml_external_system = 'WILMA-TEAM' and h.hml_external_code_grouper = 'PARENTESCO-PODER' 
                    and  h.hml_internal_code @> :jsonProcesoId", ["jsonProcesoId"=>$jsonProcesoId]);
        
        if(empty($homologation)){
            throw new Exception("No existe un valor homologado para :  $searchHomologadoInternoId .");
        }
        return json_decode($homologation[0]->hml_external_code, true);
    }
    
    private function homologadoExternoParentesco_grilla_v1($arrayCasDataValores, $frm_campo, $frm_campo_value, $frm_campo_value_goal){
        // $parentesco = $this->documentoService->findArrayCasData($arrayCasDataValores,'col_campo','DACO_PARENTESCO','col_value');
        $parentesco = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,$frm_campo,$frm_campo_value,$frm_campo_value_goal);
        $parentesco = $parentesco === UtilConstant::NOT_FOUND || $parentesco === false? null: $parentesco;
        if($parentesco !==null){
            $parentesco = explode("-",$parentesco)[1];//issue: does't match the id only code inside the grid-beneficiarios.
        }
        
        $jsonProcesoId = json_encode([["codigo" => $parentesco]]);
        $homologation = DB::select("SELECT *
            FROM homologation h
            WHERE h.hml_external_system = 'WILMA-TEAM' and h.hml_external_code_grouper = 'PARENTESCO-PODER' 
                    and  h.hml_internal_code @> :jsonProcesoId", ["jsonProcesoId"=>$jsonProcesoId]);
        
        if(empty($homologation)){
            throw new Exception("No existe un valor homologado para el codigo interno parentesco:  $parentesco");
        }
        return json_decode($homologation[0]->hml_external_code, true);
    }

    private function getTiposBoleta ($arrayCasDataValores, $casId){
        $procesoId = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','AS_TIPO_EAP_LEGAL');
        $procesoId = (int) $procesoId;
        $jsonProcesoId = json_encode([['prc_id' => $procesoId]]);
        $homologation = DB::select("SELECT *
            FROM homologation
            WHERE hml_internal_code @> :jsonProcesoId", ["jsonProcesoId"=>$jsonProcesoId]);
        
        if(empty($homologation)){
            throw new Exception("No existe un valor homologado al proceso-id:  $procesoId.");
        }
        return [json_decode($homologation[0]->hml_external_code, true)];
    }

    private function getPreguntasCheckBox($arrayCasDataValores,$casId){
        $respuesta1 = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','ESTADO_DERIVACION');
        $respuesta2 = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','HA_PAGOS_SUPENDIDOS');
        $arrayPreguntasCheckBox =[
            [
                "pregunta"=>"¿Poder se encuentra validado por el Area legal?",
                "respuesta"=> $respuesta1=== UtilConstant::LEGAL_APROBADO_DERIVATION_STATUS? true:false
            ],
            [
                "pregunta"=>"¿Cuenta con periodos pendientes de cobro o habilitacion?",
                "respuesta"=>$respuesta2 ==="1"?true:false
            ]
        ];
        return $arrayPreguntasCheckBox;        

    }
    private function getRespaldos($arrayCasDataValores, $casId){
        $arrayRespaldos = [];
        $document = DB::select("select *
                                from \"_gp_documentos\" gd 
                                where gd.doc_cas_id = :casId and gd.doc_estado ='A' and upper(gd.doc_descripcion) like upper('Poder - DIRNOPLU%') 
                                order by gd.doc_id desc
                                limit 1", array("casId"=>$casId));
        if(empty($document)){
            throw new Exception("No document-record was found for the given casId.");
        }

        $poderSector = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','TIPO_PODER');
        if(Str::contains(Str::upper($poderSector), 'NACIONAL')){
            $poderSector = 'Nacional';
        } else if(Str::contains(Str::upper($poderSector), 'EXTRANJERO')){
            $poderSector = 'Extranjero';
        } else {
            $poderSector = UtilConstant::NOT_FOUND;
        }
        $poderFechaEmision = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','FECHA_DE_EMISION');
        $poderFechaInicio = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','FORM_JUB_MES_INI');//2025-03
        $poderFechaInicio .='-01';
        $poderFechaFin = $this->documentoService->findArrayCasDataValores($arrayCasDataValores,'frm_campo','FORM_JUB_MES_FIN');//"frm_value": "2025-05"
        $poderFechaFin = explode('-',$poderFechaFin);
        // Get the last day as a Carbon instance
        $poderFechaFin = Carbon::createFromDate($poderFechaFin[0], $poderFechaFin[1], 1)->endOfMonth();
        // Format the result
        $poderFechaFin = $poderFechaFin->format('Y-m-d'); // '2024-02-29'
        $poderApostilla = [
            "codigoDescripcionRespaldo"=>'PODER',
            "nombreArchivo"=>str_replace(" ","_",$document[0]->doc_descripcion.".pdf"),
            "datosContenido"=>[
                "sector"=> $poderSector,//: "Nacional",
                "fechaEmision"=> $poderFechaEmision,//: "2023-09-12",
                "fechaInicio"=>$poderFechaInicio ,//: "2023-10-01",
                "fechaFin"=> $poderFechaFin,//: "2023-10-31",
                "fechaInicioVigencia"=> $poderFechaInicio,//: "2023-10-01", 
                "fechaFinVigencia"=> $poderFechaFin, // : "2024-03-31"
            ],
            "base64"=>$this->documentoService->getFileBase64($document[0]->doc_url)
        ];
        array_push($arrayRespaldos,$poderApostilla);
        return $arrayRespaldos;
    }
    
}

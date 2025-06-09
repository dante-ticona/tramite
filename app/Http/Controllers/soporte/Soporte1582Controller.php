<?php

namespace App\Http\Controllers\soporte;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class Soporte1582Controller extends Controller
{
    private function obtenerToken()
    {
        // $request = $request->all();
        $client = new Client([
            'verify' => false,
        ]);
        $error = array("message" => "error de instancia", "code" => 500);
        $login = array("login" => "demo.gestora@gestora-demo.bo", "password" => "Tempo.2024@");
        try {
            //url:url: urlApiRestfulBase + "/api/v1/agencia?idDepartamento=" + onlyId,
            $url = urlGestora() . "/str-seg-aut-rest/autenticacion/funcionarios/token/obtener/pass";
            $response = $client->post($url, [
                'json' => $login
            ]);
            $data = json_decode($response->getBody(), true);
            return response()->json($data, 200);
        } catch (Exception $e) {
            $error["message"] = $e->getMessage() ?? "Bad request.";
            return response()->json($error, 500);
        }
    }

    private function quarkus1582($item1582)
    {
        // $request = $request->all();
        $client = new Client();
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        // $loginCredenciales = $this->token();
        $loginCredenciales = $this->obtenerToken();
        try {
            //url:url: urlApiRestfulBase + "/api/v1/agencia?idDepartamento=" + onlyId,
            $url = urlGestora() . "/spr-tram-rest/api/solicitudPrestacion/crearPorTipoPrestacionOrigen?codigoTipoPrestacion=JUB";
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer' . $loginCredenciales->original['data']['accessToken'],
                    'Content-Type' => 'application/json',
                ],
                'json' => $item1582
            ]);
            $data = json_decode($response->getBody(), true);
            return response()->json($data, 200);
        } catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            return response()->json($error,500);
        }
    }

    public function soporteService1582(Request $request)
    {
        $request = $request->all();
        $casIds = $request['casIds'];
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "message" => "Error de instancia", "data" => 'Error'];

        // $id_persona_sip = $request->input("id_persona_sip");

        try {
            $sql = "select rvc.cas_id, rvc.cas_cod_id as \"codigoSolicitud\",
                rvc.cas_data_valores, rvc.cas_data
        from rmx_vys_casos rvc left join rmx_vys_actividades rva on rvc.cas_act_id =rva.act_id
            left join rmx_vys_procesos rvp on rva.act_prc_id = rvp.prc_id
        where rvp.prc_id = 15 and rvc.cas_id in (" . $casIds . ");";
            $dataPrimerNivel = \DB::select($sql);
            if(!isset($dataPrimerNivel) || count($dataPrimerNivel) === 0) {
                throw new Exception("No se encontraron registros para los cas_ids enviados.");
            } 

            //t1) barrido documentos configurados(documentos estaticos)
            $dataResponEstatico = collect($dataPrimerNivel)->map(function ($item) {
                // Query for additional data
                $sql = "SELECT * FROM public.obtener_valor_grilla_prop(:casId)";
                $postgresFunctionInfo = \DB::select($sql, ['casId' => $item->cas_id]);
            
                // Parse to array from JSON 
                $arraySPData = json_decode($postgresFunctionInfo[0]->vjsondata, true);
                $arraySPDocumentos = json_decode($postgresFunctionInfo[0]->vjsondoc, true);
                $idCodigoGeograficoRegistro = $postgresFunctionInfo[0]->vcodigogeograficoid;
            
                // Extract values for Solicitante
                $solicitanteTelefono = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "SOL_CELULAR");
                $solicitanteCorreo = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "SOL_CORREO");
                $solicitanteIdPersonaSip = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "SOL_IDPERSONA");
            
                // Extract values for Titular
                $titularEnteGestor = collect($arraySPData)->first(function ($item) {
                    return $item['frm_campo'] === "AS_ENTE_GESTOR";
                });
                $fechaSolicitud = collect($arraySPData)->first(function ($item) {
                    return $item['frm_campo'] === "FORM_JUB_FECHA";
                });
                $titularTelefono = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "AS_CELULAR");
                $titularCorreo = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "AS_CORREO");
                $titularIdPersonaSip = collect($arraySPData)->first(fn($i) => $i['frm_campo'] === "AS_IDPERSONA");
            
                $documentosTitular = collect($arraySPDocumentos[0] ?? [])->filter(function ($doc) use ($titularIdPersonaSip) {
                    return $doc['doc_id_persona_sip'] === $titularIdPersonaSip['frm_value'];
                })->map(function ($doc) {
                    return [
                        "idTipoDocumentoSolicitud" => $doc['idTipoDocumentoSolicitud'],
                        "tipoDocumentoGuardado" => $doc['tipoDocumentoGuardado'],
                        "idCodigoArchivo" => $doc['idCodigoArchivo'] ?? '',
                    ];
                })->values()->toArray();
            
                $arraySolicitante = [
                    "idPersonaSip" => isset($solicitanteIdPersonaSip["frm_value"]) &&  $solicitanteIdPersonaSip["frm_value"] !== null && $solicitanteIdPersonaSip["frm_value"] !== "null" && $solicitanteIdPersonaSip["frm_value"] !== ""?$solicitanteIdPersonaSip["frm_value"]: $titularIdPersonaSip["frm_value"],
                    "datosReferenciales" => [
                        [
                            "codigoTipoReferencia" => "TELEF",
                            "datoReferencia" => $solicitanteTelefono["frm_value"] ?? ""
                        ],
                        [
                            "codigoTipoReferencia" => "EMAIL",
                            "datoReferencia" => $solicitanteCorreo["frm_value"] ?? ""
                        ]
                    ],
                    "respaldos" => null
                ];
            
                $arrayParticipante = [
                    "codigoTipoParentesco" => 'TIT',
                    "idPersonaSip" => $titularIdPersonaSip["frm_value"],
                    "estadoInvalidez" => false,
                    "datosReferenciales" => [
                        [
                            "codigoTipoReferencia" => "TELEF",
                            "datoReferencia" => $titularTelefono["frm_value"] ?? ""
                        ],
                        [
                            "codigoTipoReferencia" => "EMAIL",
                            "datoReferencia" => $titularCorreo["frm_value"] ?? ""
                        ]
                    ],
                    "respaldos" => $documentosTitular
                ];
            
                // Updating main item attributes
                $arrayCasData = json_decode($item->cas_data, true);
                $usuarioRegistro = $arrayCasData["USUARIO_REGISTRO"];

                // $item->codigoEnteGestorSalud = $titularCorreo["frm_value"] ?? null;
                $item->codigoEnteGestorSalud = !isset($titularEnteGestor) || $titularEnteGestor["frm_value"] === null || $titularEnteGestor["frm_value"]==="null"?null:$titularEnteGestor["frm_value"];
                // $item->fechaSolicitud = "2025-01-13"; // 
                $item->fechaSolicitud = isset($fechaSolicitud["frm_value"]) &&  $fechaSolicitud["frm_value"] !== null && $fechaSolicitud["frm_value"] !== "null" && $fechaSolicitud["frm_value"] !== ""?$fechaSolicitud["frm_value"]: "2024-10-01";
                $item->usuarioRegistro = $usuarioRegistro; 
                $item->idCodigoGeograficoRegistro = $idCodigoGeograficoRegistro;
                $item->idCodigoGeograficoPago = 2030109;
                $item->solicitante = $arraySolicitante;
                $item->participantesSolicitud = [$arrayParticipante];
            
                // Remove unnecessary fields
                unset($item->cas_data, $item->cas_data_valores, $item->cas_id);
            
                return (array) $item;
            })->toArray();
            
            

            $respuestaFinal = [];
            foreach ($dataResponEstatico as $item) {
                array_push($respuestaFinal, ["jsonRequest" => $item, "jsonResponse" => $this->quarkus1582($item)]);
            }


            $success["data"] = $respuestaFinal;
            $success["fecha"] = (new \DateTime())->format('Y-m-d H:i:s');
            return response()->json($success, 200);
            // return response()->json(["jsonGenerados"=>$dataResponEstatico, "respuestasServicio1582"=>$respuestaFinal ]);
            // return response()->json($respuestaFinal);

        } catch (Exception $e) {
            $error['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage() ?? 'Bad request.';
            return response()->json($error);
        }
    }
}
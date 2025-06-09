<?php

namespace App\Http\Controllers\servicioGestora;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
class estructuraFamiliarPrestacionesController extends Controller
{
    public function updateStateTramite(Request $request)
    {
        $url = urlGestora();
        $urlTest = urlsggTest();

        //CodeTransacción 3 si se aprobo el tramite
        //Codetransaccion 4 si se rechazo el tramite
        ///dd($payload, $credencial, $url);usuario
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        $success_205 = array("message" => "Restablecer contenido , ya se realizo la actualizacion", "code" => 205);

        $nroTramite = $request["nroTramite"];
        $usuario = $request["usuario"];
        // dd('updateStateTramite', $nroTramite, $usuario);
        try {
            $qry = " SELECT   (
                    SELECT datos.valor->>'frm_value'
                    FROM (
                        SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                    ) datos
                    WHERE datos.valor->>'frm_campo' = 'ID_SOLICITUDPRESTACION'
                ) AS seguimiento,        
                (
                    SELECT datos.valor->>'frm_value'
                    FROM (
                        SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                    ) datos
                    WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                ) AS estado_derivacion, 
                (
                    SELECT datos.valor->>'frm_value'
                    FROM (
                        SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                    ) datos
                    WHERE datos.valor->>'frm_campo' = 'AS_ENTE_GESTOR'
                ) AS ente_gestor,     
                cas_data,
                cas_data_valores  
                FROM rmx_vys_casos c
                where cas_cod_id = '$nroTramite'";

            $data = \DB::select($qry);
            $seguimiento = $data[0]->{'seguimiento'};
            $ente_gestor = $data[0]->{'ente_gestor'};
            $codeTransaction = 0;
            if ($data[0]->{'estado_derivacion'} == 'ACEPTADO') {
                $codeTransaction = 3;
            } else {
                $codeTransaction = 4;
            }
            $client = new Client([
                'verify' => false,
            ]);
            $endpoint = $urlTest . '/otorgamiento-prestaciones-calculos/api/v1/definicion/updateStateTramite?idSeguimientoTramite=' . $seguimiento . '&codeTransaction=' . $codeTransaction . '&usuMod=' . $usuario . '@gestora.bo';
            $request_body = '[]';
            $response = $client->post($endpoint, [
                'json' => []
            ]);
            $responseBody = $response->getBody()->getContents();
            $responseBody = json_decode($responseBody);
            $codigo = $responseBody->{'codigo'};

            $headers = '{"Content-Type": "application/json"}';
            $ip = request()->ip();
            $responseBody_log = json_encode($responseBody);
            $qry_log = "select *  from public.sp_create_service_logs('POST','$endpoint', '$request_body',  '$responseBody_log','$codigo', '$headers', '$ip','$usuario', '$nroTramite','PRESTACIONES')";
            $data_log = \DB::select($qry_log);
            $data = $responseBody->{'data'};
            $fecha_hoy = date('Y-m-d');
            //dd($fecha_hoy);
            if ($codigo == '0') {
                $token = $this->token();
                $subtipoPension = $data->{'subtipoPension'};
                $client = new Client();
                $data_actuzalizar = [
                    'siglaEnteGestorSalud' => $ente_gestor,
                    'subtipoPrestacion' => $subtipoPension,
                    'nuevoEstadoPrestacion' => 'CON',
                    'usuarioModificacion' => $usuario,
                    'fechaContrato' => $fecha_hoy
                ];

                ////-----   /api/solicitudPrestacion/actualizar/estado?idSolicitudPrestacion=944901
                $endpoint_actualizar = $url . '/spr-tram-rest/api/solicitudPrestacion/actualizar/estado?idSolicitudPrestacion=' . $seguimiento;
                $headers = [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ];
                $response2 = $client->put($endpoint_actualizar, [
                    'headers' => $headers,
                    'json' => $data_actuzalizar
                ]);
                $headers_log_actualizar = json_encode($headers);
                $data2 = json_decode($response2->getBody(), true);
                $responseBody_log_actualizar = json_encode($data2);
                $data_actuzalizar = json_encode($data_actuzalizar);
                $qry_log = "select *  from public.sp_create_service_logs('PUT','$endpoint_actualizar', '$data_actuzalizar',  '$responseBody_log_actualizar','$codigo', '$headers_log_actualizar', '$ip','$usuario', '$nroTramite','PRESTACIONES_F')";
                $data_log = \DB::select($qry_log);
                return response()->json(["data" => $data_log, "codigoRespuesta" => $success]);
            } else {
                return response()->json(["data" => [], "codigoRespuesta" => $success_205]);
            }
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }


    public function token()
    {
        $url = urlGestora();
        $credencial = obtenerCredenciales();
        $client = new Client();
        try {
            $client = new Client([
                'verify' => false,
            ]);
            $response = $client->post($url . '/str-seg-aut-rest/autenticacion/funcionarios/token/obtener/pass', [
                'json' => $credencial
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['data']['accessToken'];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

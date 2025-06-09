<?php

namespace App\Http\Controllers\servicioGestora;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
class fallecidosSslpController extends Controller
{
    public function token()
    {
        $urlApiGestora = urlApiGestora();
        $client = new Client();
        try {
            $response = $client->post($urlApiGestora . '/app/connect/token?', [
                'form_params' => [
                    'client_id' => 'gestora',
                    'client_secret' => 'uhAJYkVeB1Kn7Bmd5LV9ARISjyKvC6LA',
                    'grant_type' => 'client_credentials',
                    'scope' => 'bo.gp.dbfallecidos',
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ]
            ]);
            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }


    public function registroFallecido(Request $request)
    {

        $urlApiGestora = urlApiGestora();
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        $success_205 = array("message" => "Restablecer contenido , ya se realizo la actualizacion", "code" => 205);
        $nroTramite = $request["nroTramite"];
        $usuario = $request["usuario"];
        $v_cas_id = $request["cas_id"];
        $data = \DB::select("select * from public.sp_datos_updatefallecidos_tramite('$nroTramite')");

        $data = json_decode($data[0]->sp_datos_updatefallecidos_tramite, true);
        $sexo = $data['sexo'] === 'MASCULINO' ? 'M' : 'F';
        $estadoCivil =
            $data['estadoCivil'] === 'SOLTERO(A)' ? '1' :
            ($data['estadoCivil'] === 'CASADO(A)' ? '2' :
                ($data['estadoCivil'] === 'DIVORCIADO(A)' ? '3' :
                    ($data['estadoCivil'] === 'VIUDO(A)' ? '4' :
                        ($data['estadoCivil'] === 'CONVIVIENTE' ? '5' : '5'))));

        $token = $this->token();

        $endpoint = $urlApiGestora . '/app/dbfallecidos/gestion';
        $request_body = [
            "nroCorrelativo" => $data['nroCorrelativo'],
            "cua" => $data['cua'],
            "codEntidad" => $data['codEntidad'],
            "tipoIdentificiacion" => $data['tipoIdentificiacion'],
            "nroIdentificacion" => $data['nroIdentificacion'],
            "compId" => $data['compId'],
            "pagoTitdhBen" => $data['pagoTitdhBen'],
            "primerApellido" => $data['primerApellido'],
            "segundoApellido" => $data['segundoApellido'],
            "apellidoCasada" => $data['apellidoCasada'],
            "nombres" => $data['nombres'],
            "sexo" => $sexo,
            "estadoCivil" => $estadoCivil,
            "fechaNacimiento" => $data['fechaNacimiento'],
            "fechaFallecimiento" => $data['fechaFallecimiento'],
            "fechaIdentFall" => $data['fechaIdentFall'],
            "fuenteInformacion" => $data['fuenteInformacion'],
            "observaciones" => $data['observaciones'],
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        dd($data, $urlApiGestora, 'token', $token, $endpoint, $request_body, $headers);
        $client = new Client();
        try {
            // $response = $client->post('https://desa-api.gestora.bo/app/dbfallecidos/gestion', [

            $response = $client->post($endpoint, [
                'headers' => $headers,
                'json' => $request_body
            ]);
            // Decodificar la respuesta JSON
            $data = json_decode($response->getBody(), true);
            $headers = json_encode($response);
            $ip = request()->ip();
            $request_body = json_encode($request_body);
            $responseBody_log = json_encode($data);


            $qry_log = "select *  from public.sp_create_service_logs('POST','$endpoint', '$request_body',  '$responseBody_log','POR VERIFICAR', '$headers', '$ip','$usuario', '$nroTramite', 'VERIFICACION_MUERTOS')";
            $data_log = \DB::select($qry_log);

            return response()->json($data);
        } catch (\Exception $e) {


            return response()->json(['error' => $e->getMessage()], 500);


        }
    }
    public function verificacionFallecido(Request $request)
    {
        $urlApiGestora = urlApiGestora();
        $token = $this->token();
        $nroTramite = $request["nroTramite"];
        $usuario = $request["usuario"];
        $client = new Client([
            'verify' => false, // Deshabilita la verificación SSL (solo si es necesario)
        ]);


        try {
            $data = \DB::select("select * from public.sp_datos_updatefallecidos_tramite('$nroTramite')");
            $data = json_decode($data[0]->sp_datos_updatefallecidos_tramite, true);
            //dd($token);
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ];

            $endpoint = $urlApiGestora . '/app/dbfallecidos/gestion';
            $request_body = [
                'tipoIdentificacion' => $request->input('tipoIdentificacion', $data['tipoIdentificiacion']),
                'nroIdentificacion' => $request->input('nroIdentificacion', $data['nroIdentificacion']),
                'fechaNacimimiento' => $request->input('fechaNacimimiento', $data['fechaNacimiento']),
            ];
            $response = $client->request('GET', $endpoint, [
                'query' => $request_body,
                'headers' => $headers,
            ]);

            $headers = json_encode($response);
            $data = json_decode($response->getBody()->getContents(), true);
            $responseBody_log = json_encode($data);

            $request_body = json_encode($request_body);
            $codigo = $data['estado'];

            $ip = request()->ip();
            $qry_log = "select *  from public.sp_create_service_logs('GET','$endpoint', '$request_body',  '$responseBody_log','$codigo', '$headers', '$ip','$usuario', '$nroTramite','VERIFICACION_FALLECIDOS_UPDATE')";
            $data_log = \DB::select($qry_log);


            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener los datos',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


}

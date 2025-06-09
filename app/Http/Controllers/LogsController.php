<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class LogsController extends Controller
{
    public function guardarLogsController(Request $request)
    {

        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => '');
        $alert = array("codigoRespuesta" => 201, "mensaje" => 'Registrado', "fecha" => '', "data" => '');
        $error = array("codigoRespuesta" => 500, "mensaje" => 'Error', "fecha" => '', "data" => 'Error');

        try {
            $url = $request->get('url');
            $response = $request->get('response');
            $output = $request->get('output');
            $config = $request->get('config');
            $tipo = $request->get('tipo');
            $usuario = $request->get('usuario');
            $cas_cod_id = $request->get('cas_cod_id');
            $ip = $request->ip();
           // $codigo = isset($response['codigo']) ? $response['codigo'] : 'undefined';
            $codigo = isset($response['codigo'])
                ? $response['codigo']
                : (isset($response['status'])
                    ? $response['status']
                    : 'undefined');

            $request_body = json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($request_body === false) {
                throw new \Exception("Error encoding output to JSON: " . json_last_error_msg());
            }
            $headers = json_encode($response['headers']);
            var_dump($headers);
            $responseBody_log = json_encode($response);

            $qry_log = "select *from public.sp_create_service_logs('POST','$url', '$request_body',
                '$responseBody_log','$codigo', '$headers', '$ip','$usuario', '$cas_cod_id','$tipo')";

            $data_log = \DB::select($qry_log);

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data_log;
            return response()->json($success);

        } catch (\Exception $e) {

            $url = $request->get('url');
            $response = $request->get('response');
            $output = $request->get('output');
            $config = $request->get('config');
            $tipo = $request->get('tipo');
            $usuario = $request->get('usuario');
            $cas_cod_id = $output['codigoSolicitud'];
            $ip = $request->ip();
            $codigo = isset($response['codigo']) ? $response['codigo'] : 'undefined';

            $error_message = $e->getMessage();
            $error_log = json_encode(['error' => $error_message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($error_log === false) {
                $error_log = '{"error": "Error encoding exception message to JSON"}';
            }

            $request_body = json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($request_body === false) {
                $request_body = '{"error": "Error encoding output to JSON"}';
            }

            $headers = isset($response['headers']) ? json_encode($response['headers'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '{}';
            if ($headers === false) {
                $headers = '{"error": "Error encoding headers to JSON"}';
            }

            $qry_log = "select *  from public.sp_create_service_logs('POST','$url', '$request_body',
            '$error_log','" . (isset($response['codigo']) ? $response['codigo'] : '500') . "', '$headers', '$ip','$usuario', '$cas_cod_id','$tipo')";
            $data_log = \DB::select($qry_log);

            $fechaFormateada = now()->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }

    }
}

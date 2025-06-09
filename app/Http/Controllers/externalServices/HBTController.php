<?php

namespace App\Http\Controllers\externalServices;

use App\Http\Controllers\Controller;
use App\Http\Services\DocumentoService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class HBTController extends Controller
{
    protected $documentService;

    public function __construct(DocumentoService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function consultaHbtCasos(Request $request)
    {
        $success = array("code" => 200, "message" => 'success');
        $error = array("message" => "Bad request", "code" => 400, "ldap_error" => '');
        $error500 = array("message" => "Error", "code" => 500);

        $casCorrelativo=$usuario=$url_request=$url_method=$ip=null;

        try {
            // Start the transaction
            // DB::beginTransaction();
            $request = $request->all();
            $casCorrelativo= $request['cas_correlativo'];
            $usuario = $request['usuario'];

            $credenciales = userLdap();

            $credenciales['login'] = explode('@', $credenciales['login'])[0];
            $result = ldapBuscarOtroUsuario($credenciales['login'], $credenciales['password'], $usuario);

            if (isset($result['success']) && $result['success'] === true) {
                $url_request = request()->fullUrl();
                $url_method = request()->method();
                $ip = request()->ip();
                $casCorrelativo= mb_strtoupper(trim($casCorrelativo));
                $usuario= mb_strtoupper(trim($usuario));
                //1) response = f(payload)
                $resultado = \DB::select("SELECT public.sp_consultar_hbt_casos_titular(:casCorrelativo) as datos_titular",
                            ["casCorrelativo"=>$casCorrelativo]);
                            // ["casCodId"=>$casCodId, "usuario"=>$usuario, "urlRequest"=>$url_request, "urlMethod"=>$url_method, "ipRequest"=>$ip]);
                $resultadoArrayTitular = json_decode(json_encode($resultado[0]->datos_titular), true);
                $datosTitular = json_decode($resultadoArrayTitular, true);
                $success['data'] = array("asegurado"=>$datosTitular);
                //2) persist the log request
                $this->documentService->persistRequestLog($ip,$url_request,$url_method,$request,$success,$usuario);
                // DB::commit();
                return response()->json($success, 200);
            }
            elseif ($result['success'] === false) {
                $error["message"] = $result['message'];
                return response()->json($error, 400);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Roll back the transaction if an error occurs
            // DB::rollBack();
            // Handles SQL-related exceptions
            $message = $e->errorInfo[2]??'Bad Request. CONTEXT:??';
            $issueArray = explode("CONTEXT:", $message);
            $message = $issueArray[0];
            $message = str_replace("\n", "", $message);
            $error["message"] = $message;
            $this->documentService->persistRequestLog($ip,$url_request,$url_method,$request,$error,$usuario);
            return response()->json($error,400);
        } catch (\Exception $e) {
            // Roll back the transaction if an error occurs
            DB::rollBack();
            $error500["message"] = $e->getMessage()??'Bad Request.';
            $this->documentService->persistRequestLog($ip,$url_request,$url_method,$request,$error500,$usuario);
            return response()->json($error500,500);
        } finally{

        }

    }

}

<?php

namespace App\Http\Controllers\externalServices;
use App\Http\Controllers\Controller;
use App\Http\Models\UtilConstant;
use App\Http\Services\DataTypeEnum;
use App\Http\Services\DocumentoService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LegalAPIController extends Controller
{
    protected $documentoService;

    public function __construct(DocumentoService $documentoService){
        $this->documentoService = $documentoService;
    }
    
    public function parametricaDeParametricas(Request $request){
        $error = array("message" => "error de instancia", "code" => 500);
        $success = array("message" => "success.", "code" => 200);
        $agrupador = $request["agrupador"];
        $codigoParametro = $request["codigoParametro"] || trim($request["codigoParametro"]) !== ''? $request["codigoParametro"]:null;

        try {
        //     $query = DB::table('public.parametrica_de_parametricas as pdp')
        //             ->select('pdp.pdp_parameter_name', 'pdp.pdp_parameter_value', 'pdp.pdp_data_type')
        //             ->where('pdp.pdp_group_name', $agrupador);

        // if ($codigoParametro!== null) {
        //     $query->where('pdp.pdp_parameter_name', $codigoParametro);
        // }
        // $data = $query->get();
        // $data = collect($data)->map(function($item) {
        //         if($item->pdp_data_type == DataTypeEnum::INTEGER->value){
        //             $item->pdp_parameter_value = (int)$item->pdp_parameter_value;
        //         }else if($item->pdp_data_type == DataTypeEnum::BOOLEAN->value){
        //             $item->pdp_parameter_value = (bool)$item->pdp_parameter_value;
        //         }else if($item->pdp_data_type == DataTypeEnum::FLOAT->value){
        //             $item->pdp_parameter_value = (float)$item->pdp_parameter_value;
        //         }else if($item->pdp_data_type == DataTypeEnum::TIMESTAMP->value){
        //             $item->pdp_parameter_value = Carbon::createFromFormat('Y-m-d H:i:s', $item->pdp_parameter_value)->format('Y-m-d H:i:s');
        //         }else if($item->pdp_data_type == DataTypeEnum::JSON->value){
        //             $item->pdp_parameter_value = json_decode($item->pdp_parameter_value);
        //         }
        //         return $item;
        //     })->toArray();
            $data = $this->documentoService->getParametricaDeParametricas($agrupador, $codigoParametro);
            $success["data"] = $data;
            return response()->json($success,200);
        }catch(ContinueException $e) {
            $success["message"] = $e->getMessage()??"This request is OK.";
            $success["code"] = $e->getCode()??200;
            return response()->json($success,$success["code"]);
        }catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            $error["code"] = $e->getCode()??500;
            return response()->json($error,$error["code"]);
        }
    }

    public function determinamosElTipoCaso(Request $request){
        $error = array("message" => "error de instancia", "code" => 500);
        $success = array("message" => "success.", "code" => 200);
        $casId = $request["casId"];
        try {
            $dataResponse = DB::select("select  rvp.prc_id, rvp.prc_data->>'prc_codigo' as prc_codigo,
                                                rva.act_id, (rva.act_data ->>'act_orden')::int as act_orden
                                        from rmx_vys_actividades rva 
                                            left join rmx_vys_procesos rvp on rva.act_prc_id = rvp.prc_id 
                                        where rva.act_id = (select rvc.cas_act_id 
                                                            from rmx_vys_casos rvc
                                                            where rvc.cas_id = :casId ) ", array("casId"=>$casId));
            if(empty($dataResponse)){
                $success["data"] = $dataResponse;
                throw new ContinueException("No record was found for the given casId.",200);
            }
            $success["data"] = $dataResponse;
            return response()->json($success,200);
        }catch(ContinueException $e) {
            $success["message"] = $e->getMessage()??"This request is OK.";
            $success["code"] = $e->getCode()??404;
            return response()->json($success,$success["code"]);
        }catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            $error["code"] = $e->getCode()??500;
            return response()->json($error,$error["code"]);
        }
    }

    public function fetchConfigDataFromAnyCodProcess(Request $request){
        $error = array("message" => "error de instancia", "code" => 500);
        $success = array("message" => "success.", "code" => 200);
        
        try {
            $codProceso = $request["codigoProceso"];
            $jsonCodProceso = json_encode(['prc_codigo' => $codProceso]);
            $sql = "select (rva.act_data->>'act_orden')::int as act_orden, rva.act_id
                    from rmx_vys_actividades rva 
                    where rva.act_prc_id = (select rvp.prc_id 
                            from rmx_vys_procesos rvp 
                            where rvp.prc_data @> :jsonCodProceso and rvp.prc_estado ='A'
                            limit 1)
                        and rva.act_estado ='A' ;";
    
            $dataResponse = DB::select($sql, array("jsonCodProceso"=>$jsonCodProceso));
            if(empty($dataResponse)){
                $success["data"] = $dataResponse;
                throw new ContinueException("No record was found for the given casId.",204);
            }
            $success["data"] = $dataResponse;
            return response()->json($success,200);
        }catch(ContinueException $e) {
            $success["message"] = $e->getMessage()??"This request is OK.";
            $success["code"] = $e->getCode()??204;
            return response()->json($success,$success["code"]);
        }catch (Exception $e) {
            $error["message"] = $e->getMessage()??"Bad request.";
            $error["code"] = $e->getCode()??500;
            return response()->json($error,$error["code"]);
        }
    }
}
<?php
namespace App\Http\Services;

use App\Http\Models\UtilConstant;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\DB;

class DocumentoService
{
    public function getDocumento($documento64, $id_caso, $caso = null, $extensionDocumento = '.pdf')
    {
        $archivoTemporal = null;
        try {
            $anio = null;
            if($caso === null){
                $dataCaso = \DB::select("select  rvc.cas_data->>'cas_cod_id' as cas_cod_id
                                    from rmx_vys_casos rvc 
                                    where rvc.cas_id = :casId", array('casId'=>$id_caso));
                if (!isset($dataCaso[0])) {
                    throw new Exception("No existe al caso '$id_caso'");
                }
                $codigoCaso = $dataCaso[0]->cas_cod_id;
                $arrayCodigoCaso = explode('/', $codigoCaso);
                $anio = $arrayCodigoCaso[2];
            } else {
                $arraycontent = explode('/', $caso);
                $anio = trim($arraycontent[2]);
            }

            $base64data = base64_decode($documento64, true);
            if ($base64data !== false) {
                $archivoTemporal = tmpfile();
                // $anio = date('Y');
                /// aaui caso
                // $arraycontent = explode('/', $caso);
                $strDirectorioFisico = "tramitesip/" . $anio . "/" . $id_caso . "/";
                $uuid = Str::uuid();
                $fechaHoraActual = new \DateTime();
                $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
                // $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
                $nombreArch = $uuid . '_' . $fechaHoraFormato . $extensionDocumento;
                if ($archivoTemporal !== false) {
                    fwrite($archivoTemporal, $base64data);
                    $rutaArchivoTemporal = stream_get_meta_data($archivoTemporal)['uri'];
                    $directorioDestino = '/opt/documental/' . $strDirectorioFisico;
                    if (!file_exists($directorioDestino)) {
                        File::makeDirectory($directorioDestino, 0777, true, true);
                    }
                    if (rename($rutaArchivoTemporal, $directorioDestino . $nombreArch)) {
                        $rutaArchivo = $directorioDestino . $nombreArch;
                        if (File::exists($rutaArchivo)) {
                            return $directorioDestino . $nombreArch;
                        } else {
                            // return "El archivo no existe.";
                            throw new Exception("El archivo no existe.");
                        }
    
                    } else {
                        // return "Hubo un error al mover el archivo.";
                        throw new Exception("Hubo un error al mover el archivo.");
                    }
                    fclose($archivoTemporal);
                } else {
                    // return "Hubo un error al crear el archivo temporal.";
                    throw new Exception("Hubo un error al crear el archivo temporal.");
                }
            } else {
                // return "Hubo un error al decodificar la cadena base64.";
                throw new Exception("Hubo un error al decodificar la cadena base64.");
            }
        } catch (Exception $e) {
            throw $e;
        } finally {
            if ($archivoTemporal) {
                fclose($archivoTemporal);
            }
        }
    }

    public function persistRequestLog($ip, $url_request, $url_method,$payload, $response, $usuario){
        //2) persist the log request 
        DB::table('public.logs_request_tramites_sip')->insert([
            'lrts_ip'=>$ip,
            'lrts_url_request'=>$url_request,
            'lrts_method_request'=>$url_method,
            'lrts_payload' => json_encode($payload),
            'lrts_response' => json_encode($response),
            'lrts_usuario_creacion' => $usuario
        ]);
    }

    public function findArrayCasDataValores($arrayCasDataValores, $frm_campo_find, $frm_campo_value_find,$frm_value_return = 'frm_value'){
        $found = collect($arrayCasDataValores)
                ->firstWhere($frm_campo_find,$frm_campo_value_find);
        // Check if the item exists and the 'frm_value' key is set
        // if (!isset($found[$frm_value])) {
        //     throw new Exception("fiel $.");
        // }
        return isset($found[$frm_value_return])? $found[$frm_value_return]:UtilConstant::NOT_FOUND;
    }
    public function findArrayCasData($casData, $frm_campo_find){
        $found = $casData[$frm_campo_find];
        return isset($found)? $found:UtilConstant::NOT_FOUND;
    }

    public function getFileBase64($fullPath)
    {
        // Check if the file exists and is readable
        if(!file_exists($fullPath)){
            throw new Exception("File not found...");//404
        }

        if (!is_readable($fullPath)) {
            // return response()->json(['error' => 'File is not readable'], 403);
            throw new Exception("File is not readable...");
        }
        // Read the file contents
        $fileContents = file_get_contents($fullPath);

        // Convert to base64 and return
        return base64_encode($fileContents);
    }

    public function getParametricaDeParametricas($agrupador, $codigoParametro = null){
        $query = DB::table('public.parametrica_de_parametricas as pdp')
                ->select('pdp.pdp_parameter_name', 'pdp.pdp_parameter_value', 'pdp.pdp_data_type')
                ->where('pdp.pdp_group_name', $agrupador);

        if ($codigoParametro !== null) {
            $query->where('pdp.pdp_parameter_name', $codigoParametro);
        }
        $data = $query->get();
        return collect($data)->map(function($item) {
                if($item->pdp_data_type == DataTypeEnum::INTEGER->value){
                    $item->pdp_parameter_value = (int)$item->pdp_parameter_value;
                }else if($item->pdp_data_type == DataTypeEnum::BOOLEAN->value){
                    $item->pdp_parameter_value = (bool)$item->pdp_parameter_value;
                }else if($item->pdp_data_type == DataTypeEnum::FLOAT->value){
                    $item->pdp_parameter_value = (float)$item->pdp_parameter_value;
                }else if($item->pdp_data_type == DataTypeEnum::TIMESTAMP->value){
                    $item->pdp_parameter_value = Carbon::createFromFormat('Y-m-d H:i:s', $item->pdp_parameter_value)->format('Y-m-d H:i:s');
                }else if($item->pdp_data_type == DataTypeEnum::JSON->value){
                    $item->pdp_parameter_value = json_decode($item->pdp_parameter_value);
                }
                return $item;
            })->toArray();
    }

}
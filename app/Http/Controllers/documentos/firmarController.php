<?php

namespace App\Http\Controllers\documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Http\Services\DocumentoService;
use App\Http\Controllers\documentos\documentosNfsController;
use Illuminate\Support\Facades\Http;
// use App\Http\Services\DocumentoService;

class firmarController extends Controller
{
    protected DocumentoService $documentService;

    public function __construct(DocumentoService $documentService)
    {
        $this->documentService = $documentService;
    }


    public function obtenerFirma(Request $request)
    {
        $cas_id = $request['cas_id'];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select  doc_url from  public._gp_documentos where doc_descripcion = 'Firma del Solicitante' and doc_cas_id = $cas_id");
            if ($data != []) {
                $arraycontent = explode('/', $data[0]->doc_url);
                $serv = '/opt/documental/';
                $nomfile = $arraycontent[6];
                if (strstr($serv, "/opt/documental/")) {
                    $rutaCompleta = $data[0]->doc_url;
                    if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                        $fileContent = file_get_contents($rutaCompleta);
                        $base64Content = base64_encode($fileContent);
                        return response()->json(["data" => $base64Content, "success" => $success]);
                    } else {
                        echo "<b>No Existe Documento!!!</b>";
                        exit(1);
                    }
                } else {
                    if (file_exists("../../$serv" . "$nomfile")) {
                        header("LOCATION: ../../$serv" . "$nomfile");
                    } else {
                        echo "<b>No Existe Documento!!!</b>";
                    }
                    exit(1);
                }
            } else {
                $success = array("code" => 201, "mensaje" => 'Los datos ingresados no existen en la base de datos.', );
                return response()->json(["data" => '', "codigoRespuesta" => $success]);
            }
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function vista($id)
    {
        return view('firma.firma', ['id' => $id]);
    }
    public function guardarFirmapng(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $png = $request['png64'];
        $cas_id = $request['cas_id'];
        $resGetDocumento = $this->documentService->getDocumento($png, $cas_id,null,'.png');
        $dataHistorico = \DB::select(
            "SELECT * from  public.rmx_vys_casos inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id where  cas_id =  $cas_id    order by htc_cas_id desc limit 1"
        );
        //   dd($cas_id, 1, $dataHistorico[0]->htc_id, $dataHistorico[0]->cas_cod_id, 'referencia', 'categoria', $fichero_subido, 9, 1, 'Firma del SOlicitante', '99', '', '', '', '');
        $respuesta = $this->guardarDocumentoPdf($cas_id, 1, $dataHistorico[0]->htc_id, $dataHistorico[0]->cas_cod_id, 'referencia', 'categoria', $resGetDocumento, 9, 1, 'Firma del Solicitante', '99', '', '', '', '');
        return response()->json(['data' => '{}', 'success' => $success]);
    }
    
    public function pdfGuardadoFirmadoSolicitante(Request $request)
    {

        $nombre_formulario = $request['nombre_formulario'];
        $cas_cod_id = $request['cas_cod_id'];
        $pdfAsBase64 = $request['pdf'];
        $id_caso = $request['id_caso'];
        $bandera = $request['bandera'];
        $dataHistorico = \DB::select(
            "SELECT * from  public.rmx_vys_casos  inner join public.rmx_vys_historico_casos on cas_id = htc_cas_id  where  cas_id =  $id_caso    order by htc_cas_id desc limit 1"
        );
        $success = array('code' => 200, 'mensaje' => 'OK');
        if ($pdfAsBase64 == '') {
            //  $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $caso, $parentesco, $ci, '', $valor_id, 1, $valor_descripcion, 'pendiente', $documentoOriginalObligatorio, $presentacionObligatoria, $switch, '');
        } else {
            $base64data = base64_decode($pdfAsBase64, true);
            $fechactual = date("Y-m-d H:i:s");
            $anio = date('Y');
            $strExtensionArch = ".pdf";
            $strDirectorioFisico = "archivos_pdf/" . $anio . "/" . $id_caso . "/";
            if (!file_exists($strDirectorioFisico)) {
                @mkdir($strDirectorioFisico, 0777, true);
            }
            $strNombreArch = date('dmY_His') . $strExtensionArch;
            $fichero_subido = $strDirectorioFisico . $bandera . '_' . $strNombreArch;
            if (file_put_contents($fichero_subido, $base64data)) {
                // Return the number of bytes saved, or false on failure
            }
            $respuesta = $this->guardarDocumentoPdf($id_caso, 1, $dataHistorico[0]->htc_id, $cas_cod_id, 'referencia', 'categoria', $fichero_subido, 9, 1, $nombre_formulario, '99', '', '', '', '');

        }
        return response()->json(['data' => '{}', 'success' => $success]);

    }

    public function guardarDocumentoPdf($pdoc_cas_id, $pdoc_usr_id, $pdoc_his_id, $pdoc_codigo, $pdoc_referencia, $pdoc_categoria, $pdoc_url, $pdoc_doc_id, $pdoc_usuario, $pdoc_descripcion, $pdoc_estado_preparacion, $documentoOriginalObligatorio, $presentacionObligatoria, $switch, $id_persona_sip)
    {
        $switch1 = '';
        if ($switch) {
            $switch1 = 'true';
        } else {
            $switch1 = 'false';
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT * FROM  public.crud_documento (0,$pdoc_cas_id,$pdoc_usr_id,$pdoc_his_id,'$pdoc_codigo','$pdoc_referencia','$pdoc_categoria','$pdoc_url',$pdoc_doc_id,$pdoc_usuario,'$pdoc_descripcion','$pdoc_estado_preparacion','$documentoOriginalObligatorio', '$presentacionObligatoria','$switch1', '$id_persona_sip',1)  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function firmaMasivaBack(Request $request)
    {
        $cas_id = $request['cas_id'];
        $cas_id_text = explode(",", $cas_id);

        $documentService = new DocumentoService();
        $newPrevisualizacion = new documentosNfsController($documentService);

        $dataLoteFirma = [
            'slot' => 1,
            'pin' => 'Santi:P123',
            'alias' => '258798408589',
            'pdfs' => []
        ];
        foreach ($cas_id_text as $valor) {
            $respuesta = $this->listarContratosCasos($valor);
            foreach ($respuesta as $valor2) {
                $data_json = json_encode($valor2);
                $data_json = json_decode($data_json);

                $doc_id = $data_json->doc_id;
                $response2 = $newPrevisualizacion->obtenerDocumentoPdf64($doc_id);
                //$b64 = trim($response2);
                $cleanBase64 = preg_replace('/\s+/', '', $response2);
                $b64 = trim($cleanBase64);
                $dataLoteFirma['pdfs'][] = [
                    'id' => $data_json->doc_id . '^' . $data_json->doc_cas_id . '^' . $data_json->doc_doc_id . '^' . $data_json->imp_nombre . '^' . $data_json->doc_categoria . '^' . $data_json->doc_referencia,
                    'pdf' => $b64
                ];
            }
//return $dataLoteFirma;
            // Convertir $dataLoteFirma a JSON
            // Procesar la respuesta
            // if ($response->successful()) {
            //     return response()->json($response->json());
            // } else {
            //     \Log::error('Error en la solicitud a la API: ' . $response->status());
            //     return response()->json(['error' => 'Error al firmar los PDFs'], $response->status());
            // }
        }
        
        $jsonData = json_encode($dataLoteFirma);
        $url = "https://localhost:9000/api/token/firmar_lote_pdfs";
        $response = Http::withoutVerifying()
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withBody($jsonData, 'application/json')
            ->post($url);

            return $response;
    }

    public function listarContratosCasos($cas_id)
    {
        $cas_id = $cas_id;
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $sqlQuery = "select gd.doc_url as url_documento
                                , gd.doc_descripcion as imp_nombre
                                , 'Firma Contrato' as imp_tipo_firma
                                , doc_codigo
                                , doc_referencia
                                , doc_categoria
                                , doc_doc_id
                                , doc_detalle_documento
                                , doc_cas_id,
                                 doc_id
                                from _gp_documentos gd
                                where (gd.doc_descripcion ilike 'CONTRATO%'
                                OR gd.doc_descripcion like 'CNU%')
                                and gd.doc_referencia != 'ADJUNTOS'
                                and gd.doc_cas_id in ($cas_id)";
            $data = \DB::select($sqlQuery);
            return $data;
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    private function isBase64($string)
    {
        $maxBase64Length = 10485760; // 10MB en base64

        if (strlen($string) > $maxBase64Length) {
            \Log::error('La cadena es demasiado grande para ser validada como base64', ['length' => strlen($string)]);
            return true; // Manteniendo el comportamiento del JavaScript
        }

        \Log::info('La cadena es base64', ['string' => substr($string, 0, 100)]);
        $base64Regex = '/^(?:[A-Za-z0-9+\/]{4})*(?:[A-Za-z0-9+\/]{2}==|[A-Za-z0-9+\/]{3}=)?$/';
        return preg_match($base64Regex, $string) && (strlen($string) % 4 === 0);
    }
}

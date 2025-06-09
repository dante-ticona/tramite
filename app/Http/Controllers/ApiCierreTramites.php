<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use TCPDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ApiCierreTramites extends Controller {

    public function tramitesCierre(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('idAgencia'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('idAgencia'));
        try {

            $idAgencia = $request->input('idAgencia');
            $idUsuario = $request->input('usuario');

            $data = DB::table('rmx_vys_casos as rvc')
            ->join('gp_agencia as ga', function ($join) use ($idAgencia) {
                $join->on(DB::raw("rvc.cas_data->>'cas_agencia'"), '=', 'ga.descripcion_agencia')
                ->where('ga.id_sip_agencia', '=', $idAgencia);
            })
            ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
            ->join('users as u', 'rvc.cas_usr_id','=','u.id')
            ->where('rvc.cas_padre_id', '=', 0)
            ->whereNotIn('rvc.cas_estado', ['H', 'X'])
            ->whereRaw("to_char(cas_registrado, 'yyyy-MM-dd') = ?", [\Carbon\Carbon::today()->format('Y-m-d')])
            ->whereIn(DB::raw("rva.act_data->>'act_orden'"), ['20', '30'])
            ->where('rvc.cas_data_valores', '!=', '[]')
            ->select(
                DB::raw("rvc.cas_data->>'USUARIO_REGISTRO' as usuario_registro"),
                DB::raw("rva.act_data->>'act_orden' as actividad_orden"),
                DB::raw("rva.act_data->>'act_descripcion' as actividad_descripcion"),
                DB::raw("u.nom_usuario as usuario_actual"),
                DB::raw("concat(coalesce(rvc.cas_data->>'NOMBRE_PROCESO', '')) as tipo_tramite"),
                'rvc.cas_cod_id as nro_solicitud',
                DB::raw("rvc.cas_correlativo as nro_correlativo"),
                DB::raw("rvc.cas_data->>'AS_CUA' as cua"),
                DB::raw("concat(COALESCE(rvc.cas_data->>'AS_PRIMER_NOMBRE', ''), ' ', COALESCE(rvc.cas_data->>'AS_SEGUNDO_NOMBRE', ''), ' ', COALESCE(rvc.cas_data->>'AS_PRIMER_APELLIDO', ''), ' ', COALESCE(rvc.cas_data->>'AS_SEGUNDO_APELLIDO', '')) as nombre_asegurado"),
                DB::raw("rvc.cas_data->>'ESTADO_DERIVACION' as estado_actual")
            )
            ->orderBy('usuario_registro')
            ->orderBy('tipo_tramite')
            ->get();

            if(!$data->isEmpty()) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['codigoRespuesta'] = 401;
                $error['mensaje'] = 'Existen tramites en estado INICIADO';
                $error['data'] = $data;
                return response()->json([$error]);
            } else {
                $data = DB::table('rmx_vys_casos as rvc')
                ->join('gp_agencia as ga', function ($join) use ($idAgencia) {
                    $join->on(DB::raw("rvc.cas_data->>'cas_agencia'"), '=', 'ga.descripcion_agencia')
                    ->where('ga.id_sip_agencia', '=', $idAgencia);
                })
                ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
                ->where('rvc.cas_padre_id', '=', 0)
                ->whereNotIn('rvc.cas_estado', ['H', 'X'])
                ->whereRaw("to_char(cas_registrado, 'yyyy-MM-dd') = ?", [\Carbon\Carbon::today()->format('Y-m-d')])
                ->whereNotIn(DB::raw("rva.act_data->>'act_orden'"), ['20', '30'])
                ->where('rvc.cas_data_valores', '!=', '[]')
                ->select(
                    DB::raw("concat(coalesce(rvc.cas_data->>'NOMBRE_PROCESO', '')) as tipo_tramite"),
                    DB::raw("rvc.cas_data->>'AS_CUA' as cua"),
                    DB::raw("concat(COALESCE(rvc.cas_data->>'AS_PRIMER_NOMBRE', ''), ' ', COALESCE(rvc.cas_data->>'AS_SEGUNDO_NOMBRE', ''), ' ', COALESCE(rvc.cas_data->>'AS_PRIMER_APELLIDO', ''), ' ', COALESCE(rvc.cas_data->>'AS_SEGUNDO_APELLIDO', '')) as nombre_asegurado"),
                    'rvc.cas_cod_id as nro_solicitud',
                    DB::raw("rvc.cas_correlativo as nro_correlativo"),
                    DB::raw("rvc.cas_data->>'USUARIO_REGISTRO' as usuario_registro"),
                    DB::raw("rvc.cas_data->>'ESTADO_DERIVACION' as estado_derivacion"),
                    DB::raw("rvc.cas_data->>'DESCRIPCION_DERIVACION' as descripcion_derivacion")
                )
                ->orderBy('tipo_tramite')
                ->orderBy('cua')
                ->orderBy('usuario_registro')
                ->get();

                $region = '';
                switch($request->input('departamento')) {
                    case 'BENI': $region = 'Oriente'; break;
                    case 'PANDO': $region = 'Oriente'; break;
                    case 'SANTA CRUZ': $region = 'Oriente'; break;
                    case 'CHUQUISACA': $region = 'Valles'; break;
                    case 'COCHABAMBA': $region = 'Valles'; break;
                    case 'TARIJA': $region = 'Valles'; break;
                    case 'LA PAZ': $region = 'Occidente'; break;
                    case 'ORURO': $region = 'Occidente'; break;
                    case 'POTOSI': $region = 'Occidente'; break;
                }

                $dataGerente = DB::table('gp_gerente_regional')
                ->where('region', '=', $region)
                ->select('nombre')
                ->get();

                $dataJefe = DB::table('users')
                ->where('id_agencia', '=', $idAgencia)
                ->where(function ($query) {
                    $query->where('es_jefe', '=', true)
                          ->orWhere('es_supervisor', '=', true);
                })
                ->where('id', '=' , $idUsuario)
                ->select('name')
                ->get();
                $nombreJefe = $dataJefe->first()->name;
                $jefe = ucwords(strtolower($nombreJefe));

                $reportePdf = $this->gernerarReporteCierre($dataGerente->first()->nombre, $request->input('regional'), $request->input('agencia'), $data, $jefe);

                $rutaDocumento = $this->guardarDocumento($reportePdf, $request->input('agencia'));

                $idTramiteCierre = DB::table('public.tramites_cierre')
                    ->insertGetId([
                        'gerente_regional' => $dataGerente->first()->nombre,
                        'regional' => $request->input('regional'),
                        'jefe_agencia' => $jefe,
                        'agencia' => $request->input('agencia'),
                        'fecha_cierre' => now(),
                        'documento' => $rutaDocumento,
                        'estado' => 'CERRADO',
                        'use_cre' => $idUsuario,
                        'fec_cre' => now()
                    ]);

                foreach($data as $tramite) {
                    $idInsertado = DB::table('public.tramites_cierre_detalle')->insertGetId([
                        'tramites_cierre_id' => $idTramiteCierre,
                        'tipo_tramite' => $tramite->tipo_tramite,
                        'cua_asegurado' => $tramite->cua,
                        'nombre_asegurado' => $tramite->nombre_asegurado,
                        'nro_solicitud' => $tramite->nro_solicitud,
                        'usuario_registro' => $tramite->usuario_registro
                    ]);
                }

                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['data'] = $reportePdf;
                return response()->json([$success]);
            }
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function gerenteRegional(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('region'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('region'));
        try {
            $region = $request->input('region');
            $data = DB::table('gp_gerente_regional ggr')
            ->where('ggr.region', '=', $region)
            ->select('ggr.nombre')
            ->get();

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }

    }

    public function gernerarReporteCierre($gerente, $regional, $agencia, $data, $jefe) {

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LETTER', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Gestora');
        $pdf->SetTitle('Cierre de Tramites Diario');
        $pdf->SetSubject('Reporte de Cierre');
        $pdf->SetKeywords('TCPDF, PDF, Cierre, Tramites');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->SetAlpha(0.5);
        $pageWidth = $pdf->GetPageWidth();
        $imageWidth = 60;
        $margin = ($pageWidth - $imageWidth) / 2;
        $pdf->Image('img/logo_gestora.jpg', $margin, 10, $imageWidth, 25);
        $pdf->Ln(28);

        $pdf->SetAlpha(0.1);
        $pageWidth = $pdf->GetPageWidth();
        $pageHeight = $pdf->GetPageHeight();
        $imageWidth = 100;
        $imageHeight = 100;
        $x = ($pageWidth - $imageWidth) / 2;
        $y = ($pageHeight - $imageHeight) / 2;
        $pdf->Image('img/logo-gestora.jpg', $x, $y, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0);
        $pdf->SetAlpha(1);

        $pdf->SetFont('helvetica', 'B', 11);

        // Encabezado del reporte
        $pdf->Cell(0, 3, 'NOTA INTERNA', 0, 1, 'C');
        $fechaHoraActual = new \DateTime();
        $gestionActual = $fechaHoraActual->format('Y');
        $maxId = DB::table('tramites_cierre')
        ->max('id');
        $pdf->Cell(0, 3, 'GROCC/'.($maxId+1).'/'.$gestionActual, 0, 1, 'C');
        $pdf->Cell(0, 3, 'REGIONAL '.$regional, 0, 1, 'C');
        $pdf->Cell(0, 3, 'AGENCIA '.$agencia, 0, 1, 'C');
        $pdf->Ln(5);

        $widthColumn1 = 50;
        $widthColumn2 = 100;
        $cellHeight = 5;

        $tableWidth = $widthColumn1 + $widthColumn2;
        $margin = (($pdf->GetPageWidth() - $tableWidth) / 2) + 10;
        $pdf->SetX($margin);
        // Fila 1
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, 'A:', 0, 'R', false, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, $gerente, 0, 'L', false, 1);
        // Fila 2
        $pdf->SetX($margin);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, '', 0, 'R', false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, 'GERENTE REGIONAL', 0, 'L', false, 1);
        // Fila 3
        $pdf->SetX($margin);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, 'De:', 0, 'R', false, 0);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, $jefe, 0, 'L', false, 1);
        // Fila 4
        $pdf->SetX($margin);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, '', 0, 'R', false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, 'JEFE DE AGENCIA', 0, 'L', false, 1);
        // Fila 5
        $pdf->SetX($margin);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, 'FECHA:', 0, 'R', false, 0);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fechaActual->format('d/m/Y');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, $fechaFormateada, 0, 'L', false, 1);
        // Fila 6
        $pdf->SetX($margin);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn1, $cellHeight, 'REFERENCIA:', 0, 'R', false, 0);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->MultiCell($widthColumn2, $cellHeight, 'CIERRE DE TRAMITES DIARIO', 0, 'L', false, 1);

        // Coordenadas para la línea horizontal
        $startX = $pdf->GetX();
        $startY = $pdf->GetY() + 5;
        $endX = $pdf->GetPageWidth() - $pdf->GetX();
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Line($startX, $startY, $endX, $startY);
        $pdf->SetY($startY + 2);

        $pdf->Ln(3); // Salto de línea
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 10, 'Estimado(a) '.$gerente, 0, 1, 'L');
        $texto = 'Envío las solicitudes recibidas en fecha ' . $fechaFormateada .
                ' por Gerencia Regional ' . $regional .
                ' para su Agencia ' . $agencia .
                ', bajo el siguiente detalle:';
        $width = 0;
        $height = 5;
        $pdf->MultiCell($width, $height, $texto, 0, 'L', false, 1);
        $pdf->Ln(6);

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetLineWidth(0.2);
        $pdf->Cell(5, 7, 'N°', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Tramite', 1, 0, 'C');
        $pdf->Cell(20, 7, 'CUA', 1, 0, 'C');
        $pdf->Cell(50, 7, 'Nombre Asegurado', 1, 0, 'C');
        $pdf->Cell(25, 7, 'N° Solicitud', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Usuario Registro', 1, 0, 'C');
        $pdf->Cell(30, 7, 'Observación', 1, 1, 'C');
        $contador = 1;
        $pdf->SetFont('helvetica', '', 7);
        /*for ($i = 0; $i < 10 - 1; $i++) {
            $data = $data->concat($data);
        }*/
        foreach ($data as $tramite) {
            // Verificar si se necesita una nueva página
            if ($pdf->GetY() + 8 > $pdf->getPageHeight() - $pdf->getBreakMargin()) {

                $pdf->SetFont('helvetica', 'I', 8);
                $pdf->Cell(0, 0, 'Página ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'R');

                $pdf->AddPage(); // Añadir una nueva página
                $pdf->SetAlpha(0.1);
                $pageWidth = $pdf->GetPageWidth();
                $pageHeight = $pdf->GetPageHeight();
                $imageWidth = 100;
                $imageHeight = 100;
                $x = ($pageWidth - $imageWidth) / 2;
                $y = ($pageHeight - $imageHeight) / 2;
                $pdf->Image('img/logo-gestora.jpg', $x, $y, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0);
                $pdf->SetAlpha(1);
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetLineWidth(0.2);
                $pdf->Cell(5, 7, 'N°', 1, 0, 'C');//5
                $pdf->Cell(30, 7, 'Tramite', 1, 0, 'C');//10
                $pdf->Cell(20, 7, 'CUA', 1, 0, 'C');//5
                $pdf->Cell(50, 7, 'Nombre Asegurado', 1, 0, 'C');//10
                $pdf->Cell(25, 7, 'N° Solicitud', 1, 0, 'C');//
                $pdf->Cell(30, 7, 'Usuario Registro', 1, 0, 'C');
                $pdf->Cell(30, 7, 'Observación', 1, 1, 'C');
                $pdf->SetFont('helvetica', '', 7);
            }
            /* $pdf->Cell(10, 7, $contador++, 1, 0, 'C');
            $pdf->Cell(40, 7, $tramite->tipo_tramite, 1, 0, 'L');
            $pdf->Cell(25, 7, $tramite->cua, 1, 0, 'L');
            $pdf->Cell(60, 7, $tramite->nombre_asegurado, 1, 0, 'L');
            $pdf->Cell(25, 7, $tramite->nro_solicitud, 1, 0, 'L');
            $pdf->Cell(30, 7, $tramite->usuario_registro, 1, 1, 'L'); */
            $pdf->MultiCell(5, 7, $contador++, 1, 'C', false, 0, '', '', true, 0, false, true, 7, 'M');
            $pdf->MultiCell(30, 7, $tramite->tipo_tramite, 1, 'L', false, 0, '', '', true, 0, false, true, 7, 'M');
            $pdf->MultiCell(20, 7, $tramite->cua, 1, 'C', false, 0, '', '', true, 0, false, true, 7, 'M');
            $pdf->MultiCell(50, 7, $tramite->nombre_asegurado, 1, 'L', false, 0, '', '', true, 0, false, true, 7, 'M');
            /* $pdf->MultiCell(25, 7, $tramite->nro_solicitud, 1, 'L', false, 0, '', '', true, 0, false, true, 7, 'M'); */
            $pdf->MultiCell(25, 7, $tramite->nro_correlativo, 1, 'C', false, 0, '', '', true, 0, false, true, 7, 'M');
            if($tramite->estado_derivacion == 'OBSERVADO - AVANCE'){
                $pdf->MultiCell(30, 7, $tramite->usuario_registro, 1, 'L', false, 0, '', '', true, 0, false, true, 7, 'M');
                $pdf->MultiCell(30, 7, $tramite->descripcion_derivacion, 1, 'L', false, 1, '', '', true, 0, false, true, 7, 'M');
            } else {
                $pdf->MultiCell(30, 7, $tramite->usuario_registro, 1, 'L', false, 1, '', '', true, 0, false, true, 7, 'M');
            }
        }

        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 10, 'Sin otro particular, recibe un cordial saludo.', 0, 1, 'L');

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 3, $jefe, 0, 1, 'L');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 3, 'JEFE DE AGENCIA', 0, 1, 'L');

        $footerPosY = $pdf->getPageHeight() - 30;
        $pdf->SetY($footerPosY);
        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->Cell(0, 0, 'Página ' . $pdf->getAliasNumPage() . '/' . $pdf->getAliasNbPages(), 0, false, 'R');

        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        return response()->json($base64Content);
    }

    public function guardarDocumento($documento64, $agencia) {
       $jsonContent = $documento64->getContent();
       $data = json_decode($jsonContent, true);
       $base64data = base64_decode($data);
        if ($base64data !== false) {
            $archivoTemporal = tmpfile();
            $anio = date('Y');
            $strDirectorioFisico = "tramitesip/cierres/" . $agencia . "/";
            $uuid = Str::uuid();
            $fechaHoraActual = new \DateTime();
            $fechaHoraFormato = $fechaHoraActual->format('d_m_Y_H_i_s');
            $nombreArch = $uuid . '_' . $fechaHoraFormato . '.pdf';
            if ($archivoTemporal !== false) {
                fwrite($archivoTemporal, $base64data);
                $rutaArchivoTemporal = stream_get_meta_data($archivoTemporal)['uri'];
                $directorioDestino = '/opt/documental/' . $strDirectorioFisico;
                if (!file_exists($directorioDestino)) {
                    File::makeDirectory($directorioDestino, 0777, true, true);
                }
                if (rename($rutaArchivoTemporal, $directorioDestino . $nombreArch)) {
                    return $directorioDestino . $nombreArch;
                } else {
                    echo "Hubo un error al mover el archivo.";
                }
                fclose($archivoTemporal);
            } else {
                echo "Hubo un error al crear el archivo temporal.";
            }
        } else {
            echo "Hubo un error al decodificar la cadena base64.";
        }
    }

    public function obtenerDocumentoCierre(Request $request) {
        $regional = $request['regional'];
        $agencia = $request['agencia'];
        $fechaCierre = $request['fechaCierre'];
        $data = DB::table('tramites_cierre')
                ->where('regional', '=', $regional)
                ->where('agencia', '=', $agencia)
                ->where('estado', '=', 'CERRADO')
                ->whereRaw("to_char(fecha_cierre, 'yyyy-MM-dd') = ?", [$fechaCierre])
                ->select('documento')
                ->get();

        if($data->isEmpty()) {
            return 401;
        }

        $arraycontent = explode('/', $data->first()->documento);
        $serv = '/opt/documental/';
        $nomfile = $arraycontent[6];
        if (strstr($serv, "/opt/documental/")) {
            $rutaCompleta = $data->first()->documento;
            if (file_exists($rutaCompleta) && is_file($rutaCompleta)) {
                $fileContent = file_get_contents($rutaCompleta);
                $base64Content = base64_encode($fileContent);
                return $base64Content;
            } else {
                //echo "<b>No Existe Documento!!!</b>";
                //exit(1);
                return 402;
            }
        } else {
            if (file_exists("../../$serv" . "$nomfile")) {
                header("LOCATION: ../../$serv" . "$nomfile");
            } else {
                //echo "<b>No Existe Documento!!!</b>";
                return 402;
            }
            exit(1);
        }
    }

    public function anularCierre(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('idAgencia'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('idAgencia'));
        try {
            $regional = $request['regional'];
            $agencia = $request['agencia'];
            $fechaCierre = $request['fechaCierre'];

            $ids = DB::table('tramites_cierre')
            ->where('regional', '=', $regional)
            ->where('agencia', '=', $agencia)
            ->whereRaw("to_char(fecha_cierre, 'yyyy-MM-dd') = ?", [$fechaCierre])
            ->where('estado', '=', 'CERRADO')
            ->update([
                'estado' => 'ANULADO',
                'usu_mod' => $request->input('usuario'),
                'fec_mod' => now()
            ]);

            if($ids === 0) {
                $error['codigoRespuesta'] = 401;
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['data'] = 'No se encontro ningun Cierre para ANULAR';
                return response()->json([$error]);
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = 'Se realizo con el Exito la Anulacion del Cierre '.$fechaCierre;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function verificaCierre(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('idAgencia'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('idAgencia'));
        try {
            $regional = $request['regional'];
            $agencia = $request['agencia'];
            $fechaCierre = $request['fechaCierre'];

            $data = DB::table('tramites_cierre')
                ->where('regional', '=', $regional)
                ->where('agencia', '=', $agencia)
                ->where('estado', '=', 'CERRADO')
                ->whereRaw("to_char(fecha_cierre, 'yyyy-MM-dd') = ?", [$fechaCierre])
                ->select('id')
                ->get();

            if($data->isEmpty()) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['data'] = 0;
                return response()->json([$success]);
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data->first()->id;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['mensaje'] = $e->getMessage();
            $error['data'] = 0;
            return response()->json([$error]);
        }
    }

    public function jefeAgencia(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('idAgencia'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('idAgencia'));
        try {
            $idAgencia = $request['idAgencia'];

            $data = DB::table('users')
                ->where('id_agencia', '=', $idAgencia)
                ->where('es_jefe', '=', true)
                ->select('name')
                ->get();

            if($data->isEmpty()) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['data'] = 'No existe Jefe de Agencia';
                return response()->json([$error]);
            }

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $data->first()->name;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

    public function tramitesCurso(Request $request) {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $request->input('cuaAsegurado'));
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $request->input('cuaAsegurado'));
        try {
            $cuaAsegurado = $request['cuaAsegurado'];
            $tipoTramite = $request['tipoTramite'];

            $count = DB::table('rmx_vys_casos')
            ->where('cas_data->AS_CUA', '=', $cuaAsegurado)
            ->where('cas_nodo_id', '!=', 26)
            ->whereNotIn('cas_estado', ['X', 'H'])
            ->count();

            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $success['fecha'] = $fechaFormateada;
            $success['data'] = $count;
            return response()->json([$success]);
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json([$error]);
        }
    }

}

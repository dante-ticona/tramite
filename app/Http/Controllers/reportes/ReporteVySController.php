<?php

namespace App\Http\Controllers\reportes;

require '../vendor/autoload.php';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteVySController extends Controller
{
    public $url_archivo;
    var $data;
    var $rec = 0;
    //MEJORAR EL FITRO D EBUSQUEDA
    public function buscarCasosReporte(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];

        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] === null ? '' : $request["fecha_ini"];
        $fecha_fin = $request["fecha_fin"] === null ? '' : $request["fecha_fin"];
        $id_departamento = $request["id_departamento"] === null ? 0 : $request["id_departamento"];
        $id_regional = $request["id_regional"] === null ? 0 : $request["id_regional"];
        $id_agencia = $request["id_agencia"] === null ? 0 : $request["id_agencia"];
        $id_area = $request["id_area"] === null ? 0 : $request["id_area"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $sip_dep = '';
        if ($id_departamento == 0) {
            $sip_dep = "";
        } else {
            $sip_dep = '"id_cas_departamento":' . $id_departamento;
        }

        $sip_reg = '';
        if ($id_regional == 0) {
            $sip_reg = "";
        } else {
            $sip_reg = ',"id_cas_regional":' . $id_regional;
        }

        $sip_age = '';
        if ($id_agencia == 0) {
            $sip_age = "";
        } else {
            $sip_age = ',"id_cas_agencia":' . $id_agencia;
        }

        $sip_nodo = '';
        if ($id_area == 0) {
            $sip_nodo = "";
        } else {
            $sip_nodo = 'and c.cas_nodo_id = ' . $id_area;
        }

        try {
            $sql = "SELECT c.cas_id,
                    nnn.nodo_codigo,
                    nnn.nodo_descripcion,
                    p.prc_data ->>'prc_descripcion' as prc_descripcion,
                    a.act_data->>'act_orden' as act_orden,
                    a.act_data->>'act_descripcion' as act_descripcion,
                    c.cas_cod_id,
                    REPLACE(
                        REPLACE(c.cas_data->>'cas_nombre_caso', '|', '<br>'),
                        'undefined', '-'
                    ) as cas_nombre_caso,
                    u.nom_usuario,--r.nom_usuario
                    c.cas_registrado,
                    c.cas_modificado,
                    c.cas_estado ,
                    c.cas_padre_id,
                    gea.est_codigo
                    FROM rmx_vys_casos c
                        LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                        LEFT join users u on u.id = c.cas_usr_id
                        left join gp_estados_avance gea on a.act_est_id = gea.est_id
                   WHERE c.cas_estado <> 'X' and c.cas_estado <> 'H' and c.cas_estado <> 'W'
                        AND c.cas_cod_id like '$cas_cod_id'
                        and cas_registrado::date between '$fecha_ini' and '$fecha_fin' and a.act_estado = 'A' and gea.est_estado = 'A'
                        $sip_nodo
                        and c.cas_data @> '{ $sip_dep $sip_reg $sip_age}'
                        ORDER BY c.cas_modificado desc";
            $data = \DB::select("$sql");

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    /*
    public function generarReporte(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] === null ? '' : $request["fecha_ini"];
        $fecha_fin = $request["fecha_fin"] === null ? '' : $request["fecha_fin"];
        $id_departamento = $request["id_departamento"] === null ? 0 : $request["id_departamento"];
        $id_regional = $request["id_regional"] === null ? 0 : $request["id_regional"];
        $id_agencia = $request["id_agencia"] === null ? 0 : $request["id_agencia"];
        $id_area = $request["id_area"] === null ? 0 : $request["id_area"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $sip_dep = '';
        if ($id_departamento == 0) {
            $sip_dep = "";
        } else {
            $sip_dep = '"id_cas_departamento":' . $id_departamento;
        }

        $sip_reg = '';
        if ($id_regional == 0) {
            $sip_reg = "";
        } else {
            $sip_reg = ',"id_cas_regional":' . $id_regional;
        }

        $sip_age = '';
        if ($id_agencia == 0) {
            $sip_age = "";
        } else {
            $sip_age = ',"id_cas_agencia":' . $id_agencia;
        }

        $sip_nodo = '';
        if ($id_area == 0) {
            $sip_nodo = "";
        } else {
            $sip_nodo = 'and c.cas_nodo_id = ' . $id_area;
        }

        try {
            $sql = "SELECT c.cas_id,
                    c.cas_correlativo,
                    c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                    a.act_data->>'act_orden' as act_orden,
                    a.act_data->>'act_descripcion' as act_descripcion,
                    c.cas_cod_id,
                    nnn.nodo_descripcion,
                    u.nom_usuario,
                    c.cas_registrado,
                    c.cas_modificado,
                    c.cas_estado ,
                    c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                    c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                    c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                    c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                    c.cas_data->>'AS_CI' as as_ci,
                    c.cas_data->>'AS_CUA' as as_cua,
                    c.cas_data->>'cas_regional' as cas_regional,
                    c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                    (select datos.valor->>'frm_value_label' as valor
                            FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                                    FROM public.rmx_vys_casos) datos
                                where datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                                        and datos.cas_id = c.cas_id) as sub_clasificacion,
                    gea.est_codigo,
                    c.cas_data->>'cas_agencia' as cas_agencia
                    FROM rmx_vys_casos c
                        LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                        LEFT join users u on u.id = c.cas_usr_id
                        left join gp_estados_avance gea on a.act_est_id = gea.est_id
                    WHERE c.cas_estado <> 'X' and c.cas_estado <> 'H' and c.cas_estado <> 'W'
                        AND c.cas_cod_id like '$cas_cod_id'
                        and cas_registrado::date between '$fecha_ini' and '$fecha_fin' and a.act_estado = 'A' and gea.est_estado = 'A'
                        $sip_nodo
                        and c.cas_data @> '{ $sip_dep $sip_reg $sip_age}'
                        ORDER BY c.cas_modificado desc";
            $data = \DB::select("$sql");
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }

        $titulo = "ReporteProcesosXls";
        $nombreArchivo = uniqid(md5(session_id()) . $titulo);
        $nombreArchivo .= '.xlsx';
        $this->url_archivo = "../public/reportes_generados/" . $nombreArchivo;

        set_time_limit(300);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("marco polo")->setTitle("mi excel");
        $spreadsheet->setActiveSheetIndex(0);
        $hojaActiva = $spreadsheet->getActiveSheet();

        //ESTILOS
        $styleTitulos1 = array(
            'font' => array(
                'bold' => true,
                'size' => 12,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $styleTitulos2 = array(
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $estiloSubtitulo = [
            'font' => [
                'bold' => true,
                'size' => 8,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'color' => [
                    'rgb' => 'c5d9f1'
                ]
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $estiloCuerpo = [
            'font' => [
                'bold' => false,
                'size' => 8,
                'name' => 'Arial'
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];


        $hojaActiva->setCellValue('A2', "REPORTE REGIONAL");
        $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
        $hojaActiva->mergeCells('A2:R2');

        $hojaActiva->setCellValue('A3', "Corresponde al periodo " . $fecha_ini . " Al " . $fecha_fin);
        $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
        $hojaActiva->mergeCells('A3:R3');

        $titulosColumnas = array(
            //.....................................lugar consulta........Posicion exel
            'Nro. Trámite ',
            'Caso Correlativo',
            'Tipo Sub Solicitud',
            'Actividad',
            'No. Caso',
            'Unidad Actual',
            'Usuario',
            'Fecha de Asignación',
            'Fecha de Derivación',
            // --'Estado',
            'Asegurado',
            'Nro Documento',
            'Cua',
            'Regional',
            'Estado Derivacion',
            'Sub. Clasificacion',
            'Estado',
            'Agencia',
        );

        for ($i = 0; $i < count($titulosColumnas); $i++) {
            $hojaActiva->setCellValue(chr(65 + $i) . '5', $titulosColumnas[$i]); // Set column titles
            $hojaActiva->getStyle(chr(65 + $i) . '5')->applyFromArray($estiloSubtitulo);
            $hojaActiva->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        };

        $this->rec = 6;
        $listaDetalle = $data;
        foreach ($listaDetalle as $detalle => $value) {
            $AS_PRIMER_APELLIDO = '';
            $AS_SEGUNDO_APELLIDO = '';
            $AS_PRIMER_NOMBRE = '';
            $AS_SEGUNDO_NOMBRE = '';
            $AS_CI = '';
            $AS_CUA = '';
            $cas_agencia = '';
            $cas_regional = '';
            $ESTADO_DERIVACION = '';
            $SUB_CLASIFICACION = '';
            //$ultima_derivacion = '';
            $AS_PRIMER_APELLIDO = $value->as_primer_apellido ?? '';
            $AS_SEGUNDO_APELLIDO = $value->as_segundo_apellido ?? '';
            $AS_PRIMER_NOMBRE = $value->as_primer_nombre ?? '';
            $AS_SEGUNDO_NOMBRE = $value->as_segundo_nombre ?? '';
            $AS_CI = $value->as_ci ?? '';
            $AS_CUA = $value->as_cua ?? '';
            $cas_agencia = $value->cas_agencia ?? '';
            $cas_regional = $value->cas_regional ?? '';
            //$ultima_derivacion = $value ->ultima_derivacion ?? '';
            $SUB_CLASIFICACION = $value->sub_clasificacion ?? '';
            $est_codigo = $value->est_codigo ?? '';
            $cas_registrado = $value->cas_registrado ? date('Y-m-d H:i:s', strtotime($value->cas_registrado)) : '';
            $cas_modificado = $value->cas_modificado ? date('Y-m-d H:i:s', strtotime($value->cas_modificado)) : '';
            $fila = $detalle + $this->rec;
            $estado =  '';
            if ($value->cas_estado == 'A') {
                $estado = 'Libre';
            } elseif ($value->cas_estado == 'T') {
                $estado = 'Tomado';
            } elseif ($value->cas_estado == 'E') {
                $estado = 'ENVIADO UCPP';
            } else {
                $estado = 'Sin Estado';
            }
            $ESTADO_DERIVACION = $value->estado_derivacion ?? '';
            $hojaActiva->setCellValue('A' . $fila, $value->cas_id);
            $hojaActiva->getStyle('A' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('B' . $fila, $value->cas_correlativo);
            $hojaActiva->getStyle('B' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('C' . $fila, $value->nombre_proceso);
            $hojaActiva->getStyle('C' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('D' . $fila, $value->act_orden . '-' . $value->act_descripcion);
            $hojaActiva->getStyle('D' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('E' . $fila, $value->cas_cod_id);
            $hojaActiva->getStyle('E' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('F' . $fila, $value->nodo_descripcion);
            $hojaActiva->getStyle('F' . $fila)->applyFromArray($estiloCuerpo);


            $hojaActiva->setCellValue('G' . $fila, $value->nom_usuario);
            $hojaActiva->getStyle('G' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('H' . $fila, $cas_registrado);
            $hojaActiva->getStyle('H' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('I' . $fila, $cas_modificado);
            $hojaActiva->getStyle('I' . $fila)->applyFromArray($estiloCuerpo);

            // $hojaActiva->setCellValue('J' . $fila, $estado);
            // $hojaActiva->getStyle('J' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('J' . $fila, $AS_PRIMER_APELLIDO . ' ' . $AS_SEGUNDO_APELLIDO . ' ' . $AS_PRIMER_NOMBRE . ' ' . $AS_SEGUNDO_NOMBRE);
            $hojaActiva->getStyle('J' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('K' . $fila, $AS_CI);
            $hojaActiva->getStyle('K' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('L' . $fila, $AS_CUA);
            $hojaActiva->getStyle('L' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('M' . $fila, $cas_regional);
            $hojaActiva->getStyle('M' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('N' . $fila, $ESTADO_DERIVACION);
            $hojaActiva->getStyle('N' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('O' . $fila, $SUB_CLASIFICACION);
            $hojaActiva->getStyle('O' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('P' . $fila, $est_codigo);
            $hojaActiva->getStyle('P' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('Q' . $fila, $cas_agencia);
            $hojaActiva->getStyle('Q' . $fila)->applyFromArray($estiloCuerpo);

            //$hojaActiva->setCellValue('S' . $fila, $ultima_derivacion);
            //$hojaActiva->getStyle('S' . $fila)->applyFromArray($estiloCuerpo);
        };


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="mi excel.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($this->url_archivo);
        return response()->json(['success' => true, 'nombreArchivo' => $nombreArchivo, 'message' => 'Documento Excel guardado con éxito.']);
    }
*/
    public function generarReporte(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT c.cas_id,
                    c.cas_correlativo,
                    c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                    a.act_data->>'act_orden' as act_orden,
                    a.act_data->>'act_descripcion' as act_descripcion,
                    c.cas_cod_id,
                    nnn.nodo_descripcion,
                    u.nom_usuario,
                    c.cas_registrado,
                    c.cas_modificado,
                    c.cas_estado ,
                    c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                    c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                    c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                    c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                    c.cas_data->>'AS_CI' as as_ci,
                    c.cas_data->>'AS_CUA' as as_cua,
                    c.cas_data->>'cas_regional' as cas_regional,
                    c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                    (select datos.valor->>'frm_value_label' as valor
                            FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                                    FROM public.rmx_vys_casos) datos
                                where datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                                        and datos.cas_id = c.cas_id) as sub_clasificacion,
                    gea.est_codigo,
                    c.cas_data->>'cas_agencia' as cas_agencia
                    FROM rmx_vys_casos c
                        LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                        LEFT join users u on u.id = c.cas_usr_id
                        left join gp_estados_avance gea on a.act_est_id = gea.est_id
                    WHERE c.cas_estado <> 'X' and c.cas_estado <> 'H' and c.cas_estado <> 'W'
                        AND c.cas_cod_id like '$cas_cod_id'
                        and cas_registrado::date between '$fecha_ini' and '$fecha_fin' and a.act_estado = 'A' and gea.est_estado = 'A'
                        $sip_nodo
                        and c.cas_data @> '{ $sip_dep $sip_reg $sip_age}'
                        ORDER BY c.cas_modificado desc";
            $data = \DB::select("$sql");
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }

        $titulo = "ReporteProcesosXls.xlsx";
        $nombreArchivo = uniqid(md5(session_id()) . $titulo) . '.xlsx';

        set_time_limit(300);

        $spreadsheet = new Spreadsheet();
        $hojaActiva = $spreadsheet->getActiveSheet();

        //ESTILOS
        $styleTitulos1 = array(
            'font' => array(
                'bold' => true,
                'size' => 12,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $styleTitulos2 = array(
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $estiloSubtitulo = [
            'font' => [
                'bold' => true,
                'size' => 8,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'color' => [
                    'rgb' => 'c5d9f1'
                ]
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $estiloCuerpo = [
            'font' => [
                'bold' => false,
                'size' => 8,
                'name' => 'Arial'
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];


        $hojaActiva->setCellValue('A2', "REPORTE REGIONAL");
        $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
        $hojaActiva->mergeCells('A2:R2');

        $hojaActiva->setCellValue('A3', "Corresponde al periodo " . $fecha_ini . " Al " . $fecha_fin);
        $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
        $hojaActiva->mergeCells('A3:R3');

        $titulosColumnas = array(
            //.....................................lugar consulta........Posicion exel
            'Nro. Trámite ',
            'Caso Correlativo',
            'Tipo Sub Solicitud',
            'Actividad',
            'No. Caso',
            'Unidad Actual',
            'Usuario',
            'Fecha de Asignación',
            'Fecha de Derivación',
            // --'Estado',
            'Asegurado',
            'Nro Documento',
            'Cua',
            'Regional',
            'Estado Derivacion',
            'Sub. Clasificacion',
            'Estado',
            'Agencia',
        );

        for ($i = 0; $i < count($titulosColumnas); $i++) {
            $hojaActiva->setCellValue(chr(65 + $i) . '5', $titulosColumnas[$i]); // Set column titles
            $hojaActiva->getStyle(chr(65 + $i) . '5')->applyFromArray($estiloSubtitulo);
            $hojaActiva->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        };

        $fila = 6;
        foreach ($data as $row) {
            $asegurado = trim($row->as_primer_apellido . ' ' . $row->as_segundo_apellido . ' ' . $row->as_primer_nombre . ' ' . $row->as_segundo_nombre);

            $estado = match ($row->cas_estado) {
                'A' => 'Libre',
                'T' => 'Tomado',
                'E' => 'ENVIADO UCPP',
                default => 'Sin Estado',
            };
            $cas_registrado = $row->cas_registrado ? date('Y-m-d H:i:s', strtotime($row->cas_registrado)) : '';
            $cas_modificado = $row->cas_modificado ? date('Y-m-d H:i:s', strtotime($row->cas_modificado)) : '';
            

            $hojaActiva->setCellValue('A' . $fila, $row->cas_id);
            $hojaActiva->setCellValue('B' . $fila, $row->cas_correlativo);
            $hojaActiva->setCellValue('C' . $fila, $row->nombre_proceso);
            $hojaActiva->setCellValue('D' . $fila, $row->act_orden . '-' . $row->act_descripcion);
            $hojaActiva->setCellValue('E' . $fila, $row->cas_cod_id);
            $hojaActiva->setCellValue('F' . $fila, $row->nodo_descripcion);
            $hojaActiva->setCellValue('G' . $fila, $row->nom_usuario);
            $hojaActiva->setCellValue('H' . $fila, $cas_registrado);
            $hojaActiva->setCellValue('I' . $fila, $cas_modificado);
            $hojaActiva->setCellValue('J' . $fila, $asegurado);
            $hojaActiva->setCellValue('K' . $fila, $row->as_ci);
            $hojaActiva->setCellValue('L' . $fila, $row->as_cua);
            $hojaActiva->setCellValue('M' . $fila, $row->cas_regional);
            $hojaActiva->setCellValue('N' . $fila, $row->estado_derivacion);
            $hojaActiva->setCellValue('O' . $fila, $row->sub_clasificacion);
            $hojaActiva->setCellValue('P' . $fila, $row->est_codigo);
            $hojaActiva->setCellValue('Q' . $fila, $row->cas_agencia);

            $hojaActiva->getStyle('A' . $fila . ':Q' . $fila)->applyFromArray($estiloCuerpo);
            $fila++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"'); //$fileName el nombre del archivo en si
        $writer = new Xlsx($spreadsheet);
        ob_end_clean();
        $writer->save('php://output');
        exit;
    }

    public function generarReporteCsv(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] ?? '%';
        $cas_nro_caso = $request["cas_nro_caso"] ?? '%';
        $cas_gestion = $request["cas_gestion"] ?? '%';
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] ?? '';
        $fecha_fin = $request["fecha_fin"] ?? '';
        $id_departamento = $request["id_departamento"] ?? 0;
        $id_regional = $request["id_regional"] ?? 0;
        $id_agencia = $request["id_agencia"] ?? 0;
        $id_area = $request["id_area"] ?? 0;

        $sip_dep = $id_departamento ? '"id_cas_departamento":' . $id_departamento : '';
        $sip_reg = $id_regional ? ',"id_cas_regional":' . $id_regional : '';
        $sip_age = $id_agencia ? ',"id_cas_agencia":' . $id_agencia : '';
        $sip_nodo = $id_area ? 'and c.cas_nodo_id = ' . $id_area : '';

        try {
            $sql = "SELECT c.cas_id,
             c.cas_correlativo,
              c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso, 
              a.act_data->>'act_orden' as act_orden,
               a.act_data->>'act_descripcion' as act_descripcion, c.cas_cod_id,
                nnn.nodo_descripcion, u.nom_usuario, c.cas_registrado, c.cas_modificado,
                 c.cas_estado, c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                  c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido, 
                  c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                   c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre, 
                   c.cas_data->>'AS_CI' as as_ci, c.cas_data->>'AS_CUA' as as_cua,
                    c.cas_data->>'cas_regional' as cas_regional, 
                    c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                     (SELECT datos.valor->>'frm_value_label' as valor 
                     FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id 
                     FROM public.rmx_vys_casos) datos
                      WHERE datos.valor->>'frm_campo' = 'AS_TIPO_EAP' 
                      AND datos.cas_id = c.cas_id) as sub_clasificacion,
                       gea.est_codigo,
                        c.cas_data->>'cas_agencia' as cas_agencia
                         FROM rmx_vys_casos c 
                         LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id 
                         LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id 
                         LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id 
                         LEFT JOIN users u ON u.id = c.cas_usr_id 
                         LEFT JOIN gp_estados_avance gea ON a.act_est_id = gea.est_id 
                         WHERE c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W' 
                         AND c.cas_cod_id LIKE '$cas_cod_id' 
                         AND cas_registrado::date BETWEEN '$fecha_ini' AND '$fecha_fin' AND a.act_estado = 'A' AND gea.est_estado = 'A' 
                         $sip_nodo 
                         AND c.cas_data @> '{ $sip_dep $sip_reg $sip_age}' 
                         ORDER BY c.cas_modificado DESC";
            $data = \DB::select("$sql");
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $titulo = "ReporteProcesosCsv";
        $nombreArchivo = uniqid(md5(session_id()) . $titulo) . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        $output = fopen('php://output', 'w');

        fputcsv($output, ["REPORTE REGIONAL"]);
        fputcsv($output, ["Corresponde al periodo " . $fecha_ini . " Al " . $fecha_fin]);
        fputcsv($output, []);

        // Encabezados CSV
        fputcsv($output, [
            'Nro. Trámite',
            'Caso Correlativo',
            'Tipo Sub Solicitud',
            'Actividad',
            'No. Caso',
            'Unidad Actual',
            'Usuario',
            'Fecha de Asignación',
            'Fecha de Derivación',
            'Asegurado',
            'Nro Documento',
            'Cua',
            'Regional',
            'Estado Derivacion',
            'Sub. Clasificacion',
            'Estado',
            'Agencia'
        ]);

        foreach ($data as $row) {
            $asegurado = trim($row->as_primer_apellido . ' ' . $row->as_segundo_apellido . ' ' . $row->as_primer_nombre . ' ' . $row->as_segundo_nombre);

            $cas_registrado = $row->cas_registrado ? date('Y-m-d H:i:s', strtotime($row->cas_registrado)) : '';
            $cas_modificado = $row->cas_modificado ? date('Y-m-d H:i:s', strtotime($row->cas_modificado)) : '';
            //$nombre_proceso = mb_convert_encoding($row->nombre_proceso, 'UTF-8', mb_detect_encoding($row->nombre_proceso));

            fputcsv($output, [
                $row->cas_id,
                $row->cas_correlativo,
                $row->nombre_proceso,
                $row->act_orden . '-' . $row->act_descripcion,
                $row->cas_cod_id,
                $row->nodo_descripcion,
                $row->nom_usuario,
                $cas_registrado,
                $cas_modificado,
                $asegurado,
                $row->as_ci,
                $row->as_cua,
                $row->cas_regional,
                $row->estado_derivacion,
                $row->sub_clasificacion,
                $row->est_codigo,
                $row->cas_agencia
            ]);
        }

        fclose($output);
        exit;
    }

    /*
    public function generarReporteCsvAntiguo(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];
        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $fecha_ini = $request["fecha_ini"] === null ? '' : $request["fecha_ini"];
        $fecha_fin = $request["fecha_fin"] === null ? '' : $request["fecha_fin"];
        $id_departamento = $request["id_departamento"] === null ? 0 : $request["id_departamento"];
        $id_regional = $request["id_regional"] === null ? 0 : $request["id_regional"];
        $id_agencia = $request["id_agencia"] === null ? 0 : $request["id_agencia"];
        $id_area = $request["id_area"] === null ? 0 : $request["id_area"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $sip_dep = '';
        if ($id_departamento == 0) {
            $sip_dep = "";
        } else {
            $sip_dep = '"id_cas_departamento":' . $id_departamento;
        }

        $sip_reg = '';
        if ($id_regional == 0) {
            $sip_reg = "";
        } else {
            $sip_reg = ',"id_cas_regional":' . $id_regional;
        }

        $sip_age = '';
        if ($id_agencia == 0) {
            $sip_age = "";
        } else {
            $sip_age = ',"id_cas_agencia":' . $id_agencia;
        }

        $sip_nodo = '';
        if ($id_area == 0) {
            $sip_nodo = "";
        } else {
            $sip_nodo = 'and c.cas_nodo_id = ' . $id_area;
        }

        try {
            $sql = "SELECT c.cas_id,
                    c.cas_correlativo,
                    c.cas_data->>'NOMBRE_PROCESO' as nombre_proceso,
                    a.act_data->>'act_orden' as act_orden,
                    a.act_data->>'act_descripcion' as act_descripcion,
                    c.cas_cod_id,
                    nnn.nodo_descripcion,
                    u.nom_usuario,
                    c.cas_registrado,
                    c.cas_modificado,
                    c.cas_estado ,
                    c.cas_data->>'AS_PRIMER_APELLIDO' as as_primer_apellido,
                    c.cas_data->>'AS_SEGUNDO_APELLIDO' as as_segundo_apellido,
                    c.cas_data->>'AS_PRIMER_NOMBRE' as as_primer_nombre,
                    c.cas_data->>'AS_SEGUNDO_NOMBRE' as as_segundo_nombre,
                    c.cas_data->>'AS_CI' as as_ci,
                    c.cas_data->>'AS_CUA' as as_cua,
                    c.cas_data->>'cas_regional' as cas_regional,
                    c.cas_data->>'ESTADO_DERIVACION' as estado_derivacion,
                    (select datos.valor->>'frm_value_label' as valor
                            FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                                    FROM public.rmx_vys_casos) datos
                                where datos.valor->>'frm_campo' = 'AS_TIPO_EAP'
                                        and datos.cas_id = c.cas_id) as sub_clasificacion,
                    gea.est_codigo,
                    c.cas_data->>'cas_agencia' as cas_agencia
                    FROM rmx_vys_casos c
                        LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                        LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                        LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                        LEFT join users u on u.id = c.cas_usr_id
                        left join gp_estados_avance gea on a.act_est_id = gea.est_id
                    WHERE c.cas_estado <> 'X' and c.cas_estado <> 'H' and c.cas_estado <> 'W'
                        AND c.cas_cod_id like '$cas_cod_id'
                        and cas_registrado::date between '$fecha_ini' and '$fecha_fin' and a.act_estado = 'A' and gea.est_estado = 'A'
                        $sip_nodo
                        and c.cas_data @> '{ $sip_dep $sip_reg $sip_age}'
                        ORDER BY c.cas_modificado desc";
            $data = \DB::select("$sql");
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }

        $titulo = "ReporteProcesosXls";
        $nombreArchivo = uniqid(md5(session_id()) . $titulo);
        $nombreArchivo .= '.csv';
        $this->url_archivo = "../public/reportes_generados/" . $nombreArchivo;

        set_time_limit(300);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("marco polo")->setTitle("mi excel");
        $spreadsheet->setActiveSheetIndex(0);
        $hojaActiva = $spreadsheet->getActiveSheet();

        //ESTILOS
        $styleTitulos1 = array(
            'font' => array(
                'bold' => true,
                'size' => 12,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $styleTitulos2 = array(
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Arial'
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER
            )
        );

        $estiloSubtitulo = [
            'font' => [
                'bold' => true,
                'size' => 8,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'rotation' => 90,
                'color' => [
                    'rgb' => 'c5d9f1'
                ]
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];

        $estiloCuerpo = [
            'font' => [
                'bold' => false,
                'size' => 8,
                'name' => 'Arial'
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];


        $hojaActiva->setCellValue('A2', "REPORTE REGIONAL");
        $hojaActiva->getStyle('A2:R2')->applyFromArray($styleTitulos1);
        $hojaActiva->mergeCells('A2:R2');

        $hojaActiva->setCellValue('A3', "Corresponde al periodo " . $fecha_ini . " Al " . $fecha_fin);
        $hojaActiva->getStyle('A3:R3')->applyFromArray($styleTitulos2);
        $hojaActiva->mergeCells('A3:R3');

        $titulosColumnas = array(
            //.....................................lugar consulta........Posicion exel
            'Nro. Trámite ',
            'Caso Correlativo',
            'Tipo Sub Solicitud',
            'Actividad',
            'No. Caso',
            'Unidad Actual',
            'Usuario',
            'Fecha de Asignación',
            'Fecha de Derivación',
            // --'Estado',
            'Asegurado',
            'Nro Documento',
            'Cua',
            'Regional',
            'Estado Derivacion',
            'Sub. Clasificacion',
            'Estado',
            'Agencia',
        );

        for ($i = 0; $i < count($titulosColumnas); $i++) {
            $hojaActiva->setCellValue(chr(65 + $i) . '5', $titulosColumnas[$i]); // Set column titles
            $hojaActiva->getStyle(chr(65 + $i) . '5')->applyFromArray($estiloSubtitulo);
            $hojaActiva->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        };

        $this->rec = 6;
        $listaDetalle = $data;
        foreach ($listaDetalle as $detalle => $value) {
            $AS_PRIMER_APELLIDO = '';
            $AS_SEGUNDO_APELLIDO = '';
            $AS_PRIMER_NOMBRE = '';
            $AS_SEGUNDO_NOMBRE = '';
            $AS_CI = '';
            $AS_CUA = '';
            $cas_agencia = '';
            $cas_regional = '';
            $ESTADO_DERIVACION = '';
            $SUB_CLASIFICACION = '';
            //$ultima_derivacion = '';
            $AS_PRIMER_APELLIDO = $value->as_primer_apellido ?? '';
            $AS_SEGUNDO_APELLIDO = $value->as_segundo_apellido ?? '';
            $AS_PRIMER_NOMBRE = $value->as_primer_nombre ?? '';
            $AS_SEGUNDO_NOMBRE = $value->as_segundo_nombre ?? '';
            $AS_CI = $value->as_ci ?? '';
            $AS_CUA = $value->as_cua ?? '';
            $cas_agencia = $value->cas_agencia ?? '';
            $cas_regional = $value->cas_regional ?? '';
            //$ultima_derivacion = $value ->ultima_derivacion ?? '';
            $SUB_CLASIFICACION = $value->sub_clasificacion ?? '';
            $est_codigo = $value->est_codigo ?? '';
            $cas_registrado = $value->cas_registrado ? date('Y-m-d H:i:s', strtotime($value->cas_registrado)) : '';
            $cas_modificado = $value->cas_modificado ? date('Y-m-d H:i:s', strtotime($value->cas_modificado)) : '';
            $fila = $detalle + $this->rec;
            $estado =  '';
            if ($value->cas_estado == 'A') {
                $estado = 'Libre';
            } elseif ($value->cas_estado == 'T') {
                $estado = 'Tomado';
            } elseif ($value->cas_estado == 'E') {
                $estado = 'ENVIADO UCPP';
            } else {
                $estado = 'Sin Estado';
            }
            $ESTADO_DERIVACION = $value->estado_derivacion ?? '';
            $hojaActiva->setCellValue('A' . $fila, $value->cas_id);
            $hojaActiva->getStyle('A' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('B' . $fila, $value->cas_correlativo);
            $hojaActiva->getStyle('B' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('C' . $fila, $value->nombre_proceso);
            $hojaActiva->getStyle('C' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('D' . $fila, $value->act_orden . '-' . $value->act_descripcion);
            $hojaActiva->getStyle('D' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('E' . $fila, $value->cas_cod_id);
            $hojaActiva->getStyle('E' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('F' . $fila, $value->nodo_descripcion);
            $hojaActiva->getStyle('F' . $fila)->applyFromArray($estiloCuerpo);


            $hojaActiva->setCellValue('G' . $fila, $value->nom_usuario);
            $hojaActiva->getStyle('G' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('H' . $fila, $cas_registrado);
            $hojaActiva->getStyle('H' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('I' . $fila, $cas_modificado);
            $hojaActiva->getStyle('I' . $fila)->applyFromArray($estiloCuerpo);

            // $hojaActiva->setCellValue('J' . $fila, $estado);
            // $hojaActiva->getStyle('J' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('J' . $fila, $AS_PRIMER_APELLIDO . ' ' . $AS_SEGUNDO_APELLIDO . ' ' . $AS_PRIMER_NOMBRE . ' ' . $AS_SEGUNDO_NOMBRE);
            $hojaActiva->getStyle('J' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('K' . $fila, $AS_CI);
            $hojaActiva->getStyle('K' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('L' . $fila, $AS_CUA);
            $hojaActiva->getStyle('L' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('M' . $fila, $cas_regional);
            $hojaActiva->getStyle('M' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('N' . $fila, $ESTADO_DERIVACION);
            $hojaActiva->getStyle('N' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('O' . $fila, $SUB_CLASIFICACION);
            $hojaActiva->getStyle('O' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('P' . $fila, $est_codigo);
            $hojaActiva->getStyle('P' . $fila)->applyFromArray($estiloCuerpo);

            $hojaActiva->setCellValue('Q' . $fila, $cas_agencia);
            $hojaActiva->getStyle('Q' . $fila)->applyFromArray($estiloCuerpo);

            //$hojaActiva->setCellValue('S' . $fila, $ultima_derivacion);
            //$hojaActiva->getStyle('S' . $fila)->applyFromArray($estiloCuerpo);
        };


        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');

        //$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer = new Csv($spreadsheet);
        //$writer->save($this->url_archivo);
        $writer->save('php://output');
        exit;
        //return response()->json(['success' => true, 'nombreArchivo' => $nombreArchivo, 'message' => 'Documento Excel guardado con éxito.']);
    }
*/
    //*********************GENERAR CORRELATIVO**************************************** */
    public function GetCorrelativo(Request $request)
    {
        $gestion = $request['gestion'];
        $codigo = $request['codigo'];
        $codigo_caso = $request['codigo_caso'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT correlativo+1 as res
            FROM correlativo
            where gestion=$gestion and codigo='$codigo'")[0];
            \DB::statement("UPDATE correlativo SET
					correlativo = " . $data->res . ",
					codigo_caso = '$codigo_caso'
				WHERE  gestion=$gestion and codigo='$codigo' and codigo_caso<>'$codigo_caso';");
            $data = \DB::select("SELECT codigo ||'/'||correlativo ||'/'||gestion as codigo
            FROM correlativo
            where gestion=$gestion and codigo='$codigo' and codigo_caso='$codigo_caso'")[0];
            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function GetTmc()
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT u.id,u.name ,u.codigo
            FROM public.users u
            inner join public.rmx_usr_nodos un on  u.id =un.usn_user_id
            where un.usn_nodo_id=21 and  un.usn_estado <>'X' and u.status <>'X' ");

            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function buscarDepartamento()
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('menssage' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * from public.gp_departamento where doc_estado = 'A'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarAreas()
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('menssage' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * from public.rmx_vys_nodos rvn where rvn.nodo_estado = 'A'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function buscarRegional(Request $request)
    {
        $id_departamento = $request["id_departamento"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('menssage' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * from public.gp_regional gr where gr.id_sip_departamento = $id_departamento  and doc_estado = 'A'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function buscarAgencia(Request $request)
    {
        $id_regional = $request["id_regional"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('mensaje' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * from public.gp_agencia ga where ga.id_sip_regional = $id_regional and ga.doc_estado = 'A'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    //*********************alertar fecha de publicacion o notificación**************************************** */
    public function GetAlertaFechaNotificacionPublicacion()
    {

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("WITH casos_con_fechas AS (
                                                        SELECT
                                                            c.cas_id,
                                                            p.prc_data->>'prc_descripcion' AS prc_descripcion,
                                                            (a.act_data->>'act_orden') || ' - ' || (a.act_data->>'act_descripcion') AS act_descripcion,
                                                            n.nodo_descripcion,
                                                            cas_data->>'AS_TIPO_DOCUMENTO' AS AS_TIPO_DOCUMENTO,
                                                            cas_data->>'AS_CI' AS AS_CI,
                                                            cas_data->>'AS_COMPLEMENTO' AS AS_COMPLEMENTO,
                                                            cas_data->>'AS_CUA' AS AS_CUA,
                                                            cas_data->>'AS_PRIMER_NOMBRE' AS AS_PRIMER_NOMBRE,
                                                            cas_data->>'AS_SEGUNDO_NOMBRE' AS AS_SEGUNDO_NOMBRE,
                                                            cas_data->>'AS_PRIMER_APELLIDO' AS AS_PRIMER_APELLIDO,
                                                            cas_data->>'AS_SEGUNDO_APELLIDO' AS AS_SEGUNDO_APELLIDO,
                                                            cas_data->>'AS_APELLIDO_CASADA' AS AS_APELLIDO_CASADA,
                                                            date(cas_registrado) AS cas_registrado,

                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                                                            ) AS AS_FECHA_FALLECIMIENTO,

                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'fecha_de_notificacion'
                                                            ) AS FECHA_NOTIFICACION,
                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'fecha_de_publicacion'
                                                            ) AS FECHA_PUBLICACION,
                                                            c.cas_cod_id
                                                        FROM
                                                            rmx_vys_casos c
                                                        LEFT JOIN
                                                            rmx_vys_actividades a ON cas_act_id = act_id
                                                        LEFT JOIN
                                                            rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                                                        LEFT JOIN
                                                            rmx_vys_nodos n ON act_nodo_id = n.nodo_id
                                                        WHERE
                                                            cas_cod_id LIKE 'PM%'
                                                            AND c.cas_estado NOT IN ('X', 'H', 'W')
                                                            AND cas_data_valores <> '[]'
                                                            AND (act_data->>'act_orden')::integer = 70
                                                    )
                                                    SELECT
                                                        cas_id,
                                                        prc_descripcion,
                                                        act_descripcion,
                                                        nodo_descripcion,
                                                        AS_TIPO_DOCUMENTO,
                                                        AS_CI,
                                                        AS_COMPLEMENTO,
                                                        AS_CUA,
                                                        AS_PRIMER_NOMBRE,
                                                        AS_SEGUNDO_NOMBRE,
                                                        AS_PRIMER_APELLIDO,
                                                        AS_SEGUNDO_APELLIDO,
                                                        AS_APELLIDO_CASADA,
                                                        cas_registrado,
                                                        AS_FECHA_FALLECIMIENTO,
                                                        FECHA_NOTIFICACION,
                                                        FECHA_PUBLICACION,
                                                        cas_cod_id,
                                                        COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION) AS gec,
                                                        EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) AS dias_transcurridos,
                                                        -- Nueva columna: Vencimiento
                                                        CASE
                                                            WHEN EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) > 10
                                                            THEN 'Vencido'
                                                            ELSE 'En plazo'
                                                        END AS estado_vencimiento
                                                    FROM
                                                        casos_con_fechas
                                                    WHERE
                                                        EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) >= 4;

                                                    ");
            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function GetFuncionPublicacionPM()
    {

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("WITH casos_con_fechas AS (
                    SELECT
                        c.cas_id,
                        p.prc_data->>'prc_descripcion' AS prc_descripcion,
                        (a.act_data->>'act_orden') || ' - ' || (a.act_data->>'act_descripcion') AS act_descripcion,
                        n.nodo_descripcion,
                        cas_data->>'AS_TIPO_DOCUMENTO' AS AS_TIPO_DOCUMENTO,
                        cas_data->>'AS_CI' AS AS_CI,
                        cas_data->>'AS_COMPLEMENTO' AS AS_COMPLEMENTO,
                        cas_data->>'AS_CUA' AS AS_CUA,
                        cas_data->>'AS_PRIMER_NOMBRE' AS AS_PRIMER_NOMBRE,
                        cas_data->>'AS_SEGUNDO_NOMBRE' AS AS_SEGUNDO_NOMBRE,
                        cas_data->>'AS_PRIMER_APELLIDO' AS AS_PRIMER_APELLIDO,
                        cas_data->>'AS_SEGUNDO_APELLIDO' AS AS_SEGUNDO_APELLIDO,
                        cas_data->>'AS_APELLIDO_CASADA' AS AS_APELLIDO_CASADA,
                        date(cas_registrado) AS cas_registrado,

                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                        ) AS AS_FECHA_FALLECIMIENTO,

                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'fecha_de_notificacion'
                        ) AS FECHA_NOTIFICACION,
                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'fecha_de_publicacion'
                        ) AS FECHA_PUBLICACION,
                        c.cas_cod_id
                    FROM
                        rmx_vys_casos c
                    LEFT JOIN
                        rmx_vys_actividades a ON cas_act_id = act_id
                    LEFT JOIN
                        rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN
                        rmx_vys_nodos n ON act_nodo_id = n.nodo_id
                    WHERE
                        cas_cod_id LIKE 'PM%'
                        AND c.cas_estado NOT IN ('X', 'H', 'W')
                        AND cas_data_valores <> '[]'
                        AND (act_data->>'act_orden')::integer = 71
                )
                SELECT
                    cas_id,
                    prc_descripcion,
                    act_descripcion,
                    nodo_descripcion,
                    AS_TIPO_DOCUMENTO,
                    AS_CI,
                    AS_COMPLEMENTO,
                    AS_CUA,
                    AS_PRIMER_NOMBRE,
                    AS_SEGUNDO_NOMBRE,
                    AS_PRIMER_APELLIDO,
                    AS_SEGUNDO_APELLIDO,
                    AS_APELLIDO_CASADA,
                    cas_registrado,
                    AS_FECHA_FALLECIMIENTO,
                    FECHA_NOTIFICACION,
                    FECHA_PUBLICACION,
                    cas_cod_id,
                    COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION) AS gec,
                    EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp, FECHA_PUBLICACION::timestamp))) AS dias_transcurridos,
                    -- Nueva columna: Vencimiento
                    CASE
                        WHEN EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp,  FECHA_PUBLICACION::timestamp ))) > 30
                        THEN 'Vencido'
                        ELSE 'En plazo'
                    END AS estado_vencimiento
                FROM
                    casos_con_fechas
                WHERE
                    EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp, FECHA_PUBLICACION)))<>0 ; ");
            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function GetAlertaFechaNotificacionINV()
    {

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("WITH casos_con_fechas AS (
                                                        SELECT
                                                            c.cas_id,
                                                            p.prc_data->>'prc_descripcion' AS prc_descripcion,
                                                            (a.act_data->>'act_orden') || ' - ' || (a.act_data->>'act_descripcion') AS act_descripcion,
                                                            n.nodo_descripcion,
                                                            cas_data->>'AS_TIPO_DOCUMENTO' AS AS_TIPO_DOCUMENTO,
                                                            cas_data->>'AS_CI' AS AS_CI,
                                                            cas_data->>'AS_COMPLEMENTO' AS AS_COMPLEMENTO,
                                                            cas_data->>'AS_CUA' AS AS_CUA,
                                                            cas_data->>'AS_PRIMER_NOMBRE' AS AS_PRIMER_NOMBRE,
                                                            cas_data->>'AS_SEGUNDO_NOMBRE' AS AS_SEGUNDO_NOMBRE,
                                                            cas_data->>'AS_PRIMER_APELLIDO' AS AS_PRIMER_APELLIDO,
                                                            cas_data->>'AS_SEGUNDO_APELLIDO' AS AS_SEGUNDO_APELLIDO,
                                                            cas_data->>'AS_APELLIDO_CASADA' AS AS_APELLIDO_CASADA,
                                                            date(cas_registrado) AS cas_registrado,

                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                                                            ) AS AS_FECHA_FALLECIMIENTO,

                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'fecha_de_notificacion'
                                                            ) AS FECHA_NOTIFICACION,
                                                            (
                                                                SELECT date(datos.valor->>'frm_value')
                                                                FROM (
                                                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                                                ) datos
                                                                WHERE datos.valor->>'frm_campo' = 'fecha_de_publicacion'
                                                            ) AS FECHA_PUBLICACION,
                                                            c.cas_cod_id
                                                        FROM
                                                            rmx_vys_casos c
                                                        LEFT JOIN
                                                            rmx_vys_actividades a ON cas_act_id = act_id
                                                        LEFT JOIN
                                                            rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                                                        LEFT JOIN
                                                            rmx_vys_nodos n ON act_nodo_id = n.nodo_id
                                                        WHERE
                                                            cas_cod_id LIKE 'INV%'
                                                            AND c.cas_estado NOT IN ('X', 'H', 'W')
                                                            AND cas_data_valores <> '[]'
                                                            AND (act_data->>'act_orden')::integer = 70
                                                    )
                                                    SELECT
                                                        cas_id,
                                                        prc_descripcion,
                                                        act_descripcion,
                                                        nodo_descripcion,
                                                        AS_TIPO_DOCUMENTO,
                                                        AS_CI,
                                                        AS_COMPLEMENTO,
                                                        AS_CUA,
                                                        AS_PRIMER_NOMBRE,
                                                        AS_SEGUNDO_NOMBRE,
                                                        AS_PRIMER_APELLIDO,
                                                        AS_SEGUNDO_APELLIDO,
                                                        AS_APELLIDO_CASADA,
                                                        cas_registrado,
                                                        AS_FECHA_FALLECIMIENTO,
                                                        FECHA_NOTIFICACION,
                                                        FECHA_PUBLICACION,
                                                        cas_cod_id,
                                                        COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION) AS gec,
                                                        EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) AS dias_transcurridos,
                                                        -- Nueva columna: Vencimiento
                                                        CASE
                                                            WHEN EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) > 10
                                                            THEN 'Vencido'
                                                            ELSE 'En plazo'
                                                        END AS estado_vencimiento
                                                    FROM
                                                        casos_con_fechas
                                                    WHERE
                                                        EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION))) >= 4;

                                                    ");
            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function GetFuncionPublicacionINV()
    {

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("WITH casos_con_fechas AS (
                    SELECT
                        c.cas_id,
                        p.prc_data->>'prc_descripcion' AS prc_descripcion,
                        (a.act_data->>'act_orden') || ' - ' || (a.act_data->>'act_descripcion') AS act_descripcion,
                        n.nodo_descripcion,
                        cas_data->>'AS_TIPO_DOCUMENTO' AS AS_TIPO_DOCUMENTO,
                        cas_data->>'AS_CI' AS AS_CI,
                        cas_data->>'AS_COMPLEMENTO' AS AS_COMPLEMENTO,
                        cas_data->>'AS_CUA' AS AS_CUA,
                        cas_data->>'AS_PRIMER_NOMBRE' AS AS_PRIMER_NOMBRE,
                        cas_data->>'AS_SEGUNDO_NOMBRE' AS AS_SEGUNDO_NOMBRE,
                        cas_data->>'AS_PRIMER_APELLIDO' AS AS_PRIMER_APELLIDO,
                        cas_data->>'AS_SEGUNDO_APELLIDO' AS AS_SEGUNDO_APELLIDO,
                        cas_data->>'AS_APELLIDO_CASADA' AS AS_APELLIDO_CASADA,
                        date(cas_registrado) AS cas_registrado,

                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                        ) AS AS_FECHA_FALLECIMIENTO,

                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'fecha_de_notificacion'
                        ) AS FECHA_NOTIFICACION,
                        (
                            SELECT date(datos.valor->>'frm_value')
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'fecha_de_publicacion'
                        ) AS FECHA_PUBLICACION,
                        c.cas_cod_id
                    FROM
                        rmx_vys_casos c
                    LEFT JOIN
                        rmx_vys_actividades a ON cas_act_id = act_id
                    LEFT JOIN
                        rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN
                        rmx_vys_nodos n ON act_nodo_id = n.nodo_id
                    WHERE
                        cas_cod_id LIKE 'INV%'
                        AND c.cas_estado NOT IN ('X', 'H', 'W')
                        AND cas_data_valores <> '[]'
                        AND (act_data->>'act_orden')::integer = 71
                )
                SELECT
                    cas_id,
                    prc_descripcion,
                    act_descripcion,
                    nodo_descripcion,
                    AS_TIPO_DOCUMENTO,
                    AS_CI,
                    AS_COMPLEMENTO,
                    AS_CUA,
                    AS_PRIMER_NOMBRE,
                    AS_SEGUNDO_NOMBRE,
                    AS_PRIMER_APELLIDO,
                    AS_SEGUNDO_APELLIDO,
                    AS_APELLIDO_CASADA,
                    cas_registrado,
                    AS_FECHA_FALLECIMIENTO,
                    FECHA_NOTIFICACION,
                    FECHA_PUBLICACION,
                    cas_cod_id,
                    COALESCE(FECHA_PUBLICACION::timestamp, FECHA_NOTIFICACION) AS gec,
                    EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp, FECHA_PUBLICACION::timestamp))) AS dias_transcurridos,
                    -- Nueva columna: Vencimiento
                    CASE
                        WHEN EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp,  FECHA_PUBLICACION::timestamp ))) > 30
                        THEN 'Vencido'
                        ELSE 'En plazo'
                    END AS estado_vencimiento
                FROM
                    casos_con_fechas
                WHERE
                    EXTRACT(DAY FROM age(CURRENT_DATE, COALESCE(NOW()::timestamp, FECHA_PUBLICACION)))<>0 ; ");
            return response()->json(['data' =>  $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
}

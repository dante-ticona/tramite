<?php

namespace App\Http\Controllers;

use App\Enums\ActividadFinalizadoEnum;
use Exception;
use Illuminate\Http\Request;
use TCPDF;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\servicioGestora\fallecidosSslpController;
use GuzzleHttp\Client;
use App\Http\Controllers\servicioGestora\estructuraFamiliarPrestacionesController;


class ApiVySController extends Controller
{
    public function notificaionesMC(Request $request, $cas_id)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT (select count(*)
            from _gp_documentos
            where doc_cas_id = c.cas_id and alerta = 'R') as contar
            FROM rmx_vys_casos c where cas_id = ?", [$cas_id]);

            $count = count($data) > 0 ? $data[0]->contar : 0;

            return response()->json(["count" => $count, "success" => $success]);
        } catch (Exception $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarNodosProcesos()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_nodos_procesos np
                                    inner join rmx_vys_nodos n on n.nodo_id = np.nopr_nodo_id
                                    inner join rmx_vys_procesos p on p.prc_id = np.nopr_prc_id
                                    where np.nopr_estado <> 'X'
                                    and n.nodo_estado <> 'X'
                                    and p.prc_estado <> 'X'
                                    order by nopr_registrado");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarNodosProcesos(Request $request)
    {
        $nopr_nodo_id = $request["nopr_nodo_id"];
        $nopr_prc_id = $request["nopr_prc_id"];
        $nopr_usr_id = $request["nopr_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_nodos_procesos (nopr_nodo_id, nopr_prc_id, nopr_usr_id) values
                                    ('$nopr_nodo_id', '$nopr_prc_id', '$nopr_usr_id') ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function actualizarNodosProcesos(Request $request)
    {
        $nopr_id = $request["nopr_id"];
        $nopr_nodo_id = $request["nopr_nodo_id"];
        $nopr_prc_id = $request["nopr_prc_id"];
        $nopr_usr_id = $request["nopr_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos_procesos
                                set nopr_nodo_id = '$nopr_nodo_id',
                                nopr_prc_id = '$nopr_prc_id',
                                nopr_usr_id = $nopr_usr_id
                                where nopr_id = $nopr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarNodosProcesos(Request $request)
    {
        $nopr_id = $request["nopr_id"];
        $nopr_usr_id = $request["nopr_usr_id"];
        $nopr_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos_procesos
                                    set nopr_estado = '$nopr_estado',
                                    nopr_usr_id = '$nopr_usr_id'
                                where nopr_id = $nopr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarNodos()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_nodos
                                    where nodo_estado <> 'X'
                                    order by nodo_codigo");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarNodosUsuario(Request $request)
    {
        $usr_id = $request["usr_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                        from rmx_usr_nodos run
                        inner join rmx_vys_nodos rvn on run.usn_nodo_id = rvn.nodo_id
                        where (run.usn_estado <> 'X' AND run.usn_estado <> 'E' ) and  nodo_estado <> 'X' and run.usn_user_id = $usr_id
                        order by nodo_codigo");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarNodos(Request $request)
    {
        $nodo_codigo = $request["nodo_codigo"];
        $nodo_descripcion = $request["nodo_descripcion"];
        $nodo_padre_id = $request["nodo_padre_id"];
        $nodo_usr_id = $request["nodo_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "select * from sp_insertar_nodos(?, ?, ?, ?)",
                array($nodo_codigo, $nodo_descripcion, $nodo_padre_id, 1)
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarNodos(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_codigo = $request["nodo_codigo"];
        $nodo_descripcion = $request["nodo_descripcion"];
        $nodo_padre_id = $request["nodo_padre_id"];
        $nodo_usr_id = $request["nodo_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos
                            set     nodo_codigo = '$nodo_codigo',
                                    nodo_descripcion = '$nodo_descripcion',
                                    nodo_padre_id = '$nodo_padre_id',
                                    nodo_usr_id = $nodo_usr_id
                                where nodo_id = $nodo_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarNodos(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos
                                    set nodo_estado = '$nodo_estado'
                                where nodo_id = $nodo_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTFormularios()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_tipos_formulario
                                    where tfrm_estado = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTFormularios(Request $request)
    {
        $tfrm_descripcion = $request["tfrm_descripcion"];
        $tfrm_usr_id = $request["tfrm_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_tipos_formulario (tfrm_descripcion, tfrm_usr_id) values
                                    ('$tfrm_descripcion', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTFormularios(Request $request)
    {
        $tfrm_id = $request["tfrm_id"];
        $tfrm_descripcion = $request["tfrm_descripcion"];
        $tfrm_usr_id = $request["tfrm_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_formulario
                            set     tfrm_descripcion = '$tfrm_descripcion',
                                    tfrm_usr_id = $tfrm_usr_id
                                where tfrm_id = $tfrm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTFormularios(Request $request)
    {
        $tfrm_id = $request["tfrm_id"];
        $tfrm_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_formulario
                                    set tfrm_estado = '$tfrm_estado'
                                where tfrm_id = $tfrm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTActividades()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_tipos_actividad
                                    where tact_estado = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTActividades(Request $request)
    {
        $tact_codigo = $request["tact_codigo"];
        $tact_descripcion = $request["tact_descripcion"];
        $tact_icono = $request["tact_icono"];
        $tact_usr_id = $request["tact_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_tipos_actividad (tact_codigo, tact_descripcion, tact_icono, tact_usr_id) values
                                    ('$tact_codigo', '$tact_descripcion', '$tact_icono', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTActividades(Request $request)
    {
        $tact_id = $request["tact_id"];
        $tact_codigo = $request["tact_codigo"];
        $tact_descripcion = $request["tact_descripcion"];
        $tact_icono = $request["tact_icono"];
        $tact_usr_id = $request["tact_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_actividad
                            set     tact_codigo = '$tact_codigo',
                                    tact_descripcion = '$tact_descripcion',
                                    tact_icono = '$tact_icono',
                                    tact_usr_id = $tact_usr_id
                                where tact_id = $tact_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTActividades(Request $request)
    {
        $tact_id = $request["tact_id"];
        $tact_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_actividad
                                    set tact_estado = '$tact_estado'
                                where tact_id = $tact_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarCatalogos()
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_catalogos
                                    where cat_estado = 'A'
                                    order by cat_codigo ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarCatalogos(Request $request)
    {
        $cat_codigo = $request["cat_codigo"];
        $cat_descripcion = $request["cat_descripcion"];
        $cat_padre_id = $request["cat_padre_id"];
        $cat_usr_id = $request["cat_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_catalogos (cat_codigo, cat_descripcion, cat_padre_id, cat_usr_id) values
                                    ('$cat_codigo', '$cat_descripcion', '$cat_padre_id', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarCatalogos(Request $request)
    {
        $cat_id = $request["cat_id"];
        $cat_codigo = $request["cat_codigo"];
        $cat_descripcion = $request["cat_descripcion"];
        $cat_padre_id = $request["cat_padre_id"];
        $cat_usr_id = $request["cat_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_catalogos
                            set     cat_codigo = '$cat_codigo',
                                    cat_descripcion = '$cat_descripcion',
                                    cat_padre_id = '$cat_padre_id',
                                    cat_usr_id = $cat_usr_id
                                where cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarCatalogos(Request $request)
    {
        $cat_id = $request["cat_id"];
        $cat_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_catalogos
                                    set cat_estado = '$cat_estado'
                                where cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesos(Request $request)
    {
        $cat_id = $request["cat_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X'
                                        and prc_cat_id = $cat_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosTodos(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X'
                                    order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosXUsrId(Request $request)
    {
        $usr_id = $request["usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
            from rmx_vys_procesos p
            inner join rmx_vys_catalogos on cat_id = p.prc_cat_id

            inner join rmx_vys_nodos_procesos np on np.nopr_prc_id = p.prc_id
            inner join rmx_usr_nodos usn on usn.usn_nodo_id = np.nopr_nodo_id

            where prc_estado <> 'X'
              and np.nopr_estado <> 'X'
              and usn.usn_estado <> 'X'
              and usn.usn_user_id = $usr_id
            order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getListarProcesos()
    {

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("
                        select prc_id, prc_data->>'prc_codigo' as codigo ,prc_data->>'prc_descripcion' as descripcion
                        from rmx_vys_procesos p
                        where prc_estado <> 'X'
                 ");

            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }

    public function obtenerDepartamento(Request $request)
    {
        $id_agencia = $request["id_agencia"];
        $id_regional = $request["id_regional"];
        $id_departamento = $request["id_departamento"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select (select descripcion_doc
            from gp_regional
            where  id_sip_regional = $id_regional ) as regional,
            (select descripcion_dep
            from gp_departamento
            where  id_sip_departamento = $id_departamento ) as departamento,
            (select descripcion_agencia
            from gp_agencia
            where  id_sip_agencia = $id_agencia ) as agencia");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesoXPrcId(Request $request)
    {
        $prc_id = $request["prc_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_procesos
                                    inner join rmx_vys_catalogos on cat_id = prc_cat_id
                                    where prc_estado <> 'X'
                                    and prc_id = $prc_id
                                    order by cat_codigo, prc_data->>'prc_codigo' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarProcesosXNodoId(Request $request)
    {
        $nodo_id = $request["nodo_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select u.name, p.prc_data->>'prc_codigo' as prc_codigo, p.prc_data->>'prc_descripcion' as prc_descripcion,
                                    a.act_data->>'act_descripcion' as act_descripcion, count(*) as conteo
                                    from rmx_vys_casos c
                                    inner join rmx_vys_nodos n on n.nodo_id = c.cas_nodo_id
                                    inner join rmx_vys_actividades a on a.act_id = c.cas_act_id
                                    inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                                    inner join users u on u.id = c.cas_usr_id
                                    where cas_estado <> 'X'
                                    and p.prc_estado <> 'X'
                                    and a.act_estado <> 'X'
                                    and n.nodo_estado <> 'X'
                                    and n.nodo_id = $nodo_id
                                    group by u.name, p.prc_data->>'prc_codigo', p.prc_data->>'prc_descripcion',
                                    a.act_data->>'act_descripcion'
                                    order by u.name, p.prc_data->>'prc_codigo', p.prc_data->>'prc_descripcion',
                                    a.act_data->>'act_descripcion' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarProcesos(Request $request)
    {
        $prc_cat_id = $request["prc_cat_id"];
        $prc_data = $request["prc_data"];
        $prc_data_campos_clave = $request["prc_data_campos_clave"];
        $prc_modelo = $request["prc_modelo"];
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $sql = "insert into rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_modelo, prc_usr_id) values
                        ($prc_cat_id, '$prc_data'::json, '$prc_data_campos_clave'::json, '$prc_modelo'::json, $prc_usr_id) ";
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $data = \DB::select($sql);
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarProcesos(Request $request)
    {
        $prc_id = $request["prc_id"];
        $prc_cat_id = $request["prc_cat_id"];
        $prc_data = $request["prc_data"];
        $prc_data_campos_clave = $request["prc_data_campos_clave"];
        $prc_modelo = $request["prc_modelo"];
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $sql = "update rmx_vys_procesos
                    set prc_cat_id = $prc_cat_id,
                        prc_data = '$prc_data'::json,
                        prc_data_campos_clave = '$prc_data_campos_clave'::json,
                        prc_modelo = '$prc_modelo'::json,
                        prc_usr_id = $prc_usr_id
                    where prc_id = $prc_id ";
            $data = \DB::select($sql);
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarProcesos(Request $request)
    {
        $prc_id = $request["prc_id"];
        $prc_usr_id = $request["prc_usr_id"];
        $prc_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_procesos
                                    set prc_estado = '$prc_estado',
                                    prc_usr_id = $prc_usr_id
                                where prc_id = $prc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    /*  inner join gp_estados_avance on est_id = act_est_id */
    public function listarActividades(Request $request)
    {
        $prc_id = $request["prc_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                from rmx_vys_actividades
                                    inner join rmx_vys_procesos on prc_id = act_prc_id
                                    inner join rmx_vys_tipos_actividad on tact_id = act_tact_id
                                    inner join rmx_vys_nodos on nodo_id = act_nodo_id
                                    left join gp_estados_avance on est_id = act_est_id
                                where act_estado = 'A'
                                    and act_prc_id = $prc_id
                                order by (act_data->>'act_orden')::integer");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarActividades(Request $request)
    {
        $act_prc_id = $request["act_prc_id"];
        $act_tact_id = $request["act_tact_id"];
        $act_nodo_id = $request["act_nodo_id"];
        $act_est_id = $request["act_est_id"];
        $act_data = $request["act_data"];
        $act_data_reglas = $request["act_data_reglas"];
        $act_data_form = $request["act_data_form"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_est_id, act_data, act_data_reglas, act_data_form, act_usr_id) values
                                    ($act_prc_id, $act_tact_id, $act_nodo_id, '$act_est_id', '$act_data'::json, '$act_data_reglas'::json, '$act_data_form'::json, 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarActividades(Request $request)
    {
        $act_id = $request["act_id"];
        $act_prc_id = $request["act_prc_id"];
        $act_tact_id = $request["act_tact_id"];
        $act_nodo_id = $request["act_nodo_id"];
        $act_est_id = $request["act_est_id"];
        $act_data = $request["act_data"];
        $act_data_reglas = $request["act_data_reglas"];
        $act_data_form = $request["act_data_form"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_actividades
                            set     act_prc_id = $act_prc_id,
                                    act_tact_id = $act_tact_id,
                                    act_nodo_id = $act_nodo_id,
                                    act_est_id = $act_est_id,
                                    act_data = '$act_data'::json,
                                    act_data_reglas = '$act_data_reglas'::json,
                                    act_data_form = '$act_data_form'::json,
                                    act_usr_id = $act_usr_id
                                where act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarActividades(Request $request)
    {
        $act_id = $request["act_id"];
        $act_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_actividades
                                    set act_estado = '$act_estado'
                                where act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarActividadXPrcIdXOrden(Request $request)
    {
        $prc_id = $request["prc_id"];
        $act_orden = $request["act_orden"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_actividades
                                    where act_estado = 'A'
                                      and act_prc_id = $prc_id
                                      and act_data->>'act_orden' = '$act_orden'
                                    order by act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarCasos(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $act_id = $request->query('act_id');
        $act_id = $request->has("act_id") ? $request->query('act_id') : null;

        $dia = $request->has("dia") ? $request->query('dia') : null;

        $condicionActId = $act_id ? "AND c.cas_act_id = $act_id" : "";

        $desde = ($PaginaActual - 1) * $RegistrosXPagina;

        try {
            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros --cas_cod_id,cas_id,tact_codigo,cas_estado,cas_usr_id,cas_padre_id,act_data,prc_data,cas_registrado,cas_data,act_data_reglas,prc_id,act_id
                FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T' and c.cas_nodo_id=$nodo_id $condicionActId) OR
                (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))"
            );

            $act_finalizado = ActividadFinalizadoEnum::FINALIZADO->value;


            $sss = "SELECT ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, *
                ,(
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from rmx_vys_casos rvc
                    where rvc.cas_dependiente_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T' and c.cas_nodo_id=$nodo_id $condicionActId) OR
                (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))
                ORDER BY c.cas_modificado desc
                LIMIT $RegistrosXPagina OFFSET $desde";
            // dd($sss);


            $data = \DB::select(
                "SELECT ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, *
                ,(
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c
                INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T' and c.cas_nodo_id=$nodo_id $condicionActId) OR
                (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))
                AND (
                    CASE
                        WHEN '$dia' = 'A' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) BETWEEN 7 AND 9
                        WHEN '$dia' = 'B' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) >= 10
                        ELSE TRUE
                    END
                )
                ORDER BY c.cas_modificado desc
                LIMIT $RegistrosXPagina OFFSET $desde"
            );

            $actividades = \DB::select("SELECT act_data->>'act_descripcion' as descripcion ,
            act_tipo_masivo as tipo_masivo ,act_data->>'act_siguiente' as siguiente,
            act_data->>'act_orden' as orden, act_prc_id , act_nodo_id as nodo_id , act_id,
            p.prc_data->>'prc_codigo' as codigop
            from rmx_vys_actividades a
            inner join rmx_vys_procesos p on a.act_prc_id = p.prc_id
            WHERE a.act_nodo_id = ? AND a.act_estado = 'A'", [$nodo_id]);

            return response()->json(['data' => $data, 'actividades' => $actividades, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function casosDerivarMultiple(Request $request)
    {

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        $casIds = $request["cas_id"];
        $usr_id = $request["usr_id"];
        $act_id = $request["act_id"];
        $nodo_id = $request["nodo_id"];
        //FIXME: 2024-12-11 remove the following throw
        // throw new Exception("throw error, derivacio-masiva");
        try {
            $data = \DB::select(
                "UPDATE rmx_vys_casos c
                    SET cas_act_id = $act_id,
                    cas_nodo_id = $nodo_id,
                    cas_usr_id = $usr_id
                WHERE cas_id in ($casIds)"
            );
            $data = \DB::select(
                "INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id, htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores,
                                htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id, htc_cas_correlativo, htc_cas_padre_id)
                        SELECT cas_id, cas_act_id, cas_nodo_id, cas_data, cas_data_valores, now(), cas_modificado, cas_usr_id, cas_estado, cas_cod_id, cas_correlativo, cas_padre_id
                        FROM rmx_vys_casos
                    WHERE cas_id in ($casIds)"
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosUsuario(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {

            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                --JOIN rmx_usr_nodos n ON c.cas_usr_id = n.usn_user_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
                WHERE c.cas_estado = 'T' --(c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                --AND n.usn_estado <> 'X'
                AND c.cas_usr_id = $usr_id
				AND c.cas_nodo_id = $nodo_id"
            );
            $act_finalizado = ActividadFinalizadoEnum::FINALIZADO->value;
            $sss = "SELECT  ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, * --cas_cod_id,cas_id,tact_codigo,cas_estado,cas_usr_id,cas_padre_id,act_data,prc_data,cas_registrado,cas_data,act_data_reglas,prc_id,act_id
                ,(
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                --JOIN rmx_usr_nodos n ON c.cas_usr_id = n.usn_user_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE c.cas_estado = 'T' --(c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                --AND n.usn_estado <> 'X'
                AND c.cas_usr_id = $usr_id
				AND c.cas_nodo_id = $nodo_id
                ORDER BY c.cas_modificado desc
                LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina";
            //dd($sss);
            $data = \DB::select(
                "SELECT  ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, * --cas_cod_id,cas_id,tact_codigo,cas_estado,cas_usr_id,cas_padre_id,act_data,prc_data,cas_registrado,cas_data,act_data_reglas,prc_id,act_id
                ,(
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from rmx_vys_casos rvc
                    where rvc.cas_dependiente_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                --JOIN rmx_usr_nodos n ON c.cas_usr_id = n.usn_user_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE c.cas_estado = 'T' --(c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                --AND n.usn_estado <> 'X'
                AND c.cas_usr_id = $usr_id
				AND c.cas_nodo_id = $nodo_id
                ORDER BY c.cas_modificado desc
                LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina"
            );

            $actividades = \DB::select("SELECT act_data->>'act_descripcion' as descripcion ,
            act_tipo_masivo as tipo_masivo ,act_data->>'act_siguiente' as siguiente,
            act_data->>'act_orden' as orden, act_prc_id , act_nodo_id as nodo_id , act_id,
            p.prc_data->>'prc_codigo' as codigop
            from rmx_vys_actividades a
            inner join rmx_vys_procesos p on a.act_prc_id = p.prc_id
            WHERE a.act_nodo_id = ? AND a.act_estado = 'A'", [$nodo_id]);


            return response()->json(['data' => $data, 'actividades' => $actividades, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosXNodo(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                --JOIN rmx_usr_nodos n ON c.cas_usr_id = n.usn_user_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
				AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T' and c.cas_nodo_id=$nodo_id) OR
                (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))"
            );
            $act_finalizado = ActividadFinalizadoEnum::FINALIZADO->value;
            $data = \DB::select(
                // --cas_cod_id, cas_id, tact_codigo, cas_estado, cas_usr_id, cas_padre_id, act_data, prc_data, cas_registrado, cas_data, act_data_reglas, prc_id, act_id
                "SELECT ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, *
                , (
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c
                INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
				INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER join rmx_vys_nodos on nodo_id = a.act_nodo_id
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
				AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T' and c.cas_nodo_id=$nodo_id) OR
                (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))
				ORDER BY c.cas_modificado desc
                LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina"
            );

            $actividades = \DB::select("SELECT act_data->>'act_descripcion' as descripcion ,
             act_tipo_masivo as tipo_masivo ,act_data->>'act_siguiente' as siguiente,
            act_data->>'act_orden' as orden, act_prc_id , act_nodo_id as nodo_id , act_id,
            p.prc_data->>'prc_codigo' as codigop
            from rmx_vys_actividades a
            inner join rmx_vys_procesos p on a.act_prc_id = p.prc_id
            WHERE a.act_nodo_id = ? AND a.act_estado = 'A'", [$nodo_id]);

            return response()->json(['data' => $data, 'actividades' => $actividades, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosXNodoXUsuario(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $act_id = $request->has("act_id") ? $request["act_id"] : null;
        $dia = $request->has("dia") ? $request["dia"] : null;

        $condicionActId = $act_id ? "AND c.cas_act_id = $act_id" : "";

        try {
            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros -- cas_cod_id,cas_id,tact_codigo,cas_estado,cas_usr_id,cas_padre_id,act_data,prc_data,cas_registrado,cas_data,act_data_reglas,prc_id,act_id
                FROM rmx_vys_casos c
                INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER JOIN rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER JOIN rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado = 'T' or c.cas_estado = 'A')
                AND ((c.cas_usr_id = ? and c.cas_nodo_id = ? $condicionActId) OR
                (c.cas_nodo_id = ? and c.cas_estado = 'A'))",
                [$usr_id, $nodo_id, $nodo_id]
            );
            $act_finalizado = ActividadFinalizadoEnum::FINALIZADO->value;
            $data = \DB::select(
                "SELECT  ROW_NUMBER() OVER (ORDER BY c.cas_modificado desc) AS row_index, * -- cas_cod_id,cas_id,tact_codigo,cas_estado,cas_usr_id,cas_padre_id,act_data,prc_data,cas_registrado,cas_data,act_data_reglas,prc_id,act_id
                , (
                    SELECT COUNT(*)
                    FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                    WHERE (
                        EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                        AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                    )
                ) AS dias_habiles_transcurridos
                ,CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM rmx_vys_casos AS child
                        WHERE child.cas_dependiente_id = c.cas_id
                    ) THEN 'true'
                    ELSE 'false'
                END AS caso_hijo_tipo_caso_heredaro,
                CASE
                    WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                    THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                            and rvc.cas_act_id <> (SELECT rva.act_id
                                        FROM public.rmx_vys_actividades rva
                                        where rva.act_prc_id = (select rva1.act_prc_id
                                                                from rmx_vys_actividades rva1
                                                                where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                            and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                    ELSE 0
                    END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c
                INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                INNER JOIN rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                INNER JOIN rmx_vys_nodos on nodo_id = a.act_nodo_id
                WHERE (c.cas_estado = 'T' or c.cas_estado = 'A')
                AND ((c.cas_usr_id = ? and c.cas_nodo_id = ? $condicionActId) OR
                (c.cas_nodo_id = ? and c.cas_estado = 'A'))
                AND (
                    CASE
                        WHEN '$dia' = 'A' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) BETWEEN 7 AND 9
                        WHEN '$dia' = 'B' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) >= 10
                        ELSE TRUE
                    END
                )
				ORDER BY c.cas_modificado desc
                LIMIT ? OFFSET ?",
                [$usr_id, $nodo_id, $nodo_id, $RegistrosXPagina, ($PaginaActual - 1) * $RegistrosXPagina]
            );

            $actividades = \DB::select("SELECT act_data->>'act_descripcion' as descripcion ,
            act_tipo_masivo as tipo_masivo ,act_data->>'act_siguiente' as siguiente,
            act_data->>'act_orden' as orden, act_prc_id , act_nodo_id as nodo_id , act_id,
            p.prc_data->>'prc_codigo' as codigop
            from rmx_vys_actividades a
            inner join rmx_vys_procesos p on a.act_prc_id = p.prc_id
            WHERE a.act_nodo_id = ? AND a.act_estado = 'A'", [$nodo_id]);

            return response()->json(['data' => $data, 'actividades' => $actividades, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function busquedaCasosXNodoXUsuario(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $buscarsql = $request->query('search');
        $dia = $request->query('dia');

        try {
            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros
                from rmx_vys_casos c
                inner join rmx_vys_actividades a  on a.act_id = c.cas_act_id
                inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                inner join rmx_vys_tipos_actividad t on t.tact_id = a.act_tact_id
                inner join rmx_vys_nodos n on n.nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
                WHERE c.cas_estado = 'T'
                AND c.cas_usr_id = $usr_id and c.cas_nodo_id=$nodo_id
                AND (
                    cas_cod_id LIKE ? OR
                    cas_data ->> 'AS_CI' LIKE ? OR
                    cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                    cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                    cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                    cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                    p.prc_data::json->>'prc_codigo' LIKE ? OR
                    p.prc_data::json->>'prc_version' LIKE ? OR
                    p.prc_data::json->>'prc_descripcion' LIKE ?
                    )",

                ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
            );

            $data = \DB::select(
                "SELECT c.*, a.*,
                        p.prc_id, p.prc_cat_id, p.prc_data, p.prc_data::json->>'prc_codigo' as prc_codigo,
                        p.prc_data::json->>'prc_version' as prc_version, p.prc_data::json->>'prc_descripcion' as prc_descripcion,
                        p.prc_data_campos_clave, p.prc_modelo, p.prc_registrado, p.prc_modificado, p.prc_usr_id, p.prc_estado,
                        t.*, n.*, g.*
                        , (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) AS dias_habiles_transcurridos,
                        CASE
                            WHEN EXISTS (
                                SELECT 1
                                FROM rmx_vys_casos AS child
                                WHERE child.cas_dependiente_id = c.cas_id
                            ) THEN 'true'
                            ELSE 'false'
                        END AS caso_hijo_tipo_caso_heredaro,
                        CASE
                            WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                            THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                                    and rvc.cas_act_id <> (SELECT rva.act_id
                                                FROM public.rmx_vys_actividades rva
                                                where rva.act_prc_id = (select rva1.act_prc_id
                                                                        from rmx_vys_actividades rva1
                                                                        where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                        and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                                    and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                            ELSE 0
                            END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c
                inner join rmx_vys_actividades a  on a.act_id = c.cas_act_id
                inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                inner join rmx_vys_tipos_actividad t on t.tact_id = a.act_tact_id
                inner join rmx_vys_nodos n on n.nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
                WHERE c.cas_estado = 'T'
                AND c.cas_usr_id = $usr_id and c.cas_nodo_id=$nodo_id
                AND (
                    cas_cod_id LIKE ? OR
                    cas_data ->> 'AS_CI' LIKE ? OR
                    cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                    cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                    cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                    cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                    p.prc_data::json->>'prc_codigo' LIKE ? OR
                    p.prc_data::json->>'prc_version' LIKE ? OR
                    p.prc_data::json->>'prc_descripcion' LIKE ?
                )
                AND (
                    CASE
                        WHEN '$dia' = 'A' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) BETWEEN 7 AND 9
                        WHEN '$dia' = 'B' THEN (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) >= 10
                        ELSE TRUE
                    END
                )
                ORDER BY c.cas_modificado desc",
                ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
            );

            $formattedData = [];

            if (empty($data)) {
                return response()->json(["data" => [], "success" => $success, "totalRegistros" => $dataTotalRegistros]);
            }

            foreach ($data as $fila) {
                $actData = json_decode($fila->act_data);
                //$casData = json_decode($fila->cas_data);

                $formattedData[] = [
                    'cas_act_id' => $fila->cas_act_id,
                    'cas_cod_id' => $fila->cas_cod_id,
                    'cas_id' => $fila->cas_id,
                    'tact_codigo' => $fila->tact_codigo,
                    'cas_estado' => $fila->cas_estado,
                    'cas_usr_id' => $fila->cas_usr_id,
                    'cas_padre_id' => $fila->cas_padre_id,
                    'act_data' => $actData,
                    'prc_data' => json_decode($fila->prc_data),
                    'cas_registrado' => $fila->cas_registrado,
                    /* 'cas_data' => $fila->cas_data, */
                    'act_data_reglas' => $fila->act_data_reglas,
                    'prc_id' => $fila->prc_id,
                    'act_id' => $fila->act_id,
                    'cas_data' => json_decode($fila->cas_data),
                    'cas_data_valores' => json_decode($fila->cas_data_valores),
                    'dias_habiles_transcurridos' => $fila->dias_habiles_transcurridos,
                    'caso_hijo_tipo_caso_heredaro' => $fila->caso_hijo_tipo_caso_heredaro,
                    'cantidad_casos_asociados_activos' => $fila->cantidad_casos_asociados_activos,
                    //'cas_nombre_caso' => isset($casData->cas_nombre_caso) ? $casData->cas_nombre_caso : null,
                ];
            }

            return response()->json(["data" => $formattedData, "success" => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function busquedaCasosXNodo(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $usr_id = $request["usr_id"];
        $nodo_id = $request["nodo_id"];

        $buscarsql = $request->query('search');

        $dia = $request->query('dia');

        try {
            $dataTotalRegistros = \DB::select(
                "SELECT count(*) as total_registros
                from rmx_vys_casos c
                inner join rmx_vys_actividades a  on a.act_id = c.cas_act_id
                inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                inner join rmx_vys_tipos_actividad t on t.tact_id = a.act_tact_id
                inner join rmx_vys_nodos n on n.nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T') OR (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))
                AND (
                        cas_cod_id LIKE ? OR
                        cas_data ->> 'AS_CI' LIKE ? OR
                        cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                        cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                        cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                        cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                        p.prc_data::json->>'prc_codigo' LIKE ? OR
                        p.prc_data::json->>'prc_version' LIKE ? OR
                        p.prc_data::json->>'prc_descripcion' LIKE ?
                    )",
                ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
            );

            $data = \DB::select(
                "SELECT c.*, a.*,
                        p.prc_id, p.prc_cat_id, p.prc_data, p.prc_data::json->>'prc_codigo' as prc_codigo, p.prc_data::json->>'prc_version' as prc_version, p.prc_data::json->>'prc_descripcion' as prc_descripcion, p.prc_data_campos_clave, p.prc_modelo, p.prc_registrado, p.prc_modificado, p.prc_usr_id, p.prc_estado,
                        t.*,
                        n.*,
                        g.*,
                        (
                            SELECT COUNT(*)
                            FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                            WHERE (
                                EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                            )
                        ) AS dias_habiles_transcurridos
                        ,CASE
                            WHEN EXISTS (
                                SELECT 1
                                FROM rmx_vys_casos AS child
                                WHERE child.cas_dependiente_id = c.cas_id
                            ) THEN 'true'
                            ELSE 'false'
                        END AS caso_hijo_tipo_caso_heredaro,
                        CASE
                            WHEN a.act_data->>'act_bloqueo_legal' = 'SI'
                            THEN
                (select count(*) as binded_cases
                    from lg_casos_validaciones lg
                    inner join rmx_vys_casos rvc on rvc.cas_id = lg.lcv_caso_id
                    where rvc.cas_id = c.cas_id
                                    and rvc.cas_act_id <> (SELECT rva.act_id
                                                FROM public.rmx_vys_actividades rva
                                                where rva.act_prc_id = (select rva1.act_prc_id
                                                                        from rmx_vys_actividades rva1
                                                                        where rva1.act_id = rvc.cas_act_id and rva1.act_estado = 'A')
                                                        and  rva.act_data @> '{\"act_orden\": \"200\"}' and rva.act_estado = 'A')
                                    and (rvc.cas_estado = 'A' or rvc.cas_estado = 'T'))
                            ELSE 0
                            END  AS cantidad_casos_asociados_activos,
                (
                    SELECT u.nom_usuario
                    FROM rmx_vys_historico_casos h2
                    INNER JOIN users u ON h2.htc_cas_usr_id = u.id
                    WHERE h2.htc_cas_cod_id = c.cas_cod_id
                    ORDER BY h2.htc_id DESC
                    OFFSET 1 LIMIT 1
                ) AS antepenultimo_usuario

                FROM rmx_vys_casos c
                inner join rmx_vys_actividades a  on a.act_id = c.cas_act_id
                inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
                inner join rmx_vys_tipos_actividad t on t.tact_id = a.act_tact_id
                inner join rmx_vys_nodos n on n.nodo_id = a.act_nodo_id
                INNER JOIN gp_estados_avance g on g.est_id = a.act_est_id
                WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H' AND c.cas_estado <> 'W')
                AND ((c.cas_usr_id = $usr_id and  c.cas_estado = 'T') OR (c.cas_nodo_id=$nodo_id and  c.cas_estado = 'A'))
                AND (
                        cas_cod_id LIKE ? OR
                        cas_data ->> 'AS_CI' LIKE ? OR
                        cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                        cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                        cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                        cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                        p.prc_data::json->>'prc_codigo' LIKE ? OR
                        p.prc_data::json->>'prc_version' LIKE ? OR
                        p.prc_data::json->>'prc_descripcion' LIKE ?
                    )
                    AND (
                        CASE
                            WHEN '$dia' = 'A' THEN (
                                SELECT COUNT(*)
                                FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                                WHERE (
                                    EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                    AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                                )
                            ) BETWEEN 7 AND 9
                            WHEN '$dia' = 'B' THEN (
                                SELECT COUNT(*)
                                FROM generate_series(CAST(c.cas_data->>'cas_registrado' AS DATE), CURRENT_DATE, '1 day') AS fecha
                                WHERE (
                                    EXTRACT(DOW FROM fecha) NOT IN (0, 6)
                                    AND fecha NOT IN (SELECT fecha FROM dias_no_habiles)
                                )
                            ) >= 10
                            ELSE TRUE
                        END
                    )
                ORDER BY c.cas_modificado desc",
                ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
            );

            $formattedData = [];
            foreach ($data as $fila) {
                $actData = json_decode($fila->act_data);
                //$casData = json_decode($fila->cas_data);

                $formattedData[] = [
                    'cas_act_id' => $fila->cas_act_id,
                    'cas_cod_id' => $fila->cas_cod_id,
                    'cas_id' => $fila->cas_id,
                    'tact_codigo' => $fila->tact_codigo,
                    'cas_estado' => $fila->cas_estado,
                    'cas_usr_id' => $fila->cas_usr_id,
                    'cas_padre_id' => $fila->cas_padre_id,
                    'act_data' => $actData,
                    'prc_data' => json_decode($fila->prc_data),
                    'cas_registrado' => $fila->cas_registrado,
                    /* 'cas_data' => $fila->cas_data, */
                    'act_data_reglas' => $fila->act_data_reglas,
                    'prc_id' => $fila->prc_id,
                    'act_id' => $fila->act_id,
                    'cas_data' => json_decode($fila->cas_data),
                    'cas_data_valores' => json_decode($fila->cas_data_valores),
                    'dias_habiles_transcurridos' => $fila->dias_habiles_transcurridos,
                    'caso_hijo_tipo_caso_heredaro' => $fila->caso_hijo_tipo_caso_heredaro,
                    'cantidad_casos_asociados_activos' => $fila->cantidad_casos_asociados_activos,
                    //'cas_nombre_caso' => isset($casData->cas_nombre_caso) ? $casData->cas_nombre_caso : null,
                ];
            }

            return response()->json(['data' => $formattedData, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (\Exception $e) {  // Corrected exception handling
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosArchivados(Request $request)
    {
        $usr_id = $request["usr_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT *
				FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
				WHERE c.cas_estado = 'H'
					AND n.usn_user_id = $usr_id
				ORDER BY c.cas_modificado desc");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function historicoCasos(Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');
        $cas_id = $request["cas_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $cas_id");
            $cas_id = $data1[0]->caso_id;

            $sql_legal = "SELECT cas_id from rmx_vys_casos rvc where rvc.cas_dependiente_id = $cas_id";
            $data1 = \DB::select($sql_legal);

            $cas_ids = array_map(function ($item) {
                return $item->cas_id;
            }, $data1);

            $cas_ids[] = $cas_id;
            $cas_ids_str = implode(',', $cas_ids);

            $sql = "SELECT *
                    from rmx_vys_historico_casos
                    inner join rmx_vys_actividades a on act_id = htc_cas_act_id
                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                    inner join users on id = htc_cas_usr_id
                    inner join gp_estados_avance g on g.est_id = a.act_est_id
                    where htc_cas_id = $cas_id or (htc_cas_id IN ($cas_ids_str) AND htc_cas_act_id = 261) order by htc_id asc";

            $data = \DB::select($sql);

            $ip = request()->ip();
            $data_json = json_encode([], 0);
            $data_log = \DB::select(
                "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                array($sql, $data_json, $usuario, $pro, $tramite, $ip)
            );

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listadoLegal(Request $request)
    {
        $cas_id = $request["cas_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select(
                "SELECT rmx_vys_casos.*, rmx_vys_historico_casos.*
                    FROM rmx_vys_casos
                    JOIN rmx_vys_historico_casos ON rmx_vys_historico_casos.htc_cas_id = rmx_vys_casos.cas_id
                    WHERE rmx_vys_casos.cas_dependiente_id = :cas_id
                    AND rmx_vys_casos.cas_cod_id LIKE :cas_cod_id
                    AND rmx_vys_casos.cas_estado <> 'X'
                    ORDER BY rmx_vys_historico_casos.htc_id DESC LIMIT 1",
                ['cas_id' => $cas_id, 'cas_cod_id' => '%LEGAL%']
            );

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function nroCorrelativo(Request $request)
    {
        $cas_cod_id = $request["cas_cod_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error ', 'code' => 500);
        try {
            $data = \DB::select("SELECT
                                    cas_correlativo
                                    FROM rmx_vys_casos
                                    WHERE cas_padre_id = 0 and cas_cod_id like '$cas_cod_id'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function candadito(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $cas_id");
            $cas_id = $data1[0]->caso_id;

            $data = \DB::select("SELECT *
            from rmx_vys_casos rvc
            inner join rmx_vys_actividades on act_id = cas_act_id
            inner join rmx_vys_nodos on nodo_id = cas_nodo_id
            inner join users on id = cas_usr_id
            where cas_padre_id = $cas_id and cas_estado <>'X' order by cas_id asc");

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function candidato_v2(Request $request)
    {
        $id_caso = $request["id_caso"];
        $cas_usr_id = $request["cas_usr_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $roles = '';
            $data1 = \DB::select("select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
            from rmx_vys_casos where cas_id = $id_caso");
            $cas_id = $data1[0]->caso_id;

            $data2 = \DB::select("select cas_cod_id from rmx_vys_casos where cas_id = $cas_id");

            $cas_cod_id = $data2[0]->cas_cod_id;

            $data_usuario = \DB::select("select * from users
                                        inner join rmx_usr_nodos on id = usn_user_id
                                        inner join rmx_vys_nodos on nodo_id = usn_nodo_id
                                        where nom_usuario = '$cas_usr_id'  AND usn_estado = 'A'");
            foreach ($data_usuario as $usuario) {
                $roles .= $usuario->nodo_id . ',';
            }

            $roles = rtrim($roles, ',');

            $data = \DB::select("SELECT *
                from rmx_vys_casos rvc
                inner join rmx_vys_actividades on act_id = cas_act_id
                inner join rmx_vys_nodos on nodo_id = cas_nodo_id
                inner join users on id = cas_usr_id
                where cas_padre_id = $cas_id and cas_estado = 'T' and nodo_id in ($roles) order by cas_id asc");

            $data_paralelo = \DB::select("SELECT *
                from rmx_vys_casos rvc
                    inner join rmx_vys_actividades on act_id = cas_act_id
                    inner join rmx_vys_nodos on nodo_id = cas_nodo_id
                    inner join users on id = cas_usr_id
                    where cas_cod_id = '$cas_cod_id' and cas_estado <> 'W' order by cas_id asc");
            // dd($data);
            return response()->json(['data' => $data, 'data_paralelo' => $data_paralelo, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasoXId(Request $request)
    {
        $cas_id = $request["cas_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *,(   SELECT imp_id
                                FROM rmx_vys_impresiones_actividades
                                LEFT JOIN rmx_vys_impresiones on imp_id = impact_imp_id
                                WHERE imp_nombre='ADENDA LEY 1582/2024' and imp_estado = 'A' AND impact_estado = 'A' AND impact_act_id =a.act_id) as  impid
                                    from rmx_vys_casos c
                                    inner join rmx_vys_actividades a on a.act_id = c.cas_act_id
									inner join rmx_vys_procesos p on p.prc_id = a.act_prc_id
									LEFT join rmx_vys_formularios f on f.frm_act_id = c.cas_act_id
                                    inner join gp_estados_avance g on g.est_id = a.act_est_id
                                    where c.cas_estado <> 'X'
                                    and c.cas_id = $cas_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function buscarCasos(Request $request)
    {
        $usuario = $request->query('usuario');
        $pro = $request->query('pro');
        $tramite = $request->query('tramite');

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];

        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];

        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $cas_tipo = $request["cas_tipo"] === null ? null : $request["cas_tipo"];
        $num_identificacion = $request["num_identificacion"] === null ? '' : $request["num_identificacion"];
        $cas_correlativo = $request["cas_correlativo"] === null ? '' : 'and c.cas_correlativo = ' . $request["cas_correlativo"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            if ($cas_tipo != null) {

                $dataTotalRegistros = \DB::select(
                    "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0"
                );


                $sql = "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                    ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO,
                    (
                        SELECT
                            CASE
                                WHEN COUNT(1) > 0 THEN 1
                                ELSE 0
                            END
                        FROM gp_conversaciones gpc
                        WHERE gpc.cas_cod_id = c.cas_cod_id
                    ) AS historico_chat_ucpp
                     WHERE gpc.cas_cod_id = c.cas_cod_id) AS historico_chat_ucpp
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc
                    LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina";

                //dd($sql);
                $data = \DB::select(
                    "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                    ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO,
                    (
                        SELECT
                            CASE
                                WHEN COUNT(1) > 0 THEN 1
                                ELSE 0
                            END
                        FROM gp_conversaciones gpc
                        WHERE gpc.cas_cod_id = c.cas_cod_id
                    ) AS historico_chat_ucpp
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    --LEFT JOIN rmx_usr_nodos n ON n.usn_nodo_id = c.cas_nodo_id  AND n.usn_estado <> 'X' --and n.usn_user_id = c.cas_usr_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X' AND c.cas_data @> '{ \"$cas_tipo\": \"$num_identificacion\" }' $cas_correlativo
                    AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc
                    LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina"
                );

                $ip = request()->ip();
                $data_json = json_encode([], 0);
                $data_log = \DB::select(
                    "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                    array($sql, $data_json, $usuario, $pro, $tramite, $ip)
                );
            } else {
                $dataTotalRegistros = \DB::select(
                    "SELECT count(*) as total_registros
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X'
                    AND c.cas_cod_id like '$cas_cod_id' $cas_correlativo and c.cas_padre_id = 0
                    --AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
              "
                );


                $sql = "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                    ,(
                        SELECT datos.valor->>'frm_value'
                        FROM (
                            SELECT jsonb_array_elements(cas_data_valores) AS valor,cas_cod_id
                            FROM public.rmx_vys_casos
                        ) datos
                        WHERE datos.valor->>'frm_campo' = 'MENSAJE_RECHAZO_DESISTIDO'
                        AND datos.cas_cod_id = c.cas_cod_id
                        LIMIT 1
                    ) AS MENSAJE_RECHAZO_DESISTIDO,
                    (
                        SELECT
                            CASE
                                WHEN COUNT(1) > 0 THEN 1
                                ELSE 0
                            END
                        FROM gp_conversaciones gpc
                        WHERE gpc.cas_cod_id = c.cas_cod_id
                    ) AS historico_chat_ucpp
                    FROM rmx_vys_casos c
                    LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                    LEFT join users u on u.id = c.cas_usr_id
                    LEFT JOIN gp_estados_avance g on g.est_id = a.act_est_id
                    WHERE c.cas_estado <> 'X'
                    AND c.cas_cod_id like '$cas_cod_id' $cas_correlativo and c.cas_padre_id = 0
                    ORDER BY c.cas_modificado desc
                    LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina";
                $data = \DB::select($sql);

                $ip = request()->ip();
                $data_json = json_encode([], 0);
                $data_log = \DB::select(
                    "select * from sp_create_query_logs(?, ?, ?,?,?,?)",
                    array($sql, $data_json, $usuario, $pro, $tramite, $ip)
                );
            }

            return response()->json(['data' => $data, 'success' => $success, "totalRegistros" => $dataTotalRegistros]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function buscarCasosBusqueda(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];

        $cas_cod_id = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $cas_tipo = $request["cas_tipo"] === null ? null : $request["cas_tipo"];
        $num_identificacion = $request["num_identificacion"] === null ? '' : $request["num_identificacion"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $buscarsql = $request->query('search');

        $formattedData = [];

        try {
            if ($cas_tipo != null) {
                $data = \DB::select(
                    "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name,
                FROM rmx_vys_casos c
                LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                LEFT join users u on u.id = c.cas_usr_id
                WHERE c.cas_estado <> 'X'
                AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                AND (
                cas_cod_id LIKE ? OR
                cas_data ->> 'AS_CI' LIKE ? OR
                cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                p.prc_data::json->>'prc_codigo' LIKE ? OR
                p.prc_data::json->>'prc_version' LIKE ? OR
                p.prc_data::json->>'prc_descripcion' LIKE ? OR
                u.email LIKE ?
                )
                ORDER BY c.cas_modificado desc",
                    ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
                );
            } else {
                $data = \DB::select(
                    "SELECT *, case when c.cas_estado = 'A' then '' else u.name end as name
                FROM rmx_vys_casos c
                LEFT JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                LEFT JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                LEFT JOIN rmx_vys_nodos nnn ON nnn.nodo_id = c.cas_nodo_id
                LEFT join users u on u.id = c.cas_usr_id
                WHERE c.cas_estado <> 'X'
                AND c.cas_cod_id like '$cas_cod_id' and c.cas_padre_id = 0
                AND (
                cas_id LIKE ? OR
                cas_cod_id LIKE ? OR
                cas_data ->> 'AS_CI' LIKE ? OR
                cas_data ->> 'AS_PRIMER_NOMBRE' LIKE ? OR
                cas_data ->> 'AS_SEGUNDO_NOMBRE' LIKE ? OR
                cas_data ->> 'AS_PRIMER_APELLIDO' LIKE ? OR
                cas_data ->> 'AS_SEGUNDO_APELLIDO' LIKE ? OR
                act_data ->> 'act_orden' LIKE ? OR
                act_data ->> 'act_descripcion' LIKE ? OR
                nnn.nodo_codigo LIKE ? OR
                nnn.nodo_descripcion LIKE ? OR
                p.prc_data::json->>'prc_codigo' LIKE ? OR
                p.prc_data::json->>'prc_version' LIKE ? OR
                p.prc_data::json->>'prc_descripcion' LIKE ? OR
                u.email LIKE ?
                )
                ORDER BY c.cas_modificado desc",
                    ['%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%', '%' . $buscarsql . '%']
                );
            }

            foreach ($data as $fila) {
                $actData = json_decode($fila->act_data);
                $tact_codigo = isset($fila->tact_codigo) ? $fila->tact_codigo : null;

                $formattedData[] = [
                    'cas_cod_id' => $fila->cas_cod_id,
                    'cas_id' => $fila->cas_id,
                    //'tact_codigo' => $tact_codigo,
                    'cas_estado' => $fila->cas_estado,
                    'cas_usr_id' => $fila->cas_usr_id,
                    'cas_padre_id' => $fila->cas_padre_id,
                    'act_data' => $actData,
                    'prc_data' => json_decode($fila->prc_data),
                    'cas_registrado' => $fila->cas_registrado,
                    'act_data_reglas' => $fila->act_data_reglas,
                    'prc_id' => $fila->prc_id,
                    'act_id' => $fila->act_id,
                    'cas_data' => json_decode($fila->cas_data),
                    'tact_codigo' => isset($fila->tact_codigo) ? $fila->tact_codigo : null,
                    'email' => $fila->email,
                    'nom_usuario' => $fila->nom_usuario,
                    'nodo_codigo' => $fila->nodo_codigo,
                    'nodo_descripcion' => $fila->nodo_descripcion,
                    $tact_codigo = isset($fila->tact_codigo) ? $fila->tact_codigo : null,
                ];
            }

            return response()->json(['data' => $formattedData, 'success' => $success]);
        } catch (Exception $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function grabarCasos(Request $request)
    {
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_gestion = $request["cas_data.cas_gestion"];
        $primer_act_id = $request["primer_act_id"];
        $primer_act_nodo_id = $request["primer_act_nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);

        try {

            $tramite = \DB::table('rmx_vys_casos')
                ->select(\DB::raw('coalesce(max(cast(split_part(cas_cod_id, \'/\', 2) as integer)), 0) as nro_tramite'))
                ->where('cas_cod_id', 'like', $request["prc_codigo"] . '/%/' . $cas_gestion)
                ->get();

            $nro_tramite = $tramite[0]->nro_tramite;
            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);
            $codigo = $request["prc_codigo"];
            $codigo2 = $request["prc_codigo"] . '/' . ($nro_tramite + 1) . '/' . $cas_gestion;
            $sql = "select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id ,$cas_gestion) ";
            //dd($sql);
            $data = \DB::select("select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id,$cas_gestion) ");
            return response()->json(["data" => $data[0]->sp_grabar_casos, "codigoRespuesta" => $success, "codigo" => $codigo2]);
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error, "codigo" => $codigo2], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }
    public function grabarCasosAqui(Request $request)
    {
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_gestion = $request["cas_data.cas_gestion"];
        $primer_act_id = $request["primer_act_id"];
        $primer_act_nodo_id = $request["primer_act_nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);

        try {

            $tramite = \DB::table('rmx_vys_casos')
                ->select(\DB::raw('coalesce(max(cast(split_part(cas_cod_id, \'/\', 2) as integer)), 0) as nro_tramite'))
                ->where('cas_cod_id', 'like', $request["prc_codigo"] . '/%/' . $cas_gestion)
                ->get();

            $nro_tramite = $tramite[0]->nro_tramite;
            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);
            $codigo = $request["prc_codigo"];
            $codigo2 = $request["prc_codigo"] . '/' . ($nro_tramite + 1) . '/' . $cas_gestion;
            $sql = "select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id ,$cas_gestion) ";
            //dd($sql);
            $data = \DB::select("select * from public.sp_grabar_casos($cas_act_id, $cas_nodo_id, '$cas_data', '$cas_data_valores', $cas_usr_id, '$codigo', $primer_act_id, $primer_act_nodo_id,$cas_gestion) ");
            $respuesta = array("data" => $data[0]->sp_grabar_casos, "codigoRespuesta" => $success, "codigo" => $codigo2);
            return $respuesta;
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error, "codigo" => $codigo2], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }

    public function actualizarEstadoCaso(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_estado = $request["cas_estado"];
        $cas_usr_id = $request["cas_usr_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.sp_actualizar_estado_caso($cas_id,$cas_usr_id,'$cas_estado')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function asignarUsuarioResp(Request $request)
    {
        $cas_id = $request["casId"];
        $cas_estado = $request["cas_estado"];
        $cas_nom = $request["cas_nom"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.sp_asignar_caso($cas_id,'$cas_nom','$cas_estado')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function asignarUsuarioRespEnvio(Request $request)
    {
        $cas_id = $request["casId"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        //$cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $cas_usr_nom = $request["cas_usr_nom"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $cas_data = json_encode($cas_data, 0);
            // $cas_data_valores = json_encode($cas_data_valores, 0);
            //**************** INICIO*/
            $results = \DB::select("SELECT c.cas_data_valores
             FROM rmx_vys_casos c
             WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                 AND c.cas_id = $cas_id
             ORDER BY c.cas_modificado DESC");
            // Verificar si hay resultados
            if (!empty($results)) {
                $cas_data_valores = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $cas_data_valores = json_decode($cas_data_valores, true);
            }
            $cas_data_valores = json_encode($cas_data_valores, 0);
            ///**********************FIN */
            $data = \DB::select(
                "select * from public.sp_asignar_caso_envio(?,?,?,?,?,?,?)",
                [$cas_id, $cas_act_id, $cas_nodo_id, $cas_data, $cas_data_valores, $cas_usr_nom, $cas_estado]
            );

            $respuesta = $data[0]->sp_asignar_caso_envio;
            // if ($respuesta == 0){
            //     // preguntar con quien esta, servicio de fernando
            // }
            //ESTA OPCION PERMITE EL REGISTRO DE FECHA DE DERIVACION HACIA EL MEDICO EN PM Y INV
            if ($cas_act_id == 91 || $cas_act_id == 97) {
                // Asignar la fecha actual en el formato YYYY-MM-DD
                $FECHA_DE_RESEPCION = date('Y-m-d');

                // Consultar si ya existe la fecha de recepción
                $estadoRespuestaCal = \DB::select("SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$cas_id'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' = 'FECHA_DE_RESEPCION';");

                if (empty($estadoRespuestaCal)) {
                    // Si no existe la fecha, insertar el campo en el JSON
                    try {
                        DB::table('rmx_vys_casos')
                            ->where('cas_id', $cas_id)
                            ->update([
                                'cas_data_valores' => DB::raw("
                                    cas_data_valores ||
                                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"FECHA_DE_RESEPCION\", \"frm_value\": \"$FECHA_DE_RESEPCION\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                                ")
                            ]);
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE REGISTRÓ: " . $e->getMessage());
                    }
                } else {
                    // Si ya existe la fecha, actualizarla
                    try {
                        $dataInv = \DB::select("
                            WITH updated_json AS (
                                SELECT cas_id,
                                    jsonb_agg(
                                        CASE
                                            WHEN elem->>'frm_campo' = 'FECHA_DE_RESEPCION' THEN jsonb_set(elem, '{frm_value}', '\"$FECHA_DE_RESEPCION\"')
                                            ELSE elem
                                        END
                                    ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_id = '$cas_id'
                                GROUP BY cas_id
                            )
                            UPDATE public.rmx_vys_casos
                            SET cas_data_valores = updated_json.updated_json
                            FROM updated_json
                            WHERE public.rmx_vys_casos.cas_id = updated_json.cas_id;
                        ");

                        if (empty($dataInv)) {
                            throw new \Exception("NO SE ACTUALIZÓ LA FECHA.");
                        }
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE ACTUALIZÓ: " . $e->getMessage());
                    }
                }
            }
            return response()->json(["data" => $respuesta, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function dashboardCasos(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT c.cas_data->>'cas_nro_caso' as \"No. Caso\",
					p.prc_data->>'prc_descripcion' as \"Proceso\",
					concat(a.act_data->>'act_orden', ' - ', a.act_data->>'act_descripcion') as \"Actividad\",
					no.nodo_descripcion as \"Nodo\",
					u.name as \"Funcionario\",
                    c.cas_data->>'cas_departamento' as \"Departamento\",
                    c.cas_data->>'cas_gestion' as \"Gestión\",
                    (a.act_data->>'act_orden')::int as \"orden\",
					EXTRACT(MONTH FROM c.cas_registrado)::integer as \"Mes\",
                    CASE
                    WHEN c.cas_estado = 'T' THEN 'Tomado'
                    WHEN c.cas_estado = 'A' THEN 'Libre'
                    WHEN c.cas_estado = 'E' THEN 'Enviado'
                    WHEN c.cas_estado = 'H' THEN 'Archivado'
                    END as \"Situación\",
                    concat(g.est_orden, '-' , g.est_codigo) as \"Estado\", g.est_descripcion as \"Desc Estado\",
                    c.cas_data->>'ESTADO_DERIVACION' as \"SUB Estado\"
				FROM rmx_vys_casos c
					INNER JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					INNER JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					left JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id and c.cas_usr_id = n.usn_user_id
                    left JOIN rmx_vys_nodos no ON no.nodo_id = a.act_nodo_id
                    left JOIN users u ON u.id = c.cas_usr_id
                    left join gp_estados_avance g on g.est_id = a.act_est_id
				WHERE c.cas_estado not in ( 'X', 'W')
				ORDER BY \"Proceso\", \"Estado\" ASC ");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarFormularios(Request $request)
    {
        $act_id = $request["act_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_formularios
                                    inner join rmx_vys_actividades on act_id = frm_act_id
                                    where frm_estado = 'A'
                                        and frm_act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarFormularios(Request $request)
    {
        $frm_id = $request["frm_id"];
        $frm_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_formularios
                                    set frm_estado = '$frm_estado'
                                where frm_id = $frm_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarFormularios(Request $request)
    {
        $frm_act_id = $request['frm_act_id'];
        $frm_data = $request['frm_data'];
        $frm_data_campos = $request['frm_data_campos'];
        $frm_usr_id = $request['frm_usr_id'];
        $frm_funciones = $request['frm_funciones'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            \DB::table('rmx_vys_formularios')->insert([
                'frm_act_id' => $frm_act_id,
                'frm_data' => $frm_data,
                'frm_data_campos' => $frm_data_campos,
                'frm_funciones' => $frm_funciones,
                'frm_usr_id' => $frm_usr_id,
            ]);
            return response()->json(['data' => true, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function actualizarFormularios(Request $request)
    {
        $frm_id = $request['frm_id'];
        $frm_act_id = $request['frm_act_id'];
        $frm_data = json_encode($request['frm_data']);
        $frm_data_campos = $request['frm_data_campos'];
        $frm_funciones = $request['frm_funciones'];
        $frm_usr_id = $request['frm_usr_id'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::table('rmx_vys_formularios')
                ->where('frm_id', $frm_id)
                ->update([
                    'frm_act_id' => $frm_act_id,
                    'frm_data' => $frm_data,
                    'frm_data_campos' => $frm_data_campos,
                    'frm_funciones' => $frm_funciones,
                    'frm_usr_id' => $frm_usr_id,
                ]);
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function actualizarFormulariosCampos(Request $request)
    {
        $frm_data_campos = $request['frm_data_campos'];
        $frm_id = $request['frm_id'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::table('rmx_vys_formularios')
                ->where('frm_id', $frm_id)
                ->update([
                    'frm_data_campos' => $frm_data_campos
                ]);
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function actualizarCasos(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        $cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];

        // Filtrar el array para encontrar el elemento que tiene "frm_campo" => "AS_TIPO_DOCUMENTO"
        $resultado = array_filter($cas_data_valores, function ($item) {
            return isset($item["frm_campo"]) && $item["frm_campo"] === "AS_TIPO_DOCUMENTO";
        });

        // Obtener el primer resultado (porque `array_filter` devuelve un array)
        $asTipoDocumento = reset($resultado);

        //$cas_data_valores_con = str_replace('\'', '\'\'', $cas_data_valores);
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $cas_data = json_encode($cas_data, 0);
            $cas_data_valores = json_encode($cas_data_valores, 0);
            $data = \DB::select(
                "select * from public.sp_actualizar_caso(?,?,?,?,?,?,?)",
                [
                    $cas_id,
                    $cas_act_id,
                    $cas_nodo_id,
                    $cas_data,
                    $cas_data_valores,
                    $cas_usr_id,
                    $cas_estado
                ]
            );

            if ($asTipoDocumento) {
                // echo "Valor de AS_TIPO_DOCUMENTO: " . $asTipoDocumento["frm_value"];

                if ($asTipoDocumento["frm_value"] == 'C') {
                    $this->cambiarTipoDocumento($cas_id);
                }
            } else {
                // echo "No se encontró AS_TIPO_DOCUMENTO";
            }

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function cambiarTipoDocumento($cas_id)
    {
        $frm_value = 'I';
        $results = \DB::select(" UPDATE rmx_vys_casos
                                                SET cas_data_valores = jsonb_set(
                                                    cas_data_valores,
                                                    ('{' || sub.idx - 1 || ', frm_value}')::text[],
                                                    '\"$frm_value\"',
                                                    false)
                                                FROM (
                                                    SELECT cas_id, idx
                                                    FROM rmx_vys_casos,
                                                    jsonb_array_elements(cas_data_valores) WITH ORDINALITY arr(elem, idx)
                                                    WHERE elem->>'frm_campo' = 'AS_TIPO_DOCUMENTO'
                                                    AND cas_id =  $cas_id
                                                ) sub
                                          WHERE rmx_vys_casos.cas_id = sub.cas_id;");

        $frm_value_label = 'CEDULA IDENTIDAD';
        $results = \DB::select(" UPDATE rmx_vys_casos
                                                SET cas_data_valores = jsonb_set(
                                                    cas_data_valores,
                                                    ('{' || sub.idx - 1 || ', frm_value_label}')::text[],
                                                    '\"$frm_value_label\"',
                                                    false)
                                                FROM (
                                                    SELECT cas_id, idx
                                                    FROM rmx_vys_casos,
                                                    jsonb_array_elements(cas_data_valores) WITH ORDINALITY arr(elem, idx)
                                                    WHERE elem->>'frm_campo' = 'AS_TIPO_DOCUMENTO'
                                                    AND cas_id =  $cas_id
                                                ) sub
                                            WHERE rmx_vys_casos.cas_id = sub.cas_id;");
    }

    public function archivarCasos(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_data = $request["cas_data"];
        $cas_usr_id = $request["cas_usr_id"];
        $uid = $request["uid"];
        $cas_cod_id = $request["cas_cod_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $datosTram = array(
                '_id' => $uid,
                '_tipoActualizacion' => 'H',
                '_rutaDocumento' => 'se libera el tramite por archivo del mismo',
                '_nroTramite' => $cas_cod_id,
                '_usuario' => 'TRAMITESIP'
            );
            //dd($datosTram);
             // VALIDACIÓN DEL UID
            $uidCount = \DB::table('rmx_vys_casos')
            ->whereRaw("cas_data ->> 'UID' = ?", [$uid])
            ->count();

            if ($uidCount <= 1) {
                // LLAMADA AL SERVICIO EXTERNO
            $requestEnvio = new Request($datosTram);
            $resultadoPres = $this->prestaciones1582($requestEnvio);
            }
            $cas_data = json_encode($cas_data, 0);
            $data = \DB::select("select * from public.sp_archivar_caso($cas_id,$cas_usr_id,'$cas_data')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function derivarCasosMultiple(Request $request)
    {
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $jsonString = $request["casos"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            // Decode the JSON string into an associative array
            $dataArray = json_decode($jsonString, true);
            if ($dataArray !== null) {
                // Loop through the array using foreach
                foreach ($dataArray as $entry) {
                    $cas_data_valores = $entry['cas_data_valores'];
                    $cas_data = $entry['cas_data'];
                    $cas_id = $entry['cas_id'];
                    $prc_id = $entry['prc_id'];
                    $act_siguiente = $entry['act_siguiente'];
                    if ($entry['value'] == "1") {
                        $dataA = \DB::select("select act_id,act_nodo_id
                                    from rmx_vys_actividades
                                    where act_estado = 'A'
                                      and act_prc_id = $prc_id
                                      and act_data->>'act_orden' = '$act_siguiente'
                                    order by act_id ");
                        foreach ($dataA as $item) {
                            echo "cas_id: " . $entry['cas_id'] . "<br>";
                            echo "act_id: " . $item->act_id;
                            echo "act_nodo_id: " . $item->act_nodo_id;
                            $cas_act_id = $item->act_id;
                            $cas_nodo_id = $item->act_nodo_id;
                        }
                        //  print_r($dataA);
                        $data2 = \DB::select("INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id,
                        htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores,
                        htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado)
                        SELECT * FROM rmx_vys_casos WHERE cas_id = $cas_id");

                        $data = \DB::select("UPDATE rmx_vys_casos SET
                        cas_nodo_id = $cas_nodo_id,
                        cas_act_id = $cas_act_id,
                        cas_data = '$cas_data'::json,
                        cas_data_valores = '$cas_data_valores'::json,
                        cas_modificado = now(),
                        cas_usr_id = $cas_usr_id,
                        cas_estado = '$cas_estado'
                        where cas_id = $cas_id");
                    }
                }
                return response()->json(["data" => "hhh", "success" => $success]);
            } else {
                // Handle the case where JSON decoding failed
                // Perhaps log an error or return an error response
                return response()->json(["error" => $error]);
            }
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function derivarCasosUnion(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        //$cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $cas_data = json_encode($cas_data, 0);
            // $cas_data_valores = json_encode($cas_data_valores, 0);
            //**************** INICIO*/
            $results = \DB::select("SELECT c.cas_data_valores
             FROM rmx_vys_casos c
             WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                 AND c.cas_id = $cas_id
             ORDER BY c.cas_modificado DESC");
            // Verificar si hay resultados
            if (!empty($results)) {
                $cas_data_valores = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $cas_data_valores = json_decode($cas_data_valores, true);
            }
            $cas_data_valores = json_encode($cas_data_valores, 0);
            ///**********************FIN */
            $aa = "SELECT * FROM public.sp_derivar_caso_regreso($cas_id, $cas_act_id, $cas_nodo_id, '$cas_data','$cas_data_valores',$cas_usr_id,'$cas_estado')";
            $data = \DB::select(
                "SELECT * FROM public.sp_derivar_caso_regreso(?, ?, ?, ?, ?, ?, ?)",
                [$cas_id, $cas_act_id, $cas_nodo_id, $cas_data, $cas_data_valores, $cas_usr_id, $cas_estado]
            );
            return response()->json(["data" => $cas_data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function derivarCasos(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        //$cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $act_data = $request["act_data"];
        $cas_cod_id = $request["cas_cod_id"];
        $actSiguiente = $act_data['act_siguiente'];
        $usuario = $request->query('usuario');
        $array = explode('/', $cas_cod_id);

        $cas_cod_id_ = $array[0];

        // if (($actSiguiente == '200' && $cas_cod_id_ != 'LEGAL') || ($actSiguiente == '80' && $cas_cod_id_ != 'LEGAL')) {
        //     $data = [
        //         "nroTramite" => $cas_cod_id,
        //         "usuario" => $usuario
        //     ];
        //     $updateRequest = new Request($data);
        //     $controller = new estructuraFamiliarPrestacionesController();
        //     $resultado = $controller->updateStateTramite($updateRequest);
        // }

        ///dd($act_data, $cas_cod_id, $actSiguiente);

        // if ($cas_data["TIPO_PROCESO"] == 'GFU' && $cas_data["ESTADO_DERIVACION"] == 'APROBADO') {
        //     $nuevoRequest = Request::create(
        //         '/registroFallecido',
        //         'POST',
        //         [
        //             'cas_id' => $cas_id,
        //         ]
        //     );
        //     $fallecidosController = new fallecidosSslpController();
        //     $respuesta = $fallecidosController->registroFallecido($nuevoRequest);
        // }

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $cas_data = json_encode($cas_data, 0);
            // $cas_data_valores = json_encode($cas_data_valores, 0);
            //**************** INICIO*/
            $results = \DB::select("SELECT c.cas_data_valores
             FROM rmx_vys_casos c
             WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                 AND c.cas_id = $cas_id
             ORDER BY c.cas_modificado DESC");
            // Verificar si hay resultados
            if (!empty($results)) {
                $cas_data_valores = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $cas_data_valores = json_decode($cas_data_valores, true);
            }
            $cas_data_valores = json_encode($cas_data_valores, 0);
            ///**********************FIN */
            //$data = \DB::select("SELECT * FROM public.sp_derivar_caso($cas_id, $cas_act_id, $cas_nodo_id, '$cas_data','$cas_data_valores',$cas_usr_id,'$cas_estado')");
            $data = \DB::select(
                "SELECT * FROM public.sp_derivar_caso(?, ?, ?, ?, ?, ?, ?)",
                [$cas_id, $cas_act_id, $cas_nodo_id, $cas_data, $cas_data_valores, $cas_usr_id, $cas_estado]
            );
            //ESTA OPCION PERMITE EL REGISTRO DE FECHA DE DERIVACION HACIA EL MEDICO EN PM Y INV
            if ($cas_act_id == 91 || $cas_act_id == 97) {
                // Asignar la fecha actual en el formato YYYY-MM-DD
                $FECHA_DE_RESEPCION = date('Y-m-d');

                // Consultar si ya existe la fecha de recepción
                $estadoRespuestaCal = \DB::select("SELECT *
                    FROM (
                        SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                        WHERE cas_id = '$cas_id'
                    ) tmp
                    WHERE tmp.valor->>'frm_campo' = 'FECHA_DE_RESEPCION';");

                if (empty($estadoRespuestaCal)) {
                    // Si no existe la fecha, insertar el campo en el JSON
                    try {
                        DB::table('rmx_vys_casos')
                            ->where('cas_id', $cas_id)
                            ->update([
                                'cas_data_valores' => DB::raw("
                                    cas_data_valores ||
                                    '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"FECHA_DE_RESEPCION\", \"frm_value\": \"$FECHA_DE_RESEPCION\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                                ")
                            ]);
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE REGISTRÓ: " . $e->getMessage());
                    }
                } else {
                    // Si ya existe la fecha, actualizarla
                    try {
                        $dataInv = \DB::select("
                            WITH updated_json AS (
                                SELECT cas_id,
                                    jsonb_agg(
                                        CASE
                                            WHEN elem->>'frm_campo' = 'FECHA_DE_RESEPCION' THEN jsonb_set(elem, '{frm_value}', '\"$FECHA_DE_RESEPCION\"')
                                            ELSE elem
                                        END
                                    ) AS updated_json
                                FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
                                WHERE cas_id = '$cas_id'
                                GROUP BY cas_id
                            )
                            UPDATE public.rmx_vys_casos
                            SET cas_data_valores = updated_json.updated_json
                            FROM updated_json
                            WHERE public.rmx_vys_casos.cas_id = updated_json.cas_id;
                        ");

                        if (empty($dataInv)) {
                            throw new \Exception("NO SE ACTUALIZÓ LA FECHA.");
                        }
                    } catch (\Exception $e) {
                        throw new \Exception("NO SE ACTUALIZÓ: " . $e->getMessage());
                    }
                }
            }
            return response()->json(["data" => $cas_data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function anularDesistirCasos(Request $request)
    {
        $cas_id = $request->input("cas_id");
        $act_prc_id = $request->input("act_prc_id");
        $cas_nodo_id = $request->input("cas_nodo_id");
        $estado_derivacion = $request->input("estado_derivacion");
        $descripcion_derivacion = $request->input("descripcion_derivacion");
        $cas_estado = "A";
        $error = array("message" => "error de instancia", "code" => 500);
        $numeroTramite = $request->input("numero_caso");
        $usuario = $request->input("usr_name");
        $estado_caso = $request->input("estado_caso");
        if ($estado_caso == 'E') {
            $url = "https://sgg.gestora.bo/otorgamiento-prestaciones-cpp/api/cppAsegurado/ActualizarEstadoTramiteObservacion";
            $data1 = [
                'numeroTramite' => $numeroTramite,
                'usuario' => $usuario,
                'observacion' => $descripcion_derivacion,
                'estado' => 'DESISTIR'
            ];
            try {
                $response = Http::acceptJson()->withHeaders([
                    'Content-Type' => 'application/json'
                ])->post($url, $data1);
                $response = $response->json();
                if ($response["codigo"] == "200") {
                    $data = \DB::select("SELECT * FROM public.sp_anular_desistir_caso($cas_id, $act_prc_id, $cas_nodo_id, '$estado_derivacion','$descripcion_derivacion',0,'$cas_estado')");
                }
                return $response;
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            try {
                $success = \DB::select("SELECT * FROM public.sp_anular_desistir_caso($cas_id, $act_prc_id, $cas_nodo_id, '$estado_derivacion','$descripcion_derivacion',0,'$cas_estado')");
                return response()->json(['mensaje' => 'El Caso Fue Anulado/Desistido Exitosamente', 'success' => $success]);
            } catch (error $e) {
                return response()->json(['error' => $error]);
            }
        }
    }
    public function recuperarDatosParaAnular(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT *
            from rmx_vys_casos rvc
            inner join rmx_vys_actividades on act_id = cas_act_id
            inner join rmx_vys_nodos on nodo_id = cas_nodo_id
            inner join users on id = cas_usr_id
            where cas_id = $cas_id and cas_estado <>'X' order by cas_id asc");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function derivarCasosParalelo(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_data = $request["cas_data"];
        //$cas_data_valores = $request["cas_data_valores"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $cas_cod_id = $request["cas_cod_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $cas_data = json_encode($cas_data, 0);
            // $cas_data_valores = json_encode($cas_data_valores, 0);
            //**************** INICIO*/
            $results = \DB::select("SELECT c.cas_data_valores
             FROM rmx_vys_casos c
             WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                 AND c.cas_id = $cas_id
             ORDER BY c.cas_modificado DESC");
            // Verificar si hay resultados
            if (!empty($results)) {
                $cas_data_valores = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $cas_data_valores = json_decode($cas_data_valores, true);
            }
            $cas_data_valores = json_encode($cas_data_valores, 0);
            ///**********************FIN */
            $data = \DB::select(
                "SELECT * FROM public.sp_derivar_caso_paralelo(?, ?, ?, ?, ?, ?, ?, ?)",
                [$cas_id, $cas_act_id, $cas_nodo_id, $cas_data, $cas_data_valores, $cas_usr_id, $cas_estado, $cas_cod_id]
            );
            return response()->json(["data" => $data[0]->sp_derivar_caso_paralelo, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarImpresionesCasos(Request $request)
    {
        $_id = $request['act_id'];
        $cas_id = $request['cas_id'];
        if (!empty($_id)) {
            $where = 'AND imp_act_id = ' . $_id;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data1 = \DB::select("SELECT rvc.cas_data->>'AS_CUA' AS cua from rmx_vys_casos rvc  where  rvc.cas_id = $cas_id");
            $cua = $data1[0]->cua;
            // dd($cua);

            if ($cua == null) {
                $restriccion = '';
                $restriccion_obs = '';
            } else {
                $response = Http::get('https://sgg.gestora.bo/compensacion-cotizacion/api/cargaFile/consulta', [
                    'cua' => $cua,
                ]);
                $restriccion = '';
                $restriccion_obs = '';

                if ($response['codigoRespuesta'] == 200) {
                    $restriccion = '';
                } else {
                    $restriccion = "AND   imp_nombre != 'DIFERENCIA REGISTRO'";
                }
            }

            $dataDocumentos = \DB::select("select doc_cas_id,doc_codigo, doc_descripcion,codigo ,doc_referencia
                    from _gp_documentos gd
                    inner join  gp_observacion go2 on go2.id_observacion = gd.doc_id_observacion
                    where  doc_id_observacion != 1 and doc_cas_id = $cas_id");

            if (count($dataDocumentos) > 0) {
                $restriccion_obs = '';
            } else {
                $restriccion_obs = "AND   imp_nombre != 'DOCUMENTACION OBSERVADA'";
            }
            $data = \DB::select("SELECT *, (select count(gd.doc_doc_id)
                                                    from rmx_vys_casos rvc , _gp_documentos gd
                                                    where rvc.cas_cod_id = gd.doc_codigo
                                                    and rvc.cas_id = $cas_id
                                                    and gd.doc_descripcion = imp_nombre) as firma,
                                                (select gd.doc_url
                                                    from rmx_vys_casos rvc , _gp_documentos gd
                                                    where rvc.cas_cod_id = gd.doc_codigo
                                                    and rvc.cas_id = $cas_id and gd.doc_descripcion = imp_nombre limit 1) as url_documento,
                                                (select gd.doc_id
                                                    from rmx_vys_casos rvc , _gp_documentos gd
                                                    where rvc.cas_cod_id = gd.doc_codigo
                                                    and rvc.cas_id = $cas_id and gd.doc_descripcion = imp_nombre order by gd.doc_id desc limit 1) as doc_id,
                                                1 as doc_firmado
                                    FROM rmx_vys_impresiones
                                    WHERE imp_estado = 'A' $restriccion $restriccion_obs
                                    AND imp_id = ANY(public.impresiones($cas_id,$_id))
                                    ORDER BY orden asc");

            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarImpresiones(Request $request)
    {
        $_id = $request['act_id'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_act_id, imp_estado, imp_data_reglas, imp_tipo_firma
                                FROM rmx_vys_impresiones_actividades
                                LEFT JOIN rmx_vys_impresiones on imp_id = impact_imp_id
                                WHERE imp_estado = 'A' AND impact_estado = 'A' AND impact_act_id = $_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function grabarImpresion(Request $request)
    {
        $_act_id = $request['imp_act_id'];
        $_nombre = $request['imp_nombre'];
        $_data = $request['imp_data'];
        $imp_tipo = $request['id_combo'];
        $imp_tipo_firma = $request['imp_tipo_firma'];
        $imp_data_reglas = $request["imp_data_reglas"];
        $imp_data_reglas_con = str_replace('\'', '\'\'', $imp_data_reglas);
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * FROM public.sp_crud_impresiones (0, $_act_id, '$_nombre', '$_data','$imp_data_reglas_con', 1, '$imp_tipo', '$imp_tipo_firma', 1)");
            if ($data[0]->sp_crud_impresiones == 500) {
                throw new Exception("Error al crear la impresión");
            }
            return response()->json(['message' => 'Impresión creada exitosamente', 'data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function reasignarImpresionActividad(Request $request)
    {
        $impact_imp_id = $request['impact_imp_id'];
        $impact_act_id = $request['impact_act_id'];
        $impact_imp_nombre = $request['impact_imp_nombre'];
        $impact_tipo_firma = $request['impact_tipo_firma'];
        $impact_data = $request['impact_data'];
        $impact_tipo = $request['impact_tipo'];
        $impact_data_reglas = $request['impact_data_reglas'];
        $impact_data_reglas_con = str_replace('\'', '\'\'', $impact_data_reglas);
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * FROM public.sp_crud_impresiones ($impact_imp_id, $impact_act_id, '$impact_imp_nombre', '$impact_data','$impact_data_reglas_con', 1, '$impact_tipo', '$impact_tipo_firma', 1)");
            if ($data[0]->sp_crud_impresiones == 500) {
                throw new Exception("Error al reasignar la impresión");
            }
            return response()->json(['message' => 'Impresión reasignada exitosamente', 'data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function actualizarImpresion(Request $request)
    {
        $_id = $request['imp_id'];
        $_nombre = $request['imp_nombre'];
        $_data = $request['imp_data'];
        $imp_data_reglas = $request["imp_data_reglas"];
        $imp_tipo_firma = $request["imp_tipo_firma"];
        $imp_act_id = $request["imp_act_id"];
        $imp_data_reglas_con = str_replace('\'', '\'\'', $imp_data_reglas);
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * FROM public.sp_crud_impresiones ($_id, $imp_act_id, '$_nombre', '$_data','$imp_data_reglas_con', 1, '', '$imp_tipo_firma', 2)");
            if ($data[0]->sp_crud_impresiones == 500) {
                throw new Exception("Error al actualizar la impresión");
            }
            return response()->json(['message' => 'Impresión actualizada exitosamente', 'data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function eliminarImpresion(Request $request)
    {
        $_id = $request['imp_id'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT * FROM public.sp_crud_impresiones ($_id, 0, '', '','', 1, '', '', 3)");
            if ($data[0]->sp_crud_impresiones == 500) {
                throw new Exception("Error al eliminar la impresión");
            }
            $dataArray = json_decode(json_encode($data), true);
            return response()->json(['message' => 'Impresion eliminada exitosamente', 'data' => $dataArray, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarUsrNodos(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT *
				FROM users u JOIN rmx_usr_nodos un ON u.id = un.usn_user_id
					JOIN rmx_vys_nodos n ON un.usn_nodo_id = n.nodo_id
				WHERE un.usn_estado = 'A'
					AND n.nodo_estado = 'A'");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarUsrNodosXId(Request $request)
    {
        $id = $request["id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT *
				FROM users u
					JOIN rmx_usr_nodos un ON u.id = un.usn_user_id
					JOIN rmx_vys_nodos n ON un.usn_nodo_id = n.nodo_id
				WHERE un.usn_estado = 'A'
					AND n.nodo_estado = 'A'
					AND u.id = $id ");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function grabarUsrNodos(Request $request)
    {
        $_user_id = $request['usn_user_id'];
        $_nodo_id = $request['usn_nodo_id'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("INSERT INTO rmx_usr_nodos (usn_user_id, usn_nodo_id, usn_usr_id)
				VALUES ($_user_id, $_nodo_id, 1)");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function eliminarUsrNodos(Request $request)
    {
        $_usn_id = $request['usn_id'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("UPDATE rmx_usr_nodos SET
					usn_estado = 'X'
				WHERE usn_id = $_usn_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function actualizarUsrNodos(Request $request)
    {
        $_usn_id = $request['usn_id'];
        $_user_id = $request['usn_user_id'];
        $_nodo_id = $request['usn_nodo_id'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("UPDATE rmx_usr_nodos SET
					usn_user_id = $_user_id,
					usn_nodo_id = $_nodo_id
				WHERE usn_id = $_usn_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function exportarProceso(Request $request)
    {
        $_id = $request['prc_id'];
        try {
            // Proceso
            $proceso = \DB::select("SELECT prc_id, prc_data, prc_data_campos_clave, prc_data->>'prc_descripcion' AS nombre, prc_modelo
				FROM rmx_vys_procesos
				WHERE prc_id = $_id");
            $actividades = \DB::select("SELECT act_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_data_form
				FROM rmx_vys_actividades
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					ORDER BY act_id");
            $formularios = \DB::select("SELECT act_id, frm_data, frm_data_campos, frm_funciones
				FROM rmx_vys_actividades a JOIN rmx_vys_formularios f ON act_id = frm_act_id
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					AND frm_estado = 'A'
				ORDER BY act_id");
            $impresiones = \DB::select("SELECT act_id, imp_nombre, imp_data
				FROM rmx_vys_actividades a JOIN rmx_vys_impresiones i ON act_id = imp_act_id
				WHERE act_prc_id = $_id
					AND act_estado = 'A'
					AND imp_estado = 'A'
				ORDER BY act_id");
            $file = '/tmp/' . str_replace(array(',', '/'), array('_', '-'), $proceso[0]->nombre) . '.json';
            unset($proceso[0]->nombre);
            $contenido = json_encode(['proceso' => $proceso, 'actividades' => $actividades, 'formularios' => $formularios, 'impresiones' => $impresiones]);
            $txt = fopen($file, 'w') or die('Unable to open file!');
            fwrite($txt, $contenido);
            fclose($txt);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            header('Content-Type: application/json;charset=utf-8');
            readfile($file);
        } catch (error $e) {
            return response()->json(['error' => $e]);
        }
    }

    public function importarProceso(Request $request)
    {
        $_id = $request['cat_id'];
        header('Access-Control-Allow-Origin: *');
        $filename = $_FILES['file']['name'];
        $allowed_extensions = array('json');
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $allowed_extensions)) {
            $json = json_decode(file_get_contents($_FILES['file']['tmp_name']));
            $proceso = $json->proceso;
            \DB::statement("INSERT INTO rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_modelo, prc_usr_id) VALUES
                                ($_id, '" . $proceso[0]->prc_data . "'::json, '" . $proceso[0]->prc_data_campos_clave . "'::json, '" . $proceso[0]->prc_modelo . "'::json, 1)");
            $prc_id = \DB::getPdo()->lastInsertId();
            \DB::statement("UPDATE rmx_vys_procesos SET
				prc_data = jsonb_set(prc_data, '{prc_descripcion}', CONCAT('\"', prc_data->>'prc_descripcion', ' - " . date('Y-m-d H:i:s') . "\"')::jsonb)
				WHERE prc_id = $prc_id");
            $actividades = $json->actividades;
            $i = $j = 0;
            foreach ($actividades as $actividad) {
                \DB::statement("INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_usr_id)
					VALUES ($prc_id, $actividad->act_tact_id, 1, '$actividad->act_data', '$actividad->act_data_reglas', 1)");
                $act_id = \DB::getPdo()->lastInsertId();

                $sql = '';
                $formularios = $json->formularios;
                while ($i < count($formularios)) {
                    if ($actividad->act_id == $formularios[$i]->act_id) {
                        $sql .= ",($act_id, '" . $formularios[$i]->frm_data . "', '" . $formularios[$i]->frm_data_campos . "', '" . str_replace("'", "\"", $formularios[$i]->frm_funciones) . "', 1)";
                        $i++;
                    } else
                        break;
                }
                if (!empty($sql))
                    \DB::statement('INSERT INTO rmx_vys_formularios (frm_act_id, frm_data, frm_data_campos, frm_funciones, frm_usr_id) VALUES ' . substr($sql, 1));
                $sql = '';
                $impresiones = $json->impresiones;
                while ($j < count($impresiones)) {
                    if ($actividad->act_id == $impresiones[$j]->act_id) {
                        $sql .= ",($act_id, '" . $impresiones[$j]->imp_nombre . "', '" . $impresiones[$j]->imp_data . "', 1)";
                        $j++;
                    } else
                        break;
                }
                if (!empty($sql))
                    \DB::statement('INSERT INTO rmx_vys_impresiones (imp_act_id, imp_nombre, imp_data, imp_usr_id) VALUES ' . substr($sql, 1));
                echo 1;
            }
        } else {
            echo 0;
        }
    }

    public function subirAdjunto(Request $request)
    {
        $filepath = $request["fotoPath"];
        $dir = $request["fotoDir"];
        //$filepath = 'aaa.jpg';
        $foto = $request["foto"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            // Remover la parte de la cadena de texto que no necesitamos (data:image/png;base64,)
            // y usar base64_decode para obtener la información binaria de la imagen
            $img = str_replace(' ', '+', $foto);
            $content = base64_decode($img);
            //$content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto));
            if (!is_dir($dir))
                mkdir($dir, 0777, true);
            //$filepath = "img/vys2022/" . $cas_id . "/". $frm_campo . "/name.jpg"; // or image.jpg
            // Finalmente guarda la imágen en el directorio especificado y con la informacion dada
            file_put_contents($filepath, $content);
            return response()->json(["success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function modificarUltUsrNodo(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $ult_usr = $request["ult_usr"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_usrnodos_roundrobin set
                                    rr_ultimo_usr_id = $ult_usr
                                    where rr_nodo_id = $nodo_id and rr_estado = 'A'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUltUsrNodo(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_usrnodos_roundrobin
                                    where rr_nodo_id = $nodo_id and rr_estado = 'A'
                                    limit 1");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsrNodosXNodoId(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT u.*, n.*
                                FROM users u
                                    JOIN rmx_usr_nodos un ON un.usn_user_id = u.id
                                    JOIN rmx_vys_nodos n ON n.nodo_id = un.usn_nodo_id
                                WHERE u.status = 'A'
                                    AND n.nodo_estado = 'A'
                                    AND un.usn_estado = 'A'
                                    AND n.nodo_id = $nodo_id
                                ORDER BY u.id ");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarImpresionId(Request $request)
    {
        $_id = $request['act_id'];
        $imp_id = $request['imp_id'];
        if (!empty($_id)) {
            $where = 'AND imp_act_id = ' . $_id . 'AND imp_id = ' . $imp_id;
        }
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT imp_id, imp_nombre, imp_data, imp_estado,imp_tipo
				FROM rmx_vys_impresiones
				WHERE imp_estado = 'A' $where
				ORDER BY imp_nombre");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarCasosXImpresion(Request $request)
    {
        $usr_id = $request["usr_id"];
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT c.cas_data_valores
				FROM rmx_vys_casos c JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
					JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
					JOIN rmx_usr_nodos n ON c.cas_nodo_id = n.usn_nodo_id
                    join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                    join rmx_vys_nodos on nodo_id = a.act_nodo_id
				WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                    AND n.usn_estado <> 'X'
					AND n.usn_user_id = $usr_id
                    and c.cas_id= $cas_id
                    and c.cas_act_id=$cas_act_id
				ORDER BY c.cas_modificado desc");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
    public function descargarPdf(Request $request)
    {
        $nombreArchivo = $request->input('ruta');
        $rutaCompleta = public_path('archivos_pdf/' . $nombreArchivo);
        if (File::exists($rutaCompleta)) {
            // El archivo existe
            // Puedes realizar acciones adicionales aquí
            return response()->download($rutaCompleta, $nombreArchivo);
        } else {
            // El archivo no existe
            return response()->json(['error' => 'El archivo no existe.']);
        }
    }

    public function generatePdf1()
    {
        // Crear una instancia de TCPDF
        $pdf = new TCPDF();

        // Configuraciones del PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Author');
        $pdf->SetTitle('Hello PDF');
        $pdf->SetSubject('Simple PDF');
        $pdf->SetKeywords('TCPDF, PDF, hello, world');

        // Agregar una página
        $pdf->AddPage();

        // Configuraciones adicionales
        $pdf->SetFont('dejavusans', '', 12);

        // Agregar texto directamente al PDF
        $pdf->Text(10, 20, 'Hello, World!');

        // Agregar una línea
        $pdf->Line(10, 30, 100, 30);

        // Dibujar un rectángulo
        $pdf->Rect(10, 40, 50, 20, 'F');  // 'F' para rellenar el rectángulo

        // Generar y devolver el PDF como una respuesta
        //$output = $pdf->Output('example.pdf', 'S');
        //return response($output)
        //  ->header('Content-Type', 'application/pdf')
        //->header('Content-Disposition', 'inline; filename="example.pdf"');
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);
        return response()->json($base64Content);
    }

    public function generateFormRescepcionDocumento(Request $request)
    {
        $cas_id = $request["cas_id"];
        $tipo_dibujo = $request["tipo"];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        //$firma = $this->generateHtmlFirma($cas_id);
        //echo $request["firma"];
        $firma = $this->generateHtmlFirma($request["firma"]);
        try {
            $formularios = \DB::select("SELECT cas_data->>'TIPO_PROCESO' as tipo_proceso, cas_data ->>'cas_registrado' as cas_registrado, cas_data ->>'cas_cod_id' as cas_cod_id, cas_data_valores, rvc.cas_correlativo
				FROM public.rmx_vys_casos rvc
				WHERE rvc.cas_id = $cas_id");
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
        $mapaClaveValor = [];
        $dataArray = json_decode($formularios[0]->cas_data_valores, true);
        foreach ($dataArray as $elemento) {
            if (isset($elemento['frm_campo'])) {
                $clave = $elemento['frm_campo'];
                $valor = $elemento['frm_value'] ?? null;
                $mapaClaveValor[$clave] = $valor;
            }
        }
        $TIPO_PROCESO = $formularios[0]->tipo_proceso;
        $cas_registrado = $formularios[0]->cas_registrado;
        $cas_cod_id = $formularios[0]->cas_cod_id;

        $dataCorr = \DB::select("select cas_correlativo from public.rmx_vys_casos where cas_id=$cas_id");
        $cas_correlativo = $dataCorr[0]->cas_correlativo;

        $AS_PRIMER_APELLIDO = '';
        $AS_SEGUNDO_APELLIDO = '';
        $AS_APELLIDO_CASADA = '';
        $AS_PRIMER_NOMBRE = '';
        $AS_SEGUNDO_NOMBRE = '';
        $AS_TIPO_DOCUMENTO = '';
        $AS_CI = '';
        $AS_COMPLEMENTO = '';
        $AS_GENERO = '';
        $AS_CUA = '';
        $AS_TELEFONO = '';
        $AS_CELULAR = '';
        $AS_CERT_INSALUBRIDAD = '';
        $CERT_INSALUBRIDAD = '';


        $SOL_PRIMER_APELLIDO = '';
        $SOL_SEGUNDO_APELLIDO = '';
        $SOL_PRIMER_NOMBRE = '';
        $SOL_SEGUNDO_NOMBRE = '';
        $SOL_APELLIDO_CASADA = '';
        $SOL_TIPO_DOCUMENTO = '';
        $SOL_CI = '';
        $SOL_COMPLEMENTO = '';
        $SOL_GENERO = '';
        $SOL_DIRECCION = '';
        $SOL_TELEFONO = '';
        $SOL_CELULAR = '';
        $SOL_CORREO = '';
        $SOL_FAC_REC = '';
        $DOC_FACTURA_RECIBO = '';
        $NOTA_ACLARATORIA = '';

        if ($TIPO_PROCESO != 'GFU' && $TIPO_PROCESO != 'MAHER') {
            $AS_PRIMER_APELLIDO = $mapaClaveValor['AS_PRIMER_APELLIDO'] ?? '';
            $AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? '';
            $AS_APELLIDO_CASADA = $mapaClaveValor['AS_APELLIDO_CASADA'] ?? '';
            $AS_PRIMER_NOMBRE = $mapaClaveValor['AS_PRIMER_NOMBRE'] ?? '';
            $AS_SEGUNDO_NOMBRE = $mapaClaveValor['AS_SEGUNDO_NOMBRE'] ?? '';
            $AS_CI = $mapaClaveValor['AS_CI'] ?? '';
            $AS_COMPLEMENTO = $mapaClaveValor['AS_COMPLEMENTO'] ?? '';
            $AS_GENERO = $mapaClaveValor['AS_GENERO'] ?? '';
            $AS_CUA = $mapaClaveValor['AS_CUA'] ?? '';
            $AS_TELEFONO = $mapaClaveValor['AS_TELEFONO'] == null ? '' : $mapaClaveValor['AS_TELEFONO'] . ' / ';
            $AS_CELULAR = $mapaClaveValor['AS_CELULAR'] ?? '';
        }
        //GFU SIRVE NOTA ACLARATORIA- VERIFICAR EN MAHER
        if ($TIPO_PROCESO == 'GFU' || $TIPO_PROCESO == 'MAHER') {
            $AS_PRIMER_APELLIDO = $mapaClaveValor['AS_PRIMER_APELLIDO'] ?? '';
            $AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? '';
            $AS_APELLIDO_CASADA = $mapaClaveValor['AS_APELLIDO_CASADA'] ?? '';
            $AS_PRIMER_NOMBRE = $mapaClaveValor['AS_PRIMER_NOMBRE'] ?? '';
            $AS_SEGUNDO_NOMBRE = $mapaClaveValor['AS_SEGUNDO_NOMBRE'] ?? '';
            $AS_CI = $mapaClaveValor['AS_CI'] ?? '';
            $AS_COMPLEMENTO = $mapaClaveValor['AS_COMPLEMENTO'] ?? '';
            $AS_GENERO = $mapaClaveValor['AS_GENERO'] ?? '';
            $AS_CUA = $mapaClaveValor['AS_CUA'] ?? '';
            $DOC_FACTURA_RECIBO = $mapaClaveValor['DOC_FACTURA_RECIBO'] ?? '';
            $SOL_FAC_REC = $mapaClaveValor['SOL_FAC_REC'] ?? '';
        }

        if ($TIPO_PROCESO == 'GFU') {
            $NOTA_ACLARATORIA = $mapaClaveValor['NOTA_ACLARATORIA'] ?? '';
        }

        $SOL_DIFERENTE_AS = $mapaClaveValor['SOL_DIFERENTE_AS'] ?? null;

        $SOL_PRIMER_APELLIDO = $mapaClaveValor['SOL_PRIMER_APELLIDO'] ?? '';
        $SOL_SEGUNDO_APELLIDO = $mapaClaveValor['SOL_SEGUNDO_APELLIDO'] ?? '';
        $SOL_APELLIDO_CASADA = $mapaClaveValor['SOL_APELLIDO_CASADA'] ?? '';
        $SOL_PRIMER_NOMBRE = $mapaClaveValor['SOL_PRIMER_NOMBRE'] ?? '';
        $SOL_SEGUNDO_NOMBRE = $mapaClaveValor['SOL_SEGUNDO_NOMBRE'] ?? '';
        $SOL_CI = $mapaClaveValor['SOL_CI'] ?? '';
        $SOL_COMPLEMENTO = $mapaClaveValor['SOL_COMPLEMENTO'] ?? '';
        $SOL_GENERO = $mapaClaveValor['SOL_GENERO'] ?? '';
        $SOL_DIRECCION = $mapaClaveValor['SOL_DIRECCION'] ?? '';
        $SOL_NUM = $mapaClaveValor['SOL_NUM'] ?? '';
        $SOL_ZONA = $mapaClaveValor['SOL_ZONA'] ?? '';
        $SOL_TELEFONO = $mapaClaveValor['SOL_TELEFONO'] == null ? '' : $mapaClaveValor['SOL_TELEFONO'] . ' / ';
        $SOL_CELULAR = $mapaClaveValor['SOL_CELULAR'] ?? '';
        $SOL_CORREO = $mapaClaveValor['SOL_CORREO'] ?? "S/C";
        $AS_TIPO_DOCUMENTO = $mapaClaveValor['AS_TIPO_DOCUMENTO'] ?? '';
        $SOL_TIPO_DOCUMENTO = $mapaClaveValor['SOL_TIPO_DOCUMENTO'] ?? '';


        switch ($AS_TIPO_DOCUMENTO) {
            case 'I':
                $AS_TIPO_DOCUMENTO = 'C.I.';
                break;
            case 'E':
                $AS_TIPO_DOCUMENTO = 'C.E.';
                break;
            default:
                $AS_TIPO_DOCUMENTO = 'P';
        }
        switch ($SOL_TIPO_DOCUMENTO) {
            case 'I':
                $SOL_TIPO_DOCUMENTO = 'C.I.';
                break;
            case 'E':
                $SOL_TIPO_DOCUMENTO = 'C.E.';
                break;
            default:
                $SOL_TIPO_DOCUMENTO = 'P';
        }

        // Crear una instancia de TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // Configuraciones del PDF
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Author');
        $pdf->SetTitle('Hello PDF');
        $pdf->SetSubject('Simple PDF');
        $pdf->SetKeywords('TCPDF, PDF, hello, world');
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->SetFont('dejavusans', '', 10);

        // Agregar una página
        $pdf->AddPage();

        // Configuraciones adicionales
        $pdf->SetFont('dejavusans', '', 12);

        $cuerpox = "";
        $tablaContenido = $cuerpox;
        $cuerpox = "";
        // Escribir HTML en el PDF
        $html = '';
        $grilla = '';
        $grilla_solicitante = '';
        $cabeceza_solicitante = '';
        $cabezera_documentos_asegurado = '';
        $cabezera_adjuntos = '';
        $cabezera_adjuntos_medicos = '';
        $conyugue = '';
        $titular_grilla = '';
        $solicitante_grilla = '';
        $adjunto_grilla = '';
        $adjunto_medico_grilla = '';
        $respaldo_grilla = '';
        $primer_grado_grilla = '';
        $segunda_grado_grilla = '';
        $tercera_grado_grilla = '';
        $firmaDocmuento = '';
        $html .= '
        <style type="text/css">
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
        <style type="text/css">

        td.style14 {
            vertical-align: bottom;
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border-left: 2px solid #000000 !important;
            border-right: none #000000;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
            background-color: white
        }

        td.style17 {
            vertical-align: middle;
            text-align: center;
            border: 1px solid black;
            color: #000000;
            font-family: "Calibri";
            font-size: 11pt;
            background-color: white;
        }

        td.style40 {
            text-align: center;
            border-bottom: 2px solid #000000 !important;
            border-top: 2px solid #000000 !important;
            border: none;
            color: #000000;
            font-size: 11pt;
            background-color: white;
        }
        td.style41 {
            text-align: center;
            border-bottom: 2px solid #000000 !important;

            border: none;
            color: #000000;
            font-size: 11pt;
            background-color: white;
        }
         th.style42 {
            text-align: center;


            border: none;
            color: #000000;


        }
        td.style45 {
            text-align: center;
            color: #000000;
            font-size: 7pt;
            background-color: #CDDAF7;
            border: none;
        }

        td.style71 {
            vertical-align: bottom;
            text-align: left;
            border-bottom: none;
            border: none;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 2px solid #000000 !important;
            background-color: white;
        }

        td.style69 {
            border: none;
            vertical-align: bottom;
            text-align: center;
            font-weight: Light;
            color: #000000;
            font-family: "Verdana", sans-serif;
            font-size: 20pt;
            background-color: white;
        }

        th.style85 {
            text-align: center;
            border-left: 1px solid #000000 !important;
            color: #000000;
            font-family: "Calibri";
            font-size: 8pt;
            font-weight: bold;
            background-color: #FEF2CB;
            border: none;
        }
        </style>
        </head>
        <body>
        <table border="1">
        <thead>
        <tr style="font-size:8px; text-align:center;">
        <th colspan="12"><img src="img/recepcion_documento_3.png" /></th>
        <th colspan="4"><p></p>
         <p> Nro. Caso: ' . $cas_cod_id . '</p>
          <p> Nro. Correlativo: ' . $cas_correlativo . '</p></th>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align:center; font-size:8px;">
        <th colspan="16" class="style85" >DATOS DEL ASEGURADO</th>
        </tr>
        <tr style="font-size:8px; text-align:center;">
        <th colspan="2">Asegurado: </th>
        <th colspan="7">' . htmlspecialchars($AS_PRIMER_APELLIDO . ' ' . $AS_SEGUNDO_APELLIDO . ' ' . $AS_APELLIDO_CASADA . ' ' . $AS_PRIMER_NOMBRE . ' ' . $AS_SEGUNDO_NOMBRE) . '</th>
        <th colspan="1">' . $AS_TIPO_DOCUMENTO . ': </th>
        <th colspan="3">' . htmlspecialchars($AS_CI . ' ' . $AS_COMPLEMENTO) . '</th>
        <th colspan="1">Sexo: </th>
        <th colspan="2">' . htmlspecialchars($AS_GENERO) . '</th>
        </tr>
        <tr style="font-size:8px; text-align:center; ">
        <th colspan="1">CUA: </th>
        <th colspan="5">' . htmlspecialchars($AS_CUA) . '</th> ';

        if ($TIPO_PROCESO != 'GFU' && $TIPO_PROCESO != 'MAHER') {
            $html .= '
            <th colspan="5">Telefono/Celular: </th>
            <th colspan="5">' . htmlspecialchars($AS_TELEFONO . $AS_CELULAR) . '</th>';
        } else {
            $html .= '
            <th colspan="5">Fecha Recepción Documentos: </th>
            <th colspan="5">' . htmlspecialchars($cas_registrado) . '</th>';
        }
        $html .= '</tr>';

        $cabeceza_solicitante .= '
                    <tr style="text-align:center; font-size:8px;">

                    <tr><td class="style40" colspan="16"></td></tr>
                    <th colspan="16"  class="style85" >DATOS DEL SOLICITANTE / DERECHOHABIENTE</th>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                    <th colspan="3">Nombre Completo: </th>
                    <th colspan="6">' . htmlspecialchars($SOL_PRIMER_APELLIDO . ' ' . $SOL_SEGUNDO_APELLIDO . ' ' . $SOL_APELLIDO_CASADA . ' ' . $SOL_PRIMER_NOMBRE . ' ' . $SOL_SEGUNDO_NOMBRE) . '</th>
                    <th colspan="1">' . $SOL_TIPO_DOCUMENTO . ': </th>
                    <th colspan="3">' . htmlspecialchars($SOL_CI . ' ' . $SOL_COMPLEMENTO) . '</th>
                    <th colspan="1">Sexo: </th>
                    <th colspan="2">' . htmlspecialchars($SOL_GENERO) . '</th>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                    <th colspan="2">DIRECCION: </th>
                    <th colspan="7">' . htmlspecialchars($SOL_DIRECCION . ' ' . $SOL_NUM . ' ' . $SOL_ZONA) . '</th>
                    <th colspan="3">Telefono/Celular: </th>
                    <th colspan="4">' . htmlspecialchars($SOL_TELEFONO . $SOL_CELULAR) . '</th>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                    <th colspan="3">Correo electronico: </th>
                    <th colspan="13">' . htmlspecialchars($SOL_CORREO) . '</th>
                    </tr>';
        //DATOS DEL ASEGURADO
        $cabezera_documentos_asegurado .= '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6" class="style45">DOCUMENTOS ASEGURADO</td>
                    <td colspan="1" class="style45">Original</td>
                    <td colspan="1" class="style45">Fotocopia</td>
                    <td colspan="1" class="style45">SEGIP /SERECI</td>
                    <td colspan="1" class="style45">Fecha Emision</td>
                    <td colspan="2" class="style45">Fecha Matrimonio</td>
                    <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

        $sql = "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_categoria = '$AS_CI' and doc_estado ='A' order by doc_id asc";
        $gp_documentos = \DB::select($sql);

        //JUB GFU RMIN funciona para ambos
        if (isset($gp_documentos)) {
            foreach ($gp_documentos as $documentos) {
                $detalle_documentos_tit = '';
                $detalle_documento_solicitante = '';
                $detalle_documento_adjuntos = '';
                $detalle_documento_respaldo = '';
                $true = '';
                $true2 = '';
                $obs = '';
                $parentesco = '';
                $adjunto = '';
                $descripcion_referencia = '';
                $referencia = $documentos->doc_referencia == null ? '' : $documentos->doc_referencia;
                if (strpos($referencia, '-') !== false) {
                    $parentesco_explote = explode("-", $referencia);
                    $parentesco = $parentesco_explote[1];
                } else {
                    $adjunto = $referencia;
                }
                if ($documentos->doc_url != "") {
                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                }
                if ($true == '' && $true2 == '') {
                    $obs = 'Sin Documento';
                } else {
                    $obs = $documentos->doc_detalle_documento;
                }
                if ($parentesco == 'TIT') {
                    $detalle_documentos_tit =
                        '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6">' . $documentos->doc_descripcion . '</td>
                    <td colspan="1">' . $true . '</td>
                    <td colspan="1">' . $true2 . '</td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="2"></td>
                    <td colspan="5">' . $obs . '</td>
                    </tr>';
                    $titular_grilla = $titular_grilla . $detalle_documentos_tit;
                }
                if ($parentesco == 'SOL') {
                    $detalle_documento_solicitante =
                        '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6">' . $documentos->doc_descripcion . '</td>
                    <td colspan="1">' . $true . '</td>
                    <td colspan="1">' . $true2 . '</td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="2"></td>
                    <td colspan="5">' . $obs . '</td>
                    </tr>';
                    $solicitante_grilla .= $detalle_documento_solicitante;
                }
                if ($parentesco == 'RES') {
                    $detalle_documento_respaldo =
                        '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6">' . $documentos->doc_descripcion . '</td>
                    <td colspan="1">' . $true . '</td>
                    <td colspan="1">' . $true2 . '</td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="2"></td>
                    <td colspan="5">' . $obs . '</td>
                    </tr>';
                    $respaldo_grilla .= $detalle_documento_respaldo;
                }
                if ($adjunto == 'ADJUNTOS') {
                    $descripcion_referencia = $documentos->doc_descripcion == '' ? 'Sin Referecia' : $documentos->doc_descripcion;
                    $detalle_documento_adjuntos =
                        '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6">' . $descripcion_referencia . '</td>
                    <td colspan="1">' . $true . '</td>
                    <td colspan="1">' . $true2 . '</td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="2"></td>
                    <td colspan="5">' . $obs . '</td>
                    </tr>';
                    $adjunto_grilla .= $detalle_documento_adjuntos;
                }
                if ($adjunto == 'ADJUNTO_MEDICOS') {
                    $descripcion_adj_medicos = $documentos->doc_descripcion == '' ? 'Sin Referecia' : $documentos->doc_descripcion;
                    $detalle_documento_adjuntos =
                        '<tr style="font-size:8px; text-align:center; ">
                    <td colspan="6">' . $descripcion_adj_medicos . '</td>
                    <td colspan="1">' . $true . '</td>
                    <td colspan="1">' . $true2 . '</td>
                    <td colspan="1"></td>
                    <td colspan="1"></td>
                    <td colspan="2"></td>
                    <td colspan="5">' . $obs . '</td>
                    </tr>';
                    $adjunto_medico_grilla .= $detalle_documento_adjuntos;
                }
            }
        }
        $cabezera_documentos_asegurado .= $titular_grilla;

        //TIT DOCUMENTOS RESPALDO
        if ($respaldo_grilla != '' && $TIPO_PROCESO != 'GFU') {
            $cabezera_documentos_asegurado .= $respaldo_grilla;
        }

        //ARCHIVOS ADJUNTOS
        if ($adjunto_grilla != null) {
            $cabezera_adjuntos .= '
            <tr><td class="style40" colspan="16"></td></tr>
                <tr style=" text-align:center; ">
                <th colspan="16" class="style85">DOCUMENTOS ADJUNTOS</th>
            </tr>
            <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS</td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
            </tr>';
            $cabezera_adjuntos .= $adjunto_grilla;
        }
        //ARCHIVOS ADJUNTOS MEDICOS
        if ($adjunto_medico_grilla != null) {
            $cabezera_adjuntos_medicos .= '
            <tr><td class="style40" colspan="16"></td></tr>
                <tr style=" text-align:center; ">
                <th colspan="16" class="style85">DOCUMENTOS MEDICOS</th>
            </tr>
            <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS</td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
            </tr>';
            $cabezera_adjuntos_medicos .= $adjunto_medico_grilla;
        }

        //RMIN SOLICITANTE
        if ($TIPO_PROCESO != 'GFU' && $TIPO_PROCESO != 'MAHER' && $TIPO_PROCESO != 'JUB') {
            if (!$SOL_DIFERENTE_AS || $SOL_DIFERENTE_AS == null) {
                $grilla_solicitante .= '
                <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS SOLICITANTE </td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
            </tr>';
                $grilla .= $grilla_solicitante . $solicitante_grilla;
            }

            //DATOS DE DERECHO AMBIENTE
            $vacio = empty($mapaClaveValor['GRILLA_DERECHOHABIENTES'][0]);
            if (!$vacio) {
                $grilla .= '
                <tr><td class="style40" colspan="16"></td></tr>
                <tr style="font-size:8px; text-align:center; ">
                <th colspan="16" class="style85">DERECHOHABIENTES</th>
                </tr>';

                foreach ($mapaClaveValor['GRILLA_DERECHOHABIENTES'] as $item) {
                    $parentesco_value = $item[14]['col_value'];
                    $parentesco_explote = explode("-", $parentesco_value);
                    $grado = $parentesco_explote[0];
                    $primer_grado = '';
                    $segundo_grado = '';
                    $tercer_grado = '';
                    //$AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? null;
                    $DH_NOMBRES = $item[6]['col_value'] ?? '';
                    $DH_PRIMER_APELLIDO = $item[7]['col_value'] ?? '';
                    $DH_SEGUNDO_APELLIDO = $item[8]['col_value'] ?? '';
                    $DH_APELLIDO_CASADA = $item[9]['col_value'] ?? '';
                    $DH_CI_GRILLA_PROP = $item[2]['col_value'] ?? '';
                    $DH_COMP_GRILLA_PROP = $item[3]['col_value'] ?? '';
                    $DH_GENERO = $item[10]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                    $DH_IDPERSONA_GRILLA_PROP = $item[1]['col_value'] ?? '';
                    $DH_TIPO_DOCUMENTO = $item[0]['col_value'] ?? '';
                    switch ($DH_TIPO_DOCUMENTO) {
                        case 'I':
                            $DH_TIPO_DOCUMENTO = 'C.I.';
                            break;
                        case 'E':
                            $DH_TIPO_DOCUMENTO = 'C.E.';
                            break;
                        case 'T':
                            $DH_TIPO_DOCUMENTO = 'T.';
                            break;
                        default:
                            $DH_TIPO_DOCUMENTO = 'P';
                    }
                    if ($grado == 1) {
                        $primer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <th colspan="16" class="style85">DATOS DERECHOHABIENTES</th>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_APELLIDO_CASADA . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">1ER. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                        </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                $descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $primer_grado = $primer_grado . $detalle_documentos;
                            }
                        }

                        $primer_grado_grilla = $primer_grado_grilla . $primer_grado;
                    }
                    if ($grado == 2) {
                        $segundo_grado =
                            '<tr><td class="style40" colspan="16"></td></tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="16" class="style85">DATOS DERECHOHABIENTES</th>
                            </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">2DO. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $segundo_grado = $segundo_grado . $detalle_documentos;
                            }
                        }
                        $segunda_grado_grilla = $segunda_grado_grilla . $segundo_grado;
                    }
                    if ($grado == 3) {
                        $tercer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <td colspan="16">DATOS DERECHOHABIENTES</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">3ER. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $tercer_grado = $tercer_grado . $detalle_documentos;
                            }
                        }
                        $tercera_grado_grilla = $tercera_grado_grilla . $tercer_grado;
                    }
                }
            }
        }

        //JUB RMIN SOLICITANTE
        if ($TIPO_PROCESO == 'JUB') {
            if (!$SOL_DIFERENTE_AS || $SOL_DIFERENTE_AS == null) {
                $grilla_solicitante .= '
                <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS SOLICITANTE </td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
            </tr>';
                $grilla .= $grilla_solicitante . $solicitante_grilla;
            }

            //DATOS DE DERECHO AMBIENTE JUB
            $vacio = empty($mapaClaveValor['GRILLA_DERECHOHABIENTES'][0]);
            if (!$vacio) {
                $grilla .= '
                <tr><td class="style40" colspan="16"></td></tr>
                <tr style="font-size:8px; text-align:center; ">
                <th colspan="16" class="style85">DERECHOHABIENTES</th>
                </tr>';

                foreach ($mapaClaveValor['GRILLA_DERECHOHABIENTES'] as $item) {
                    $parentesco_value = $item[16]['col_value'];
                    $parentesco_explote = explode("-", $parentesco_value);
                    $grado = $parentesco_explote[0];
                    $parentesco = $parentesco_explote[1];
                    $primer_grado = '';
                    $segundo_grado = '';
                    $tercer_grado = '';
                    //$AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? null;
                    $DH_NOMBRES = $item[7]['col_value'] ?? '';
                    $DH_PRIMER_APELLIDO = $item[8]['col_value'] ?? '';
                    $DH_SEGUNDO_APELLIDO = $item[9]['col_value'] ?? '';
                    $DH_APELLIDO_CASADA = $item[10]['col_value'] ?? '';
                    $DH_CI_GRILLA_PROP = $item[3]['col_value'] ?? '';
                    $DH_COMP_GRILLA_PROP = $item[4]['col_value'] ?? '';
                    $DH_GENERO = $item[11]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                    $DH_IDPERSONA_GRILLA_PROP = $item[2]['col_value'] ?? '';
                    $DH_TIPO_DOCUMENTO = $item[1]['col_value'] ?? '';
                    switch ($DH_TIPO_DOCUMENTO) {
                        case 'I':
                            $DH_TIPO_DOCUMENTO = 'C.I.';
                            break;
                        case 'E':
                            $DH_TIPO_DOCUMENTO = 'C.E.';
                            break;
                        case 'T':
                            $DH_TIPO_DOCUMENTO = 'T.';
                            break;
                        default:
                            $DH_TIPO_DOCUMENTO = 'P';
                    }
                    if ($parentesco == 'CONY') {
                        $DH_MATRIMONIO = $item[19]['col_value'] ?? '';
                        $DH_FECHA_EMISION_MATRIMONIO = $item[18]['col_value'] ?? '';
                        if (!empty($DH_MATRIMONIO)) {
                            $fecha_partes = explode('-', $DH_MATRIMONIO);
                            $DH_MATRIMONIO = $fecha_partes[2] . '-' . $fecha_partes[1] . '-' . $fecha_partes[0];
                        }
                        if (!empty($DH_FECHA_EMISION_MATRIMONIO)) {
                            $fecha_partes2 = explode('-', $DH_FECHA_EMISION_MATRIMONIO);
                            $DH_FECHA_EMISION_MATRIMONIO = $fecha_partes2[2] . '-' . $fecha_partes2[1] . '-' . $fecha_partes2[0];
                        }
                    }
                    if ($grado == 1) {
                        $primer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <th colspan="16" class="style85">DATOS DERECHOHABIENTES</th>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="7">Nombre Completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_APELLIDO_CASADA . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="3">Parentesco: ' . $parentesco . '</td>
                        <td colspan="3">Sexo: ' . $DH_GENERO . '</td>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">1ER. GRADO</td>
                        <td colspan="4" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="2" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                        </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                $descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                if ($descripcion == 'Certificado de Matrimonio') {
                                    $detalle_documentos =
                                        '<tr style="font-size:8px; text-align:center; ">
                                    <td colspan="5">' . $descripcion . '</td>
                                    <td colspan="1">' . $true . '</td>
                                    <td colspan="1">' . $true2 . '</td>
                                    <td colspan="1"></td>
                                    <td colspan="2">' . $DH_MATRIMONIO . '</td>
                                    <td colspan="2">' . $DH_FECHA_EMISION_MATRIMONIO . '</td>
                                    <td colspan="5">' . $obs . '</td>
                                    </tr>';
                                    $primer_grado = $primer_grado . $detalle_documentos;
                                } else {
                                    $detalle_documentos =
                                        '<tr style="font-size:8px; text-align:center; ">
                                    <td colspan="5">' . $descripcion . '</td>
                                    <td colspan="1">' . $true . '</td>
                                    <td colspan="1">' . $true2 . '</td>
                                    <td colspan="1"></td>
                                    <td colspan="2"></td>
                                    <td colspan="2"></td>
                                    <td colspan="5">' . $obs . '</td>
                                    </tr>';
                                    $primer_grado = $primer_grado . $detalle_documentos;
                                }
                            }
                        }

                        $primer_grado_grilla = $primer_grado_grilla . $primer_grado;
                    }
                    if ($grado == 2) {
                        $segundo_grado =
                            '<tr><td class="style40" colspan="16"></td></tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="16" class="style85">DATOS DERECHOHABIENTES</th>
                            </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="7">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="3">Parentesco: ' . $parentesco . '</td>
                        <td colspan="3">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">2DO. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                <td colspan="1">' . $true . '</td>
                                <td colspan="1">' . $true2 . '</td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="2"></td>
                                <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $segundo_grado = $segundo_grado . $detalle_documentos;
                            }
                        }
                        $segunda_grado_grilla = $segunda_grado_grilla . $segundo_grado;
                    }
                    if ($grado == 3) {
                        $tercer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <td colspan="16">DATOS DERECHOHABIENTES</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="7">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="3">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="3">Parentesco: ' . $parentesco . '</td>
                        <td colspan="3">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">3ER. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO</td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip = '$DH_IDPERSONA_GRILLA_PROP' and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                <td colspan="1">' . $true . '</td>
                                <td colspan="1">' . $true2 . '</td>
                                <td colspan="1"></td>
                                <td colspan="1"></td>
                                <td colspan="2"></td>
                                <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $tercer_grado = $tercer_grado . $detalle_documentos;
                            }
                        }
                        $tercera_grado_grilla = $tercera_grado_grilla . $tercer_grado;
                    }
                }
            }
        }

        if ($TIPO_PROCESO == 'GFU') {
            //SOLICITANTE ES DIFERENTE DEL ASEGURADO GFU
            if (!$SOL_DIFERENTE_AS || $SOL_DIFERENTE_AS == null) {
                $grilla_solicitante .= '
                <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS SOLICITANTE</td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
                </tr>';
                $grilla .= $grilla_solicitante . $solicitante_grilla;
            }

            //SI TIENE FACTURA O RECIBO GFU
            $true = '';
            $true2 = '';
            $obs = '';
            if ($DOC_FACTURA_RECIBO != "") {
                $true = 'X';
                //$true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
            }
            if ($true == '' && $true2 == '') {
                $obs = 'Sin Documento';
            }

            $grilla .= '
            <tr><td  colspan="16"></td></tr>';
            $grilla .= '<tr style="font-size:8px; text-align:center; ">
            <td colspan="6" class="style45">DOCUMENTOS PRESENTADOS</td>
            <td colspan="1" class="style45">Original</td>
            <td colspan="1" class="style45">Fotocopia</td>
            <td colspan="1" class="style45">SEGIP /SERECI</td>
            <td colspan="1" class="style45">Fecha Emision</td>
            <td colspan="2" class="style45">Fecha Matrimonio</td>
            <td colspan="5" class="style45">Observaciones</td>
            </tr>
            <tr style="font-size:8px; text-align:center; ">
            <td colspan="6">' . $SOL_FAC_REC . '</td>
            <td colspan="1">' . $true . '</td>
            <td colspan="1">' . $true2 . '</td>
            <td colspan="1"></td>
            <td colspan="1"></td>
            <td colspan="2"></td>
            <td colspan="5">' . $obs . '</td>
            </tr>';


            //DATOS DE DERECHO AMBIENTE GFU
            $vacio = empty($mapaClaveValor['GRILLA_DERECHOHABIENTES'][0]);

            if (!$vacio) {
                $grilla .= '
                <tr><td colspan="16"></td></tr>
                <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">TESTIGO</td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
                </tr>';

                foreach ($mapaClaveValor['GRILLA_DERECHOHABIENTES'] as $item) {
                    $DH_IDPERSONA_GRILLA_PROP = $item[1]['col_value'] ?? '';
                    $DH_CI_GRILLA_PROP = $item[2]['col_value'] ?? '';
                    $DT_COMP_GRILLA_PROP = $item[3]['col_value'] ?? '';
                    $DT_SEGUNDO_APELLIDO = $item[8]['col_value'] ?? '';
                    $DT_PRIMER_APELLIDO = $item[7]['col_value'] ?? '';
                    $DT_NOMBRES = $item[6]['col_value'] ?? '';
                    $DT_APELLIDO_CASADA = $item[9]['col_value'] ?? '';

                    $gp_documentos = \DB::select(
                        "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip::integer =$DH_IDPERSONA_GRILLA_PROP and doc_estado ='A' order by doc_id asc"
                    );

                    if (isset($gp_documentos)) {
                        foreach ($gp_documentos as $documentos) {
                            $detalle_documentos = '';
                            $true = '';
                            $true2 = '';
                            $obs = '';
                            $descripcion = $documentos->doc_descripcion;
                            if ($documentos->doc_url != "") {
                                $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                            }
                            if ($true == '' && $true2 == '') {
                                $obs = 'Sin Documento';
                            } else {
                                $obs = $documentos->doc_detalle_documento;
                            }
                            $detalle_documentos =
                                '<tr style="font-size:8px; text-align:center; ">
                              <td colspan="6">' . $DH_CI_GRILLA_PROP . ' ' . $DT_COMP_GRILLA_PROP . ' - ' . $DT_PRIMER_APELLIDO . ' ' . $DT_SEGUNDO_APELLIDO . ' ' . ' ' . $DT_APELLIDO_CASADA . ' ' . $DT_NOMBRES . '</td>
                              <td colspan="1">' . $true . '</td>
                              <td colspan="1">' . $true2 . '</td>
                              <td colspan="1"></td>
                              <td colspan="1"></td>
                              <td colspan="2"></td>
                              <td colspan="5">' . $obs . '</td>
                            </tr>';
                            $grilla .= $detalle_documentos;
                        }
                    }
                }
            }
        }

        if ($TIPO_PROCESO == 'MAHER') {
            //SOLICITANTE ES DIFERENTE DEL ASEGURADO MAHER

            if (!$SOL_DIFERENTE_AS || $SOL_DIFERENTE_AS == null) {
                $grilla_solicitante .= '
                <tr style="font-size:8px; text-align:center; ">
                <td colspan="6" class="style45">DOCUMENTOS SOLICITANTE </td>
                <td colspan="1" class="style45">Original</td>
                <td colspan="1" class="style45">Fotocopia</td>
                <td colspan="1" class="style45">SEGIP /SERECI</td>
                <td colspan="1" class="style45">Fecha Emision</td>
                <td colspan="2" class="style45">Fecha Matrimonio</td>
                <td colspan="5" class="style45">Observaciones</td>
            </tr>';
                $grilla .= $grilla_solicitante . $solicitante_grilla;
            }

            //DATOS DE DERECHO AMBIENTE MAHER
            $vacio = empty($mapaClaveValor['GRILLA_DERECHOHABIENTES'][0]);
            if (!$vacio) {
                $grilla .= '
                <tr><td class="style40" colspan="16"></td></tr>
                <tr style="font-size:8px; text-align:center; ">
                <th colspan="16" class="style85">HEREDEROS</th>
                </tr>';

                foreach ($mapaClaveValor['GRILLA_DERECHOHABIENTES'] as $item) {
                    //////////////////////////////MAHER_v2
                    // $parentesco_value = $item[19]['col_value'];
                    // $parentesco_explote = explode("-", $parentesco_value);
                    // $grado = $parentesco_explote[0];
                    // $primer_grado = '';
                    // $segundo_grado = '';
                    // $tercer_grado = '';
                    // //$AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? null;
                    // $DH_NOMBRES = $item[8]['col_value'] ?? '';
                    // $DH_PRIMER_APELLIDO = $item[9]['col_value'] ?? '';
                    // $DH_SEGUNDO_APELLIDO = $item[10]['col_value'] ?? '';
                    // $DH_APELLIDO_CASADA = $item[11]['col_value'] ?? '';
                    // $DH_CI_GRILLA_PROP = $item[4]['col_value'] ?? '';
                    // $DH_COMP_GRILLA_PROP = $item[5]['col_value'] ?? '';
                    // $DH_GENERO = $item[12]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                    // $DH_IDPERSONA_GRILLA_PROP = $item[3]['col_value'] ?? '';
                    // $DH_TIPO_DOCUMENTO = $item[2]['col_value'] ?? '';
                    /////----

                    $parentesco_value = $item[18]['col_value'];
                    $parentesco_explote = explode("-", $parentesco_value);
                    $grado = $parentesco_explote[0];
                    $primer_grado = '';
                    $segundo_grado = '';
                    $tercer_grado = '';
                    //$AS_SEGUNDO_APELLIDO = $mapaClaveValor['AS_SEGUNDO_APELLIDO'] ?? null;
                    $DH_NOMBRES = $item[7]['col_value'] ?? '';
                    $DH_PRIMER_APELLIDO = $item[8]['col_value'] ?? '';
                    $DH_SEGUNDO_APELLIDO = $item[9]['col_value'] ?? '';
                    $DH_APELLIDO_CASADA = $item[10]['col_value'] ?? '';
                    $DH_CI_GRILLA_PROP = $item[3]['col_value'] ?? '';
                    $DH_COMP_GRILLA_PROP = $item[4]['col_value'] ?? '';
                    $DH_GENERO = $item[11]['col_value'] === 'M' ? 'Masculino' : 'Femenino';
                    $DH_IDPERSONA_GRILLA_PROP = $item[2]['col_value'] ?? '';
                    $DH_TIPO_DOCUMENTO = $item[1]['col_value'] ?? '';
                    //////////-----
                    switch ($DH_TIPO_DOCUMENTO) {
                        case 'I':
                            $DH_TIPO_DOCUMENTO = 'C.I.';
                            break;
                        case 'E':
                            $DH_TIPO_DOCUMENTO = 'C.E.';
                            break;
                        case 'T':
                            $DH_TIPO_DOCUMENTO = 'T.';
                            break;
                        default:
                            $DH_TIPO_DOCUMENTO = 'P';
                    }
                    if ($grado == 1) {
                        $primer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <th colspan="16" class="style85">DATOS HEREDEROS</th>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_APELLIDO_CASADA . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="4">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                        </tr>
                        <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">HEREDEROS</td>
                        <td colspan="5" class="style45">DOCUMENTO </td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                        </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip::integer =$DH_IDPERSONA_GRILLA_PROP and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                $descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $primer_grado = $primer_grado . $detalle_documentos;
                            }
                        }

                        $primer_grado_grilla = $primer_grado_grilla . $primer_grado;
                    }
                    if ($grado == 2) {
                        $segundo_grado =
                            '<tr><td class="style40" colspan="16"></td></tr>
                            <tr style="font-size:8px; text-align:center; ">
                                <th colspan="16" class="style85">DATOS DERECHOHABIENTES</th>
                            </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="4">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">2DO. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO </td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip::integer =$DH_IDPERSONA_GRILLA_PROP and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $segundo_grado = $segundo_grado . $detalle_documentos;
                            }
                        }
                        $segunda_grado_grilla = $segunda_grado_grilla . $segundo_grado;
                    }
                    if ($grado == 3) {
                        $tercer_grado =
                            '<tr style="font-size:8px; text-align:center; ">
                        <td colspan="16">DATOS DERECHOHABIENTES</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="8">Nombre completo: ' . $DH_PRIMER_APELLIDO . ' ' . $DH_SEGUNDO_APELLIDO . ' ' . $DH_NOMBRES . '</td>
                        <td colspan="4">' . $DH_TIPO_DOCUMENTO . ': ' . $DH_CI_GRILLA_PROP . ' ' . $DH_COMP_GRILLA_PROP . '</td>
                        <td colspan="4">Sexo: ' . $DH_GENERO . '</td>
                    </tr>
                    <tr style="font-size:8px; text-align:center; ">
                        <td colspan="1" class="style45">3ER. GRADO</td>
                        <td colspan="5" class="style45">DOCUMENTO </td>
                        <td colspan="1" class="style45">Original</td>
                        <td colspan="1" class="style45">Fotocopia</td>
                        <td colspan="1" class="style45">SEGIP /SERECI</td>
                        <td colspan="1" class="style45">Fecha Emision</td>
                        <td colspan="2" class="style45">Fecha Matrimonio</td>
                        <td colspan="5" class="style45">Observaciones</td>
                    </tr>';

                        $gp_documentos = \DB::select(
                            "SELECT * from  public._gp_documentos where doc_cas_id = $cas_id and doc_id_persona_sip::integer =$DH_IDPERSONA_GRILLA_PROP and doc_estado ='A' order by doc_id asc"
                        );

                        if (isset($gp_documentos)) {
                            foreach ($gp_documentos as $documentos) {
                                $detalle_documentos = '';
                                $true = '';
                                $true2 = '';
                                $obs = '';
                                //$descripcion = $documentos->doc_descripcion;
                                if ($documentos->doc_url != "") {
                                    $true = $documentos->doc_copia_original === 'true' ? '' : 'X';
                                    $true2 = $documentos->doc_copia_original === 'true' ? 'X' : '';
                                }
                                if ($true == '' && $true2 == '') {
                                    $obs = 'Sin Documento';
                                } else {
                                    $obs = $documentos->doc_detalle_documento;
                                }
                                $detalle_documentos =
                                    '<tr style="font-size:8px; text-align:center; ">
                                  <td colspan="6">' . $documentos->doc_descripcion . '</td>
                                  <td colspan="1">' . $true . '</td>
                                  <td colspan="1">' . $true2 . '</td>
                                  <td colspan="1"></td>
                                  <td colspan="1"></td>
                                  <td colspan="2"></td>
                                  <td colspan="5">' . $obs . '</td>
                                </tr>';
                                $tercer_grado = $tercer_grado . $detalle_documentos;
                            }
                        }
                        $tercera_grado_grilla = $tercera_grado_grilla . $tercer_grado;
                    }
                }
            }
        }



        $firmaDocmuento =
            '<tr style="border: none;">
                <td colspan="16"></td>
            </tr>
            <tr>
                <td colspan="8" class="style14" rowspan ="3">' . $firma . '</td>
                <td colspan="8" class="style14" rowspan ="3"></td>

            </tr>
                <tr><td colspan="16"></td></tr>
                <tr><td colspan="16"></td></tr>

            <tr style="font-size:8px; text-align:center; ">
                <td colspan="8"> FIRMA DEL SOLICITANTE </td>
                <td colspan="8"> FIRMA DE RECEPCIÓN RESPONSABLE DE GESTORA </td>
            </tr>
           ';
        $finmja = '
                    </tbody>
                </table>
            </body>
        </html>';
        $html = $html . $cabezera_documentos_asegurado . $cabeceza_solicitante . $grilla . $primer_grado_grilla . $segunda_grado_grilla . $tercera_grado_grilla . $cabezera_adjuntos . $cabezera_adjuntos_medicos . $firmaDocmuento . $finmja;
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();
        $pdfAsBase64 = $pdf->Output('', 'S');
        $base64Content = base64_encode($pdfAsBase64);

        if ($tipo_dibujo == 'Dibujar') {
            return array("mensaje" => $base64Content);
        } else {
            return array("mensaje" => $pdfAsBase64);
        }
    }

    /*public function generateHtmlFirma($cas_id)
    {
        $data = \DB::select("select  doc_url from  public._gp_documentos where doc_descripcion = 'Firma del Solicitante' and doc_cas_id = $cas_id");
        if ($data != []) {
            $imagen = $data[0]->doc_url;
            $firma = ' <img src="' . $imagen . '" style="height:50px; width:150px border: none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }
        return $firma;
    }*/
    public function generateHtmlFirma($firmaSolicitante)
    {
        if ($firmaSolicitante !== null && $firmaSolicitante !== '') {
            $firma = '<img src="data:image/png;base64,' . $firmaSolicitante . '" style="height:50px; width:150px; border:none;" />';
        } else {
            $firma = ' <img src="" style="height:50px; width:150px border: none;" />';
        }
        return $firma;
    }

    public function listarCasosXImpresion12(Request $request)
    {
        $usr_id = $request["usr_id"];
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
    }

    public function dataSolicitudPrestacionES(Request $request)
    {
        $cas_act_id = $request["cas_act_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $data = \DB::select("SELECT * from  public.obtener_valor_grilla_prop ('$cas_act_id')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    /*public function setIDSolitudPrestacion(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $idsolicitudprestacion = $request["idsolicitudprestacion"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            // Ejecutar la consulta SQL
            $results = \DB::select("SELECT c.cas_data_valores
            FROM rmx_vys_casos c
            WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                AND c.cas_id = $cas_id
            ORDER BY c.cas_modificado DESC");
            // print_r( $results);
            // Verificar si hay resultados
            if (!empty($results)) {

                $jsonOriginal = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $dataJson = json_decode($jsonOriginal, true);

                foreach ($dataJson as &$item) {
                    if ($item['frm_campo'] === 'ID_SOLICITUDPRESTACION') {
                        // Agregar el campo "frm_value_label"
                        $item['frm_value_label'] = $idsolicitudprestacion;
                    }
                }

                $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);

                $data = \DB::select("UPDATE rmx_vys_casos SET
                    cas_data_valores = '$jsonActualizado'::json,
                    cas_modificado = now()
                    --,cas_usr_id = $cas_usr_id
                    where cas_id = $cas_id");
                return response()->json(["data" => $data, "success" => $success]);

            } else {
                // No se encontraron resultados

                return response()->json(["data" => "No se encontraron resultados para cas_id $cas_id.", "success" => $success]);
            }
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }*/
    public function setIDSolitudPrestacion(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $idsolicitudprestacion = $request["idsolicitudprestacion"];

        try {
            // Obtener los datos originales
            $results = \DB::select("SELECT c.cas_data_valores
            FROM rmx_vys_casos c
            WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                AND c.cas_id = $cas_id
            ORDER BY c.cas_modificado DESC");


            // Verificar si hay resultados
            if (!empty($results)) {
                $jsonOriginal = $results[0]->cas_data_valores;
                // Decodificar el JSON
                $dataJson = json_decode($jsonOriginal, true);

                // Buscar y actualizar el valor si existe
                $foundField = false;
                foreach ($dataJson as &$item) {
                    if ($item['frm_campo'] === 'ID_SOLICITUDPRESTACION') {
                        $foundField = true;
                        // Verificar si el campo tiene 'frm_value'
                        if (array_key_exists('frm_value', $item)) {
                            // Modificar el valor existente
                            $item['frm_value'] = $idsolicitudprestacion;
                        } else {
                            // $foundField = false;
                            // Agregar el campo 'frm_value'
                            $item['frm_value'] = $idsolicitudprestacion;
                        }

                        // Actualizar la base de datos con el dato modificado
                        $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);


                        /*$data = \DB::select("UPDATE rmx_vys_casos SET
                        cas_data_valores = '$jsonActualizado'::json,
                        cas_modificado = now()
                        --,cas_usr_id = $cas_usr_id
                        WHERE cas_id = $cas_id");*/
                        $data = \DB::select(
                            "UPDATE rmx_vys_casos SET
                        cas_data_valores = ?::json,
                        cas_modificado = now()
                        --,cas_usr_id = $cas_usr_id
                        WHERE cas_id = ?",
                            [$jsonActualizado, $cas_id]
                        );

                        return response()->json(["data" => $data, "success" => $success, "message" => "El valor ha sido modificado correctamente."]);
                    }
                }

                // Si no se encontró el campo, puedes agregarlo al array
                if (!$foundField) {
                    $dataJson[] = array(
                        "frm_campo" => "ID_SOLICITUDPRESTACION",
                        "frm_value" => $idsolicitudprestacion,
                        "frm_deshabilitado" => "false",
                        "frm_deshabilitadoo" => false
                    );

                    // Actualizar la base de datos con los datos modificados
                    $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);
                    $data = \DB::select("UPDATE rmx_vys_casos SET
                    cas_data_valores = '$jsonActualizado'::json,
                    cas_modificado = now()
                    --,cas_usr_id = $cas_usr_id
                    WHERE cas_id = $cas_id");

                    return response()->json(["data" => $data, "success" => $success, "message" => "Se agregó el campo 'ID_SOLICITUDPRESTACION' correctamente."]);
                }
            } else {
                // No se encontraron resultados
                return response()->json(["data" => "No se encontraron resultados para cas_id $cas_id.", "success" => $success]);
            }
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function SeguimientoTramites(Request $request)
    {
        $cas_id = $request["cas_id"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(" select c.cas_data, c.cas_data->>'cas_cod_id' as codigoSolicitud,
            cas_data->>'USUARIO_REGISTRO' as usuario_registro ,TO_CHAR((cas_data->>'cas_registrado')::date, 'YYYY-MM-DD HH24:MI:SS') AS formatted_date,
            (
                select datos.valor->>'frm_value'
                from (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_id
                        FROM public.rmx_vys_casos
                    ) datos
                where datos.valor->>'frm_campo' = 'ID_SOLICITUDPRESTACION'
                and datos.cas_id = c.cas_id) as ID_SOLICITUDPRESTACIONN
            FROM
            rmx_vys_casos c
            JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                    JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                    join rmx_vys_tipos_actividad on tact_id = a.act_tact_id
                    join rmx_vys_nodos on nodo_id = a.act_nodo_id
        WHERE
            c.cas_id = $cas_id
            AND (c.cas_estado <> 'X' AND c.cas_estado <> 'H')  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function VerificacionNodo(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(" SELECT count(*) as cant
            FROM rmx_vys_casos c
                JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                join rmx_vys_nodos on nodo_id = a.act_nodo_id
            WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                and c.cas_id= $cas_id
                and c.cas_nodo_id =82
                and a.act_data->>'act_orden'='40'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function VerificacionNodoParalelo(Request $request)
    {
        $cas_id = $request["cas_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(" SELECT count(*) as cant
            FROM rmx_vys_casos c
                JOIN rmx_vys_actividades a ON a.act_id = c.cas_act_id
                JOIN rmx_vys_procesos p ON p.prc_id = a.act_prc_id
                join rmx_vys_nodos on nodo_id = a.act_nodo_id
            WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                and c.cas_padre_id= $cas_id
                and c.cas_nodo_id =82
                and a.act_data->>'act_orden'='40';");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function tomarcaso(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.sp_obtener_caso($cas_id,$cas_usr_id)");


            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function tomarcasoOv(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];

        $data = \DB::select(
            "SELECT u.id, u.name, u.email,
                    u.status, gr.id_sip_regional as id_regional , descripcion_doc as _regional,
                    gd.id_sip_departamento as _id_departamento, gd.descripcion_dep as _departamento,
                    ga.id_sip_agencia as _id_agencia, ga.descripcion_agencia as _agencia
                    FROM users u
                INNER JOIN roles r ON u.role_id = r.id
                LEFT JOIN gp_regional gr ON gr.id_sip_regional = u.id_regional
                LEFT JOIN gp_departamento gd ON gd.id_sip_departamento = u.id_departamento
                LEFT JOIN gp_agencia ga ON ga.id_sip_agencia = u.id_agencia
                WHERE u.id = $cas_usr_id
                ORDER BY u.name;"
        );

        $id_agencia = $data[0]->_id_agencia;
        $agencia = $data[0]->_agencia;

        $id_regional = $data[0]->id_regional;
        $regional = $data[0]->_regional;

        $id_departamento = $data[0]->_id_departamento;
        $departamento = $data[0]->_departamento;

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.sp_obtener_caso_ov(
                    $cas_id,$cas_usr_id, $cas_usr_id,
                    $id_departamento, '$departamento',
                    $id_regional, '$regional',
                    $id_agencia, '$agencia')");

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function enmienda(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $cas_observacion = $request["cas_observacion"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $data = \DB::select("SELECT * FROM public.enmienda($cas_id,  $cas_usr_id,'$cas_estado','$cas_observacion')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function derivarenmienda(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $cas_estado = $request["cas_estado"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {

            $data = \DB::select("SELECT * FROM public.derivarenmienda($cas_id,  $cas_usr_id,'$cas_estado')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function getId_solicitudprestacion(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);

        $cas_id = $request["cas_id"];
        $idsolicitudprestacion = $request["idsolicitudprestacion"];

        try {
            $data = \DB::select("
                select subquery.frm_campo,subquery.frm_value
                FROM (
                    SELECT
                        jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_campo' AS frm_campo,
                        jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_value' AS frm_value
                    FROM
                        rmx_vys_casos c
                    WHERE
                        c.cas_id = $cas_id
                        AND (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                ) subquery
                WHERE subquery.frm_campo IN ('ID_SOLICITUDPRESTACION'); ");

            if ($data[0]->frm_value === null || $data[0]->frm_value === '') {
                $results = \DB::select("SELECT c.cas_data_valores
                    FROM rmx_vys_casos c
                    WHERE (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                        AND c.cas_id = $cas_id
                    ORDER BY c.cas_modificado DESC");

                if (!empty($results)) {

                    $jsonOriginal = $results[0]->cas_data_valores;
                    $dataJson = json_decode($jsonOriginal, true);

                    foreach ($dataJson as &$item) {
                        if ($item['frm_campo'] === 'ID_SOLICITUDPRESTACION') {
                            if (array_key_exists('frm_value', $item)) {
                                $item['frm_value'] = $idsolicitudprestacion;

                                $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);

                                $data_update = \DB::select(
                                    "UPDATE rmx_vys_casos SET
                                    cas_data_valores = ?::json,
                                    cas_modificado = now()
                                    WHERE cas_id = ?",
                                    [$jsonActualizado, $cas_id]
                                );

                                $data = \DB::select("
                                    select subquery.frm_campo,subquery.frm_value
                                    FROM (
                                        SELECT
                                            jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_campo' AS frm_campo,
                                            jsonb_array_elements(c.cas_data_valores::jsonb)->>'frm_value' AS frm_value
                                        FROM
                                            rmx_vys_casos c
                                        WHERE
                                            c.cas_id = $cas_id
                                            AND (c.cas_estado <> 'X' AND c.cas_estado <> 'H')
                                    ) subquery
                                    WHERE subquery.frm_campo IN ('ID_SOLICITUDPRESTACION'); ");

                                return response()->json(["data" => $data, "success" => $success, "message" => "El valor ha sido modificado correctamente."]);
                            } else {
                                if (array_key_exists('frm_value', $item)) {
                                    $item['frm_value'] = $idsolicitudprestacion;
                                } else {
                                    $item['frm_value'] = $idsolicitudprestacion;
                                }

                                $jsonActualizado = json_encode($dataJson, JSON_PRETTY_PRINT);

                                $data = \DB::select(
                                    "UPDATE rmx_vys_casos SET
                                    cas_data_valores = ?::json,
                                    cas_modificado = now()
                                    WHERE cas_id = ?",
                                    [$jsonActualizado, $cas_id]
                                );

                                return response()->json(["data" => $data, "success" => $success, "message" => "El valor ha sido modificado correctamente."]);
                            }
                        }
                    }
                } else {
                    return response()->json(["data" => "No se encontraron resultados para cas_id $cas_id.", "success" => $success]);
                }
            } else {
                return response()->json(["data" => $data, "success" => $success]);
            }
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsuariosNodo(Request $request)
    {
        $nodoId = $request["nodo_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::table('users as u')
                ->join('rmx_usr_nodos as run', 'run.usn_user_id', '=', 'u.id')
                ->join('rmx_vys_nodos as rvn', 'run.usn_nodo_id', '=', 'rvn.nodo_id')
                ->where('run.usn_estado', '<>', 'X')
                ->where('run.usn_estado', '<>', 'E')
                ->where('rvn.nodo_estado', '<>', 'X')
                ->where('rvn.nodo_id', $nodoId)
                ->select('u.id', 'u.name', 'u.email')
                ->distinct()
                ->orderBy('u.name')
                ->get();

            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function asignarCaso(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $descripcion = $request["descripcion"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.sp_obtener_caso_asignacion($cas_id, $cas_usr_id, '$descripcion')");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function asignarCasoMasivo(Request $request)
    {
        $cas_ids = $request["cas_ids"];
        $cadena_ids = json_encode($cas_ids);
        $cas_usr_id = $request["cas_usr_id"];
        $descripcion = $request["descripcion"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select public.sp_obtener_caso_asignacion_multiple(array$cadena_ids, $cas_usr_id, '$descripcion')");
            return response()->json(["data" => true, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarContratosCasos(Request $request)
    {
        $cas_id = $request['cas_id'];
        $act_id = $request['act_id'];
        //dd($act_id);
        $condicion = "";

        if ($act_id == 263) {
            $condicion = " OR gd.doc_descripcion like '%DIRNOPLU%' ";
        }

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {

            $sqlQuery = "select distinct on (gd.doc_cas_id)
                                gd.doc_url as url_documento
                                , gd.doc_descripcion as imp_nombre
                                , 'Firma Contrato' as imp_tipo_firma
                                , gd.doc_codigo
                                , gd.doc_referencia
                                , gd.doc_categoria
                                , gd.doc_doc_id
                                , gd.doc_detalle_documento
                                , gd.doc_cas_id
                                , gd.doc_id
                                from _gp_documentos gd
                                where gd.doc_referencia != 'ADJUNTOS'
                                and gd.doc_cas_id in ($cas_id)
                                and ((gd.doc_descripcion ilike 'CNU%'
                                    or (gd.doc_descripcion ilike 'CONTRATO%' and gd.doc_descripcion not like '%FIRMADO%'
                                        and not exists (select 1
                                                        from _gp_documentos gd1
                                                        where gd1.doc_descripcion ilike 'CNU%'
                                                        and gd1.doc_referencia != 'ADJUNTOS'
                                                        and gd1.doc_cas_id = gd.doc_cas_id)
                                        )
                                    )
                                 $condicion
                                    OR gd.doc_descripcion like 'FORMULARIO DE REVISION GERENCIA NACIONAL LEGAL' )
                                order by gd.doc_cas_id, gd.doc_registrado desc
                                ;";

            $data = \DB::select($sqlQuery);
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function resetearFirmaDoc(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $cas_id = $request['cas_id'];
        $act_id = $request['act_id'];
        $tipo = $request['tipo'];

        try {
            $resetDocs = \DB::select("
                UPDATE _gp_documentos
                SET doc_firmado = 1
                WHERE (doc_descripcion ILIKE 'CONTRATO%' OR doc_descripcion LIKE 'CNU%')
                AND doc_cas_id = $cas_id
            ");

            return response()->json(['data' => $resetDocs, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function resetearFirmaDocDesp(Request $request)
    {
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);

        $cas_id = $request['cas_id'];
        $act_id = $request['act_id'];
        $tipo = $request['tipo'];

        try {
            $resetDocs = \DB::select("
                UPDATE _gp_documentos
                SET doc_firmado = 0
                WHERE (doc_descripcion ILIKE 'CONTRATO%' OR doc_descripcion LIKE 'CNU%')
                AND doc_cas_id = $cas_id
            ");

            return response()->json(['data' => $resetDocs, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function listarDocumentosFirma(Request $request)
    {
        $cas_id = $request['cas_id'];
        $act_id = $request['act_id'];
        $tipo = $request['tipo'];
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        $impact_imp_id = 0;
        $cond = '';
        if ($tipo == 'atender')
            $cond = ' and impact_estado_documento = 10';
        try {
            $data = \DB::select("select gd.doc_url as url_documento
                                , gd.doc_descripcion as imp_nombre
                                , 'Firma Contrato' as imp_tipo_firma
                                , doc_codigo
                                , doc_referencia
                                , doc_categoria
                                , doc_doc_id
                                , doc_cas_id
                                , doc_id
                                ,impact_rubrica->>'p_x' as x
                                ,impact_rubrica->>'p_y' as y
                                ,impact_rubrica->>'pagina' as pagina
                                from _gp_documentos gd
                                inner join rmx_vys_impresiones_actividades on impact_id = doc_impact_id
                                where gd.doc_cas_id in (  $cas_id) and impact_imp_id in (select impact_imp_id
                                        from rmx_vys_impresiones_actividades rvia
                                        where impact_act_id = $act_id  and impact_estado = 'A' $cond  )
                                    and doc_estado = 'A'  ");
            //dd($data);
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }


    public function derivarCasosFirma(Request $request)
    {
        $cas_id = $request["cas_id"];
        $cas_act_id = $request["cas_act_id"];
        $cas_nodo_id = $request["cas_nodo_id"];
        $cas_usr_id = $request["cas_usr_id"];
        $estado_derivacion = $request["estado_derivacion"];
        $cas_estado = $request["cas_estado"];

        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT * FROM public.sp_derivar_caso_firma(?, ?, ?, ?, ?, ?)",
                [$cas_id, $cas_act_id, $cas_nodo_id, $cas_usr_id, $estado_derivacion, $cas_estado]
            );
            return response()->json(["data" => $data[0]->sp_derivar_caso_firma, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function buscarDocumentosCasos(Request $request)
    {
        $prc_codigo = $request["prc_codigo"] === null ? '%' : $request["prc_codigo"];
        $cas_nro_caso = $request["cas_nro_caso"] === null ? '%' : $request["cas_nro_caso"];
        $cas_gestion = $request["cas_gestion"] === null ? '%' : $request["cas_gestion"];

        $doc_codigo = $prc_codigo . '/' . $cas_nro_caso . '/' . $cas_gestion;
        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select(
                "SELECT *
                FROM _gp_documentos
                inner join rmx_vys_casos on doc_codigo = cas_cod_id
                inner join rmx_vys_actividades on act_id = cas_act_id
                inner join rmx_vys_nodos on nodo_id = cas_nodo_id
                inner join users on id = cas_usr_id
                WHERE doc_codigo = '$doc_codigo' and doc_estado = 'A'
                ORDER BY doc_registrado desc"
            );
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->jsion(['error' => $error]);
        }
    }

    public function datosSeguimientoTramite($casId)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $casId);
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $casId);

        try {
            $resultado = \DB::select('SELECT public.sp_datos_seguimiento_tramite(?) AS datos', [$casId]);

            if (!empty($resultado)) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['data'] = $resultado[0]->datos;
                return response()->json($success);
            } else {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['data'] = 'No se encontraron datos para el caso ID: ' . $casId;
                return response()->json($error);
            }
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function datosDictamenRegistro($casId)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => $casId);
        $error = array("codigoRespuesta" => 400, "mensaje" => 'Error', "fecha" => '', "data" => $casId);

        try {
            $resultado = \DB::select('SELECT public.sp_datos_seguimiento_tramite_invalidez(?) AS datos', [$casId]);

            if (!empty($resultado)) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['data'] = $resultado[0]->datos;
                return response()->json($success);
            } else {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $error['fecha'] = $fechaFormateada;
                $error['data'] = 'No se encontraron datos para el caso ID: ' . $casId;
                return response()->json($error);
            }
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }
    public function datosSeguimientoTramiteUpdate(Request $request)
    {
        $cas_cod_id = $request["cas_cod_id"];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error = array('mensaje' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select(
                "
                        select cas_data->'ID_SOLICITUDPRESTACION' as id_solicitud_prestaciones, (
                                SELECT datos.valor->>'frm_value_label'
                                FROM (
                                    SELECT jsonb_array_elements(cas_data_valores) AS valor
                                ) datos
                                WHERE datos.valor->>'frm_campo' = 'ESTADO_DERIVACION'
                            ) as estado_derivacion,   (
                                SELECT datos.valor->>'frm_value'
                                FROM (
                                    SELECT jsonb_array_elements(cas_data_valores) AS valor
                                ) datos
                                WHERE datos.valor->>'frm_campo' = 'AS_ENTE_GESTOR'
                            ) as ente_gestor,  TO_CHAR(NOW(), 'YYYY-MM-DD') as fecha
                        from public.rmx_vys_casos rvc
                        where cas_cod_id = '$cas_cod_id' "
            );
            return response()->json(['data' => $data, 'codigoRespuesta' => $success]);
        } catch (error $e) {

            return response()->jsion(['data' => [], 'codigoRespuesta' => $error]);
        }
    }

    public function duplicarCasoJubilacion(Request $request)
    {
        $cas_cua = $request["cas_cua"];
        $cas_new_id = $request["cas_new_id"];
        $success = array("code" => 200, "mensaje" => 'OK',);
        $error = array("mensaje" => "error de instancia", "code" => 500);
        try {
            $dataRespon = \DB::select("select * from public.sp_duplicar_jub($cas_cua, $cas_new_id)");
            return response()->json(["data" => $dataRespon, "codigoRespuesta" => $success]);
        } catch (error $e) {
            return response()->json(["data" => [], "codigoRespuesta" => $error]);
        }
    }


    public function validarBoleta(Request $request)
    {
        $cas_cua = $request->input("cas_cua");

        $url = "https://pruebas.gestora.bo/seguridad-apis/api/autenticar";
        $data1 = [
            'username' => 'wsprestaciones',
            'password' => 'Prestaciones2023'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $token = $response["token"];
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token, // Aquí agregas el token en el header ultimo clasificacion de de un solo dato
            ])->get('https://pruebas.gestora.bo/reporte-boletas-pago/api/v1/boletasJubilacionTitularByCua?cuaAsegurado=' . $cas_cua);
            return $response2;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function boletasPendientesCobro(Request $request)
    {
        $cas_cua = $request->input("cas_cua");
        $url = "https://pruebas.gestora.bo/seguridad-apis/api/autenticar";
        $data1 = [
            'username' => 'wsprestaciones',
            'password' => 'Prestaciones2023'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $token = $response["token"];
            $response2 = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token, // Aquí agregas el token en el header
            ])->get('https://pruebas.gestora.bo/reporte-boletas-pago/api/v1/boletasPendientesCobro?cuaAsegurado=' . $cas_cua);
            return $response2;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function boletasPago(Request $request)
    {
        $cas_cua = $request->input("cas_cua");
        $url = "https://pruebas.gestora.bo/seguridad-apis/api/autenticar";
        $data1 = [
            'username' => 'wsprestaciones',
            'password' => 'Prestaciones2023'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $token = $response["token"];
            $response2 = Http::withHeaders([])->get('https://planilla-prestacion.gestora.bo/api/v1/boletasJubilacionMaestroPago?cuaAsegurado=' . $cas_cua);
            return $response2;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function buscarBeneficiario1582(Request $request)
    {
        $tipoDoc = $request->input("tipo_documento");
        $numDoc = $request->input("numero_documento");
        $complemento = $request->input("complemento");
        $fecNac = $request->input("fecha_nacimiento");
        $error = array("message" => "error de instancia", "code" => 500);
        $url = urlGestora() . "/spr-nls-rest/api/consulta/prestaciones?tipoDoc=" . $tipoDoc . "&numDoc=" . $numDoc . "&complemento=" . $complemento . "&fecNac=" . $fecNac;
        //dd($url);
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->get($url);
            $response = $response->json();
            // echo($response);
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function verificarAsegurado1582($cua)
    {
        $cua = $cua;
        $url = urlPersonas() . "/api/v1/personasip/buscaPersonaRegistroDirectoSip";
        $data1 = [
            'cua' => $cua,
            'tipoBusqueda' => 'T'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $codigoRespuesta = $response["codigoRespuesta"];
            $mensaje = $response["mensaje"];
            $data = $response["data"];
            return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function buscarAsegurado1582($cua)
    {
        $cua = $cua;
        $url = urlPersonas() . "/api/v1/personasip/buscaPersonaRegistroDirectoSip";
        $data1 = [
            'cua' => $cua,
            'tipoBusqueda' => 'T'
        ];
        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $data1);
            $response = $response->json();
            $codigoRespuesta = $response["codigoRespuesta"];
            $mensaje = $response["mensaje"];
            $data = $response["data"];
            if ($mensaje == 'La persona no se encuentra en la Base de Datos de Aseguramiento.') {
                $data1 = [
                    'cua' => $cua,
                    'tipoBusqueda' => 'NRF'
                ];
                $response = Http::acceptJson()->withHeaders([
                    'Content-Type' => 'application/json'
                ])->post($url, $data1);
                $response = $response->json();
                $codigoRespuesta = $response["codigoRespuesta"];
                $mensaje = $response["mensaje"];
                $data = $response["data"];
                $respuesta = array("codigoRespuesta" => $codigoRespuesta, "mensaje" => $mensaje, "data" => $data);
            } else {
                $respuesta = array("codigoRespuesta" => $codigoRespuesta, "mensaje" => $mensaje, "data" => $data);
            }
            return $respuesta;
            //return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function beneficiarioParentesco($cua)
    {
        $success = ["codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["codigoRespuesta" => 500, "fecha" => '', "message" => "Error de instancia", "data" => 'Error'];

        $url = urlautenticarPlanillas();
        $payload = obtenerCredencialesPlanillas();

        try {
            $responseToken = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            $responseToken = $responseToken->json();
            $token = $responseToken["token"];

            $responseServicio = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get(urlPlanillas() . '/api/v1/beneficiarioParentesco?cuaAsegurado=' . $cua);

            if ($responseServicio->json()['codigo'] == "0") {
                $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $success['data'] = $responseServicio->json();
                return response()->json($success);
            } else {
                $noencontrado['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $noencontrado['data'] = $responseServicio->json();
                return response()->json($noencontrado);
            }
        } catch (Exception $e) {
            $error['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function cenPersonaSIP($id_persona_sip)
    {
        $success = ["_codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => 'Correcto'];
        $noencontrado = ["_codigoRespuesta" => 400, "mensaje" => 'No encontrado', "fecha" => '', "data" => 'Error'];
        $error = ["_codigoRespuesta" => 500, "fecha" => '', "message" => "Error de instancia", "data" => 'Error'];

        try {
            $response = Http::get(urlPersonas() . '/api/v1/personasip/persona/' . $id_persona_sip);
            if ($response->json()['codigoRespuesta'] == "0000") {
                $success['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $success['data'] = $response->json();
                return response()->json($success);
            } else {
                $noencontrado['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
                $noencontrado['data'] = $response->json();
                return response()->json($noencontrado);
            }
        } catch (Exception $e) {
            $error['fecha'] = (new \DateTime())->format('Y-m-d H:i:s');
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function cargar1582(Request $request)
    {
        $id = $request->input("id");
        $cua = $request->input("cua");
        $tipoConsumo = $request->input("tipoConsumo");

        $datosBusqueda = $this->buscarAsegurado1582($cua);

        if ($datosBusqueda["codigoRespuesta"] == 0) {
            //dd($datosBusqueda["mensaje"]);
            $caso = $this->grabarCasosAqui($request);
            $datosAsegurado = $datosBusqueda["data"];
            $cas_id = $caso["data"];
            $codigo = $caso["codigo"];
            $tipo = $request->input("tipo");
            $detalles = json_encode($request->input("detalles"));
            $valor = '';
            $valorLabel = '';
            //dd($detalles);
            if ($tipo == 'AUTOMATICO') {
                $valor = 'CVEAP-B1';
                $valorLabel = '1. SOLICITUD DE JUBILACIÓN (LEY 1582) - AUTOMATICO';
            } else {
                $valor = 'CVEAP-B2';
                $valorLabel = '2. SOLICITUD DE JUBILACIÓN (LEY 1582) - MANUAL';
            }
            try {
                $tipoIdentidad = $datosAsegurado["tipoIdentidad"];
                $documentoIdentidad = $datosAsegurado["documentoIdentidad"];
                $complemento = $datosAsegurado["complemento"];
                $cua = $datosAsegurado["cua"];
                $primerNombre = $datosAsegurado["primerNombre"];
                $primerNombre = str_replace("'", "''", $primerNombre);
                $segundoNombre = $datosAsegurado["segundoNombre"];
                $segundoNombre = str_replace("'", "''", $segundoNombre);
                $primerApellido = $datosAsegurado["primerApellido"];
                $primerApellido = str_replace("'", "''", $primerApellido);
                $segundoApellido = $datosAsegurado["segundoApellido"];
                $segundoApellido = str_replace("'", "''", $segundoApellido);
                $apellidoCasada = $datosAsegurado["apellidoCasada"];
                $apellidoCasada = str_replace("'", "''", $apellidoCasada);
                $fechaNacimiento = $datosAsegurado["fechaNacimiento"];
                $idGenero = $datosAsegurado["idGenero"];
                $idEstadoCivil = $datosAsegurado["idEstadoCivil"];
                $telefonoCelular = $datosAsegurado["telefonoCelular"];
                $telefonoFijo = $datosAsegurado["telefonoFijo"];
                $idPersonaSip = $datosAsegurado["idPersonaSip"];
                if ($tipoIdentidad == 'I')
                    $tipoIdentidadValue = 'CEDULA IDENTIDAD';
                if ($tipoIdentidad == 'E')
                    $tipoIdentidadValue = 'EXTRANJERO';
                if ($tipoIdentidad == 'P')
                    $tipoIdentidadValue = 'PASAPORTE';

                $idEstadoCivilValue = 'CASADO(A)';
                if ($idEstadoCivil == 'C')
                    $idEstadoCivilValue = 'CASADO(A)';
                if ($idEstadoCivil == 'S')
                    $idEstadoCivilValue = 'SOLTERO(A)';
                if ($idEstadoCivil == 'D')
                    $idEstadoCivilValue = 'DIVORCIADO(A)';
                if ($idEstadoCivil == 'V')
                    $idEstadoCivilValue = 'VIUDO(A)';

                if ($idGenero == 'M')
                    $idGeneroValue = 'MASCULINO';
                if ($idGenero == 'F')
                    $idGeneroValue = 'FEMENINO';

                DB::table('rmx_vys_casos')
                    ->where('cas_id', $cas_id)
                    ->update([
                        'cas_data_valores' => DB::raw("
                        cas_data_valores ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_TIPO_EAP\", \"frm_value\": \"$valor\", \"frm_value_label\": \"$valorLabel\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DATE\", \"frm_campo\": \"FORM_JUB_FECHA\", \"frm_value\": \"2024-10-01\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_TIPO_DOCUMENTO\", \"frm_value\": \"$tipoIdentidad\", \"frm_value_label\": \"$tipoIdentidadValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_CI\", \"frm_value\": \"$documentoIdentidad\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_COMPLEMENTO\", \"frm_value\": \"$complemento\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DATE\", \"frm_campo\": \"AS_NACIMIENTO\", \"frm_value\": \"$fechaNacimiento\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_CUA\", \"frm_value\": \"$cua\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_PRIMER_APELLIDO\", \"frm_value\": \"$primerApellido\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_SEGUNDO_APELLIDO\", \"frm_value\": \"$segundoApellido\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_APELLIDO_CASADA\", \"frm_value\": \"$apellidoCasada\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_PRIMER_NOMBRE\", \"frm_value\": \"$primerNombre\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_SEGUNDO_NOMBRE\", \"frm_value\": \"$segundoNombre\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"DROPDOWNLIST\", \"frm_campo\": \"AS_ESTADO_CIVIL\", \"frm_value\": \"$idEstadoCivilValue\", \"frm_value_label\": \"$idEstadoCivilValue\",\"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_GENERO\", \"frm_value\": \"$idGeneroValue\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_WHATSAPP\", \"frm_value\": \"$telefonoCelular\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TEXT\", \"frm_campo\": \"AS_TELEFONO\", \"frm_value\": \"$telefonoFijo\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"NUMBER\", \"frm_campo\": \"AS_CELULAR\", \"frm_value\": \"$telefonoCelular\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"TABLA\", \"frm_campo\": \"DATA_DETALLES\", \"frm_value\": $detalles}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"AS_IDPERSONA\", \"frm_value\": \"$idPersonaSip\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb ||
                        '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"UID\", \"frm_value\": \"$id\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                    ")
                    ]);


                DB::table('rmx_vys_casos')
                    ->where('cas_id', $cas_id)
                    ->update([
                        'cas_data' => DB::raw("
                            cas_data || jsonb_build_object('AS_CI', '$documentoIdentidad')
                            || jsonb_build_object('UID', '$id')
                            || jsonb_build_object('cas_nro_caso', '$cas_id')
                            || jsonb_build_object('cas_cod_id', '$codigo')
                            || jsonb_build_object('AS_CUA', '$cua')
                            || jsonb_build_object('AS_CORREO', '')
                            || jsonb_build_object('AS_CELULAR', '$telefonoCelular')
                            || jsonb_build_object('AS_TIPO_EAP', '$valorLabel')
                            || jsonb_build_object('AS_COMPLEMENTO', '$complemento')
                            || jsonb_build_object('AS_PRIMER_NOMBRE', '$primerNombre')
                            || jsonb_build_object('AS_SEGUNDO_NOMBRE', '$segundoNombre')
                            || jsonb_build_object('AS_TIPO_DOCUMENTO', '$tipoIdentidadValue')
                            || jsonb_build_object('AS_PRIMER_APELLIDO', '$primerApellido')
                            || jsonb_build_object('AS_SEGUNDO_APELLIDO', '$segundoApellido')
                            || jsonb_build_object('cas_nombre_caso', '$documentoIdentidad|$primerNombre|$segundoNombre|$primerApellido|$segundoApellido')
                        ")
                    ]);

                $respBenefParen = $this->beneficiarioParentesco($cua);

                $respBenefParenArray = json_decode($respBenefParen->getContent(), true);
                $codigoRespuesta = $respBenefParenArray['codigoRespuesta'];

                if ($codigoRespuesta == 200) {
                    $beneficiarios = $respBenefParenArray["data"]["data"];

                    $cas_data_valores = [];

                    foreach ($beneficiarios as $beneficiario) {
                        $f_id_persona_sip = $beneficiario["f_id_persona_sip"];
                        $parentesco = $beneficiario["parentesco"];
                        $ente_gestor_salud = $beneficiario["ente_gestor_salud"];

                        $responseCenPersonaSIP = $this->cenPersonaSIP($f_id_persona_sip);
                        $responseCenPersonaSIPArray = json_decode($responseCenPersonaSIP->getContent(), true);

                        if ($responseCenPersonaSIPArray['_codigoRespuesta'] == 200) {

                            $apiEstado = $responseCenPersonaSIPArray['data']['data']['apiEstado'];
                            $idTipoDocumentoIdentidad = $responseCenPersonaSIPArray['data']['data']['idTipoDocumentoIdentidad'];
                            $numeroDocumento = $responseCenPersonaSIPArray['data']['data']['numeroDocumento'];
                            $complemento = $responseCenPersonaSIPArray['data']['data']['complemento'];
                            $fechaNacimiento = $responseCenPersonaSIPArray['data']['data']['fechaNacimiento']; // 1948-12-27T04:00:00.000+00:00
                            $fechaNacimiento = substr($fechaNacimiento, 0, 10); // formateando la fecha
                            $primerNombre = $responseCenPersonaSIPArray['data']['data']['primerNombre'];
                            $segundoNombre = $responseCenPersonaSIPArray['data']['data']['segundoNombre'];
                            $primerApellido = $responseCenPersonaSIPArray['data']['data']['primerApellido'];
                            $segundoApellido = $responseCenPersonaSIPArray['data']['data']['segundoApellido'];
                            $apellidoEsposo = $responseCenPersonaSIPArray['data']['data']['apellidoEsposo'];
                            $idGenero = $responseCenPersonaSIPArray['data']['data']['idGenero'];

                            $cas_data_valores[] = [
                                ["col_campo" => "DH_TIPO_DOCUMENTO", "col_value" => $idTipoDocumentoIdentidad],
                                ["col_campo" => "DH_IDPERSONA_GRILLA_PROP", "col_value" => $f_id_persona_sip],
                                ["col_campo" => "DH_CI_GRILLA_PROP", "col_value" => $numeroDocumento],
                                ["col_campo" => "DH_COMP_GRILLA_PROP", "col_value" => $complemento],
                                ["col_campo" => "DH_FECHA_NAC", "col_value" => $fechaNacimiento],
                                ["col_campo" => "DH_NOMBRES", "col_value" => $primerNombre . ' ' . $segundoNombre],
                                ["col_campo" => "DH_PRIMER_APELLIDO", "col_value" => $primerApellido],
                                ["col_campo" => "DH_SEGUNDO_APELLIDO", "col_value" => $segundoApellido],
                                ["col_campo" => "DH_APELLIDO_CASADA", "col_value" => $apellidoEsposo],
                                ["col_campo" => "DH_GENERO", "col_value" => $idGenero],
                                ["col_campo" => "DH_ENTE_GESTOR_SALUD", "col_value" => $ente_gestor_salud],
                                ["col_campo" => "DH_PARENTESCO", "col_value" => $parentesco]
                            ];
                        }
                    }

                    $cas_data_valores_json = json_encode($cas_data_valores);

                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $cas_id)
                        ->update([
                            'cas_data_valores' => DB::raw("cas_data_valores || '{\"frm_tipo\": \"GRID_1582\", \"frm_campo\": \"GRILLA_DERECHOHABIENTES\", \"frm_value\": $cas_data_valores_json, \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'")
                        ]);

                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $cas_id)
                        ->update([
                            'cas_data_valores' => DB::raw("
                                cas_data_valores ||
                                '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"AS_ENTE_GESTOR\", \"frm_value\": \"$ente_gestor_salud\", \"frm_value_label\": \"null\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                            ")
                        ]);
                } else {
                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $cas_id)
                        ->update([
                            'cas_data_valores' => DB::raw("
                                cas_data_valores ||
                                '{\"frm_tipo\": \"HIDDEN\", \"frm_campo\": \"AS_ENTE_GESTOR\", \"frm_value\": \"null\", \"frm_value_label\": \"null\", \"frm_deshabilitado\": \"true\", \"frm_deshabilitadoo\": true}'::jsonb
                            ")
                        ]);
                }

                $datosTram = array(
                    '_id' => $id,
                    '_tipoActualizacion' => $tipoConsumo,
                    '_rutaDocumento' => 'dddd',
                    '_nroTramite' => $codigo,
                    '_usuario' => 'TRAMITESIP'
                );
                $requestEnvio = new Request($datosTram);
                $resultadoPres = $this->prestaciones1582($requestEnvio);
                // return $cas_id;
                return response()->json(["data" => $cas_id, "codigoRespuesta" => 200, "codigo" => $codigo]);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(["data" => 0, "codigoRespuesta" => 201, "mensaje" => $datosBusqueda["mensaje"]]);
        }
    }

    public function prestaciones1582(Request $request)
    {
        $id = $request->input("_id");
        $tipoActualizacion = $request->input("_tipoActualizacion");
        $rutaDocumento = $request->input("_rutaDocumento");
        $nroTramite = $request->input("_nroTramite");
        $usuario = $request->input("_usuario");

        $error = array("message" => "error de instancia", "code" => 500);

        $url = urlGestora() . "/spr-nls-rest/api/consulta/prestaciones";

        $params = [
            'id' => $id,
            'tipoActualizacion' => $tipoActualizacion,
            'rutaDocumento' => $rutaDocumento,
            'nroTramite' => $nroTramite,
            'usuario' => $usuario
        ];

        try {
            $response = Http::acceptJson()->withHeaders([
                'Content-Type' => 'application/json'
            ])->put($url, $params);

            $response = $response->json();
            return $response;
        } catch (Exception $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function generarTokenServicios(Request $request)
    {
        $url = "https://pruebas.gestora.bo/auth-sip-des/realms/servicios/protocol/openid-connect/token";

        $data1 = [
            'grant_type' => 'client_credentials',
            'client_id' => 'client_spr-nls-rest_tramites',
            'client_secret' => '8wJHpxP7JvwtmcO7PdjG9f4qlihoPkir'
        ];

        try {
            $response = Http::asForm()->post($url, $data1);

            $response = $response->json();
            return $response;
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function consultaPrestacionesConToken(Request $request)
    {
        $url = "https://cali-sipre.gestora.bo/spr-nls-rest/api/consulta/prestaciones";
        $tokenResponse = $this->generarTokenServicios($request);
        $token = $tokenResponse['access_token'];

        $data = [
            "id" => "930a862b-f605-4c18-ae53-0580e4834a51",
            "tipoActualizacion" => "G",
            "rutaDocumento" => "dfdsfad",
            "nroTramite" => "JUB/2",
            "usuario" => "cesad.piaes"
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->put($url, $data);

            return response()->json($response->json());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function actualizarDatosContrato(Request $request)
    {
        $responseTemplate = [
            "codigoRespuesta" => null,
            "mensaje" => '',
            "fecha" => now()->format('Y-m-d H:i:s'),
            "data" => $request->input('cas_id'),
        ];

        try {
            // Validar entrada
            $request->validate([
                'cas_id' => 'required|integer',
                'cua' => 'required|integer',
            ]);

            $casCodId = $request->input('cas_id');
            $cua = $request->input('cua');
            $estado = '';

            //dd($casCodId);
            //dd($cua);
            // Consumir servicio externo
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->get(urlGestora() . "/spr-nls-rest/api/consulta/prestaciones/cua?cua={$cua}", [
                    'headers' => [
                        'Accept' => 'application/json',
                        'x-csrf-token' => '381wUX7slWEaFUsUR64QzMSDTIDky5bZEZtD4aLo',
                        'x-requested-with' => 'XMLHttpRequest',
                    ],
                ]);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                throw new \Exception("Error al conectarse al servicio externo: " . $e->getMessage());
            }

            $externalData = json_decode($response->getBody(), true);

            $mensaje_prestaciones = $externalData['mensaje'];
            $codigo_prestaciones = $externalData['codigo'];

            // Validar la respuesta del servicio
            if (!$externalData || !isset($externalData['codigo'])) {
                throw new \Exception("La respuesta del servicio no es válida o está incompleta.");
            }

            // Determinar estado basado en la respuesta del servicio
            if ($externalData['codigo'] != "200") {
                $estado = ""; //'RECHAZADO';
            } else {
                $estado = $externalData['data']['accede'] ?? 'INDEFINIDO';
            }

            // Manejo de cadena para reporteRechazo
            $cadena = $externalData['data']['reporteRechazo'] ?? '';
            $aprobado = $externalData['data']['aprobado'] ?? '';
            if ($aprobado == 'NO')
                $estado = '';

            // Construir y ejecutar consulta de actualización
            $updateQuery = $this->construirUpdateQuery($casCodId, $estado, $cadena);
            \DB::select($updateQuery);

            // Actualizar DATA_DETALLES si el estado no es "RECHAZADO"
            if ($estado != 'RECHAZADO' && isset($externalData['data']['detalles'][0])) {
                $detallesExterno = $externalData['data']['detalles'];
                $this->actualizarDataDetalles($casCodId, $detallesExterno);
            }

            // Respuesta exitosa
            return response()->json(array_merge($responseTemplate, [
                "codigoRespuesta" => 200,
                "mensaje" => 'Correcto',
                "casId" => $casCodId,
                "estado" => $estado,
                "mensaje_prestaciones" => $mensaje_prestaciones,
                "codigo_prestaciones" => $codigo_prestaciones,
            ]));
        } catch (\Exception $e) {
            // Manejo de errores
            \Log::error("Error en actualizarDatosContrato: {$e->getMessage()}", [
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(array_merge($responseTemplate, [
                "codigoRespuesta" => 400,
                "mensaje" => 'Error',
                "data" => $e->getMessage(),
            ]));
        }
    }

/*
        //$client = new Client();
        // $loginCredenciales = $this->token();
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
            $error["message"] = $e->getMessage() ?? "Bad request.";
            return response()->json($error, 500);
        }
    }*/

    private function construirUpdateQuery($casCodId, $estado, $cadena)
    {
        return "WITH updated_json AS (
        SELECT cas_id,
            jsonb_agg(
                CASE
                    WHEN elem->>'frm_campo' = 'ESTADO_DERIVACION' THEN
                        jsonb_set(
                            jsonb_set(elem, '{frm_value}', '\"$estado\"'),
                            '{frm_value_label}', '\"$estado\"'
                        )
                    WHEN elem->>'frm_campo' = 'RECHAZO' THEN jsonb_set(elem, '{frm_value}', '\"$cadena\"')
                    WHEN elem->>'frm_campo' = 'ESTA_RZT' THEN jsonb_set(elem, '{frm_value}', '\"$estado\"')
                    ELSE elem
                END
            ) AS updated_json
        FROM public.rmx_vys_casos, jsonb_array_elements(cas_data_valores) AS elem
        WHERE cas_id = '$casCodId' AND cas_estado = 'T'
        GROUP BY cas_id
    )
    UPDATE public.rmx_vys_casos
    SET cas_data_valores = updated_json.updated_json
    FROM updated_json
    WHERE public.rmx_vys_casos.cas_id = updated_json.cas_id;";
    }

    private function actualizarDataDetalles($casCodId, $detallesExterno)
    {
        $detalles = $detallesExterno;
        $registro = DB::table('rmx_vys_casos')
            ->where('cas_id', $casCodId)
            ->first();
        if (!$registro) {
            throw new \Exception("Registro con cas_id {$casCodId} no encontrado.");
        }
        $valoresActuales = json_decode($registro->cas_data_valores, true) ?: [];
        $found = false;
        foreach ($valoresActuales as &$campo) {
            if ($campo['frm_campo'] === 'DATA_DETALLES') {
                $campo['frm_value'] = $detalles;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $valoresActuales[] = [
                'frm_campo' => 'DATA_DETALLES',
                'frm_value' => $detalles,
            ];
        }
        DB::table('rmx_vys_casos')
            ->where('cas_id', $casCodId)
            ->update([
                'cas_data_valores' => json_encode($valoresActuales, JSON_UNESCAPED_UNICODE)
            ]);
    }
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
        $success = array("code" => 200, "mensaje" => 'OK',);
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
            $error["message"] = $e->getMessage() ?? "Bad request.";
            return response()->json($error, 500);
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
            if (!isset($dataPrimerNivel) || count($dataPrimerNivel) === 0) {
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
                    "idPersonaSip" => isset($solicitanteIdPersonaSip["frm_value"]) && $solicitanteIdPersonaSip["frm_value"] !== null && $solicitanteIdPersonaSip["frm_value"] !== "null" && $solicitanteIdPersonaSip["frm_value"] !== "" ? $solicitanteIdPersonaSip["frm_value"] : $titularIdPersonaSip["frm_value"],
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
                $item->codigoEnteGestorSalud = !isset($titularEnteGestor) || $titularEnteGestor["frm_value"] === null || $titularEnteGestor["frm_value"] === "null" ? null : $titularEnteGestor["frm_value"];
                // $item->fechaSolicitud = "2025-01-13"; //
                $item->fechaSolicitud = isset($fechaSolicitud["frm_value"]) && $fechaSolicitud["frm_value"] !== null && $fechaSolicitud["frm_value"] !== "null" && $fechaSolicitud["frm_value"] !== "" ? $fechaSolicitud["frm_value"] : "2024-10-01";
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

    public function datosSeguimientoTramLegaldDuplicados(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => '');
        $alert = array("codigoRespuesta" => 400, "mensaje" => 'Alerta', "fecha" => '', "data" => 'Alerta');
        $error = array("codigoRespuesta" => 500, "mensaje" => 'Error', "fecha" => '', "data" => 'Error');

        try {
            $casId = $request->input('cas_id');
            $nroTramite = $request->input('nroTramite');
            $ci = $request->input('ci');
            $cua = $request->input('cua');

            $resultado = \DB::select('SELECT public.sp_datos_seguimiento_tramite_duplicados(?, ?, ?, ?) AS datos', [$casId, $nroTramite, $ci, $cua]);

            if (!empty($resultado) && $resultado[0]->datos !== null) {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                $success['data'] = json_decode($resultado[0]->datos, true);
                return response()->json($success);
            } else {
                $fechaActual = new \DateTime();
                $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
                $alert['fecha'] = $fechaFormateada;
                $alert['data'] = 'No se encontraron datos para el caso ID: ' . $casId;
                return response()->json($alert, 400);
            }
        } catch (\Exception $e) {
            $fechaActual = new \DateTime();
            $fechaFormateada = $fechaActual->format('Y-m-d H:i:s');
            $error['fecha'] = $fechaFormateada;
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function calculosSemiAutomaticos(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => '');
        $error = array("codigoRespuesta" => 500, "mensaje" => 'Error', "fecha" => '', "data" => 'Error');

        $numeroContrato = $request->input('numeroContrato'); // 69434
        $cuaAsegurado = $request->input('cuaAsegurado'); // 17961105
        $usuario = $request->input('usuario'); // operador7

        $numeroTramite = $request->input('numeroTramite'); // para fines de logs

        $url = urlsggTest() . "/compensacion-cotizacion/api/v1/calculosSemiAutomaticos/tramitesProcesados?numeroContrato=$numeroContrato&cuaAsegurado=$cuaAsegurado&usuario=$usuario";
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request_body = json_encode([
            'numeroContrato' => $numeroContrato,
            'cuaAsegurado' => $cuaAsegurado,
            'usuario' => $usuario
        ]);

        $headers = '{"Content-Type": "application/json"}';
        $ip = request()->ip();

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            $responseBody = $response->getBody()->getContents();
            $responseBodyDecoded = json_decode($responseBody, true);

            $codigo = isset($responseBodyDecoded['codigo']) ? $responseBodyDecoded['codigo'] : '200';
            $responseBody_log = json_encode($responseBodyDecoded);

            guardarServiceLog('POST', $url, $request_body, $responseBody_log, $codigo, $headers, $ip, $usuario, $numeroTramite, 'CALCULOS_SEMI_AUTOMATICOS');

            if (isset($responseBodyDecoded['codigo']) && $responseBodyDecoded['codigo'] == "200") {
                $success['data'] = $responseBodyDecoded['data'];
                $success['fecha'] = now()->format('Y-m-d H:i:s');
                $success['cantidad'] = $responseBodyDecoded['cantidad'];
                return response()->json($success);
            } elseif (isset($responseBodyDecoded['codigo']) && $responseBodyDecoded['codigo'] == "201") {
                $success['codigoRespuesta'] = 201;
                $success['mensaje'] = $responseBodyDecoded['mensaje'];
                $success['data'] = $responseBodyDecoded['data'];
                return response()->json($success);
            } else {
                $error['data'] = $responseBodyDecoded['mensaje'] ?? 'Error desconocido';
                return response()->json($error);
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            $codigo = '500';
            $responseBody_log = '';
            if ($e instanceof \GuzzleHttp\Exception\ClientException && $e->hasResponse()) {
                $responseBody_log = (string) $e->getResponse()->getBody();
                $error['data'] = $responseBody_log;
            } else {
                $error['data'] = $errorMsg;
            }

            guardarServiceLog('POST', $url, $request_body, $responseBody_log, $codigo, $headers, $ip, $usuario, $numeroTramite, 'CALCULOS_SEMI_AUTOMATICOS');

            return response()->json($error);
        }
    }

    public function guardarDatosServicioSemiAutomaticos(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => '');
        $alert = array("codigoRespuesta" => 201, "mensaje" => 'Registrado', "fecha" => '', "data" => '');
        $error = array("codigoRespuesta" => 500, "mensaje" => 'Error', "fecha" => '', "data" => 'Error');
        $ESTADO_DATA_CALCULOS = 1;

        try {
            $resultados = json_encode($request->get('resultados'));
            $cas_id = $request->get('cas_nro_caso');
            $data_calculos = $request->get('data_calculos');

            if (isset($data_calculos) && $data_calculos == 1) {
                $fechaFormateada = now()->format('Y-m-d H:i:s');
                $alert['fecha'] = $fechaFormateada;
                return response()->json($alert);
            } else {

                $registro = DB::table('rmx_vys_casos')
                    ->where('cas_id', $cas_id)
                    ->first();

                if ($registro) {
                    $casData = json_decode($registro->cas_data, true) ?: [];
                    $casData['DATA_CALCULOS'] = $ESTADO_DATA_CALCULOS;

                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $cas_id)
                        ->update([
                            'cas_data' => json_encode($casData, JSON_UNESCAPED_UNICODE)
                        ]);
                } else {
                    DB::table('rmx_vys_casos')
                        ->insert([
                            'cas_id' => $cas_id,
                            'cas_data' => json_encode(['DATA_CALCULOS' => $ESTADO_DATA_CALCULOS], JSON_UNESCAPED_UNICODE)
                        ]);
                }

                if ($registro) {
                    $valoresActuales = json_decode($registro->cas_data_valores, true) ?: [];
                    $found = false;

                    foreach ($valoresActuales as &$campo) {
                        if (isset($campo['frm_campo']) && $campo['frm_campo'] === 'DATA_CALCULOS') {
                            $campo = [
                                "frm_tipo" => "DATA_CALCULOS_SEMI_AUTOMATICOS",
                                "frm_campo" => "DATA_CALCULOS",
                                "frm_value" => json_decode($resultados, true),
                                "frm_deshabilitado" => "true",
                                "frm_deshabilitadoo" => true
                            ];
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $valoresActuales[] = [
                            "frm_tipo" => "DATA_CALCULOS_SEMI_AUTOMATICOS",
                            "frm_campo" => "DATA_CALCULOS",
                            "frm_value" => json_decode($resultados, true),
                            "frm_deshabilitado" => "true",
                            "frm_deshabilitadoo" => true
                        ];
                    }

                    DB::table('rmx_vys_casos')
                        ->where('cas_id', $cas_id)
                        ->update([
                            'cas_data_valores' => json_encode($valoresActuales, JSON_UNESCAPED_UNICODE)
                        ]);
                }

                $fechaFormateada = now()->format('Y-m-d H:i:s');
                $success['fecha'] = $fechaFormateada;
                return response()->json($success);
            }
        } catch (\Exception $e) {
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }

    public function actualizarEstTramCalculosSemiAutomaticos(Request $request)
    {
        $success = array("codigoRespuesta" => 200, "mensaje" => 'Correcto', "fecha" => '', "data" => '', "cantidad" => '');
        $alert = array("codigoRespuesta" => 201, "mensaje" => 'Incorrecto', "fecha" => '', "data" => '', "cantidad" => '');
        $error = array("codigoRespuesta" => 500, "mensaje" => 'Error', "fecha" => '', "data" => 'Error');

        try {
            $numeroContrato = $request->input('numeroContrato');
            $cuaAsegurado = $request->input('cuaAsegurado');
            $estado = $request->input('estado');
            $usuario = $request->input('usuario');
            $numeroTramite = $request->input('numeroTramite');
            $idSolicitudPrestacion = $request->input('idSolicitudPrestacion');

            $url = urlsggTest() . "/compensacion-cotizacion/api/v1/calculosSemiAutomaticos/actualizarEstadoTramite";

            $client = new \GuzzleHttp\Client(['verify' => false]);
            $response = $client->put($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'numeroContrato' => $numeroContrato, // APS_CORRELATIVO
                    'cuaAsegurado' => $cuaAsegurado, // AS_CUA
                    'estado' => $estado, //"APROBAR"
                    'usuario' => $usuario, // usuario@gestora.bo
                    'numeroTramite' => $numeroTramite, // JUB/39197/2025
                    'idSolicitudPrestacion' => $idSolicitudPrestacion, // codigo planillas
                ],

            ]);
            $responseData = json_decode($response->getBody(), true);

            if ($responseData['codigo'] == "200") {
                $success['fecha'] = now()->format('Y-m-d H:i:s');
                $success['data'] = $responseData['data'];
                $success['cantidad'] = $responseData['cantidad'];
                return response()->json($success);
            } elseif ($responseData['codigo'] == "201") {
                $alert['fecha'] = now()->format('Y-m-d H:i:s');
                $alert['cantidad'] = $responseData['cantidad'];
                $alert['data'] = $responseData['data'];
                return response()->json($alert);
            }
        } catch (\Exception $e) {
            $error['data'] = $e->getMessage();
            return response()->json($error);
        }
    }
}

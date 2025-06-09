<?php

namespace App\Http\Controllers\estados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class estadoController extends Controller
{

    public function listarEAvanceXProceso(Request $request)
    {
        $prc_id = $request["prc_id"];

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from gp_estados_avance 
                                    inner join rmx_vys_procesos on est_prc_id = prc_id
                                    where est_estado <> 'X' 
                                        and est_prc_id = $prc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarEAvance(Request $request, $RegistrosXPagina, $PaginaActual)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $RegistrosXPagina = $request["RegistrosXPagina"];
        $PaginaActual = $request["PaginaActual"];
        try {
            $data = \DB::select(
                "SELECT est_id, est_codigo, est_descripcion, est_estado, est_cat_id, cat_descripcion, est_prc_id,(prc_data ->> 'prc_descripcion') as prc_descripcion
                from gp_estados_avance
                inner join rmx_vys_catalogos
                on est_cat_id = cat_id
                inner join rmx_vys_procesos
                on est_prc_id = prc_id 
                where est_estado = 'A'
                order by est_id asc
                LIMIT $RegistrosXPagina OFFSET ($PaginaActual - 1) * $RegistrosXPagina"
            );

            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function getTotalRegistros()
    {
        $totalRegistros = \DB::table('gp_estados_avance')->where("est_estado", '=', 'A')->count();
        return response()->json(["totalRegistros" => $totalRegistros]);
    }

    public function BuscarlistarEAvance(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $searchQuery = $request->query('search');

        try {
            if (empty($searchQuery)) {
                $data = \DB::select(
                    "SELECT est_id, est_codigo, est_descripcion, est_estado, est_cat_id, cat_descripcion, est_prc_id,(prc_data ->> 'prc_descripcion') as prc_descripcion
                    FROM gp_estados_avance
                    INNER JOIN rmx_vys_catalogos ON est_cat_id = cat_id
                    INNER JOIN rmx_vys_procesos ON est_prc_id = prc_id 
                    WHERE est_estado = 'A'
                    ORDER BY est_id ASC
                    LIMIT ? OFFSET ?",
                    [$request->query('RegistrosXPagina'), ($request->query('PaginaActual') - 1) * $request->query('RegistrosXPagina')]
                );
            } else {
                $data = \DB::select(
                    "SELECT est_id, est_codigo, est_descripcion, est_estado, est_cat_id, cat_descripcion, est_prc_id,
                            prc_data ->> 'prc_descripcion' as prc_descripcion
                    FROM gp_estados_avance
                    INNER JOIN rmx_vys_catalogos ON est_cat_id = cat_id
                    LEFT JOIN rmx_vys_procesos ON est_prc_id = prc_id 
                    WHERE est_estado = 'A' AND (
                        UPPER(est_codigo) LIKE UPPER(?) OR 
                        UPPER(est_descripcion) LIKE UPPER(?) OR 
                        UPPER(cat_descripcion) LIKE UPPER(?) OR 
                        UPPER(prc_data ->> 'prc_descripcion') LIKE UPPER(?)
                    )
                    ORDER BY est_id ASC",
                    ['%' . $searchQuery . '%', '%' . $searchQuery . '%', '%' . $searchQuery . '%', '%' . $searchQuery . '%']
                );
            }

            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }




    /* public function grabarEAvance(Request $request)
    {
        $est_codigo = $request["est_codigo"];
        $est_descripcion = $request["est_descripcion"];
        $est_cat_id = $request["est_cat_id"];
        $est_prc_id = $request["est_prc_id"]; */
    /*$est_act_id = $request["est_act_id"]; */
    /* $est_usr_id = $request["est_usr_id"];

    $success = array("code" => 200, "mensaje" => 'OK', );
    $error = array("message" => "error de instancia", "code" => 500);
    $prueba = "insert into gp_estados_avance (est_codigo,est_descripcion, est_cat_id, est_prc_id, est_usr_id) values
    ('$est_codigo','$est_descripcion','$est_cat_id','$est_prc_id', 1)"; */
    /* print($prueba); */
    /* try {
        $data = \DB::select("insert into gp_estados_avance (est_codigo,est_descripcion, est_cat_id, est_prc_id, est_usr_id) values
                                    ('$est_codigo','$est_descripcion','$est_cat_id','$est_prc_id', 1) ");
        return response()->json(["data" => $data, "success" => $success]);
    } catch (\Exception $e) {
        return response()->json(["error" => $error]);
    }
} */

    /* public function grabarEAvance(Request $request)
    {
        $validated = $request->validate([
            'est_codigo' => 'required|string|max:255',
            'est_descripcion' => 'required|string|max:255',
            'est_cat_id' => 'required|integer',
            'est_prc_id' => 'required|integer',
            'est_usr_id' => 'required|integer',
        ]);

        $est_codigo = $validated["est_codigo"];
        $est_descripcion = $validated["est_descripcion"];
        $est_cat_id = $validated["est_cat_id"];
        $est_prc_id = $validated["est_prc_id"];
        $est_usr_id = $validated["est_usr_id"];
        $est_estado = 'A';
        $est_registrado = now();
        $est_modificado = now();

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            \DB::insert("INSERT INTO gp_estados_avance (est_codigo, est_descripcion, est_cat_id, est_prc_id, est_registrado, est_modificado, est_usr_id, est_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
                $est_codigo,
                $est_descripcion,
                $est_cat_id,
                $est_prc_id,
                $est_registrado,
                $est_modificado,
                $est_usr_id,
                $est_estado
            ]);

            return response()->json(["success" => $success]);
        } catch (\Exception $e) {
            $error['message'] = $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    } */

    /* public function grabarEAvance(Request $request)
    {
        $validated = $request->validate([
            'est_codigo' => 'required|string|max:255',
            'est_descripcion' => 'required|string|max:255',
            'est_cat_id' => 'required|integer',
            'est_prc_id' => 'required|integer',
            'est_usr_id' => 'required|integer',
        ]);

        $est_codigo = $validated["est_codigo"];
        $est_descripcion = $validated["est_descripcion"];
        $est_cat_id = $validated["est_cat_id"];
        $est_prc_id = $validated["est_prc_id"];
        $est_usr_id = $validated["est_usr_id"];
        $est_estado = 'A';
        $est_registrado = now();
        $est_modificado = now();

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        $query = "INSERT INTO 
        gp_estados_avance (est_codigo, est_descripcion, est_cat_id, est_prc_id, est_registrado, est_modificado, est_usr_id, est_estado) 
        VALUES ('$est_codigo', '$est_descripcion', $est_cat_id, $est_prc_id, '$est_registrado', '$est_modificado', $est_usr_id, '$est_estado')";

        try {
            \DB::statement($query);
            return response()->json(["success" => $success]);
        } catch (\Exception $e) {
            $error['message'] = $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    } */

    public function grabarEAvance(Request $request)
    {
        $validated = $request->validate([
            'est_codigo' => 'required|string|max:255',
            'est_descripcion' => 'required|string|max:255',
            'est_cat_id' => 'required|integer',
            'est_prc_id' => 'required|integer',
            'est_usr_id' => 'required|integer',
        ]);

        $est_codigo = $validated["est_codigo"];
        $est_descripcion = $validated["est_descripcion"];
        $est_cat_id = $validated["est_cat_id"];
        $est_prc_id = $validated["est_prc_id"];
        $est_usr_id = $validated["est_usr_id"];
        $est_estado = 'A';
        $est_registrado = now();
        $est_modificado = now();

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            \DB::beginTransaction();

            \DB::statement("SELECT setval(pg_get_serial_sequence('gp_estados_avance', 'est_id'), (SELECT MAX(est_id) FROM gp_estados_avance));");

            $id = \DB::table('gp_estados_avance')->insertGetId(
                [
                    'est_codigo' => $est_codigo,
                    'est_descripcion' => $est_descripcion,
                    'est_cat_id' => $est_cat_id,
                    'est_prc_id' => $est_prc_id,
                    'est_registrado' => $est_registrado,
                    'est_modificado' => $est_modificado,
                    'est_usr_id' => $est_usr_id,
                    'est_estado' => $est_estado
                ],
                'est_id' 
            );

            \DB::commit();

            return response()->json(["success" => $success, "id" => $id]);
        } catch (\Exception $e) {
            \DB::rollBack();

            $error['message'] = $e->getMessage();
            return response()->json(["error" => $error], 500);
        }
    }


    public function actualizarEAvance(Request $request)
    {
        $est_id = $request["est_id"];
        $est_codigo = $request["est_codigo"];
        $est_descripcion = $request["est_descripcion"];
        $est_cat_id = $request["est_cat_id"];
        $est_prc_id = $request["est_prc_id"];
        /*         $est_act_id = $request["est_act_id"]; */
        $est_usr_id = $request["est_usr_id"];

        $prueba = "UPDATE gp_estados_avance 
                    SET est_codigo = '$est_codigo',
                        est_descripcion = '$est_descripcion',
                        est_cat_id = '$est_cat_id',
                        est_prc_id = '$est_prc_id',
                        est_usr_id = '1'
                    WHERE est_id = '$est_id'";
        print ($prueba);
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("UPDATE gp_estados_avance 
                                set     est_codigo = '$est_codigo',
                                        est_descripcion = '$est_descripcion',
                                        est_cat_id = '$est_cat_id',
                                        est_prc_id = '$est_prc_id',
                                        est_usr_id = '1'
                                    where est_id = '$est_id'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarEAvance(Request $request)
    {
        $est_id = $request["est_id"];
        $est_estado = 'X';

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update gp_estados_avance 
                                        set est_estado = '$est_estado'
                                    where est_id = $est_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (\Exception $e) {
            return response()->json(["error" => $error]);
        }
    }

}
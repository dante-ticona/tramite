<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiWsController extends Controller
{

    public function listarTipoWs()
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_tipos_ws
                                    where tws_estado = 'A'
                                    order by tws_codigo");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTipoWs(Request $request)
    {
        $tws_codigo = $request["tws_codigo"];
        $tws_descripcion = $request["tws_descripcion"];
        $tws_usr_id = $request["tws_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_tipos_ws (tws_codigo, tws_descripcion, tws_usr_id) values
                                    ('$tws_codigo', '$tws_descripcion', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTipoWs(Request $request)
    {
        $tws_id = $request["tws_id"];
        $tws_codigo = $request["tws_codigo"];
        $tws_descripcion = $request["tws_descripcion"];
        $tws_usr_id = $request["tws_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_ws
                            set     tws_codigo = '$tws_codigo',
                                    tws_descripcion = '$tws_descripcion',
                                    tws_usr_id = $tws_usr_id
                                where tws_id = $tws_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTipoWs(Request $request)
    {
        $tws_id = $request["tws_id"];
        $tws_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_tipos_ws
                                    set tws_estado = '$tws_estado'
                                where tws_id = $tws_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarWs(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_vys_ws
                                    inner join rmx_vys_tipos_ws on tws_id = ws_tws_id
                                    where ws_estado = 'A'
                                    order by ws_codigo ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarWs(Request $request)
    {
        $ws_tws_id = $request["ws_tws_id"];
        $ws_codigo = $request["ws_codigo"];
        $ws_descripcion = $request["ws_descripcion"];
        $ws_contenido = $request["ws_contenido"];
        $ws_usr_id = $request["ws_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_vys_ws (ws_tws_id, ws_codigo, ws_descripcion, ws_contenido, ws_usr_id) values
                                    ($ws_tws_id, '$ws_codigo', '$ws_descripcion', '$ws_contenido',  $ws_usr_id) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarWs(Request $request)
    {
        $ws_id = $request["ws_id"];
        $ws_tws_id = $request["ws_tws_id"];
        $ws_codigo = $request["ws_codigo"];
        $ws_descripcion = $request["ws_descripcion"];
        $ws_contenido = $request["ws_contenido"];
        $ws_usr_id = $request["ws_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_ws
                            set     ws_tws_id = $ws_tws_id,
                                    ws_codigo = '$ws_codigo',
                                    ws_descripcion = '$ws_descripcion',
                                    ws_contenido = '$ws_contenido',
                                    ws_usr_id = $ws_usr_id
                                where ws_id = $ws_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarWs(Request $request)
    {
        $ws_id = $request["ws_id"];
        $ws_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_ws
                                    set ws_estado = '$ws_estado'
                                where ws_id = $ws_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


}

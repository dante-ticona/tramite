<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiArchController extends Controller
{
    public function listarArchivos(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_cp_archivos
                                    inner join rmx_cp_tipos_archivo on tarch_id = arch_tarch_id
                                    where arch_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTiposArchivo(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_cp_tipos_archivo
                                    where tarch_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTiposArchivo(Request $request)
    {
        $tarch_codigo = $request["tarch_codigo"];
        $tarch_descripcion = $request["tarch_descripcion"];
        $tarch_usr_id = $request["tarch_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_cp_tipos_archivo (tarch_codigo, tarch_descripcion, tarch_usr_id) values
                                    ('$tarch_codigo', '$tarch_descripcion', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTiposArchivo(Request $request)
    {
        $tarch_id = $request["tarch_id"];
        $tarch_codigo = $request["tarch_codigo"];
        $tarch_descripcion = $request["tarch_descripcion"];
        $tarch_usr_id = $request["tarch_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_tipos_archivo 
                            set     tarch_codigo = '$tarch_codigo', 
                                    tarch_descripcion = '$tarch_descripcion',
                                    tarch_usr_id = $tarch_usr_id
                                where tarch_id = $tarch_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTiposArchivo(Request $request)
    {
        $tarch_id = $request["tarch_id"];
        $tarch_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_tipos_archivo 
                                    set tarch_estado = '$tarch_estado'
                                where tarch_id = $tarch_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarArchivos(Request $request)
    {
        $arch_tarch_id = $request["arch_tarch_id"];
        $arch_codigo = $request["arch_codigo"];
        $arch_descripcion = $request["arch_descripcion"];
        $arch_padre_id = $request["arch_padre_id"];
        $arch_usr_id = $request["arch_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_cp_archivos (arch_tarch_id, arch_codigo, arch_descripcion, arch_padre_id, arch_usr_id) values
                                    ($arch_tarch_id, '$arch_codigo', '$arch_descripcion', '$arch_padre_id', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarArchivos(Request $request)
    {
        $arch_id = $request["arch_id"];
        $arch_tarch_id = $request["arch_tarch_id"];
        $arch_codigo = $request["arch_codigo"];
        $arch_descripcion = $request["arch_descripcion"];
        $arch_padre_id = $request["arch_padre_id"];
        $arch_usr_id = $request["arch_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_archivos 
                            set     arch_tarch_id = '$arch_tarch_id', 
                                    arch_codigo = '$arch_codigo', 
                                    arch_descripcion = '$arch_descripcion',
                                    arch_padre_id = '$arch_padre_id',
                                    arch_usr_id = $arch_usr_id
                                where arch_id = $arch_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarArchivos(Request $request)
    {
        $arch_id = $request["arch_id"];
        $arch_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_archivos 
                                    set arch_estado = '$arch_estado'
                                where arch_id = $arch_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarTiposDoc(Request $request)
    {

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_cp_tipos_documentales
                                    where tdoc_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarTiposDoc(Request $request)
    {
        $tdoc_codigo = $request["tdoc_codigo"];
        $tdoc_descripcion = $request["tdoc_descripcion"];
        $tdoc_usr_id = $request["tdoc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_cp_tipos_documentales (tdoc_codigo, tdoc_descripcion, tdoc_usr_id) values
                                    ('$tdoc_codigo', '$tdoc_descripcion', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarTiposDoc(Request $request)
    {
        $tdoc_id = $request["tdoc_id"];
        $tdoc_codigo = $request["tdoc_codigo"];
        $tdoc_descripcion = $request["tdoc_descripcion"];
        $tdoc_usr_id = $request["tdoc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_tipos_documentales 
                            set     tdoc_codigo = '$tdoc_codigo', 
                                    tdoc_descripcion = '$tdoc_descripcion',
                                    tdoc_usr_id = $tdoc_usr_id
                                where tdoc_id = $tdoc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarTiposDoc(Request $request)
    {
        $tdoc_id = $request["tdoc_id"];
        $tdoc_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_tipos_documentales 
                                    set tdoc_estado = '$tdoc_estado'
                                where tdoc_id = $tdoc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarSubtiposDoc(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_cp_subtipos_documentales
                                    inner join rmx_cp_tipos_documentales on tdoc_id = stdoc_tdoc_id
                                    where stdoc_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarSubtiposDoc(Request $request)
    {
        $stdoc_tdoc_id = $request["stdoc_tdoc_id"];
        $stdoc_codigo = $request["stdoc_codigo"];
        $stdoc_descripcion = $request["stdoc_descripcion"];
        $stdoc_contenido = $request["stdoc_contenido"];
        $stdoc_usr_id = $request["stdoc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_cp_subtipos_documentales (stdoc_tdoc_id, stdoc_codigo, stdoc_descripcion, stdoc_contenido, stdoc_usr_id) values
                                    ($stdoc_tdoc_id, '$stdoc_codigo', '$stdoc_descripcion', '$stdoc_contenido', 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarSubtiposDoc(Request $request)
    {
        $stdoc_id = $request["stdoc_id"];
        $stdoc_tdoc_id = $request["stdoc_tdoc_id"];
        $stdoc_codigo = $request["stdoc_codigo"];
        $stdoc_descripcion = $request["stdoc_descripcion"];
        $stdoc_contenido = $request["stdoc_contenido"];
        $stdoc_usr_id = $request["stdoc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_subtipos_documentales 
                            set     stdoc_tdoc_id = '$stdoc_tdoc_id', 
                                    stdoc_codigo = '$stdoc_codigo', 
                                    stdoc_descripcion = '$stdoc_descripcion',
                                    stdoc_contenido = '$stdoc_contenido',
                                    stdoc_usr_id = $stdoc_usr_id
                                where stdoc_id = $stdoc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarSubtiposDoc(Request $request)
    {
        $stdoc_id = $request["stdoc_id"];
        $stdoc_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_cp_subtipos_documentales 
                                    set stdoc_estado = '$stdoc_estado'
                                where stdoc_id = $stdoc_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
}
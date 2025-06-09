<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;
use TCPDF;
use Illuminate\Support\Facades\File;

class ApiGPSIPController extends Controller
{
    public function obtenerUsuario(Request $request)
    {
        $user = $request["user"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from sip_usuario_sip sus 
                                where nom_usuario = '$user' ans status = 'A'");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function obtenerSupervisoresAgencia(Request $request)
    {
        $id_agencia = $request["id_agencia"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from users     
                                 where es_supervisor and id_agencia = $id_agencia 
                                 and status='A'" );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function obtenerUsuariosDelNodo(Request $request)
    {
        $id_nodo = $request["id_nodo"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select u.* from rmx_usr_nodos run 
                                inner join users u on run.usn_user_id = u.id 
                                where usn_nodo_id = $id_nodo and usn_estado = 'A' and u.status = 'A'" );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function obtenerUsuarioDerivar(Request $request)
    {
        $user = $request["user"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from public.users u
                                 where u.email = '$user' and status = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
}

<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class tutorController extends Controller
{

    public function grabarTutor(Request $request)
    {
        $tutor = $request["tutor"];
        $cas_cod_id = $request["cas_cod_id"];
        $numero_documento = $request["numero_documento"];
        $id_persona_sip = $request["id_persona_sip"];
        $tut_cas_id_ = $request["tut_cas_id_"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        try {
            $data = \DB::select(
                "
                INSERT INTO public.tutores
                    ( tut_cod_id, tut_data, tut_id_persona, tut_ci_persona,tut_usr_id,tut_estado, tut_cas_id_)
                    VALUES('$cas_cod_id', '$tutor'::jsonb,   $id_persona_sip ,    $numero_documento, 1,'A',   $tut_cas_id_);
                "
            );
            return response()->json(["data" => $data[0], "codigoRespuesta" => $success]);
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }
    public function getTutor(Request $request)
    {
        $tutor = $request["tutor"];
        $cas_cod_id = $request["cas_cod_id"];
        $numero_documento = $request["numero_documento"];
        $id_persona_sip = $request["id_persona_sip"];
        $tut_cas_id_ = $request["tut_cas_id_"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $success2 = array("code" => 201, "mensaje" => 'No Hay datos ', );
        try {
            $data = \DB::select(
                "
                select * from  public.tutores
                   where tut_cas_id_ = ' $tut_cas_id_' and tut_id_persona =  $id_persona_sip
                "
            );
            if (empty($data)) {
                return response()->json(["data" => [], "codigoRespuesta" => $success2]);
            } else {
                return response()->json(["data" => $data[0], "codigoRespuesta" => $success]);
            }
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }


    public function listTutores(Request $request)
    {
        $tutor = $request["tutor"];
        $cas_cod_id = $request["cas_cod_id"];
        $numero_documento = $request["numero_documento"];
        $id_persona_sip = $request["id_persona_sip"];
        $tut_cas_id_ = $request["tut_cas_id_"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $success2 = array("code" => 201, "mensaje" => 'No Hay datos ', );
        try {
            $data = \DB::select(
                "
                select * from  public.tutores
                   where tut_cas_id_ = ' $tut_cas_id_' and tut_id_persona =  $id_persona_sip
                "
            );
            if (empty($data)) {
                return response()->json(["data" => [], "codigoRespuesta" => $success2]);
            } else {
                return response()->json(["data" => $data[0], "codigoRespuesta" => $success]);
            }
        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($errorCode == 'P0001') {
                $error = array("message" => $errorMessage, "code" => 400);
                return response()->json(["data" => [], "codigoRespuesta" => $error], 200);
            } else {
                return response()->json(['error2' => 'Ocurrió un error al insertar el registro. ' . $errorMessage], 500);
            }
        }
    }

}

<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiVySIntegracionController extends Controller
{
    public function recibirCaso(Request $request)
    {
        $ext_BODY = $request["ext_body"];
        $ext_ACT_ID = $request["ext_act_id"];
        $ext_NODO_ID = $request["ext_nodo_id"];
        $ext_USR_ID = $request["ext_usr_id"];
        $ext_CONV_USR = $request["CONV_USR"];
        $ext_CONV_CODIGO_RECEPCION = $request["CONV_CODIGO_RECEPCION"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $gestion = date("Y");
            $ext_data = '{"cas_gestion": "' . $gestion . '", "cas_nro_caso": "INNOVA", "cas_nombre_caso": "INNOVA", 
                            "cas_ext_usr":"' . $ext_CONV_USR . '", "cas_ext_codigo":"' . $ext_CONV_CODIGO_RECEPCION . '"}';
            $ext_data_valores = '[]';
            $ext_body2 = json_decode($ext_BODY, 0);

            // Define PREFIJO de variables
            if ($ext_body2->CONV_TPO_TRAM == 1) {
                $_prefijo = "CONV_";
                $_act_id = 88;
                $_nodo_id = 4;
            } else if ($ext_body2->CONV_TPO_TRAM == 2) {
                $_prefijo = "CONVCAR_";
                $_act_id = 116;
                $_nodo_id = 4;
            } else if ($ext_body2->CONV_TPO_TRAM == 3) {
                $_prefijo = "CONVPH_";
                $_act_id = 130;
                $_nodo_id = 4;
            }

            $cas_id = 0; // caso inicializado
            $caso = [];
            $personasss = []; //varias personas
            $data = []; $data2 = [];
            foreach ($ext_body2->CONV_PERSONAS as $p) {
                $_nombre_completo = $p->nombres . " " . $p->primer_apellido . " " . $p->segundo_apellido;
                $_tipo_persona = $p->tipo_persona;

                $persona = []; //una persona
                $persona[] = array("col_campo" => $_prefijo . "CI_N",     "col_value" => $p->ci,               "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "EXP",      "col_value" => strval($p->expedido), "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "NOM",      "col_value" => $p->nombres,          "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "PAT",      "col_value" => $p->primer_apellido,  "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "MAT",      "col_value" => $p->segundo_apellido, "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "CAS",      "col_value" => $p->apellido_casada,  "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "DOM_PROP", "col_value" => $p->domicilio,        "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "TEL",      "col_value" => $p->telefono,         "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "CEL",      "col_value" => $p->telefono,         "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $persona[] = array("col_campo" => $_prefijo . "MAIL",     "col_value" => $p->email,            "col_deshabilitado" => "false", "col_deshabilitadoo" => false);

                // CONV_TIPO_NAT 1=Propietario, 2=Apoderado,3=Tercero --> Tercero Responsable
                if ($_tipo_persona == 'Propietario') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS",       "frm_value" => "1",                "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT",        "frm_value" => "1",                "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_PROPIET_SOL",  "frm_value" => $p->ci,             "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_PROPIET_SOL", "frm_value" =>  $_nombre_completo, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    // solo carga PROPIETARIOS
                    $personasss[] = $persona; // cargar una persona en personas
                }
                if ($_tipo_persona == 'Apoderado') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS", "frm_value" => "1",                  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT",  "frm_value" => "2",                  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_J",      "frm_value" => $p->ci,               "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "EXP_J",     "frm_value" => $p->expedido,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_J",     "frm_value" => $p->nombres,          "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "PAT_J",     "frm_value" => $p->primer_apellido,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAT_J",     "frm_value" => $p->segundo_apellido, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CAS_J",     "frm_value" => $p->apellido_casada,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "DOM_J",     "frm_value" => $p->domicilio,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TEL_J",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CEL_J",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAIL_J",    "frm_value" => $p->email,            "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NUM_POD_J", "frm_value" => $p->nro_poder,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "FEC_POD_J", "frm_value" => $p->fecha_poder,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOT_J",     "frm_value" => $p->nro_notaria,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                }
                if ($_tipo_persona == 'Tercero') {
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_PERS",    "frm_value" => "1",                  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TIPO_NAT",     "frm_value" => "3",                  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CI_TERC",      "frm_value" => $p->ci,               "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "EXP_TERC",     "frm_value" => $p->expedido,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOM_TERC",     "frm_value" => $p->nombres,          "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "PAT_TERC",     "frm_value" => $p->primer_apellido,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAT_TERC",     "frm_value" => $p->segundo_apellido, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CAS_TERC",     "frm_value" => $p->apellido_casada,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "DOM_TERC",     "frm_value" => $p->domicilio,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "TEL_TERC",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "CEL_TERC",     "frm_value" => $p->telefono,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "MAIL_TERC",    "frm_value" => $p->email,            "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NUM_POD_TERC", "frm_value" => $p->nro_poder,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "FEC_POD_TERC", "frm_value" => $p->fecha_poder,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                    $caso[] = array("frm_campo" => $_prefijo . "NOT_TERC",     "frm_value" => $p->nro_notaria,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                }
            }
            $caso[] = array("frm_campo" => "GRILLA_PROP", "frm_value" => $personasss, "frm_deshabilitado" => "true", "frm_deshabilitadoo" => true);

            foreach ($ext_body2->CONV_PREDIO as $pred) {
                $caso[] = array("frm_campo" => $_prefijo . "ZN",             "frm_value" => $pred->zona,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "SB_DISTR",       "frm_value" => $pred->subdistrito, "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "DISTR",          "frm_value" => $pred->distrito,    "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "MZNO",           "frm_value" => $pred->manzana,     "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "TIPO_VIA",       "frm_value" => "N/A",              "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NOM_VIA",        "frm_value" => $pred->calle,       "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_LOTE",       "frm_value" => $pred->lote,        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_PREDIO",     "frm_value" => $pred->nro_predio,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "SUB_REG",        "frm_value" => "- falta -",        "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_PARTIDA",    "frm_value" => $pred->matricula,   "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "LUGAR_PARTIDA",  "frm_value" => $pred->asiento,             "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "ZN_CBB",         "frm_value" => $pred->zona_cercado,         "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "DISTR_CBB",      "frm_value" => $pred->distrito_cercado,     "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "SB_DISTR_CBB",   "frm_value" => $pred->subdistrito_cercado,  "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "MZNO_CBB",       "frm_value" => $pred->manzana_cercado,      "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
                $caso[] = array("frm_campo" => $_prefijo . "NUM_PREDIO_CBB", "frm_value" => $pred->nro_predio_cercado,   "frm_deshabilitado" => "false", "frm_deshabilitadoo" => false);
            }

            // Registra ADJUNTOS
            $adjuntos = [];
            foreach ($ext_body2->CONV_REQS as $r) {
                $adj = [];
                $adj[] = array("col_campo" => $_prefijo . "DESCRIPCION",  "col_value" => $r->nombre,            "col_deshabilitado" => "true", "col_deshabilitadoo" => true);
                $adj[] = array("col_campo" => $_prefijo . "URL",          "col_value" => $r->url_adjunto,       "col_deshabilitado" => "false", "col_deshabilitadoo" => false);
                $adj[] = array("col_campo" => $_prefijo . "REQUISITO",    "col_value" => $r->requisito_nombre,  "col_deshabilitado" => "true", "col_deshabilitadoo" => true);
                $adjuntos[] = $adj;
            }
            
            $caso[] = array("frm_campo" => "GRILLA_REQS", "frm_value" => $adjuntos);            

            // Registra CASO
            $ext_data_valores = json_encode($caso);

            $data2 = \DB::select("insert into rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id) values
                                    ($_act_id, $_nodo_id, '$ext_data'::json, '$ext_data_valores'::json, '$ext_USR_ID') 
                                    returning cas_id ");

            // Registra EXTERNO
            $data = \DB::select("insert into rmx_vys_externos (ext_act_id, ext_nodo_id, ext_body, ext_usr_id) values
                                    ($_act_id, $_nodo_id, '$ext_BODY'::json, '$ext_USR_ID') ");

            return response()->json(["data" => $data, "data2" => $data2, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

}

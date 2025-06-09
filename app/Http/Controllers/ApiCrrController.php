<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiCrrController extends Controller
{
    public function listarTiposCorrespondencia(Request $request)
    {
        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_crr_tipos_correspondencia
                                    where tcrr_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarCorrespondenciaXId(Request $request)
    {
        $crr_id = $request["crr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_crr_correspondencia
                                    inner join rmx_vys_nodos on nodo_id = crr_nodo_id 
                                    inner join rmx_crr_tipos_correspondencia on tcrr_id = crr_tcrr_id
                                    where crr_estado <> 'X' 
                                        and crr_id = '$crr_id'
                                    order by crr_registrado desc");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

	public function listarCorrespondencia(Request $request) {
		$usr_id = $request['usr_id'];

		$success = array('code' => 200, 'mensaje' => 'OK',);
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select("SELECT *
				FROM rmx_crr_correspondencia JOIN rmx_vys_nodos ON nodo_id = crr_nodo_id
					JOIN rmx_crr_tipos_correspondencia ON tcrr_id = crr_tcrr_id
					JOIN rmx_usr_nodos n ON crr_nodo_id = n.usn_nodo_id
				WHERE crr_estado <> 'X'
					AND n.usn_user_id = $usr_id
				ORDER BY crr_registrado desc");
			return response()->json(["data" => $data, "success" => $success]);
		} catch (error $e) {
			return response()->json(["error" => $error]);
		}
	}

    public function actualizarCorrespondencia(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_codigo = $request["nodo_codigo"];
        $nodo_descripcion = $request["nodo_descripcion"];
        $nodo_padre_id = $request["nodo_padre_id"];
        $nodo_usr_id = $request["nodo_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
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

    public function eliminarCorrespondencia(Request $request)
    {
        $nodo_id = $request["nodo_id"];
        $nodo_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_vys_nodos 
                                    set nodo_estado = '$nodo_estado'
                                where nodo_id = $nodo_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function actualizarEstadoCorrespondencia(Request $request)
    {
        $crr_id = $request["crr_id"];
        $crr_estado = $request["crr_estado"];
        $crr_usr_id = $request["crr_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_crr_correspondencia 
                                set crr_estado = '$crr_estado',
                                    crr_usr_id = $crr_usr_id
                                where crr_id = $crr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


    public function listarCopiasXCrrId(Request $request)
    {
        $crr_id = $request["crr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select *
                                    from rmx_crr_copias
                                    inner join rmx_vys_nodos on nodo_id = copia_destino_id 
                                    where copia_estado <> 'X' 
                                        and copia_crr_id = '$crr_id'
                                    order by copia_nro_copia  ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarActuacionesXCRR(Request $request)
    {
        $crr_id = $request["crr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select a.*, u.name
                                    from rmx_crr_actuaciones a
                                    inner join users u on id = act_usr_id 
                                    where act_estado <> 'X' 
                                        and act_crr_id = '$crr_id'
                                    order by act_registrado desc ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarActuaciones(Request $request)
    {

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * 
                                    from rmx_crr_actuaciones a
                                    inner join rmx_crr_correspondencia ON crr_id = act_crr_id 
                                    where act_estado <> 'X' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarActuaciones(Request $request)
    {
        $act_crr_id = $request["act_crr_id"];
        $act_data = $request["act_data"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("insert into rmx_crr_actuaciones (act_crr_id, act_data, act_usr_id) values
                                    ('$act_crr_id', '$act_data'::json, 1) ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarActuaciones(Request $request)
    {
        $act_id = $request["act_id"];
        $act_crr_id = $request["act_crr_id"];
        $act_data = $request["act_data"];
        $act_usr_id = $request["act_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_crr_actuaciones 
                            set     act_crr_id = '$act_crr_id', 
                                    act_data = '$act_data'::json,
                                    act_usr_id = $act_usr_id
                                where act_id = $act_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarActuaciones(Request $request)
    {
        $act_id = $request["act_id"];
        $act_estado = 'X';

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("update rmx_crr_actuaciones 
                                    set act_estado = '$act_estado',
                                where act_id = $act_id ");
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
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $data = \DB::select("insert into rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_usr_id) values
                                    ($prc_cat_id, '$prc_data'::json, '$prc_data_campos_clave'::json, $prc_usr_id) ");
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
        $prc_usr_id = $request["prc_usr_id"];

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
        try {
            //$prc_data_campos_clave NO usar json_encode($prc_data_campos_clave, 0);
            $data = \DB::select("update rmx_vys_procesos 
                            set     prc_cat_id = $prc_cat_id, 
                                    prc_data = '$prc_data'::json,
                                    prc_data_campos_clave = '$prc_data_campos_clave'::json,
                                    prc_usr_id = $prc_usr_id
                                where prc_id = $prc_id ");
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

        $success = array("code"    => 200, "mensaje"    => 'OK',);
        $error   = array("message" => "error de instancia", "code" => 500);
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

//  INICIO  -  JOJO
	public function listarSubtiposDocXDocID(Request $request) {
		$doc_id = $request['doc_id'];

		$success = array('code' => 200, 'mensaje' => 'OK',);
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			$data = \DB::select('SELECT *
				FROM rmx_cp_subtipos_documentales
				WHERE stdoc_tdoc_id = '. $doc_id);
			return response()->json(['data' => $data, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

	public function grabarCorrespondencia(Request $request) {
		$crr_tcrr_id = $request['crr_tcrr_id'];
		$crr_nodo_id = $request['crr_nodo_id'];
		$crr_clonado_id = $request['crr_clonado_id'];
		$crr_data = json_encode($request['crr_data']);
		$crr_usr_id = $request['crr_usr_id'];

		$success = array('code' => 200, 'mensaje' => 'OK');
		$error   = array('message' => 'error de instancia', 'code' => 500);
		try {
			\DB::table('rmx_crr_correspondencia')->insert([
				'crr_tcrr_id' => $crr_tcrr_id,
				'crr_nodo_id' => $crr_nodo_id,
				'crr_data' => $crr_data,
				'crr_usr_id' => $crr_usr_id
			]);
			return response()->json(['data' => true, 'success' => $success]);
		} catch (error $e) {
			return response()->json(['error' => $error]);
		}
	}

    public function setCopia(Request $request)
    {
        $_id = $request['crr_id'];
        $_nodo_id = $request['nodo_id'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT max(copia_nro_copia) AS nro_copia
				FROM rmx_crr_copias
				WHERE copia_crr_id = $_id")[0];

            \DB::statement("INSERT INTO rmx_crr_copias (copia_crr_id, copia_destino_id, copia_nro_copia, copia_usr_id)
				VALUES ($_id, $_nodo_id, " . ($data->nro_copia + 1) . ", 1)");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function delCopia(Request $request)
    {
        $_id = $request['cp_id'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::statement("DELETE FROM rmx_crr_copias
				WHERE copia_id = $_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function setHistorico(Request $request)
    {
        $_id = $request['crr_id'];
        $success = array('code'    => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("INSERT INTO rmx_crr_historicos (hst_id, hst_nodo_id, hst_clonado_id, hst_data, hst_usr_id, hst_copias)
				SELECT crr_id, crr_nodo_id, crr_clonado_id, crr_data, crr_usr_id,
					(SELECT json_agg(rmx_crr_copias)
					FROM rmx_crr_copias
					WHERE copia_crr_id = $_id) AS copias
				FROM rmx_crr_correspondencia
				WHERE crr_id = $_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }

    public function derivar(Request $request)
    {
        $_id = $request['crr_id'];
        $_nodo_id = $request['nodo_id'];

        $success = array('code' => 200, 'mensaje' => 'OK');
        $error   = array('message' => 'error de instancia', 'code' => 500);
        try {
            $data = \DB::select("SELECT copia_id, copia_destino_id
				FROM rmx_crr_copias
				WHERE copia_crr_id = $_id
				ORDER BY copia_nro_copia
				LIMIT 1")[0];
            \DB::statement("UPDATE rmx_crr_correspondencia SET
					crr_nodo_id = " . $data->copia_destino_id . ",
					crr_estado = 'A'
				WHERE crr_id = $_id");
            \DB::statement("INSERT INTO rmx_crr_correspondencia (crr_nodo_id, crr_tcrr_id, crr_clonado_id, crr_data, crr_usr_id, crr_estado)
				SELECT p.copia_destino_id, c.crr_tcrr_id, c.crr_id, c.crr_data, c.crr_usr_id, 'C'
				FROM rmx_crr_correspondencia c JOIN rmx_crr_copias p ON c.crr_id = p.copia_crr_id AND p.copia_estado = 'A'
				WHERE c.crr_id = $_id
					AND p.copia_id <> " . $data->copia_id);
            $data = \DB::statement("DELETE FROM rmx_crr_copias
				WHERE copia_crr_id = $_id");
            return response()->json(['data' => $data, 'success' => $success]);
        } catch (error $e) {
            return response()->json(['error' => $error]);
        }
    }
//  FIN  -  JOJO
}
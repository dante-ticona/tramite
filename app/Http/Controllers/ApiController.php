<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegistrationFormRequest;

class ApiController extends Controller
{
    /**
     * @var bool
     */
    public $loginAfterSignUp = true;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login2(Request $request)
    {
        dd('como estas ');
    }
    public function login(Request $request)
    {
        //$input = $request->only('email', 'password'); // application/x-www-form-urlencoded
        $input = json_decode($request->getContent(), true); // application/json
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    /**
     * @param RegistrationFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationFormRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function find(Request $request)
    {
        $email = $request["email"];

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select * from users u
                                    inner join rmx_ig_dptos d on d.dpt_id = u.emp_id
                                where u.status = 'A'
                                    and u.email = '$email'
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarRoles()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("select r.id as role_id, r.code as role_code, 
                                    r.description as role_description
                                from roles r
                                order by r.id");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsersXJrs(Request $request)
    {
        $jrs_id = $request["jrs_id"];

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $sql2 = ($jrs_id !== 'null') ? " where branch_id = $jrs_id " : "";

            $data = \DB::select("select u.*, j.*,
                                r.id as role_id, r.code as role_code, r.description as role_description
                                from users u
                                    inner join roles r on r.id = u.role_id 
                                    inner join rmx_ig_jurisdicciones j on j.jrs_id = u.branch_id 
                                $sql2
                                    -- and u.status <> 'X' 
                                    -- lista incluidos los eliminados
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarUsers()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT u.id, u.name, u.email, u.created_at, u.updated_at, u.role_id, u.branch_id, r.description,
                u.status, gr.descripcion_doc AS regional_description, gd.descripcion_dep AS departamento_description,
                ga.descripcion_agencia AS agencia_description, u.id_regional, u.id_departamento, u.id_agencia,
                u.es_atc, u.es_supervisor, u.es_jefe, gr.id_sip_regional, gr.id_sip_departamento, ga.id_sip_regional 
                FROM users u
                inner JOIN roles r ON u.role_id = r.id
                left JOIN gp_regional gr ON gr.id_sip_regional = u.id_regional
                left JOIN gp_departamento gd ON gd.id_sip_departamento = u.id_departamento
                left JOIN gp_agencia ga ON ga.id_sip_agencia = u.id_agencia
                ORDER by u.name;"
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function listarUsersATC()
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT u.id, u.name, u.email, u.created_at, u.updated_at, u.role_id, u.branch_id, r.description,
                u.status, gr.descripcion_doc AS regional_description, gd.descripcion_dep AS departamento_description,
                ga.descripcion_agencia AS agencia_description, u.id_regional, u.id_departamento, u.id_agencia,
                u.es_atc, u.es_supervisor, u.es_jefe, gr.id_sip_regional, gr.id_sip_departamento, ga.id_sip_regional 
                FROM users u
                inner JOIN roles r ON u.role_id = r.id
                left JOIN gp_regional gr ON gr.id_sip_regional = u.id_regional
                left JOIN gp_departamento gd ON gd.id_sip_departamento = u.id_departamento
                left JOIN gp_agencia ga ON ga.id_sip_agencia = u.id_agencia
                where u.es_atc = true
                ORDER by u.name;"
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
    public function actualizarUserATC(Request $request)
    {
        $id = $request["id"];
        $id_departamento = $request['id_departamento'];
        $id_regional = $request['id_regional'];
        $id_agencia = $request['id_agencia'];
        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $data = \DB::update(
                "UPDATE users SET id_departamento=$id_departamento, id_regional=$id_regional, id_agencia=$id_agencia
                        WHERE id=$id"
            );
            return response()->json(["data" => $data, "success" => $success]);
            echo "$data";
        } catch (Error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function buscarUser(Request $request)
    {
        $id = $request["id"];

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT u.*,
                                r.id as role_id, r.code as role_code, r.description as role_description
                                from users u
                                    inner join roles r on r.id = u.role_id 
                                where u.id = $id 
                                -- u.status <> 'X'
                                -- lista incluidos los eliminados
                                order by u.name
                                ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function grabarUser(Request $request)
    {
        $role_id = $request["role_id"];
        $branch_id = $request["branch_id"];
        $name = $request["name"];
        $email = $request["email"];
        $password = Hash::make($request["password"]);
        $nom_usuario = $email;
        $email_verified_at = $email;
        $id_regional = $request['id_regional'];
        $id_departamento = $request['id_departamento'];
        $id_agencia = $request['id_agencia'];
        $es_atc = $request['es_atc'] ? 'true' : 'false';
        $es_supervisor = $request['es_supervisor'] ? 'true' : 'false';
        $es_jefe = $request['es_jefe'] ? 'true' : 'false';
        $status = 'A';

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            \DB::beginTransaction();

            \DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), (SELECT MAX(id) FROM users));");

            $id = \DB::table('users')->insertGetId(
                [
                    'role_id' => $role_id,
                    'branch_id' => $branch_id,
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'id_regional' => $id_regional,
                    'id_departamento' => $id_departamento,
                    'id_agencia' => $id_agencia,
                    'es_atc' => $es_atc,
                    'es_supervisor' => $es_supervisor,
                    'es_jefe' => $es_jefe,
                    'nom_usuario' => $nom_usuario,
                    'email_verified_at' => $email_verified_at . '@gestora.bo',
                    'status' => $status
                ]
            );

            \DB::commit();

            return response()->json(["success" => $success, "id" => $id]);
        } catch (\Exception $e) {
            \DB::rollBack();

            $error['details'] = $e->getMessage();
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarUser(Request $request)
    {
        $id = $request["id"];
        $role_id = $request["role_id"];
        $branch_id = $request["branch_id"];
        $name = $request["name"];
        $email = $request["email"];
        $password = Hash::make($request["password"]);
        $id_departamento = $request['id_departamento'];
        $id_regional = $request['id_regional'];
        $id_agencia = $request['id_agencia'];
        $es_atc = $request['es_atc'];
        $es_supervisor = $request['es_supervisor'];
        $es_jefe = $request['es_jefe'];

        $success = array("code" => 200, "mensaje" => 'OK');
        $error = array("message" => "error de instancia", "code" => 500);

        try {
            $data = \DB::update(
                "UPDATE users
                        SET role_id=?, branch_id=?, name=?, email=?, email_verified_at=?, password=?, status='A', 
                            nom_usuario=?,  id_departamento=?, id_regional=?, id_agencia=?, es_atc=?, es_supervisor=?, es_jefe=?
                        WHERE id=?",
                [$role_id, $branch_id, $name, $email, $email . '@gestora.bo', $password, $email, $id_departamento, $id_regional, $id_agencia, $es_atc, $es_supervisor, $es_jefe, $id]
            );
            return response()->json(["data" => $data, "success" => $success]);
            echo "$data";
        } catch (Error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function eliminarUser(Request $request)
    {
        $id = $request["id"];
        $status = 'X';

        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("UPDATE users
                                    set status = '$status'
                                where id = $id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function obtenerUsuario(Request $request)
    {
        $usrId = $request["usrId"];
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("SELECT *
                                    from public.users u 
                                    where id = $usrId and  status = 'A' ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function actualizarUsuario(Request $request)
    {
        $usr_id = $request["usr_id"];
        $contrasena = Hash::make($request["contrasena"]);
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select("UPDATE users
                    set    
                    password = '$contrasena',
                    updated_at = now()
                where id = $usr_id ");
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }


    /* LISTAR - REGIONAL - DEPARTAMENTO - AGENCIA */
    public function listarRegional(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT * from gp_regional gr"
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarDepartamento(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT * from gp_departamento gd"
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function listarAgencia(Request $request)
    {
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $data = \DB::select(
                "SELECT * from gp_agencia ga"
            );
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }

    public function consultaRegistro1582(Request $request)
    {
        $tipo_documento = $request["tipo_documento"];
        $numero_documento = $request["numero_documento"];
        $complemento = $request["complemento"];
        $fecha_nacimiento = $request["fecha_nacimiento"];
        $cadena = '';
        //var_dump("request", $request["complemento"]); // 2024-10-29
        if ($request["complemento"] !== null) {
            $cadena = " and complemento = '" . $complemento . "'";
        }
        if ($request["fecha_nacimiento"] !== null) {
            $cadena = " and fecha_nacimiento_titular =  '" . $fecha_nacimiento . "'";
        }
        $success = array("code" => 200, "mensaje" => 'OK', );
        $error = array("message" => "error de instancia", "code" => 500);
        try {
            $sql = "SELECT r.accede1 as accede, r.evalua_acceso  from public.recalculo_1582 r 
                        where r.tipo_identificacion_titular = '$tipo_documento' and r.numero_identificacion_titular = '$numero_documento' $cadena";
            //var_dump("sql", $sql);
            $data = \DB::select($sql);
            return response()->json(["data" => $data, "success" => $success]);
        } catch (error $e) {
            return response()->json(["error" => $error]);
        }
    }
}

 <?php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;

    function getUser($emailUser)
    {
        $dataUsuario = DB::table('users')
        ->where('email_verified_at', '=', $emailUser)
        ->select(
            'id',
            'role_id as roleId',
            'branch_id as branchId',
            'name',
            'email',
            'email_verified_at as emailVerifiedAt',
            'status',
            'nom_usuario as nomUsuario',
            'id_regional as idRegional',
            'id_departamento as idDepartamento',
            'id_agencia as idAgencia',
            'es_atc as esAtc',
            'es_supervisor as esSupervisor'
        )
        ->get();

        return $dataUsuario;
    }

    function getUserId($id)
    {
        $nombreUsuario = DB::table('users')
        ->where('id', '=', $id)
        ->select('name',)->get();
        return $nombreUsuario;
    }

    function getNameRegional($id)
    {
        $nombreRegional = DB::table('gp_regional')
        ->where('id_sip_regional', '=', $id)
        ->select('descripcion_doc',)->get();
        if ($nombreRegional->isNotEmpty()) {
            $nombreRegional = $nombreRegional[0]->descripcion_doc;
        } else {
            $nombreRegional = "";
        }

        return $nombreRegional;
    }

    function getDataTramite($nroTramite, $estados)
    {
        $data = DB::table('rmx_vys_casos as rvc')
        ->join('rmx_vys_actividades as rva', 'rvc.cas_act_id', '=', 'rva.act_id')
        ->where('rvc.cas_nodo_id', '=', DB::raw('rva.act_nodo_id'))
        ->whereIn('rvc.cas_estado', $estados)
        ->where('rvc.cas_cod_id', '=', $nroTramite)
        ->select(
            'rvc.cas_id as casId',
            'rvc.cas_act_id as casActId',
            'rvc.cas_nodo_id as casNodoId',
            'rva.act_prc_id as actPrcId',
            'rvc.cas_estado as casEstado',
            DB::raw('CAST(rvc.cas_data AS TEXT) AS "casData"'),
            DB::raw('CAST(rvc.cas_data_valores AS TEXT) AS "casDataValores"'),
            DB::raw('rvc.cas_data ->> \'cas_gestion\' AS "casGestion"'),
            DB::raw('rvc.cas_data ->> \'USUARIO_REGISTRO\' AS "usuarioRegistro"'),
            DB::raw('rva.act_data ->> \'act_descripcion\' AS "actDescripcion"')
        )
        ->get();

        return $data;
    }

    // Autenticación LDAP
    function ldapAuth($username, $password)
    {
        $ldapHost = env('LDAP_HOST', '10.10.16.8');
        $ldapPort = env('LDAP_PORT', 389);
        $ldapDomain = env('LDAP_DOMAIN', 'gestora.bo');

        $ldapUser = trim($username) . '@' . $ldapDomain;

        if (!extension_loaded('ldap')) {
            return [
                'success' => false,
                'message' => 'extension LDAP no habilitada en el servidor'
            ];
        }

        $ldapConnection = ldap_connect($ldapHost, $ldapPort);

        if (!$ldapConnection) {
            return [
                'success' => false,
                'message' => 'no se puede conectar al servidor LDAP'
            ];
        }

        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldapConnection, $ldapUser, trim($password));

        if ($bind && $password !== '') {
            ldap_close($ldapConnection);
            return [
                'success' => true,
                'message' => 'Autenticación exitosa'
            ];
        } else {
            ldap_close($ldapConnection);
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }
    }

    function ldapBuscarOtroUsuario($miUsername, $miPassword, $usuarioABuscar)
    {
        $ldapHost = env('LDAP_HOST', '10.10.16.8');
        $ldapPort = env('LDAP_PORT', 389);
        $ldapDomain = env('LDAP_DOMAIN', 'gestora.bo');
        $ldapBaseDn = env('LDAP_BASE_DN', 'dc=gestora,dc=bo');

        $miUsuarioLdap = trim($miUsername) . '@' . $ldapDomain;

        if (!extension_loaded('ldap')) {
            return [
                'success' => false,
                'message' => 'La extensión LDAP no está habilitada en el servidor'
            ];
        }

        $ldapConnection = ldap_connect($ldapHost, $ldapPort);

        if (!$ldapConnection) {
            return [
                'success' => false,
                'message' => 'No se puede conectar al servidor LDAP'
            ];
        }

        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldapConnection, $miUsuarioLdap, trim($miPassword));

        if (!$bind) {
            $error = ldap_error($ldapConnection);
            ldap_close($ldapConnection);
            return [
                'success' => false,
                'message' => 'No se pudo autenticar en el servidor LDAP',
                'ldap_error' => $error
            ];
        }

        $escapedUsername = ldap_escape($usuarioABuscar, '', LDAP_ESCAPE_FILTER);
        $filter = "(sAMAccountName=$escapedUsername)"; // o uid/cn según el tipo de LDAP

        $search = @ldap_search($ldapConnection, $ldapBaseDn, $filter);

        if (!$search) {
            $error = ldap_error($ldapConnection);
            ldap_close($ldapConnection);
            return [
                'success' => false,
                'message' => 'Error al realizar la búsqueda',
                'ldap_error' => $error
            ];
        }

        $entries = ldap_get_entries($ldapConnection, $search);
        ldap_close($ldapConnection);

        if ($entries['count'] > 0) {
            return [
                'success' => true,
                'message' => 'El usuario fue encontrado',
                'data' => $entries[0]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'El usuario no existe en LDAP'
            ];
        }
    }

    function getCodigoTramites()
    {
        $tabla = 'listado_procesos';
        $tiempoCache = 60;

        $cacheKey = 'tramites_codigos_cache';
        $cacheTime = $tiempoCache;

        try {
            $data_procesos = Cache::remember($cacheKey, $cacheTime, function () {
                return \DB::table('rmx_vys_procesos')
                    ->selectRaw("prc_data->>'prc_codigo' as codigo")
                    ->where('prc_estado', '<>', 'X')
                    ->pluck('codigo');
            });

            return $data_procesos;
        } catch (\Exception $e) {
            \Log::error('Error al obtener los trámites: ' . $e->getMessage());
            return [];
        }
    }

    // centralized function to get the PDF document
    function globalGetPdfDocument($fullPath,$fileName)
    {
        // 1) Clean all buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        // 2) Disable compression (if on)
        if (ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }
        // 3) Send correct headers
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($fileName) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullPath));
        // 4) Stream the file
        readfile($fullPath);
    }


    function guardarServiceLog($metodo, $url, $requestBody, $responseBody, $codigo, $headers, $ip, $usuario, $numeroTramite, $servicio)
    {
        $qry_log = "select * from public.sp_create_service_logs(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        \DB::select($qry_log, [
            $metodo,
            $url,
            $requestBody,
            $responseBody,
            $codigo,
            $headers,
            $ip,
            $usuario,
            $numeroTramite,
            $servicio
        ]);
    }

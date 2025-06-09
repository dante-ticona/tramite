<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ldapAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $username = trim($request->input('username'));
        $password = trim($request->input('password'));

        $ldapHost = env('LDAP_HOST', '10.10.16.8');
        $ldapPort = env('LDAP_PORT', 389);
        $ldapDomain = env('LDAP_DOMAIN', 'gestora.bo');

        $ldapUser = $username . '@' . $ldapDomain;

        if (!extension_loaded('ldap')) {
            return response()->json([
                'success' => false,
                'message' => 'La extensión LDAP no está habilitada en el servidor'
            ], 500);
        }

        $ldapConnection = ldap_connect($ldapHost, $ldapPort);

        if (!$ldapConnection) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo conectar al servidor LDAP'
            ], 500);
        }

        ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldapConnection, $ldapUser, $password);

        if ($bind && $password !== '') {
            ldap_close($ldapConnection);
            
            return response()->json([
                'success' => true,
                'message' => 'Autenticación exitosa'
            ]);
        } else {
            ldap_close($ldapConnection);
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }
    }
}

<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

// ##############################################################
// #                                                            #
// #                CREDENCIALES PARA CALIDAD                   #
// #                                                            #
// ##############################################################

    function entorno()
    {
        $entorno = "CALIDAD";
        return $entorno;
    }

    function version()
    {
        $version = "449/2024"; // VERSION DE LANZAMIENTO -> CODIGO DE LANZAMIENTO * NRO SOLICITUD /SISTEMA DE REQUERIMIENTO SGG
        return $version;
    }

    function urlGestora()
    {
        // $url_gestora = "https://cali-sipre.gestora.bo";
        $url_gestora = "https://desa-sipre.gestora.bo";
        return $url_gestora;
    }

    function urlsggTest()
    {
        $url_gestora_sgg = "https://sgg.test.gestora.bo";
        return $url_gestora_sgg;
    }

    function urlsggGestora1()
    {
        $url_gestora_sgg1 = "https://sgg.test.gestora.bo";
        return $url_gestora_sgg1;
    }

    function urlPersonas()
    {
        $url_personas = "https://pruebas.gestora.bo/servicios/cenpersonas";
        return $url_personas;
    }

    function urlPlanillas()
    {
        $url_planillas = "https://pruebas.gestora.bo/reporte-boletas-pago";
        return $url_planillas;
    }

    function urlautenticarPlanillas()
    {
        $url_autenticar_planillas = "https://pruebas.gestora.bo/seguridad-apis/api/autenticar";
        return $url_autenticar_planillas;
    }

    function obtenerCredencialesPlanillas()
    {
        $credenciales = [
            "username" => "wsprestaciones",
            "password" => "Prestaciones2023"
        ];

        return json_decode(json_encode($credenciales), true);
    }

    // CREDENCIALES DE CALIDAD
    function obtenerCredenciales()
    {
        $credenciales = [
            "login" => "demo.gestora@gestora-demo.bo",
            "password" => "Tempo.2024@"
        ];

        return json_decode(json_encode($credenciales), true);
    }

    function colorTema()
    {
        $tema = "sidebar-light-orange";
        return $tema;
    }

    function appUrl()
    {
        $url = "https://cali-tramitesip.gestora.bo";
        return $url;
    }

    function gtalentoQuarkusUrlBase(){
        $url_gtalento_sgg = "https://pruebas.gestora.bo";
        return $url_gtalento_sgg;
    }

    function authenticateUrlRegistrarPoder()
    {
        $credenciales = [
            "url"=>"https://sgg.test.gestora.bo/api/auth/authorize_password",
            "credenciales"=> [
                "username" => "operador7",
                "password" => "operador7"
            ]
        ];

        return json_decode(json_encode($credenciales), true);
    }

    function userLdap() {
        $credenciales_ldap = [
            "login" => "liliana.ramos@gestora.bo",
            "password" => "Tempo.12"
        ];
        return json_decode(json_encode($credenciales_ldap), true);
    }

    function rutaLogoTramiteSIP(){
        $ruta = '/img/logoEmpresaCali.png';
        return $ruta;
    }

    function GetTokenDocumentosUcpp()
    {
        $token = 'eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJEZXNhcnJvbGxhZG9yZXMiLCJqdGkiOiJGOTQ0VyIsImlhdCI6MTY3MjMyNjc5NCwicmVhZCI6dHJ1ZSwid3JpdGUiOnRydWUsInVwZGF0ZSI6dHJ1ZX0.sAFEy-NPcgy4ElUZlPkGiDl50IRmMAS9gHHNAvD87RE';
        return $token;
    }

    function urlDocumentosUcpp()
    {
        $url_documentos = "https://pruebas.gestora.bo/servidor-archivos/api/files/";
        return $url_documentos;
    }

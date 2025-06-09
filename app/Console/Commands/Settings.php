<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Settings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'variables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configuracion de variables de entorno Sistema de TramiteSIP';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Comando para cambio de variables de entorno Desarrollo/Calidad/Producción Sistema de TramiteSIP');

        $entorno = $this->choice(
            '¿Qué entorno deseas usar?',
            ['desarrollo', 'producción', 'calidad'],
            0
        );

        $ruta = match ($entorno) {
            'desarrollo' => base_path('app/helpersDesa.php'),
            'producción' => base_path('app/helpersProd.php'),
            'calidad' => base_path('app/helpersCali.php'),
            default => null,
        };

        $destino = base_path('app/helpers.php');

        if ($ruta && File::exists($ruta)) {
            File::copy($ruta, $destino);

            if ($entorno === 'desarrollo') {
                $this->cambiarCredencialesLdapDesa();
            }

            if ($entorno === 'calidad') {
                $this->cambiarCredencialesLdapCali();
            }

            if ($entorno === 'producción') {
                $versionCod = $this->ask('Por favor, ingresa el código de versión (ejemplo., 449/2024)');
                $this->cambiarVersion($versionCod);
                $this->cambiarCredenciales();
            }

            $masterRuta = match ($entorno) {
                'desarrollo' => base_path('resources/views/layouts/entornos/masterDesarrollo.blade.php'),
                'producción' => base_path('resources/views/layouts/entornos/masterProduccion.blade.php'),
                'calidad' => base_path('resources/views/layouts/entornos/masterCalidad.blade.php'),
                default => null,
            };

            $masterDestino = base_path('resources/views/layouts/master.blade.php');

            if ($masterRuta && File::exists($masterRuta)) {
                File::copy($masterRuta, $masterDestino);
                $this->info("El contenido de {$masterRuta} ha sido copiado a master.blade.php");
            } else if ($entorno !== 'desarrollo') {
                $this->error('Error al intentar copiar el archivo de master.blade.php');
            }

            $this->info('');
            $this->info('----------------------------------------------------------------------');
            $this->info('   >>>>>  Configuracion de variables de entorno               <<<<<   ');
            $this->info('            #####  SISTEMA TRAMITESIP  #####   ');
            $this->info('----------------/DESARROLLO/CALIDAD/PRODUCCION------------------------');
            $this->info('');
            $this->info('----------------------------------------------------------------------');
            $this->info('->>>>>>>> SE REALIZO EL CAMBIO DE LOGO DEL PROYECTO <<<<<<<<-');
            $this->info("El contenido de {$ruta} ha sido copiado a helpers.php");
            $this->call('cache:clear');
            $this->call('config:cache');
            $this->call('route:clear');
            $this->call('view:clear');
            $this->call('event:clear');
            $this->call('clear-compiled');
            shell_exec('composer dump-autoload');
        } else {
            $this->error('Error al intentar copiar el archivo de configuración');
        }
    }

    protected function cambiarVersion($versionCod)
    {
        $helpersRuta = base_path('app/helpers.php');
        $helpersContenido = File::get($helpersRuta);

        $actualizarContenidoHelper = preg_replace(
            '/function version\(\)\s*{\s*\$version = "[^"]+";/',
            'function version() { $version = "' . $versionCod . '";',
            $helpersContenido
        );

        File::put($helpersRuta, $actualizarContenidoHelper);
        $this->info("Codigo actualizado a {$versionCod} en helpers.php");
    }

    protected function cambiarCredenciales()
    {
        $helpersRuta = base_path('app/helpers.php');
        $helpersRutaProd = base_path('app/helpersProd.php');

        $helpersContenido = File::get($helpersRuta);

        preg_match(
            '/function obtenerCredenciales\(\)\s*{\s*\$credenciales = \[\s*"login" => "([^"]+)",\s*"password" => "([^"]+)"\s*\];/',
            $helpersContenido,
            $matches
        );

        $loginActual = $matches[1];
        $passwordActual = $matches[2];

        $this->info("Credenciales actuales:");
        $this->info("Login: " . $loginActual);
        $this->info("Password: " . $passwordActual);

        $cambiar = $this->choice(
            '¿Deseas cambiar las credenciales?',
            ['no', 'si'],
            0
        );

        if ($cambiar === 'si') {
            $nuevoLogin = $this->ask('Introduce el nuevo login');
            $nuevoPassword = $this->ask('Introduce el nuevo password');

            $actualizarContenidoHelper = preg_replace(
                '/function obtenerCredenciales\(\)\s*{\s*\$credenciales = \[\s*"login" => "[^"]+",\s*"password" => "[^"]+"\s*\];/',
                'function obtenerCredenciales() { $credenciales = ["login" => "' . $nuevoLogin . '", "password" => "' . $nuevoPassword . '"];',
                $helpersContenido
            );

            $actualizarContenidoHelper = preg_replace(
                '/function userLdap\(\)\s*{\s*\$credenciales_ldap = \[\s*"login" => "[^"]+",\s*"password" => "[^"]+"\s*\];/',
                'function userLdap() { $credenciales_ldap = ["login" => "' . $nuevoLogin . '", "password" => "' . $nuevoPassword . '"];',
                $actualizarContenidoHelper // Aquí usamos el resultado de la primera operación
            );

            File::put($helpersRuta, $actualizarContenidoHelper);
            File::put($helpersRutaProd, $actualizarContenidoHelper);

            $this->info("Credenciales actualizadas a login: {$nuevoLogin}, password: {$nuevoPassword} en helpersProd.php");
        } else {
            $this->info("No se realizaron cambios en las credenciales.");
        }
    }

    protected function cambiarCredencialesLdapDesa()
    {
        $helpersRuta = base_path('app/helpers.php');
        $helpersRutaDesa = base_path('app/helpersDesa.php');

        $helpersContenido = File::get($helpersRuta);

        preg_match(
            '/function userLdap\(\)\s*{\s*\$credenciales_ldap = \[\s*"login" => "([^"]+)",\s*"password" => "([^"]+)"\s*\];/',
            $helpersContenido,
            $matches
        );

        $loginActual = $matches[1];
        $passwordActual = $matches[2];

        $this->info("Credenciales actuales para LDAP son:");
        $this->info("Login: " . $loginActual);
        $this->info("Password: " . $passwordActual);

        $cambiar = $this->choice(
            '¿Deseas cambiar las credenciales?',
            ['no', 'si'],
            0
        );

        if ($cambiar === 'si') {
            $this->info('Por favor, ingresa credenciales para LDAP (ejemplo.,nombre.apellido@gestora.bo, Tempo.2025)');
            $nuevoLogin = $this->ask('Introduce el nuevo login LDAP');
            $nuevoPassword = $this->ask('Introduce el nuevo password LPAP');

            $actualizarContenidoHelper = preg_replace(
                '/function userLdap\(\)\s*{\s*\$credenciales_ldap = \[\s*"login" => "[^"]+",\s*"password" => "[^"]+"\s*\];/',
                'function userLdap() { $credenciales_ldap = ["login" => "' . $nuevoLogin . '", "password" => "' . $nuevoPassword . '"];',
                $helpersContenido
            );

            File::put($helpersRuta, $actualizarContenidoHelper);
            File::put($helpersRutaDesa, $actualizarContenidoHelper);

            $this->info("Credenciales actualizadas a login: {$nuevoLogin}, password: {$nuevoPassword} en helpersProd.php");
        } else {
            $this->info("No se realizaron cambios en las credenciales.");
        }
    }

    protected function cambiarCredencialesLdapCali()
    {
        $helpersRuta = base_path('app/helpers.php');
        $helpersRutaCali = base_path('app/helpersCali.php');

        $helpersContenido = File::get($helpersRuta);

        preg_match(
            '/function userLdap\(\)\s*{\s*\$credenciales_ldap = \[\s*"login" => "([^"]+)",\s*"password" => "([^"]+)"\s*\];/',
            $helpersContenido,
            $matches
        );

        $loginActual = $matches[1];
        $passwordActual = $matches[2];

        $this->info("Credenciales actuales para LDAP son:");
        $this->info("Login: " . $loginActual);
        $this->info("Password: " . $passwordActual);

        $cambiar = $this->choice(
            '¿Deseas cambiar las credenciales?',
            ['no', 'si'],
            0
        );

        if ($cambiar === 'si') {
            $this->info('Por favor, ingresa credenciales para LDAP (ejemplo.,nombre.apellido@gestora.bo, Tempo.2025)');
            $nuevoLogin = $this->ask('Introduce el nuevo login LDAP');
            $nuevoPassword = $this->ask('Introduce el nuevo password LPAP');

            $actualizarContenidoHelper = preg_replace(
                '/function userLdap\(\)\s*{\s*\$credenciales_ldap = \[\s*"login" => "[^"]+",\s*"password" => "[^"]+"\s*\];/',
                'function userLdap() { $credenciales_ldap = ["login" => "' . $nuevoLogin . '", "password" => "' . $nuevoPassword . '"];',
                $helpersContenido
            );

            File::put($helpersRuta, $actualizarContenidoHelper);
            File::put($helpersRutaCali, $actualizarContenidoHelper);

            $this->info("Credenciales actualizadas a login: {$nuevoLogin}, password: {$nuevoPassword} en helpersProd.php");
        } else {
            $this->info("No se realizaron cambios en las credenciales.");
        }
    }
}

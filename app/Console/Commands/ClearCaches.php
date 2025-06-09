<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearCaches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:caches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todas las caches del proyecto';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('');
        $this->info('----------------------------------------------------------------------');
        $this->info('   >>>>>          Limpieza de Caches del Proyecto               <<<<<   ');
        $this->info('            #####  SISTEMA TRAMITESIP  #####   ');
        $this->info('');
        $this->info('----------------------------------------------------------------------');

        $this->info('Limpiando cachés del proyecto...');

        $caches = [
            ['comando' => 'cache:clear', 'descripcion' => 'Limpiando caché de la aplicación'],
            ['comando' => 'config:clear', 'descripcion' => 'Limpiando caché de configuración'],
            ['comando' => 'route:clear', 'descripcion' => 'Limpiando caché de rutas'],
            ['comando' => 'view:clear', 'descripcion' => 'Limpiando caché de vistas'],
            ['comando' => 'event:clear', 'descripcion' => 'Limpiando caché de eventos'],
            ['comando' => 'clear-compiled', 'descripcion' => 'Limpiando archivos compilados'],
        ];

        $totalTareas = count($caches);
        $barraDeProgreso = $this->output->createProgressBar($totalTareas);
        $barraDeProgreso->start();

        foreach ($caches as $index => $task) {
            $this->info("🔄 {$task['descripcion']}...");
            $this->call($task['comando']);
            $barraDeProgreso->advance();
            $this->info(" ✅ Completado (" . ($index + 1) . "/$totalTareas)");
        }

        $this->info('🔄 Ejecutando composer dump-autoload...');
        shell_exec('composer dump-autoload');
        $barraDeProgreso->advance();
        $this->info(" ✅ Completado ({$totalTareas}/{$totalTareas})");

        $barraDeProgreso->finish();
        $this->info('');
        $this->info('Todas las cachés han sido limpiadas exitosamente.');
        return 0;
    }
}

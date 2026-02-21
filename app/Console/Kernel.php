<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Renovar carnets automáticamente todos los días a las 2:00 AM
        $schedule->command('carnets:renovar-automatico')
            ->daily()
            ->at('02:00')
            ->appendOutputTo(storage_path('logs/carnets_renovacion.log'));

        // Verificar carnets vencidos y marcarlos (cada semana)
        $schedule->call(function () {
            \App\Models\Carnet::where('fecha_vencimiento', '<', now())
                ->where('estado', 'activo')
                ->update(['estado' => 'vencido']);
        })->weekly()
            ->sundays()
            ->at('03:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Carnet;
use App\Models\Usuario;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RenovarCarnetsAutomatico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carnets:renovar-automatico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renueva automáticamente los carnets que están próximos a vencer (30 días antes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Iniciando renovación automática de carnets...');
        $this->newLine();

        // Fecha límite: carnets que vencen en los próximos 30 días
        $fechaLimite = Carbon::now()->addDays(30);

        // Buscar carnets próximos a vencer
        $carnetsProximosVencer = Carnet::where('estado', 'activo')
            ->where('fecha_vencimiento', '<=', $fechaLimite)
            ->with('usuario')
            ->get();

        if ($carnetsProximosVencer->isEmpty()) {
            $this->info('✅ No hay carnets próximos a vencer.');
            return Command::SUCCESS;
        }

        $this->info("📋 Se encontraron {$carnetsProximosVencer->count()} carnets para renovar:");
        $this->newLine();

        $renovados = 0;
        $errores = 0;

        foreach ($carnetsProximosVencer as $carnetViejo) {
            try {
                $usuario = $carnetViejo->usuario;

                // Marcar carnet viejo como vencido
                $carnetViejo->update([
                    'estado' => 'vencido'
                ]);

                // Generar nuevo código QR
                $codigoQR = 'ISTPET-CARNET-' . time() . '-' . Str::random(10);

                // Crear nuevo carnet
                $nuevoCarnet = Carnet::create([
                    'usuario_id' => $usuario->id,
                    'codigo_qr' => $codigoQR,
                    'fecha_emision' => Carbon::now(),
                    'fecha_vencimiento' => Carbon::now()->addMonths(6), // 6 meses
                    'estado' => 'activo',
                ]);

                $renovados++;

                // Convertir fechas a Carbon si son strings
                $fechaVencimientoViejo = $carnetViejo->fecha_vencimiento instanceof \Carbon\Carbon
                    ? $carnetViejo->fecha_vencimiento
                    : \Carbon\Carbon::parse($carnetViejo->fecha_vencimiento);

                $fechaVencimientoNuevo = $nuevoCarnet->fecha_vencimiento instanceof \Carbon\Carbon
                    ? $nuevoCarnet->fecha_vencimiento
                    : \Carbon\Carbon::parse($nuevoCarnet->fecha_vencimiento);

                $this->line("✅ Renovado: {$usuario->nombres} {$usuario->apellidos} (Cédula: {$usuario->cedula})");
                $this->line("   Vencía: {$fechaVencimientoViejo->format('d/m/Y')}");
                $this->line("   Nuevo vencimiento: {$fechaVencimientoNuevo->format('d/m/Y')}");
                $this->newLine();

                // TODO: Enviar notificación por email al estudiante
                // $this->enviarNotificacionRenovacion($usuario, $nuevoCarnet);

            } catch (\Exception $e) {
                $errores++;
                $this->error("❌ Error renovando carnet de {$usuario->nombreCompleto}: {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info('📊 RESUMEN:');
        $this->table(
            ['Concepto', 'Cantidad'],
            [
                ['Carnets renovados', $renovados],
                ['Errores', $errores],
                ['Total procesados', $carnetsProximosVencer->count()],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Enviar notificación de renovación (implementar después)
     */
    private function enviarNotificacionRenovacion($usuario, $carnet)
    {
        // TODO: Implementar envío de email
        // Mail::to($usuario->correo)->send(new CarnetRenovado($usuario, $carnet));
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CedulaValidator;

class EstudiantesFicticiasSeeder extends Seeder
{
    /**
     * Nombres ficticios ecuatorianos
     */
    private $nombresHombres = [
        'Juan',
        'Carlos',
        'Luis',
        'Miguel',
        'José',
        'Francisco',
        'Manuel',
        'Antonio',
        'Pedro',
        'Diego',
        'Andrés',
        'David',
        'Daniel',
        'Pablo',
        'Javier',
        'Fernando',
        'Alejandro',
        'Sebastián',
        'Mateo',
        'Santiago',
        'Nicolás',
        'Gabriel',
        'Martín',
        'Ricardo',
        'Eduardo',
        'Roberto',
        'Raúl',
        'Gonzalo',
        'Emilio',
        'Ángel'
    ];

    private $nombresMujeres = [
        'María',
        'Ana',
        'Carmen',
        'Rosa',
        'Lucía',
        'Isabel',
        'Patricia',
        'Laura',
        'Sandra',
        'Daniela',
        'Andrea',
        'Gabriela',
        'Sofía',
        'Valentina',
        'Camila',
        'Fernanda',
        'Alejandra',
        'Carolina',
        'Natalia',
        'Paola',
        'Diana',
        'Verónica',
        'Mónica',
        'Jessica',
        'Katherine',
        'Cristina',
        'Elena',
        'Silvia',
        'Martha',
        'Gloria'
    ];

    private $apellidos = [
        'González',
        'Rodríguez',
        'Pérez',
        'López',
        'Martínez',
        'García',
        'Sánchez',
        'Ramírez',
        'Torres',
        'Flores',
        'Rivera',
        'Gómez',
        'Díaz',
        'Cruz',
        'Morales',
        'Herrera',
        'Jiménez',
        'Álvarez',
        'Romero',
        'Vargas',
        'Castro',
        'Ruiz',
        'Ortiz',
        'Mendoza',
        'Vega',
        'Guzmán',
        'Paredes',
        'Salazar',
        'Córdova',
        'Espinoza',
        'Chávez',
        'Ríos',
        'Medina',
        'Reyes',
        'Núñez',
        'Guerrero',
        'Maldonado',
        'Aguilar',
        'León',
        'Moreno',
        'Campos',
        'Rojas',
        'Valencia',
        'Zamora',
        'Acosta',
        'Delgado',
        'Castillo',
        'Peña'
    ];

    private $carreras = [
        'Desarrollo de Software',
        'Redes y Telecomunicaciones',
        'Administración de Empresas',
        'Marketing Digital',
        'Diseño Gráfico'
    ];

    private $semestres = [
        'PRIMER NIVEL',
        'SEGUNDO NIVEL',
        'TERCER NIVEL',
        'CUARTO NIVEL',
        'QUINTO NIVEL',
        'SEXTO NIVEL'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Generando 100 estudiantes ficticios...\n\n";

        // Verificar que la carpeta de fotos existe
        if (!Storage::disk('public')->exists('fotos_perfil')) {
            Storage::disk('public')->makeDirectory('fotos_perfil');
        }

        $estudiantesCreados = 0;
        $errores = 0;

        for ($i = 1; $i <= 100; $i++) {
            try {
                // Determinar género aleatorio
                $esHombre = rand(0, 1) === 1;

                // Seleccionar nombre según género
                $nombre = $esHombre
                    ? $this->nombresHombres[array_rand($this->nombresHombres)]
                    : $this->nombresMujeres[array_rand($this->nombresMujeres)];

                // Agregar segundo nombre ocasionalmente
                if (rand(0, 2) === 1) {
                    $segundoNombre = $esHombre
                        ? $this->nombresHombres[array_rand($this->nombresHombres)]
                        : $this->nombresMujeres[array_rand($this->nombresMujeres)];
                    $nombre .= ' ' . $segundoNombre;
                }

                // Apellidos
                $apellido1 = $this->apellidos[array_rand($this->apellidos)];
                $apellido2 = $this->apellidos[array_rand($this->apellidos)];
                $apellidos = $apellido1 . ' ' . $apellido2;

                // Generar cédula válida
                $cedula = CedulaValidator::generar();

                // Carrera y semestre
                $carrera = $this->carreras[array_rand($this->carreras)];
                $semestre = $this->semestres[array_rand($this->semestres)];

                // Generar correo institucional
                $nombreLimpio = $this->limpiarTexto($nombre);
                $apellidoLimpio = $this->limpiarTexto($apellido1);
                $correo = strtolower($nombreLimpio . '.' . $apellidoLimpio . $cedula[7] . $cedula[8] . '@istpet.edu.ec');

                // Verificar que no exista
                $existe = DB::table('usuarios')
                    ->where('cedula', $cedula)
                    ->orWhere('correo_institucional', $correo)
                    ->exists();

                if ($existe) {
                    // Generar otra cédula
                    $cedula = CedulaValidator::generar();
                    // Regenerar correo
                    $correo = strtolower($nombreLimpio . '.' . $apellidoLimpio . $cedula[7] . $cedula[8] . '@istpet.edu.ec');
                }

                // Celular (09 + 8 dígitos aleatorios)
                $celular = '09' . rand(10000000, 99999999);

                // Dirección aleatoria
                $direccion = $this->generarDireccion();

                // Fecha de nacimiento (18-25 años)
                $edad = rand(18, 25);
                $fechaNacimiento = date('Y-m-d', strtotime("-$edad years"));

                // Descargar foto de API
                $fotoPath = $this->descargarFotoRandom($esHombre, $cedula);

                // Crear estudiante
                $usuarioId = DB::table('usuarios')->insertGetId([
                    'tipo_documento' => 'cedula',
                    'cedula' => $cedula,
                    'nombres' => $nombre,
                    'apellidos' => $apellidos,
                    'nacionalidad' => 'Ecuatoriana',
                    'carrera' => $carrera,
                    'semestre' => $semestre,
                    'ciclo_nivel' => $semestre, // Usar el semestre también para ciclo_nivel
                    'correo_institucional' => $correo,
                    'celular' => $celular,
                    'password' => Hash::make('estudiante123'), // Password por defecto
                    'foto_url' => $fotoPath,
                    'tipo_usuario' => 'estudiante',
                    'estado' => 'activo',
                    'password_temporal' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $estudiantesCreados++;

                echo "[$estudiantesCreados/100] ✅ {$nombre} {$apellidos} - {$cedula}\n";

                // Pausa pequeña para no saturar la API
                if ($i % 10 === 0) {
                    sleep(1);
                }
            } catch (\Exception $e) {
                $errores++;
                echo "❌ Error en estudiante $i: " . $e->getMessage() . "\n";
            }
        }

        echo "\n🎉 Proceso completado!\n";
        echo "✅ Estudiantes creados: $estudiantesCreados\n";
        echo "❌ Errores: $errores\n";
    }

    /**
     * Descargar foto random de API
     */
    private function descargarFotoRandom($esHombre, $cedula)
    {
        try {
            // Usar API de fotos aleatorias
            // Alternativas:
            // 1. https://randomuser.me/api/ (API gratuita con fotos reales)
            // 2. https://i.pravatar.cc/300 (avatares)
            // 3. https://thispersondoesnotexist.com/ (fotos generadas por IA)

            // Usaremos randomuser.me porque es estable y gratuita
            $genero = $esHombre ? 'male' : 'female';
            $url = "https://randomuser.me/api/?gender={$genero}&nat=us,gb,es";

            $response = @file_get_contents($url);

            if ($response === false) {
                // Si falla, usar avatar genérico
                return $this->generarAvatarGenerico($esHombre, $cedula);
            }

            $data = json_decode($response, true);

            if (isset($data['results'][0]['picture']['large'])) {
                $imageUrl = $data['results'][0]['picture']['large'];

                // Descargar imagen
                $imageData = @file_get_contents($imageUrl);

                if ($imageData !== false) {
                    // Guardar en storage
                    $filename = 'foto_' . $cedula . '_' . time() . '.jpg';
                    $path = 'fotos_perfil/' . $filename;
                    Storage::disk('public')->put($path, $imageData);

                    // Retornar ruta para foto_url
                    return 'storage/' . $path;
                }
            }

            // Fallback a avatar genérico
            return $this->generarAvatarGenerico($esHombre, $cedula);
        } catch (\Exception $e) {
            return $this->generarAvatarGenerico($esHombre, $cedula);
        }
    }

    /**
     * Generar avatar genérico si falla la API
     */
    private function generarAvatarGenerico($esHombre, $cedula)
    {
        // Usar pravatar.cc como fallback (más simple y siempre funciona)
        $seed = $cedula;
        $url = "https://i.pravatar.cc/300?img=" . ($esHombre ? rand(1, 50) : rand(51, 70));

        try {
            $imageData = @file_get_contents($url);

            if ($imageData !== false) {
                $filename = 'foto_' . $cedula . '_' . time() . '.jpg';
                $path = 'fotos_perfil/' . $filename;
                Storage::disk('public')->put($path, $imageData);

                return 'storage/' . $path;
            }
        } catch (\Exception $e) {
            // Si todo falla, retornar null (se usará foto por defecto)
        }

        return null;
    }

    /**
     * Limpiar texto para correo
     */
    private function limpiarTexto($texto)
    {
        $texto = strtolower($texto);
        $texto = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $texto);
        $texto = str_replace(['ñ'], ['n'], $texto);
        $texto = preg_replace('/[^a-z]/', '', $texto);

        return substr($texto, 0, 10); // Máximo 10 caracteres
    }

    /**
     * Generar dirección aleatoria de Quito
     */
    private function generarDireccion()
    {
        $sectores = [
            'La Carolina',
            'La Mariscal',
            'El Batán',
            'Iñaquito',
            'La Floresta',
            'Guápulo',
            'González Suárez',
            'La Pradera',
            'Quito Tenis',
            'El Bosque',
            'La Kennedy',
            'Ponceano',
            'Carcelén',
            'Comité del Pueblo',
            'Cotocollao',
            'El Condado',
            'Jipijapa',
            'Rumipamba',
            'San Isidro del Inca',
            'Zámbiza'
        ];

        $calles = [
            'Av. Amazonas',
            'Av. 6 de Diciembre',
            'Av. República',
            'Av. Shyris',
            'Av. Naciones Unidas',
            'Av. Eloy Alfaro',
            'Av. 10 de Agosto',
            'Av. Patria'
        ];

        $sector = $sectores[array_rand($sectores)];
        $calle = $calles[array_rand($calles)];
        $numero = rand(100, 9999);

        return "$calle N$numero y $sector, Quito";
    }
}

@echo off
echo ========================================
echo INSTALACION AUTOMATICA - SISTEMA ISTPET
echo ========================================
echo.
echo Este script creara toda la estructura MVC
echo del Sistema de Carnetizacion ISTPET
echo.
pause

echo.
echo [1/7] Creando Migraciones...
php artisan make:migration create_usuarios_table
php artisan make:migration create_carnets_table
php artisan make:migration create_profesores_table
php artisan make:migration create_administradores_table
php artisan make:migration create_laboratorios_table
php artisan make:migration create_accesos_table
echo ✓ Migraciones creadas

echo.
echo [2/7] Creando Modelos...
php artisan make:model Usuario
php artisan make:model Carnet
php artisan make:model Profesor
php artisan make:model Administrador
php artisan make:model Laboratorio
php artisan make:model Acceso
echo ✓ Modelos creados

echo.
echo [3/7] Creando Controladores...
php artisan make:controller Auth/LoginController
php artisan make:controller Auth/LogoutController
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/UsuarioController --resource
php artisan make:controller Admin/CarnetController --resource
php artisan make:controller Admin/LaboratorioController --resource
php artisan make:controller Admin/AccesoController
php artisan make:controller Admin/ReporteController
php artisan make:controller Profesor/DashboardController
php artisan make:controller Profesor/AccesoController
php artisan make:controller Profesor/ReporteController
php artisan make:controller Estudiante/DashboardController
php artisan make:controller Estudiante/CarnetController
php artisan make:controller Estudiante/AccesoController
echo ✓ Controladores creados

echo.
echo [4/7] Creando Seeders...
php artisan make:seeder AdministradorSeeder
php artisan make:seeder ProfesorSeeder
php artisan make:seeder UsuarioSeeder
php artisan make:seeder LaboratorioSeeder
echo ✓ Seeders creados

echo.
echo [5/7] Creando Middlewares...
php artisan make:middleware CheckAdministrador
php artisan make:middleware CheckProfesor
php artisan make:middleware CheckEstudiante
echo ✓ Middlewares creados

echo.
echo [6/7] Creando Requests...
php artisan make:request StoreUsuarioRequest
php artisan make:request UpdateUsuarioRequest
php artisan make:request LoginRequest
echo ✓ Requests creados

echo.
echo [7/7] Creando carpeta Services...
if not exist "app\Services" mkdir app\Services
echo ✓ Carpeta Services creada

echo.
echo ========================================
echo ✓ INSTALACION COMPLETADA EXITOSAMENTE
echo ========================================
echo.
echo Estructura MVC creada:
echo - 6 Migraciones
echo - 6 Modelos
echo - 14 Controladores
echo - 4 Seeders
echo - 3 Middlewares
echo - 3 Requests
echo - 1 Carpeta Services
echo.
echo Siguiente paso: Copiar el codigo de las migraciones
echo.
pause

# 🎓 Sistema de Carnetización y Control de Accesos - ISTPET

Sistema web completo desarrollado en Laravel 10 para la gestión integral de carnets digitales, control de accesos a laboratorios e importación masiva de estudiantes del Instituto Superior Tecnológico Mayor Pedro Traversari.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-orange)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple)](https://getbootstrap.com)

---

## 🎯 Descripción del Proyecto

Sistema integral que permite la administración completa de estudiantes, generación automática de carnets con códigos QR únicos, control de accesos a laboratorios técnicos y aulas interactivas, importación masiva desde Excel, y renovación automática de carnets.

Desarrollado como proyecto de tesis para el tercer semestre de la carrera de Desarrollo de Software en ISTPET.

---

## ✨ Características Principales

### 🎫 **Gestión de Carnets Digitales**

- ✅ Generación automática de carnets con QR único por estudiante
- ✅ Código QR formato: `ISTPET-2026-[CÉDULA]`
- ✅ Validez de 4 años desde la emisión
- ✅ Estados: Activo, Vencido, Bloqueado
- ✅ Diseño institucional con colores ISTPET (azul #1a2342 y dorado #F7931E)
- ✅ Descarga en PDF individual o masiva
- ✅ Vista previa en navegador
- ✅ **Renovación automática** 30 días antes de vencer (comando artisan programado)

### 👥 **Gestión de Estudiantes**

- ✅ CRUD completo (Crear, Leer, Actualizar, Eliminar)
- ✅ **Validación de cédulas ecuatorianas** (algoritmo oficial del módulo 10)
- ✅ **Subir fotos de perfil** (JPG, PNG hasta 2MB)
- ✅ Vista previa de fotos con iniciales si no hay imagen
- ✅ Campos: nombres, apellidos, cédula, correo, celular, carrera, ciclo/nivel
- ✅ **5 carreras disponibles:**
    - Desarrollo de Software
    - Redes y Telecomunicaciones
    - Seguridad Informática
    - Administración de Sistemas
    - Soporte Técnico
- ✅ Estados: Activo, Inactivo, Bloqueado
- ✅ Bloqueo/desbloqueo de acceso
- ✅ Reseteo de contraseñas temporales
- ✅ Búsqueda por nombre, apellido o cédula
- ✅ Filtros por ciclo y estado
- ✅ Paginación de resultados
- ✅ **Importación masiva desde Excel** (ver sección completa abajo)

### 📊 **Importación Masiva desde Excel**

- ✅ **Subir archivo Excel** (.xlsx, .xls, .csv) con múltiples estudiantes
- ✅ **Plantilla descargable** con formato correcto
- ✅ **Validaciones automáticas:**
    - Cédulas ecuatorianas válidas
    - Emails únicos
    - Celulares formato correcto (09XXXXXXXX)
    - Detección de duplicados
    - Campos obligatorios completos
- ✅ **Subir fotos en ZIP** (opcional)
    - Nombrar fotos por cédula: `1726429283.jpg`
    - Asignación automática a estudiantes
- ✅ **Generación automática de carnets** al importar
- ✅ **Reporte detallado** post-importación:
    - Cantidad de exitosos
    - Lista de errores con detalles
    - Duplicados detectados
    - Carnets generados
- ✅ Importación transaccional (rollback si hay errores)

### 🔬 **Gestión de Laboratorios**

- ✅ Separación clara: **Laboratorios Técnicos** vs **Aulas Interactivas**
- ✅ CRUD completo de laboratorios
- ✅ Campos: nombre, capacidad, ubicación, código QR
- ✅ Control de capacidad en tiempo real
- ✅ Estados: Disponible, Ocupado, Mantenimiento
- ✅ Indicador visual de ocupación con barra de progreso
- ✅ Filtros por tipo y estado
- ✅ Búsqueda por nombre

### 📍 **Control de Accesos**

- ✅ Registro de entrada/salida mediante escaneo QR
- ✅ Validación de carnet activo
- ✅ Asignación automática de equipo
- ✅ Marca de ausencia para estudiantes que no salen
- ✅ Observaciones por acceso
- ✅ Historial completo por estudiante
- ✅ Historial por laboratorio
- ✅ Estudiantes actualmente en laboratorio
- ✅ Estadísticas de accesos por mes

### 📈 **Dashboard Administrativo**

- ✅ **Estadísticas en tiempo real:**
    - Total de estudiantes
    - Carnets activos
    - Estudiantes en laboratorios
    - Solicitudes pendientes
- ✅ **8 acciones rápidas:**
    - Nuevo Estudiante
    - Generar Carnet
    - Gestionar Laboratorios
    - Ver Estadísticas
    - **Importación Masiva** ← NUEVO
    - Ver Carnets
    - Ver Estudiantes
    - Gestionar Solicitudes
- ✅ **Estado de laboratorios** con:
    - Ocupación actual
    - Porcentaje de uso
    - Barra de progreso con colores (verde/amarillo/rojo)
    - Acceso directo a estudiantes en cada lab
- ✅ **Últimos accesos** del día
- ✅ **Solicitudes recientes** de contraseña

### 🔐 **Seguridad y Validaciones**

- ✅ **Validación de cédulas ecuatorianas** (algoritmo módulo 10)
- ✅ Contraseñas encriptadas con Bcrypt
- ✅ Protección CSRF en todos los formularios
- ✅ Middleware de autenticación por roles:
    - Admin (acceso total)
    - Estudiante (vista limitada)
- ✅ Validaciones en servidor y cliente
- ✅ Detección de duplicados (cédula, email)
- ✅ Sesiones seguras
- ✅ Contraseñas temporales con cambio obligatorio

### 🌐 **Interfaz de Usuario**

- ✅ Diseño responsive con Bootstrap 5
- ✅ Colores institucionales ISTPET
- ✅ Iconos Bootstrap Icons
- ✅ Mensajes flash de éxito/error
- ✅ Modales de confirmación
- ✅ Alertas visuales
- ✅ Tablas con paginación
- ✅ Búsqueda en tiempo real (AJAX)
- ✅ Preview de imágenes antes de subir
- ✅ Interfaz en español

### 🔄 **Automatizaciones**

- ✅ **Renovación automática de carnets** (comando artisan)
    - Se ejecuta diariamente a las 2:00 AM
    - Busca carnets que vencen en 30 días
    - Marca viejos como "vencido"
    - Genera nuevos con QR diferente
    - Log en: `storage/logs/carnets_renovacion.log`
- ✅ Generación de códigos QR únicos
- ✅ Cálculo automático de fechas de vencimiento
- ✅ Asignación automática de equipos en laboratorios

---

## 🛠️ Tecnologías Utilizadas

### **Backend**

- **Laravel 10.x** - Framework PHP
- **PHP 8.2+** - Lenguaje de programación
- **MySQL 8.0** - Base de datos relacional
- **Composer** - Gestor de dependencias

### **Frontend**

- **Bootstrap 5.3** - Framework CSS
- **Bootstrap Icons** - Iconografía
- **JavaScript ES6** - Lógica del cliente
- **jQuery** - AJAX y manipulación DOM

### **Librerías PHP**

- **barryvdh/laravel-dompdf** - Generación de PDFs
- **simplesoftwareio/simple-qrcode** - Códigos QR
- **phpoffice/phpspreadsheet** - Lectura/escritura Excel
- **Carbon** - Manejo de fechas

### **Desarrollo**

- **XAMPP** - Entorno de desarrollo local
- **Git/GitHub** - Control de versiones
- **VS Code** - Editor de código

---

## 📦 Requisitos del Sistema

### **Requisitos Mínimos**

- PHP >= 8.2
- MySQL >= 8.0
- Composer >= 2.5
- Apache 2.4+ (incluido en XAMPP)
- Extensiones PHP:
    - OpenSSL
    - PDO
    - Mbstring
    - Tokenizer
    - XML
    - Ctype
    - JSON
    - BCMath
    - GD (para imágenes)
    - Zip (para importación con fotos)

### **Requisitos Recomendados**

- RAM: 4GB mínimo
- Espacio en disco: 1GB libre
- Navegador moderno (Chrome, Firefox, Edge)

---

## 🚀 Instalación y Configuración

### **1. Clonar el Repositorio**

```bash
git clone https://github.com/Inforney/sistema-carnetizacion-istpet.git
cd sistema-carnetizacion-istpet
```

### **2. Instalar Dependencias PHP**

```bash
composer install
```

### **3. Configurar Variables de Entorno**

```bash
# Copiar archivo de ejemplo
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### **4. Configurar Base de Datos**

Editar `.env` con tus credenciales:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carnetizacion_istpet
DB_USERNAME=root
DB_PASSWORD=
```

### **5. Crear Base de Datos**

En phpMyAdmin o MySQL CLI:

```sql
CREATE DATABASE carnetizacion_istpet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### **6. Ejecutar Migraciones**

```bash
php artisan migrate
```

### **7. Ejecutar Seeders (Datos de Prueba)**

```bash
php artisan db:seed
```

Esto creará:

- 1 administradores
- 1 estudiante de prueba (Kevin - CI: 1726429283)
- 2 laboratorios 

### **8. Crear Enlace Simbólico para Storage**

```bash
php artisan storage:link
```

Esto permite acceder a las fotos subidas.

### **9. Configurar Permisos (Linux/Mac)**

```bash
chmod -R 775 storage bootstrap/cache
```

### **10. Iniciar Servidor de Desarrollo**

```bash
php artisan serve
```

### **11. Acceder al Sistema**

```
http://127.0.0.1:8000
```

---

## 👥 Credenciales de Acceso
### **Administrador Principal**
- Usuario: `admin`
- Contraseña: `IstpetAdmin2026!`

**Perfil de Profesor**
- Usuario: `1711223344`
- Contraseña: `Profesor2026!`

### **Perfil Estudiante**
- Cédula: `1726429283`
- Contraseña: `28112000k`

## 📂 Estructura del Proyecto

```
carnetizacion-istpet/
│
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── RenovarCarnetsAutomatico.php    # Comando renovación automática
│   ├── Helpers/
│   │   └── CedulaValidator.php                 # Validador de cédulas ecuatorianas
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── CarnetController.php        # CRUD carnets
│   │   │   │   ├── EstudianteController.php    # CRUD estudiantes + fotos
│   │   │   │   ├── LaboratorioController.php   # CRUD laboratorios
│   │   │   │   ├── AccesoController.php        # Control accesos
│   │   │   │   ├── ImportacionController.php   # Importación Excel ← NUEVO
│   │   │   │   └── DashboardController.php     # Dashboard admin
│   │   │   └── Estudiante/
│   │   │       └── CarnetController.php        # Vista estudiante
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php             # Protección admin
│   │       └── EstudianteMiddleware.php        # Protección estudiante
│   └── Models/
│       ├── Usuario.php                         # Modelo estudiantes
│       ├── Carnet.php                          # Modelo carnets
│       ├── Laboratorio.php                     # Modelo laboratorios
│       ├── Acceso.php                          # Modelo accesos
│       └── Admin.php                           # Modelo administradores
│
├── database/
│   ├── migrations/                             # Estructura de BD
│   │   ├── 2024_01_01_create_usuarios_table.php
│   │   ├── 2024_01_02_create_carnets_table.php
│   │   ├── 2024_01_03_create_laboratorios_table.php
│   │   ├── 2024_01_04_create_accesos_table.php
│   │   └── 2024_01_05_create_admins_table.php
│   └── seeders/                                # Datos iniciales
│       └── DatabaseSeeder.php
│
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php             # Dashboard principal
│       │   ├── estudiantes/
│       │   │   ├── index.blade.php             # Lista estudiantes
│       │   │   ├── create.blade.php            # Crear con foto ← MEJORADO
│       │   │   ├── edit.blade.php              # Editar con foto ← MEJORADO
│       │   │   └── show.blade.php              # Detalle estudiante
│       │   ├── carnets/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   ├── show.blade.php
│       │   │   └── pdf.blade.php               # Template PDF
│       │   ├── laboratorios/
│       │   │   ├── index.blade.php             # Con filtros tipo
│       │   │   ├── create.blade.php
│       │   │   ├── edit.blade.php
│       │   │   └── show.blade.php
│       │   ├── accesos/
│       │   │   ├── index.blade.php
│       │   │   ├── crear.blade.php
│       │   │   └── estadisticas.blade.php
│       │   └── importacion/                    # ← NUEVO
│       │       ├── index.blade.php             # Formulario importación
│       │       └── resultado.blade.php         # Reporte resultados
│       ├── estudiante/
│       │   └── carnet/
│       │       └── show.blade.php              # Vista carnet estudiante
│       └── layouts/
│           └── app.blade.php                   # Layout principal
│
├── routes/
│   └── web.php                                 # Rutas del sistema
│
├── storage/
│   ├── app/
│   │   └── public/
│   │       └── fotos_perfil/                   # Fotos estudiantes ← NUEVO
│   └── logs/
│       └── carnets_renovacion.log              # Log renovaciones
│
├── public/
│   └── storage/                                # Symlink a storage/app/public
│
├── .env                                        # Configuración del sistema
├── composer.json                               # Dependencias PHP
├── artisan                                     # CLI de Laravel
└── README.md                                   # Este archivo
```

<<<<<<< HEAD
## 🔐 Seguridad

- Contraseñas encriptadas con Bcrypt
- Protección CSRF en formularios
- Middleware de autenticación por roles
- Validación de datos en servidor

## 👨‍💻 Autores

- Kevin Huilca - Desarrollo completo
- Matias Bedon - Ayudante
- Ivan Ceron - Ayudante

## 📄 Licencia

Este proyecto fue desarrollado como proyecto académico para ISTPET.

## 🔄 Comandos Artisan Personalizados

### **Renovación Automática de Carnets**

```bash
# Ejecutar manualmente
php artisan carnets:renovar-automatico

# Ver logs
type storage\logs\carnets_renovacion.log
```

**Funcionamiento:**

1. Busca carnets que vencen en los próximos 30 días
2. Marca el carnet viejo como `estado = 'vencido'`
3. Genera un nuevo carnet con:
    - Código QR único diferente
    - Fecha emisión: HOY
    - Fecha vencimiento: HOY + 6 meses
    - Estado: activo
4. Guarda log detallado

**Programación Automática:**

En `app/Console/Kernel.php`:

```php
$schedule->command('carnets:renovar-automatico')
         ->daily()
         ->at('02:00');
```

Para activar en producción:

```bash
# Agregar al crontab del servidor
* * * * * cd /ruta/proyecto && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📊 Importación Masiva - Guía Completa

### **Formato del Archivo Excel**

| nombres | apellidos | cedula     | tipo_documento | correo_institucional | celular    | carrera                | ciclo_nivel   | nacionalidad | foto_filename  |
| ------- | --------- | ---------- | -------------- | -------------------- | ---------- | ---------------------- | ------------- | ------------ | -------------- |
| María   | González  | 1760234567 | cedula         | maria@istpet.edu.ec  | 0998765432 | Desarrollo de Software | SEGUNDO NIVEL | Ecuatoriana  | 1760234567.jpg |

### **Columnas Obligatorias**

- `nombres` - Nombres del estudiante
- `apellidos` - Apellidos completos
- `cedula` - Cédula ecuatoriana válida (10 dígitos)
- `correo_institucional` - Email único
- `celular` - Celular ecuatoriano (09XXXXXXXX)
- `carrera` - Una de las 5 carreras disponibles
- `ciclo_nivel` - PRIMER/SEGUNDO/TERCER/CUARTO NIVEL

### **Columnas Opcionales**

- `tipo_documento` - Por defecto: "cedula"
- `nacionalidad` - Por defecto: "Ecuatoriana"
- `foto_filename` - Nombre del archivo en ZIP (ej: 1760234567.jpg)

### **Paso a Paso**

1. **Ir a Importación Masiva:**

    ```
    Dashboard > Importación Masiva
    ```

2. **Descargar plantilla Excel**

3. **Llenar plantilla con datos**

4. **(Opcional) Preparar fotos:**
    - Nombrar cada foto con la cédula: `1726429283.jpg`
    - Comprimir todas en un archivo `.zip`

5. **Subir archivos:**
    - Seleccionar Excel
    - Seleccionar ZIP de fotos (opcional)
    - Marcar "Generar carnets automáticamente"
    - Click "Iniciar Importación"

6. **Revisar resultados:**
    - Exitosos (verde)
    - Errores con detalles (rojo)
    - Duplicados detectados (amarillo)
    - Carnets generados (azul)

### **Validaciones Automáticas**

- ✅ Cédula ecuatoriana válida (algoritmo módulo 10)
- ✅ Email único en BD
- ✅ Celular formato 09XXXXXXXX
- ✅ Detección de duplicados
- ✅ Campos obligatorios completos
- ✅ Formato de archivo correcto

---

## 🔐 Seguridad Implementada

### **Autenticación y Autorización**

- Middleware `AdminMiddleware` protege rutas administrativas
- Middleware `EstudianteMiddleware` protege rutas de estudiantes
- Guards separados para admin y estudiante
- Sesiones seguras con Laravel Sanctum

### **Validación de Datos**

- **Cédulas ecuatorianas:** Algoritmo oficial del módulo 10
- **Emails:** Formato válido + unicidad
- **Contraseñas:** Mínimo 8 caracteres, encriptadas con Bcrypt
- **Archivos:** Validación de tipo y tamaño (fotos max 2MB, Excel max 10MB)

### **Protección CSRF**

- Token CSRF en todos los formularios
- Validación automática por Laravel

### **SQL Injection**

- Uso de Eloquent ORM
- Prepared statements en todas las consultas

### **XSS Protection**

- Blade templates con escape automático
- Validación de entrada en servidor

---

## 📱 Módulos del Sistema

### **1. Módulo de Estudiantes**

- Lista paginada con búsqueda y filtros
- Crear estudiante con foto y carrera
- Editar información completa
- Subir/cambiar/eliminar foto
- Bloquear/desbloquear acceso
- Resetear contraseña
- Ver historial de accesos
- Eliminar estudiante

### **2. Módulo de Carnets**

- Generar carnet individual
- Generar carnets masivos
- Descargar PDF individual
- Descargar PDF masivo (múltiples por página)
- Visualizar en navegador
- Renovar carnet manualmente
- Bloquear/activar carnet
- Ver detalle de carnet

### **3. Módulo de Laboratorios**

- Listar con filtros (Técnicos/Aulas)
- Crear laboratorio con QR
- Editar información
- Ver ocupación actual
- Cambiar estado (Disponible/Mantenimiento)
- Ver estudiantes activos en lab
- Historial de accesos
- Eliminar laboratorio

### **4. Módulo de Accesos**

- Registrar entrada (escaneo QR)
- Registrar salida
- Marcar ausentes
- Ver estudiantes activos por lab
- Historial completo
- Filtros por fecha y laboratorio
- Estadísticas mensuales
- Exportar registros

### **5. Módulo de Importación**

- Subir Excel con estudiantes
- Subir ZIP con fotos
- Validación en tiempo real
- Reporte detallado
- Generación automática de carnets
- Detección de duplicados
- Log de errores

---

## 🎨 Capturas de Pantalla

### **Dashboard Administrativo**

Estadísticas en tiempo real, acciones rápidas, estado de laboratorios.

### **Gestión de Estudiantes**

Lista con búsqueda, filtros, paginación. Formularios con foto y carrera.

### **Importación Masiva**

Formulario de carga, plantilla descargable, reporte de resultados.

### **Carnet Digital**

Vista previa, diseño institucional, código QR grande.

### **Control de Accesos**

Registro de entrada/salida, estudiantes activos, historial.

### **Gestión de Laboratorios**

Separación técnicos/aulas, ocupación en tiempo real, filtros.

---

## 🧪 Testing

Para ejecutar pruebas:

```bash
# Instalar dependencias de desarrollo
composer install --dev

# Ejecutar tests
php artisan test
```

---

## 🐛 Solución de Problemas Comunes

### **Error: "Storage link not found"**

```bash
php artisan storage:link
```

### **Error: "Permission denied" en storage**

```bash
chmod -R 775 storage bootstrap/cache
```

### **Fotos no se muestran (404)**

1. Verificar symlink: `php artisan storage:link`
2. Verificar permisos: `storage/app/public/fotos_perfil`
3. Verificar ruta en BD: debe ser `storage/fotos_perfil/foto_xxx.jpg`

### **Importación Excel falla**

1. Verificar que PhpSpreadsheet está instalado: `composer require phpoffice/phpspreadsheet`
2. Verificar formato de archivo (xlsx, xls, csv)
3. Revisar logs: `storage/logs/laravel.log`

### **Renovación automática no funciona**

1. Verificar comando: `php artisan carnets:renovar-automatico`
2. Ver logs: `storage/logs/carnets_renovacion.log`
3. Verificar cron configurado en servidor

---

## 📝 Changelog

### **Versión 2.0** (Actual - Enero 2026)

- ✅ Importación masiva desde Excel
- ✅ Subir fotos de estudiantes
- ✅ Campo carrera con 5 opciones
- ✅ Dashboard mejorado con 8 acciones rápidas
- ✅ Validación de cédulas ecuatorianas
- ✅ Renovación automática de carnets
- ✅ Separación laboratorios técnicos/aulas
- ✅ Almacenamiento correcto con Storage facade

### **Versión 1.0** (Diciembre 2025)

- ✅ CRUD de estudiantes básico
- ✅ Generación de carnets con QR
- ✅ Control de accesos a laboratorios
- ✅ Dashboard administrativo
- ✅ Sistema de autenticación

---

## 🚀 Roadmap (Futuras Mejoras)

- [ ] Dashboard con gráficos (Chart.js)
- [ ] Reportes en PDF
- [ ] Notificaciones por email
- [ ] Exportar datos a Excel
- [ ] Backup automático
- [ ] API REST
- [ ] App móvil (React Native)

---

## 👨‍💻 Equipo de Desarrollo

### **Desarrollador Principal**

- **Kevin Gabriel Huilca Campaña**
    - Cédula: 1726429283
    - Carrera: Desarrollo de Software
    - Ciclo: Tercer Nivel
    - Email: kevin.huilca@istpet.edu.ec
    - Rol: Full Stack Developer

### **Colaboradores**

- **Matías** - Ayudante de desarrollo
- **Iván** - Ayudante de desarrollo

---

## 🏫 Institución

**Instituto Superior Tecnológico Mayor Pedro Traversari (ISTPET)**

- Carrera: Desarrollo de Software
- Nivel: Tercer Nivel
- Año: 2025-2026
- Proyecto: Tesis de Grado

---

## 📄 Licencia

Este proyecto fue desarrollado como proyecto académico para el Instituto Superior Tecnológico Mayor Pedro Traversari (ISTPET).

**Restricciones:**

- Uso exclusivamente académico
- No comercial
- Propiedad intelectual de ISTPET y desarrolladores

---

## 📞 Soporte y Contacto

Para dudas o sugerencias sobre el proyecto:

- **Email:** kevin.huilca@istpet.edu.ec
- **GitHub:** https://github.com/Inforney/sistema-carnetizacion-istpet
- **Institución:** ISTPET - Quito, Ecuador

---

## 🙏 Agradecimientos

- Instituto Superior Tecnológico Mayor Pedro Traversari
- Docentes de la carrera de Desarrollo de Software
- Compañeros de clase por el apoyo
- Comunidad Laravel por la documentación

---

## 📚 Referencias

- [Documentación Laravel 10](https://laravel.com/docs/10.x)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3)
- [PhpSpreadsheet Docs](https://phpspreadsheet.readthedocs.io)
- [DomPDF Docs](https://github.com/barryvdh/laravel-dompdf)
- [Simple QR Code](https://www.simplesoftware.io/docs/simple-qrcode)

---

<div align="center">

**Desarrollado con ❤️ por estudiantes de Desarrollo de Software**

**ISTPET 2025-2026**

[![GitHub](https://img.shields.io/badge/GitHub-Inforney-black?logo=github)](https://github.com/Inforney)
[![Laravel](https://img.shields.io/badge/Made%20with-Laravel-red?logo=laravel)](https://laravel.com)

</div>
